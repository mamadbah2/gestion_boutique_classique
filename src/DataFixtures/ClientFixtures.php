<?php
namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\DetailArticleDette;
use App\Entity\DetailDetteRequest;
use App\Entity\DetteRequest;
use App\Entity\Payement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Client;
use App\Entity\User;
use App\Entity\Dette;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    private function createArticles(ObjectManager $manager): array
    {
        $articles = [];
        // Create standard products
        for ($j = 0; $j < 3; $j++) {
            $article = new Article();
            $article->setLibelle("Produit" . $j);
            $article->setPrix(1000);
            $article->setQteStock(100);
            $manager->persist($article);
            $articles['standard'][] = $article;
        }
        
        // Create request products
        for ($j = 0; $j < 2; $j++) {
            $article = new Article();
            $article->setLibelle("Article Request " . $j);
            $article->setPrix(750);
            $article->setQteStock(50);
            $manager->persist($article);
            $articles['request'][] = $article;
        }
        
        return $articles;
    }

    public function load(ObjectManager $manager): void
    {
        // First create and persist all articles
        $articles = $this->createArticles($manager);
        
        for ($i = 0; $i < 20; $i++) {
            $client = new Client();
            $client->setSurname("Surnom" . $i);
            $client->setTelephone('77123445' . $i);
            $client->setAdresse('Adresse' . $i);

            if ($i % 2 == 0) {
                $user = new User();
                $user->setNom('Nom' . $i);
                $user->setPrenom('Prenom' . $i);
                
                $plaintextPassword = "password";
                $hashedPassword = $this->encoder->hashPassword($user, $plaintextPassword);
                
                $user->setPassword($hashedPassword);
                $user->setLogin('Login' . $i);
                $user->setActive(true);
                $client->setAccount($user);
                $manager->persist($user);
            }

            if ($i % 3 == 0) {
                $dette = new Dette();
                $dette->setData(new \DateTime());
                $dette->setMontantVerser(100);
                $dette->setMontant(0);

                foreach ($articles['standard'] as $index => $article) {
                    $detailArticleDette = new DetailArticleDette();
                    $detailArticleDette->setArticle($article);
                    $detailArticleDette->setQte($index + 1);
                    $detailArticleDette->setTotal($detailArticleDette->getQte() * $article->getPrix());

                    $dette->setMontant($dette->getMontant() + $detailArticleDette->getTotal());
                    $detailArticleDette->setDette($dette);
                    $dette->addDetailArticleDette($detailArticleDette);
                    $manager->persist($detailArticleDette);
                }

                $dette->setClient($client);
                $manager->persist($dette);

                $payement = new Payement();
                $payement->setDette($dette);
                $payement->setMontant(50);
                $manager->persist($payement);

                $detteRequest = new DetteRequest();
                $detteRequest->setClient($client);
                $detteRequest->setDate(new \DateTime());
                $detteRequest->setMontant(1500);
                $manager->persist($detteRequest);

                foreach ($articles['request'] as $index => $article) {
                    $detailDetteRequest = new DetailDetteRequest();
                    $detailDetteRequest->setArticle($article);
                    $detailDetteRequest->setDetteRequest($detteRequest);
                    $detailDetteRequest->setQte($index + 1);
                    $manager->persist($detailDetteRequest);
                }
            }

            $manager->persist($client);
        }

        $manager->flush();
    }
}