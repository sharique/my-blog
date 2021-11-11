<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

#[Route('/tag')]
class TagController extends AbstractController
{
  #[Route('/', name: 'tag_index', methods: ['GET'])]
  public function index(TagRepository $tagRepository): Response
  {
    return $this->render('tag/index.html.twig', [
      'tags' => $tagRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'tag_new', methods: ['GET', 'POST'])]
  public function new(Request $request): Response
  {
    // Check if user is logged in.
    $this->isAuthenticated($request);
    $tag = new Tag();
    $form = $this->createForm(TagType::class, $tag);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($tag);
      $entityManager->flush();

      return $this->redirectToRoute('tag_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('tag/new.html.twig', [
      'tag' => $tag,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'tag_show', methods: ['GET'])]
  public function show(Tag $tag): Response
  {
    return $this->render('tag/show.html.twig', [
      'tag' => $tag,
    ]);
  }

  #[Route('/{id}/edit', name: 'tag_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Tag $tag): Response
  {
    // Check if user is logged in.
    $this->isAuthenticated($request);
    $form = $this->createForm(TagType::class, $tag);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('tag_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('tag/edit.html.twig', [
      'tag' => $tag,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'tag_delete', methods: ['POST'])]
  public function delete(Request $request, Tag $tag): Response
  {
    $this->isAuthenticated($request);
    if ($this->isCsrfTokenValid('delete' . $tag->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($tag);
      $entityManager->flush();
    }

    return $this->redirectToRoute('tag_index', [], Response::HTTP_SEE_OTHER);
  }

  /**
   * @param Request $request
   */
  public function isAuthenticated(Request $request): void
  {
    // Check if user is logged in.
    if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED') == FALSE) {
      throw new AuthenticationException();
    }
  }
}
