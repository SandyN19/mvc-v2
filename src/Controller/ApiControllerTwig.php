<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiControllerTwig extends AbstractController
{
    #[Route("/api", name: "api_start")]
    public function home(): Response
    {
        return $this->render('Api/home.html.twig');
    }
}
