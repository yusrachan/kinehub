<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\CabinetValide;
use App\Entity\CabinetEnAttente;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminInscriptionController extends AbstractController
{

    private $entityManager;
    private $mailer;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
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
        $cabinetValide->setAdresse($cabinetEnAttente->getAdresse());
        $cabinetValide->setZipcode($cabinetEnAttente->getZipcode());
        $cabinetValide->setContactEmail($cabinetEnAttente->getContactEmail());
        $cabinetValide->setNumBCE($cabinetEnAttente->getNumBCE());
        
        $entityManager->persist($cabinetValide);
        $entityManager->remove($cabinetEnAttente);
        $entityManager->flush();
        
        $password = bin2hex(random_bytes(4));
        $user = new User();
        
        $user->setEmail($cabinetValide->getContactEmail());
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(["ROLE_USER"]);
        $user->setCabinet($cabinetValide);

        $entityManager->persist($user);
        $entityManager->flush();

        
        $urlPaiement = $this->generateUrl('route_paiement', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('KineHub <y.arigui99@gmail.com>')
            ->to($cabinetValide->getContactEmail())
            ->subject('Validation de votre inscription à KineHub')
            ->html("Merci de vous être inscrit à KineHub !
            Voici vos identifiants personnels afin d'accéder à la plateforme :

            Nom d\'utilisateur : {$user->getEmail()}
            Mot de passe : $password
            
            Pour finaliser votre inscription et commencer à utiliser la plateforme, vous allez devoir procéder au paiement après vous être connecter.");
        $mailer->send($email);

        return $this->redirectToRoute('admin_inscriptions');
    }
}
