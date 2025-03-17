<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonListParams;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Wise\Receiver\Domain\Receiver\Service\Interfaces\ReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiversServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverAdditionalFieldsServiceInterface;

/**
 * Serwis to wyciągania listy odbirców
 */
class ListReceiversService extends AbstractListService implements ListReceiversServiceInterface
{
    /**
     * Czy umożliwiać wyszukiwanie za pomocą searchKeyword
     */
    protected const ENABLE_SEARCH_KEYWORD = true;

    /**
     * Czy zwracać liczbę porządkową
     */
    protected const INCLUDE_LP_FIELD = true;


    public function __construct(
        private readonly ReceiverRepositoryInterface $repository,
        private readonly ReceiverServiceInterface $receiverService,
        private readonly ?ReceiverAdditionalFieldsServiceInterface $additionalFieldsService = null,
    ) {
        parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Lista pól, które mają być obsługiwane w filtrowaniu z pola searchKeyword
     * @return string[]
     */
    protected function getDefaultSearchFields(): array
    {
        return [
            'name',
            'email',
            'phone',
        ];
    }

    /**
     * Zwraca listę joinów dołączonych do zapytania
     * @param CommonListParams $params
     * @param QueryFilter[] $filters
     * @return array
     */
    protected function prepareJoins(CommonListParams $params, array $filters): array
    {
        return $this->receiverService->prepareJoins($params->getFields());
    }
}
