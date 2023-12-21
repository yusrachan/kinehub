<?php

namespace App\Controller;

use App\Entity\Cabinet;
use App\Form\CabinetType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\RegistrationNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            } else {
                $this->entityManager->persist($cabinet);
                $this->entityManager->flush();

                $session = new Session();
                $session->set('cabinetData', $cabinet);

                return $this->redirectToRoute('inscription_confirmation');
            }
        }
        return $this->render('cabinet_inscription/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function sendEmails(MailerInterface $mailer, $cabinet)
    {
        $emailAdmin = (new Email())
            ->from('y.arigui99@gmail.com')
            ->to('ariguiyusra@gmail.com')
            ->subject('Nouvelle demande d\'un cabinet')
            ->text('Un nouveau cabinet s\'est inscrit. Numéro BCE: ' . $cabinet->getNumBCE() . ' et son e-mail : ' . $cabinet->getContactEmail());

        $mailer->send($emailAdmin);

        $emailUser = (new Email())
            ->from('y.arigui99@gmail.com')
            ->to($cabinet->getContactEmail())
            ->subject('Votre demande d\'inscription a été envoyé')
            ->text('Votre inscription est en attente de validation.');

        $mailer->send($emailUser);
    }

    #[Route('inscription-confirmation', name: 'inscription_confirmation')]
    public function confirmation(SessionInterface $session): Response
    {
        $cabinet = $session->get('cabinetData');

        if ($cabinet) {
            $this->sendEmails($this->mailer, $cabinet);
        }
        return $this->render('inscription_confirmation.html.twig');
    }
}
