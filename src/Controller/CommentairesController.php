<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Articles;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commentaires')]
final class CommentairesController extends AbstractController
{
    #[Route(name: 'app_commentaires_index', methods: ['GET'])]
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        return $this->render('commentaires/index.html.twig', [
            'commentaires' => $commentairesRepository->findBy([], ["createdAt" => "DESC"]),
        ]);
    }

    #[Route('/new/{id}', name: 'app_commentaires_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $commentaire = new Commentaires();

        //Récupération de l'article lié
        $article = $entityManager->getRepository(Articles::class)->find($id);
        if (!$article) {
            throw $this->createNotFoundException("Article avec l'id $id introuvable.");
        }

        $commentaire->setArticle($article);

        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaires/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
            'id' => $id,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaires_show', methods: ['GET'])]
    public function show(Commentaires $commentaire): Response
    {
        return $this->render('commentaires/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaires_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaires $commentaire, EntityManagerInterface $entityManager, $id): Response
    {


        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaires/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaires_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaires $commentaire, EntityManagerInterface $entityManager): Response
    {
        $articleId = $commentaire->getArticle()->getId();

        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

         return $this->redirectToRoute('app_articles_show', ['id' => $articleId]);
    }
}
