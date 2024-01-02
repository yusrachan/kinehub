<?php

namespace App\Controller;

use App\Entity\Cabinet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminInscriptionController extends AbstractController
{

    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('admin/demande-inscription', name: 'admin_inscriptions')]
    public function index(): Response
    {
        $inscriptions = $this->entityManager->getRepository(Cabinet::class)->findAll(); 

        return $this->render('admin/demande_inscriptions.html.twig', ['inscriptions' => $inscriptions]);
    }
}
