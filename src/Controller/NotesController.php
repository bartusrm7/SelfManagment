<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Form\NotesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NotesController extends AbstractController
{
    #[Route('/notes', name: 'app_notes')]
    public function displayNotes(EntityManagerInterface $entityManager): Response
    {
        $notes = $entityManager->getRepository(Notes::class)->findAll();

        return $this->render('notes/display-notes.html.twig', [
            'notes' => $notes
        ]);
    }

    #[Route('/notes/create-note', name: 'app_create_note')]
    public function createNotes(Request $request, EntityManagerInterface $entityManager): Response
    {
        $note = new Notes();
        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('app_notes');
        }

        return $this->render(
            'notes/create-note.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/notes/remove-note/{id}', name: 'app_remove_note', methods: ['DELETE'])]
    public function removeNote(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $note = $entityManager->getRepository(Notes::class)->find($id);
        if ($note) {
            $entityManager->remove($note);
            $entityManager->flush();

            return new JsonResponse(['status' => 'removed'], 200);
        }
    }
}
