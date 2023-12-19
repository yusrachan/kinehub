<?php

namespace App\Controller;

use App\Entity\Cabinet;
use App\Form\CabinetType;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\RegistrationNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;

class CabinetInscriptionController extends AbstractController
{
    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    #[Route('inscription', name: 'cabinet_inscription')]
    public function index(Request $request): Response
    {
        $cabinet = new Cabinet;
        $form = $this->createForm(CabinetType::class, $cabinet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $numBCE = $cabinet->getNumBCE();
            $existingCabinet = $this->entityManager->getRepository(Cabinet::class)->findOneBy(['numBCE' => $numBCE]);

            if ($existingCabinet) {
                $this->addFlash('error', 'Ce numéro BCE est déjà enregistré.');

                return $this->redirectToRoute('cabinet_inscription');
            }

            $this->entityManager->persist($cabinet);
            $this->entityManager->flush();

            $this->sendEmails($cabinet);

            return $this->redirectToRoute('inscription_confirmation');
        }
        return $this->render('cabinet_inscription/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function sendEmails(Cabinet $cabinet) {
        $emailAdmin = (new Email())
            ->from('y.arigui99@gmail.com')
            ->to('ariguiyusra@gmail.com')
            ->subject('Nouvelle inscription d\'un cabinet')
            ->text('Un nouveau cabinet s\'est inscrit. Numéro BCE: '.$cabinet->getNumBCE());
        
        $this->mailer->send($emailAdmin);

        $emaiUser = (new Email())
            ->from('y.arigui99@gmail.com')
            ->to($cabinet->getContactEmail())
            ->subject('Votre demande d\'inscription a été envoyé')
            ->text('Votre inscription est en attente de validation.');
        
        $this->mailer->send($emaiUser);
    }

    #[Route('inscription-confirmation', name: 'inscription_confirmation')]
    public function confirmation() : Response {
        return $this->render('inscription_confirmation.html.twig');
    }

}
