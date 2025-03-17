<?php

namespace Wise\Core\Service;

use Wise\Core\Domain\Event\DomainEvent;
use Wise\Core\Dto\CommonServiceDTO;

/**
 * Klasa abstrakcyjna dla serwisów udostępniająca dodatkowe funkcjonalności
 */
abstract class AbstractService
{
    /**
     * Providery obsługujące parametry
     * @var iterable|null
     */
    private ?iterable $providersParams;

    /**
     * Providery obsługujące wynik serwisu
     * @var iterable|null
     */
    private ?iterable $providersResult;

    public function __construct(
        private readonly ServiceShareMethodsHelper $serviceShareMethodsHelper,
        //        #[TaggedIterator('service.service_params')]
        ?iterable $providersParams = null,
        ?iterable $providersResult = null,
    ){
        $this->providersParams = $providersParams;
        $this->providersResult = $providersResult;
    }

    /**
     * Przygotowanie parametrów za pomocą providerów
     * @param CommonServiceDTO $commonServiceDTO
     * @param string $class
     * @return void
     */
    protected function prepareParamsByProvider(CommonServiceDTO $commonServiceDTO, string $class): void
    {
        if($this->providersParams === null) {
            return;
        }

        foreach ($this->providersParams as $provider) {
            if ($provider->supports($commonServiceDTO, $class)) {
                $provider->prepareParams($commonServiceDTO, $class);
            }
        }
    }

    /**
     * Przygotowanie wyniku za pomocą providerów
     * @param CommonServiceDTO $commonServiceDTO
     * @param string $class
     * @return CommonServiceDTO
     */
    protected function prepareResultByProvider(CommonServiceDTO $commonServiceDTO, string $class): CommonServiceDTO
    {
        if($this->providersResult === null) {
            return $commonServiceDTO;
        }

        foreach ($this->providersParams as $provider) {
            if ($provider->supports($commonServiceDTO, $class)) {
                return $provider->prepareResult($commonServiceDTO, $class);
            }
        }

        return $commonServiceDTO;
    }

    /**
     * Wywołanie zdarzeń wewnętrznych
     * @return void
     */
    protected function dispatchInternalEvents(): void
    {
        $this->serviceShareMethodsHelper->eventsDispatcher->flushInternalEvents();
    }

    /**
     * Wywołanie zdarzeń zewnętrznych
     * @return void
     */
    protected function dispatchExternalEvents(): void
    {
        $this->serviceShareMethodsHelper->eventsDispatcher->flush();
    }

    /**
     * Wywołanie zdarzenia na zakończenie procesu serwisu
     * @param DomainEvent $event
     * @return void
     */
    protected function dispatchResultEvent(DomainEvent $event): void
    {
        $this->serviceShareMethodsHelper->eventsDispatcher->dispatchEvent($event);
    }
}
