<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'session_start')]
    public function home(SessionInterface $session): Response
    {
        $data = [
            'session' => $session->all(),
        ];

        return $this->render('session.html.twig', $data);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function delete(SessionInterface $session): Response
    {
        $this->addFlash(
            'notice',
            'Session is now deleted!'
        );
        // clear session (clear() returns void)
        $session->clear();

        return $this->redirectToRoute('session_start');
    }
}
