<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Service\CurrencyService;
use App\Service\ExternalApiConnectionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CurrencyController extends AbstractController
{
    public function __construct(
        private readonly ExternalApiConnectionService $externalApiConnectionService,
        private readonly CurrencyService $currencyService
    ){
    }

    #[Route('/fetch_currencies', name: 'app_fetch_currencies')]
    public function fetch(EntityManagerInterface $entityManager): JsonResponse
    {
        $response = $this->externalApiConnectionService->getDecodeResponseByUriAndEndpoint(
            'https://api.nbp.pl/',
            'api/exchangerates/tables/a'
        );

        //Return catch error with HTTP code response
        if (array_key_exists('code', $response)) {
            return $this->json($response, $response['code']);
        }

        $this->currencyService->processCurrencyByResponse($response);

        return $this->json($entityManager->getRepository(Currency::class)->findAll());
    }
}
