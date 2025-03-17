<?php

declare(strict_types=1);

namespace Wise\User\Domain\Agreement;

use Wise\Core\Repository\RepositoryInterface;

interface AgreementTranslationRepositoryInterface extends RepositoryInterface
{
    public function removeByAgreementId(int $agreementId, bool $flush = false): void;
}
