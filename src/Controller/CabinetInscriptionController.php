<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cabinet;
use App\Form\CabinetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CabinetInscriptionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('inscription', name: 'cabinet_inscription')]
    public function index(Request $request): Response
    {
        $company = new Cabinet;
        $form = $this->createForm(CabinetType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($company);
            $this->entityManager->flush();

            return $this->redirectToRoute('inscription_confirmation');
        }
        return $this->render('cabinet_inscription/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('inscription-confirmation', name: 'inscription_confirmation')]
    public function confirmation() : Response {
        return $this->render('inscription_confirmation.html.twig');
    }
}
