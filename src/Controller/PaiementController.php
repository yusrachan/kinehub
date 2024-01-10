<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'app_paiement')]
    public function index(): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_51OVEizFtm1LR4NNUnbQJ7fV2h5xi7m11joWjTWJZ217FJAwJ9a6uWCOYMLn2qSFUYEbo8wjxlkfmgTEZyBOoCVzZ00PGCV86Qd');

        // $session = \Stripe\Checkout\Session::create([
        //     'payment_method_types' => ['card'],
        //     'line_items' => [[
        //         'price_data' => [
        //             'currency' => 'eur',
        //             'product_data' => [
        //                 'name' => 'Abonnement à KineHub',
        //             ],
        //             'unit_amount' => 100, // Montant en centimes
        //         ],
        //         'quantity' => 1,
        //     ]],
        //     'mode' => 'payment',
        //     'success_url' => $this->generateUrl('route_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
        //     'cancel_url' => $this->generateUrl('route_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        //     ]);

        return $this->render('paiement/index.html.twig');
    }

    #[Route('/paiement/succes', name: 'route_success')]
    public function succes(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user) {
            $user->$this->getUser()->setHasPaid(true);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('paiement/success.html.twig');
        }
        
        return $this->redirectToRoute('app_login');
    }

    #[Route('/paiement/annulation', name: 'route_cancel')]
    public function annulation(): Response
    {
        return $this->render('paiement/cancel.html.twig');
    }
}
