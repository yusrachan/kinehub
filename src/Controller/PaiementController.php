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
        \Stripe\Stripe::setApiKey('pk_live_51OVEizFtm1LR4NNUF7eHQTFxXtch480Cb59fC6tXHSU8ubDAjwJ1fsvemnRKyS30uP6HxmHSOWbIoeRynyzSQIkq00YmqN3zxX');

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Nom du produit ou service',
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
