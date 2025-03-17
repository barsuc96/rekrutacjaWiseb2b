<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\User\ApiUi\Dto\Users\UserAgreementsResponseDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersAgreementsServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\ListAllAggrementsForUserServiceInterface;
use Wise\User\Service\UserAgreement\ListAllAggrementsForUserServiceParams;

/**
 * Serwis pobierający dane zgód danego użytkownika, lista wszystkich z oznaczeniem, czy zaakceptowane i kiedy
 */
class GetUsersAgreementsService extends AbstractGetService implements GetUsersAgreementsServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListAllAggrementsForUserServiceInterface $service
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $joins = [];

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'contentLanguage') {
                continue;
            }

            if($field === "userId"){
                $filters[] = new QueryFilter('userId', $parameters->getInt('userId'));
                continue;
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields = [
        ];

        $fields = (new UserAgreementsResponseDto())->mergeWithMappedFields($fields);

        //Przekazanie parametrów do serwisu
        $params = new ListAllAggrementsForUserServiceParams();

        $params
            ->setFilters($filters)
            ->setJoins($joins)
            ->setFields($fields);

        $serviceDtoData = ($this->service)($params)->read();

        return (new UserAgreementsResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
