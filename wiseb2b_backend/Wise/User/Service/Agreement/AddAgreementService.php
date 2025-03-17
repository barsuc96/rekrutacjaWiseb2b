<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectExistsException;
use Wise\Core\Service\Merge\MergeService;
use Wise\User\Domain\Agreement\Agreement;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;
use Wise\User\Service\Agreement\Interfaces\AddAgreementServiceInterface;

class AddAgreementService implements AddAgreementServiceInterface
{
    public function __construct(
        protected readonly MergeService $mergeService,
        private readonly AgreementRepositoryInterface $repository,
    ) {}

    public function __invoke(CommonModifyParams $agreementServiceDto): CommonModifyParams
    {
        $newAgreementData = $agreementServiceDto->read();
        $id = $newAgreementData['id'] ?? null;

        if ($this->repository->findOneBy(['id' => $id])) {
            throw new ObjectExistsException(
                'Obiekt w bazie już istnieje. ID: ' . $id
            );
        }

        $newAgreement = new Agreement();
        $this->mergeService->merge($newAgreement,$agreementServiceDto->read());

        $newAgreement->validate();

        $newAgreement = $this->repository->save($newAgreement);

        ($resultDTO = new CommonModifyParams())->write($newAgreement);

        return $resultDTO;
    }
}
