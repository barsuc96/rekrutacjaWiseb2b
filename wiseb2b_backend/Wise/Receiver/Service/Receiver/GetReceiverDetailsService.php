<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Wise\Cart\Domain\Cart\Cart;
use Wise\Client\Domain\Client\Client;
use Wise\Core\Helper\QueryFilter\QueryJoinsHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryJoin;
use Wise\Core\Service\AbstractDetailsService;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Wise\Receiver\Service\Receiver\Interfaces\GetReceiverDetailsServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverAdditionalFieldsServiceInterface;

/**
 * Serwis to wyciągania szczegółów odbiorcy
 */
class GetReceiverDetailsService extends AbstractDetailsService implements GetReceiverDetailsServiceInterface
{
    /**
     * Pola obsługiwane ręcznie przez metody
     * Klucz to nazwa pola a wartość to nazwa metody obsługującej
     */
    protected const MANUALLY_HANDLED_FIELDS = [
        'lp' => 'prepareLp',
    ];

    public function __construct(
        private readonly ReceiverRepositoryInterface $repository,
        private readonly ?ReceiverAdditionalFieldsServiceInterface $additionalFieldsService = null,
    ){
        return parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Zwraca listę joinów dołączonych do zapytania
     * @param CommonDetailsParams $params
     * @param QueryFilter[] $filters
     * @return array
     */
    protected function prepareJoins(CommonDetailsParams $params, array $filters): array
    {
        $joins = [];

        $fieldsWhichRequireJoin = QueryJoinsHelper::prepareFieldsWhichRequireJoinsByFieldNames($params->getFields());

        if(array_key_exists('clientId', $fieldsWhichRequireJoin) || array_key_exists('cart', $fieldsWhichRequireJoin)){
            $joins[] = new QueryJoin(Client::class, 'client', ['clientId' => 'client.id']);
        }

        if (array_key_exists('cart', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(Cart::class,  'cart', ['client.id' => 'cart.clientId']);
        }

        return $joins;
    }

    public function prepareLp(array $entity, array $fields)
    {
        $entity['lp'] = 1;

        return $entity;
    }
}
