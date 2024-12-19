<?php

namespace App\Controller;

use App\Dtos\ClientDto;
use App\Entity\Client;
use App\Entity\User;
use App\enum\StatusDette;
use App\Form\ClientFormType;
use App\Form\DetteFilterType;
use App\Form\SearchClientFormType;
use App\Form\UserFormType;
use App\Repository\ClientRepository;
use App\Repository\DetteRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class YourController extends AbstractController
{
    public function yourAction(ValidatorInterface $validator)
    {
        $client = new Client(); // Exemple de création d'un objet
        $error_client = $validator->validate($client);
        // Traitez les erreurs si nécessaire
    }
}

class ClientController extends AbstractController
{

    // private $mailService;


    private $clientRepository;
    //MailService $mailService,
    public function __construct(ClientRepository $clientRepository)
    {
        // $this->mailService = $mailService;
        $this->clientRepository = $clientRepository;
    }
    #[Route('/client', name: 'app_client')]
    public function index(Request $request): Response
    {
        $client = new Client();
        // $user = new User();
        $page = $request->query->getInt('page', 1);
        $limit = 5;
        $clients = $this->clientRepository->paginateClients($page, $limit);
        $clientDto = new ClientDto();

        // Creation des formulaires : client-User-SearchClient

        $form = $this->createForm(ClientFormType::class, $client);

        // $formUser = $this->createForm(UserFormType::class, $user);

        $formSearch = $this->createForm(SearchClientFormType::class, $clientDto);

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            // $formSearch->get('telephone')->getData()
            $clients = $this->clientRepository->findByClient($clientDto, $page, $limit);

        }
        $error_user = ['nom' => null, 'prenom' => null];

        $count = $clients->count();
        $maxPage = ceil($count / $limit);
        return $this->render('client/index.html.twig', [
            'form' => $form->createView(),
            'formSearch' => $formSearch->createView(),
            'clients' => $clients,
            'maxPage' => $maxPage,
            'page' => $page,
            'error_user' => $error_user,
        ]);
    }

    #[Route('/client/new', name: 'client_new')]
    public function new(Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validator): Response
    {
        $client = new Client();
        $user = new User();
        $client->setAccount($user);
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);
        $clientDto = new ClientDto();
        $formSearch = $this->createForm(SearchClientFormType::class, $clientDto);
        // dd($form->get('addAccount')->getData());
        if ($form->isSubmitted()) {
            $error_client = $validator->validate($client);
            $error_user = ['nom' => null, 'prenom' => null];
            if (!$form->get('addAccount')->getData()) {
                $client->setAccount(null);
            } else {
                $user = $client->getAccount();
                $errors = $validator->validate($user);
                if (count($errors) > 0) {
                    foreach ($errors as $violation) {
                        $property = $violation->getPropertyPath();
                        $message = trim($violation->getMessage());
                        if ($property === 'prenom' && $message === "Veuillez saisir un prenom valide") {
                            $error_user["prenom"] = "Veuillez saisir un prenom valide";
                        }
                        if ($property === 'nom' && $message === "Veuillez saisir un nom valide") {
                            $error_user["nom"] = "Veuillez saisir un nom valide";
                        }
                    }
                }
            }


            $page = $request->query->getInt('page', 1);
            $limit = 5;
            $clients = $this->clientRepository->paginateClients($page, $limit);


            $count = $clients->count();
            $maxPage = ceil($count / $limit);

            if ($error_user["nom"] != null || $error_user["prenom"] != null || count($error_client) > 0) {
                //  dd($error_user, $error_client);
                return $this->render('client/index.html.twig', [
                    'form' => $form->createView(),
                    'formSearch' => $formSearch->createView(),
                    'clients' => $clients,
                    'maxPage' => $maxPage,
                    'error_user' => $error_user,
                    'error_client' => $error_client,
                    'page' => $page,
                ]);
            }

            if ($client->getAccount() != null) {
                $content = sprintf(
                    "Your login information:\n\nLogin: %s\nPassword: %s",
                    $client->getAccount()->getlogin(),
                    $client->getAccount()->getPassword()
                );
                // $this->mailService->sendEmail( $client->getAccount()->getlogin(), 'Your information', $content);
            }
            $entityManagerInterface->persist($client);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_client');
        }

        return $this->render('client/index.html.twig', [
            'form' => $form->createView(),
            'formSearch' => $formSearch->createView(),
        ]);
    }
    #[Route('client/dettes/{idClient}', name: 'dettesbyClient', methods: "GET")]
    public function getDettesByClient(int $idClient, DetteRepository $detteRepository, Request $request, ClientRepository $clientRepository): Response
    {
        $form = $this->createForm(DetteFilterType::class, null, [
            'method' => 'GET',
        ]);

        $page = $request->query->getInt('page', 1);
        $limit = 5;
        // dd( $form->handleRequest($request));
        // $form->handleRequest($request);

        if ($request->query->has('dette_filter')) {
            $status = $request->query->all('dette_filter')['status'];
        } else {
            $status = $request->query->get('statuts', StatusDette::Impaye->value);
        }
        // dd($status);

        $dettes = $detteRepository->findDettesById($idClient,  $page, $status, $limit);
        $count = $dettes->count();
        $client = $clientRepository->find($idClient);
        $maxPage = ceil($count / $limit);
        return $this->render('client/dettes.html.twig', [
            'controller_name' => 'DetteController',
            'dettes' => $dettes,
            'form' => $form->createView(),
            'maxPage' => $maxPage,
            'page' => $page,
            'client' => $client,
            'statuts' => $status,
        ]);
    }


}
