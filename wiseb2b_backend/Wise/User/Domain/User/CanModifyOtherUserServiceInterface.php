<?php

namespace Wise\User\Domain\User;

use Wise\Security\Exception\AuthenticationException;

interface CanModifyOtherUserServiceInterface
{
    /**
     * Weryfikuje czy nasz użytkownik może modyfikować innego użytkownika
     *
     * @param int $userIdToModify
     * @param bool $strict Szczegółowa weryfikacja (uniemożliwiającego modyfikację superadmina, samego siebie)
     * @return void
     */
    public function check(int $userIdToModify, bool $strict = false): void;
}