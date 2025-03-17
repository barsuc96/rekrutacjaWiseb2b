<?php

namespace Wise\Core\Service;

/**
 * Klasa udostępniająca metody pomocnicze dla serwisów
 * Wykorzystywana w klasach serwisów dziedziczącej bo AbstractService
 * Służy do udostępniania serwisów. Pozwala na uniknięcie duplikacji kodu w klasach serwisów oraz udostępnienie klas
 */
class ServiceShareMethodsHelper
{
    public function __construct(
        public readonly DomainEventsDispatcher $eventsDispatcher,
    ){}
}
