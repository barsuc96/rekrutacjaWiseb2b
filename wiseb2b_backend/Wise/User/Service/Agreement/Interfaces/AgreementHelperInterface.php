<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement\Interfaces;

use Wise\User\Domain\Agreement\Agreement;

interface AgreementHelperInterface
{
    public function findAgreementForModify(array $data): ?Agreement;

    public function getAgreement(?int $id, ?string $symbol): Agreement;
}
