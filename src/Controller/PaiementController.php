<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'app_paiement')]
    public function index(): Response
    {
        $stripe = new \Stripe\StripeClient("pk_test_51OVEizFtm1LR4NNUUYrk14m7u5H4CD0hWupAnMntf3NdEaZ6871TZRSa3Yo1YRrYGxLoIRlBIHP7Pd2Xs1WLs2px000xs4YUHL");

        $product = 

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Abonnement à KineHub',
                    ],
                    'unit_amount' => 100, // Montant en centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'votre_url_de_succès',
            'cancel_url' => 'votre_url_d_annulation',
            ]);

        return $this->render('paiement/index.html.twig', [
            // 'stripeSessionId' => $session->id,
            // 'montant' => 1,
            // 'description' => 'Description du paiement',
        ]);
    }
}
