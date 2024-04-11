<?php

namespace App\Service;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;

readonly class CurrencyService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){
    }

    public function processCurrencyByResponse(array $response): void
    {
        foreach ($response as $responseItem) {
            $existsCurrency = $this->entityManager->getRepository(Currency::class)->findOneBy(
                ['name' => $responseItem->currency]
            );

            $processCurrency = $this->shouldProcessCurrency($responseItem->mid, $existsCurrency);

            if (!$processCurrency) {
                continue;
            }

            switch ($processCurrency) {
                case 'create':
                    $currency = (new Currency())
                        ->setName($responseItem->currency)
                        ->setCurrencyCode($responseItem->code)
                        ->setExchangeRate($responseItem->mid);
                    $this->entityManager->persist($currency);
                    break;
                case 'update':
                    $existsCurrency->setExchangeRate($responseItem->mid);
                    break;
                default:
                    continue 2;
            }
        }

        $this->entityManager->flush();
    }

    private function shouldProcessCurrency(float $newCurrencyValue, ?Currency $currency): string|bool
    {
        if (!isset($currency)) {
            return 'create';
        }

        if ($currency->getExchangeRate() !== $newCurrencyValue) {
            return 'update';
        }

        return false;
    }
}