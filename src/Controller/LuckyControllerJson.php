<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
            'Quote' => 'api/quote',
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/quote", name: "api/quote")]
    public function apiQuote(): Response
    {
        date_default_timezone_set("Europe/Stockholm");
        $quotes = array("You can totally do this.",
        "We can do anything we want to if we stick to it long enough.",
        "The fastest road to meaning and success: choose one thing and go all in.",
        "Try again. Fail again. Fail better.",
        "Impossible is for the unwilling",
        "I can and I will.",
        "No pressure, no diamonds.",
        "Don’t tell people your plans. Show them your results.");

        $number = random_int(0, count($quotes) -1);
        $quote = $quotes[$number];

        $data = [
            'Todays-quote' => $quote,
            'time' =>  date('Y-m-d H:i:s'),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}