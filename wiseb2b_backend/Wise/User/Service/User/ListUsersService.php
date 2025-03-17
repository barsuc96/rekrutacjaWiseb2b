<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonListParams;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\User\UserServiceInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;

/**
 * Serwis to wyciągania listy użytkowników
 */
class ListUsersService extends AbstractListService implements ListUsersServiceInterface
{
    /**
     * Czy umożliwiać wyszukiwanie za pomocą searchKeyword
     */
    protected const ENABLE_SEARCH_KEYWORD = true;

    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly ?UserAdditionalFieldsService $additionalFieldsService,
        private readonly UserServiceInterface $userService
    ) {
        parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Zwraca listę joinów dołączonych do zapytania
     * @param CommonListParams $params
     * @param QueryFilter[] $filters
     * @return array
     */
    protected function prepareJoins(CommonListParams $params, array $filters): array
    {
        return $this->userService->prepareJoins($params->getFields());
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
            'login',
            'clientId.name',
            'clientId.taxNumber',
        ];
    }
}
