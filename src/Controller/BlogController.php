<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/blog')]
class BlogController extends AbstractController
{
  private $security;

  public function __construct(Security $security)
  {
    $this->security = $security;
  }

  #[Route('/', name: 'blog_index', methods: ['GET'])]
  public function index(BlogRepository $blogRepository): Response
  {
    return $this->render('blog/index.html.twig', [
      'blogs' => $blogRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'blog_new', methods: ['GET', 'POST'])]
  public function new(Request $request): Response
  {
    // Check if user is logged in.
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
    $blog = new Blog();
    $form = $this->createForm(BlogType::class, $blog);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      if ($user) {
        $blog->setAuthor($user);
      }
      $time = new DateTime();
      $blog->setCreated($time);
      $blog->setUpdated($time);
      $entityManager->persist($blog);
      $entityManager->flush();

      return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('blog/new.html.twig', [
      'blog' => $blog,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'blog_show', methods: ['GET'])]
  public function show(Blog $blog): Response
  {
    return $this->render('blog/show.html.twig', [
      'blog' => $blog,
    ]);
  }

  #[Route('/{id}/edit', name: 'blog_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Blog $blog): Response
  {
    // Check if user is logged in.
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
    $form = $this->createForm(BlogType::class, $blog);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('blog/edit.html.twig', [
      'blog' => $blog,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'blog_delete', methods: ['POST'])]
  public function delete(Request $request, Blog $blog): Response
  {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
    if ($this->isCsrfTokenValid('delete' . $blog->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($blog);
      $entityManager->flush();
    }

    return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
  }
}
