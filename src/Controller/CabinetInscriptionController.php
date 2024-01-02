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
        return $this->render('cabinet_inscription/cabinet_inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function sendEmails(MailerInterface $mailer, $cabinet)
    {
        $message = "Un nouveau cabinet s'est inscrit.\n";
        $message .= "Numéro BCE: " . $cabinet->getNumBCE() . "\n";
        $message .= "Nom: " . $cabinet->getNom() . "\n";
        $message .= "Adresse: " . $cabinet->getAdresse() . "\n";
        $message .= "Code postal : " . $cabinet->getZipcode() . "\n";
        $message .= "E-mail : " . $cabinet->getContactEmail() . "\n";

        $emailAdmin = (new Email())
            ->from('KineHub <y.arigui99@gmail.com>')
            ->to('ariguiyusra@gmail.com')
            ->subject('Nouvelle demande d\'un cabinet')
            ->text($message);

        $mailer->send($emailAdmin);

        $emailUser = (new Email())
            ->from('KineHub <y.arigui99@gmail.com>')
            ->to($cabinet->getContactEmail())
            ->subject('Votre demande d\'inscription a été envoyé')
            ->html('
                    <html>
                        <head>
                            <style>
                                body { font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif;
                                    margin: 0;
                                    padding: 0;
                                    background-color: #25293d;
                                    color: #fff;
                                }
                                .header img {
                                    display: block;
                                    max-height: 200px;
                                    width: auto;
                                    margin-left: 10%;
                                    margin-right: 10%;
                                }
                                .content { margin: 15px; }
                            </style>
                        </head>
                        <body>
                            <div class="header">
                            <img src="/public/images/1.png" alt="Logo KineHub" /></div>
                            <div class="content">
                                <h2>Bonjour ' . htmlspecialchars($cabinet->getNom()) . ',</h2><br>
                                <p>Merci, pour votre demande d\'inscription ! Elle sera traitée dans les plus brefs délais.</p>
                            </div>
                        </body>
                    </html>');
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
