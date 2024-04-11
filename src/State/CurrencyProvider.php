<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Currency;
use App\Service\CurrencyService;
use App\Service\ExternalApiConnectionService;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyProvider implements ProviderInterface
{
    public function __construct(
        private readonly ExternalApiConnectionService $externalApiConnectionService,
        private readonly CurrencyService $currencyService,
        private readonly EntityManagerInterface $entityManager
    ){
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $response = $this->externalApiConnectionService->getDecodeResponseByUriAndEndpoint(
            'https://api.nbp.pl/',
            'api/exchangerates/tables/a'
        );

        //Return catch error with HTTP code response
        if (array_key_exists('code', $response)) {
            return [$response, $response['code']];
        }

        $this->currencyService->processCurrencyByResponse($response);

        return $this->entityManager->getRepository(Currency::class)->findAll();
    }
}
