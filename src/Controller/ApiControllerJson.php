<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiControllerJson
{
    #[Route("/api/quote")]
    public function jsonQuote(): Response
    {

        $quotes = [
            'Be yourself; everyone else is already taken. - Oscar Wilde',
            'So many books, so little time. - Frank Zappa',
            "Two things are infinite: the universe and human stupidity; and Im not sure about the universe. - Albert Einstein",
            "Im selfish, impatient and a little insecure. I make mistakes, I am out of control and at times hard to handle. But if you cant handle me at my worst, then you sure as hell dont deserve me at my best. - Marilyn Monroe"
        ];

        $data = [
            'Today-date' => date('Y-m-d H:i:s'),
            'random-quote' => $quotes[array_rand($quotes)],
        ];

        //return new JsonResponse($data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
