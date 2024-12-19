<?php

// src/Controller/TestController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherInterface;
class TestContollerController extends AbstractController
{
    #[Route('/test-password/{email}/{plainPassword}', name: 'test_password')]
    public function testPassword(string $email, string $plainPassword, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Récupérer l'utilisateur par email
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            // Vérifier si le mot de passe est valide
            if ($passwordEncoder->isPasswordValid($user->getPassword(), $plainPassword, null)) {
                return new Response("Mot de passe valide");
            } else {
                return new Response("Mot de passe invalide");
            }
        }
        return new Response("Utilisateur introuvable");
    }
}
