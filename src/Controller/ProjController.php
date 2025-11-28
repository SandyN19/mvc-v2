<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProjController extends AbstractController
{
    #[Route('/proj', name: 'project_start')]
    public function projHome(): Response
    {
        return $this->render('proj/home.html.twig');
    }

    #[Route('/proj/init', name: 'project_init_get', methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('proj/init.html.twig');
    }

    #[Route('/proj/init', name: 'project_init_post', methods: ['POST'])]
    public function initCallback(
        SessionInterface $session,
        Request $request
    ): Response {
        $username = $request->request->get('username');
        $handCount = $request->request->get('handCount');
        $bet = $request->request->get('bet');
        $session->set('username', $username);
        $session->set('handCount', $handCount);
        $session->set('bet', $bet);

        if (!$session->has('bank')) {
            $session->set('bank', 1000);
        }

        return $this->redirectToRoute('project_play');

    }

    #[Route('/proj/about', name: 'project_about')]
    public function doc(): Response
    {
        return $this->render('proj/about.html.twig');
    }

}
