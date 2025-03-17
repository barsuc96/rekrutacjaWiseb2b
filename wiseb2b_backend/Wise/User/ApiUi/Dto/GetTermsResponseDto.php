<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto;

class GetTermsResponseDto
{
    /** @var TermsDto */
    protected TermsDto $userAgreement;
    protected TermsDto $userRodoAgreement;
    protected TermsDto $userDataProcessingAgreement;

    public function getUserAgreement(): TermsDto
    {
        return $this->userAgreement;
    }

    public function setUserAgreement(TermsDto $userAgreement): self
    {
        $this->userAgreement = $userAgreement;

        return $this;
    }

    public function getUserRodoAgreement(): TermsDto
    {
        return $this->userRodoAgreement;
    }

    public function setUserRodoAgreement(TermsDto $userRodoAgreement): self
    {
        $this->userRodoAgreement = $userRodoAgreement;

        return $this;
    }

    public function getUserDataProcessingAgreement(): TermsDto
    {
        return $this->userDataProcessingAgreement;
    }

    public function setUserDataProcessingAgreement(TermsDto $userDataProcessingAgreement): self
    {
        $this->userDataProcessingAgreement = $userDataProcessingAgreement;

        return $this;
    }
}
