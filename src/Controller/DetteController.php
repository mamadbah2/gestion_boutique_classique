<?php

namespace App\Controller;

use App\Form\DetteFormType;
use App\Form\DetteSearchFormType;
use App\Repository\ClientRepository;
use App\Repository\DetteRepository;
use App\Entity\Dette;
use App\Entity\Payement;
use App\Form\PayementType;
use App\enum\StatusDette;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
class DetteController extends AbstractController
{
    #[Route('/dettes', name: 'app_dettes')]
    public function index(DetteRepository $detteRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 5;
        $dettes = $detteRepository->findAllByPaginate($page, $limit);
        $form = $this->createForm(DetteSearchFormType::class);
        $count = $dettes->count();
        $maxPage = ceil($count / $limit);
        
        return $this->render('dette/index.html.twig', [
            'dettes' => $dettes,
            'maxPage' => $maxPage,
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/dette/{id}/payment', name: 'app_dette_payment', methods: ['POST'])]
    public function makePayment(Request $request, Dette $dette, EntityManagerInterface $entityManager): Response
    {
        $payement = new Payement();
        $payement->setDette($dette);
        $form = $this->createForm(PayementType::class, $payement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $payement->getMontant() > 0) {
            $payement->setDette($dette);
            $dette->setMontantVerser($dette->getMontantVerser() + $payement->getMontant());

            if ($dette->getMontantVerser() >= $dette->getMontant()) {
                $dette->setStatus(StatusDette::Paye);
            }

            $entityManager->persist($payement);
            $entityManager->flush();

            $this->addFlash('success', 'Payment made successfully.');
            
            return $this->redirectToRoute('app_dettes');
        }

        $this->addFlash('error', 'There was an error processing your payment.');
        return $this->redirectToRoute('app_dettes');
    }


    #[Route('/dette/client/{id}/payment', name: 'app_detteClient_payment', methods: ['POST'])]
    public function makePaymentClient(Request $request, Dette $dette, EntityManagerInterface $entityManager): Response
    {
        $payement = new Payement();
        $payement->setDette($dette);
        $form = $this->createForm(PayementType::class, $payement);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $payement->getMontant() > 0) {
            $payement->setDette($dette);
            $dette->setMontantVerser($dette->getMontantVerser() + $payement->getMontant());
    
            if ($dette->getMontantVerser() >= $dette->getMontant()) {
                $dette->setStatus(StatusDette::Paye);
            }
    
            $entityManager->persist($payement);
            $entityManager->flush();
            $this->addFlash('success', 'Payment made successfully.');
            
            return $this->redirectToRoute('dettesbyClient', [
                'idClient' => $dette->getClient()->getId()
            ]);
        }
    
        $this->addFlash('error', 'There was an error processing your payment.');
        return $this->redirectToRoute('dettesbyClient', [
            'idClient' => $dette->getClient()->getId()
        ]);
    }
    #[Route('/dette/new', name: 'app_dette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dette = new Dette();
        $form = $this->createForm(DetteFormType::class, $dette);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $dette->setStatus(StatusDette::Impaye);
            $montantVerser = $form->get('montantVerser')->getData();
            $detailArticleDettes = $form->get('detailArticleDettes')->getData();
            
            $montant = 0;
            foreach ($detailArticleDettes as $detailArticleDette) {
                $total = $detailArticleDette->getArticle()->getPrix() * $detailArticleDette->getQte();
                $detailArticleDette->setTotal($total);
                
                $montant += $total;
                
                $detailArticleDette->setDette($dette);
            }
            
            $dette->setMontant($montant);
            $dette->setMontantVerser($montantVerser === null ? 0 : $montantVerser);
            if ($montantVerser > 0) {
                $payement = new Payement();
                $payement->setMontant($montantVerser);
                // $payement->setDate(new \DateTime());
                $payement->setDette($dette);
                
                $dette->addPayement($payement);
                // $dette->setMontantVerser($montantVerser);
    
                if ($montantVerser >= $dette->getMontant()) {
                    $dette->setStatus(StatusDette::Paye);
                } else {
                    $dette->setStatus(StatusDette::Impaye);
                }
            }
    
            $entityManager->persist($dette);
            $entityManager->flush();
    
            $this->addFlash('success', 'La dette a été créée avec succès.');
            return $this->redirectToRoute('app_dette_new');
        }
    
        return $this->render('dette/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    // #[Route('/dettes/{id}', name: 'dettesbyClient')]
  
}
