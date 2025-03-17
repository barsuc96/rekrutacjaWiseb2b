<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader;

use Exception;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Helper\Object\ObjectNonModelFieldsHelper;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonListParams;
use Wise\User\Domain\Trader\TraderRepositoryInterface;
use Wise\User\Domain\Trader\TraderServiceInterface;
use Wise\User\Domain\User\User;
use Wise\User\Service\Trader\Interfaces\ListTradersServiceInterface;

/**
 * Serwis to wyciągania listy traderów
 */
class ListTradersService extends AbstractListService implements ListTradersServiceInterface
{
    public function __construct(
        private readonly TraderRepositoryInterface $repository,
    ) {
        parent::__construct($repository);
    }

    /**
     * Lista pól, które mają być obsługiwane w filtrowaniu z pola searchKeyword
     * @return string[]
     */
    protected function getDefaultSearchFields(): array
    {
        return [
            'firstName',
            'lastName',
            'email',
            'phone',
        ];
    }
}
