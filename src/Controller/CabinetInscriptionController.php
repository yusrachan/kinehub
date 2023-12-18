<?php

namespace App\Controller;

use App\Entity\Cabinet;
use App\Form\CabinetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CabinetInscriptionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('inscription', name: 'cabinet_inscription')]
    public function index(Request $request, Cabinet $cabinet): Response
    {
        $company = new Cabinet;
        $form = $this->createForm(CabinetType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $numBCE = $company->getNumBCE();
            $existingCabinet = $this->entityManager->getRepository(Cabinet::class)->findOneBy(['numBCE' => $numBCE]);

            if ($existingCabinet) {
                $this->addFlash('error', 'Ce numéro BCE est déjà enregistré.');

                return $this->redirectToRoute('cabinet_inscription');
            }

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
