<?php

namespace Wise\Core\Tests\Support\Trait;

use Symfony\Component\Uid\Uuid;

trait UiApiTesterTrait
{
    /**
     * Get Ui Api Authorization Token.
     * Set is via $I->amBearerAuthenticated($I->takeAuthorizationCode());
     * @return string
     */
    public function takeAuthorizationCode(): string
    {
        $data = [
            "username" => "biuro@sente.pl",
            "password" => "przykladowehaslo",
            "client_id" => "ff65a8109ad27bggggbe036d08b7abb9",
            "client_secret" => "6bgggaa06d4b9437b42c51e5ee57092a91b933450d1ce3d6d087b0855130df5b8cc188968aa357b355dfe4755c95e53cd0ea3b85ae47162e0637816736202b03"
        ];
        $response = $this->sendPostAsJson('/auth/login', $data);
        return $response['access_token'];
    }

    public function takeRequestUuid(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
