<?php

namespace App\Controller;

use App\Entity\CabinetValide;
use App\Entity\CabinetEnAttente;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
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
        $inscriptions = $this->entityManager->getRepository(CabinetEnAttente::class)->findAll();

        return $this->render('admin/demande_inscriptions.html.twig', ['inscriptions' => $inscriptions]);
    }

    #[Route('admin/refuser-inscription/{id}', name: 'refuser_inscription')]
    public function refuserInscription($id, MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $cabinet = $entityManager->getRepository(CabinetEnAttente::class)->find($id);

        if (!$cabinet) {
            throw $this->createNotFoundException('Inscription introuvable.');
        }

        $email = (new Email())
            ->from('KineHub <y.arigui99@gmail.com>')
            ->to($cabinet->getContactEmail())
            ->subject('Refus d\'inscription')
            ->text('Nous vous remercions pour votre intérêt envers notre plateforme KineHub.

            Après examen attentif de votre demande, nous regrettons de vous informer que nous ne pouvons pas l\'accepter. Cette décision peut être due à des critères non remplis ou à des informations manquantes.

            Pour toute question, n\'hésitez pas à nous contacter.
            
            Cordialement,
            L\'équipe KineHub');
        $mailer->send($email);

        $entityManager->remove($cabinet);
        $entityManager->flush();

        return $this->redirectToRoute('admin_inscriptions');
    }

    #[Route('admin/valider-inscription/{id}', name: 'valider_inscription')]
    public function validerInscription($id, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $cabinetEnAttente = $entityManager->getRepository(CabinetEnAttente::class)->find($id);

        if (!$cabinetEnAttente) {
            throw $this->createNotFoundException('Inscription introuvable.');
        }

        $cabinetValide = new CabinetValide();
        $cabinetValide->setNom($cabinetEnAttente->getNom());
        $cabinetValide->setNom($cabinetEnAttente->getAdresse());
        $cabinetValide->setNom($cabinetEnAttente->getZipcode());
        $cabinetValide->setNom($cabinetEnAttente->getContactEmail());
        $cabinetValide->setNom($cabinetEnAttente->getNumBCE());

        $entityManager->persist($cabinetValide);

        $entityManager->remove($cabinetEnAttente);

        $entityManager->flush();

        return $this->redirectToRoute('admin_inscriptions');
    }
}
