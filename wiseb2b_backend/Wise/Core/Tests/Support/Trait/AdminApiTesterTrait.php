<?php

namespace Wise\Core\Tests\Support\Trait;

use Symfony\Component\Uid\Uuid;

trait AdminApiTesterTrait
{
    /**
     * Get Authorization Token.
     *
     * Set is via $I->amBearerAuthenticated($I->takeAuthorizationCode());
     *
     * @return string
     */
    public function takeAuthorizationCode(): string
    {
        $data = [
            'client_id' => 'dea6d5hagv0c342d674o087Ef3e13E7g',
            'client_secret' => '8eefe0a060250c96cc9ee1bada11d4069ca6553b9dae4f81180f9777866db0799010e9fe75a0244d209924d1337946393f1682a5d52e07a738dc842891d97509',
        ];

        $response = $this->sendPostAsJson('/token', $data);

        return $response['access_token'];
    }

    public function takeRequestUuid(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
