<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
  #[Route('/login', name: 'login')]
  public function index(AuthenticationUtils $auth): Response
  {
    $error = $auth->getLastAuthenticationError();
    $lastLogin = $auth->getLastUsername();
    return $this->render('login/index.html.twig', [
      'last_username' => $lastLogin,
      'error' => $error
    ]);
  }

  #[Route('/logout', name: 'logout')]
  public function logout(): void
  {
    // controller can be blank: it will never be called!
    throw new \Exception('Don\'t forget to activate logout in security.yaml');
  }
}
