<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Currency;
use App\Service\CurrencyService;
use App\Service\ExternalApiConnectionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CurrencyProvider extends AbstractController implements ProviderInterface
{
    public function __construct(
        private readonly ExternalApiConnectionService $externalApiConnectionService,
        private readonly CurrencyService $currencyService,
        private readonly EntityManagerInterface $entityManager,
    ){
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $wholeEndpoint = $this->getParameter('app.nbp_exchange_rates_endpoint').$this->getParameter('app.nbp_exchange_rates_table_one');
        $response = $this->externalApiConnectionService->getDecodeResponseByUriAndEndpoint(
            $this->getParameter('app.nbp_api_url'),
            $wholeEndpoint
        );

        //Return catch error with HTTP code response
        if (array_key_exists('code', $response)) {
            return [$response, $response['code']];
        }

        $this->currencyService->processCurrencyByResponse($response);

        return $this->entityManager->getRepository(Currency::class)->findAll();
    }
}
