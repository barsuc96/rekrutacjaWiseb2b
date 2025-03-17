<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Service\Merge\MergeService;
use Wise\User\Domain\Agreement\Agreement;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;
use Wise\User\Service\Agreement\Interfaces\AgreementHelperInterface;
use Wise\User\Service\Agreement\Interfaces\ModifyAgreementServiceInterface;

class ModifyAgreementService implements ModifyAgreementServiceInterface
{
    public function __construct(
        protected readonly MergeService $mergeService,
        private readonly AgreementRepositoryInterface $repository,
        private readonly AgreementHelperInterface $helper,
    ) {}

    public function __invoke(CommonModifyParams $agreementServiceDto): CommonModifyParams
    {
        $newAgreementData = $agreementServiceDto->read();
        $id = $newAgreementData['id'] ?? null;
        $agreement = $this->helper->findAgreementForModify($newAgreementData);

        if (!isset($agreement) || !($agreement instanceof Agreement)) {
            throw new ObjectNotFoundException(
                'Obiekt w bazie nie istnieje. ID: ' . $id
            );
        }

        $this->mergeService->merge($agreement, $newAgreementData, $agreementServiceDto->getMergeNestedObjects());

        $agreement->validate();

        $agreement = $this->repository->save($agreement);

        ($resultDTO = new CommonModifyParams())->write($agreement);

        return $resultDTO;
    }
}
