<?php

namespace App\Controller;

use App\Entity\Meetings;
use App\Form\MeetingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MeetingsController extends AbstractController
{
    #[Route('/meetings', name: 'app_meetings')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $meeting = new Meetings();
        $form = $this->createForm(MeetingsType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($meeting);
            $entityManager->flush();

            return $this->redirectToRoute('app_meetings');
        }

        return $this->render('meetings/meetings.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/meetings/json', name: 'app_meetings_json', methods: ['GET'])]
    public function meetingsJson(EntityManagerInterface $entityManager): Response
    {
        $meetings = $entityManager->getRepository(Meetings::class)->findAll();
        $meetingsArray = array_map(function ($meeting) {
            return [
                'id' => $meeting->getId(),
                'title' => $meeting->getName(),
                'description' => $meeting->getDescription(),
                'start' => $meeting->getStartDate()->format('Y-m-d\TH:i'),
                'end' => $meeting->getEndDate()->format('Y-m-d\TH:i'),
            ];
        }, $meetings);

        if ($meetingsArray) {
            return new JsonResponse(['data' => $meetingsArray]);
        }
        return new JsonResponse([]);
    }
}
