<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\User\ApiUi\Dto\Users\GetUserResponseDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\User\Interfaces\ListUsersForCurrentUserServiceInterface;

class GetUsersService extends AbstractGetService implements GetUsersServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListUsersForCurrentUserServiceInterface $service,
        private readonly TranslatorInterface $translator,
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $searchKeyword = null;

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'contentLanguage') {
                continue;
            }

            if ($field === 'searchKeyword') {
                $searchKeyword = $value;
                continue;
            }

            if ($field === 'role') {
                if(empty($value)){
                    continue;
                }else{
                    $field = 'roleId';
                    $value = UserRoleEnum::fromName($value);
                }
            }

            $filters[] = new QueryFilter($field, $value);
        }

        // Nie umieszczać w listingu użytkowników wygenerowanych dla ClientApi
        $filters[] = new QueryFilter('roleId', [7], QueryFilter::COMPARATOR_NOT_IN);

        $fields = [
            'status' => 'isActive',
        ];

        $fields = (new GetUserResponseDto())->mergeWithMappedFields($fields);

        //Przekazanie parametrów do serwisu
        $params = new CommonListParams();

        $params
            ->setFilters($filters)
            ->setFields($fields)
            ->setSearchKeyword($searchKeyword)
            ->setFetchTotalCount(true);;

        $serviceDto = ($this->service)($params);
        $serviceDtoData = $serviceDto->read();
        $this->setTotalCount($serviceDto->getTotalCount());

        $this->fillRoleName($serviceDtoData);

        return (new GetUserResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }

    protected function fillRoleName(?array &$users)
    {
        foreach ($users as &$user){
            $user['role'] = $this->translator->trans('user.role.' . $user['role']);
        }
    }
}
