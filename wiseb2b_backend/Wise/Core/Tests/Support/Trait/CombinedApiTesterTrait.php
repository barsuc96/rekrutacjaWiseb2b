<?php
namespace Wise\Core\Tests\Support\Trait;

trait CombinedApiTesterTrait
{
    /**
     * Get Admin Api Authorization Token.
     *
     * Set is via $I->amBearerAuthenticated($I->takeAuthorizationCode());
     *
     * @return string
     */
    public function takeAdminAuthorizationCode(): string
    {
        $data = [
            'client_id' => 'dea6d5hagv0c342d674o087Ef3e13E7g',
            'client_secret' => '8eefe0a060250c96cc9ee1bada11d4069ca6553b9dae4f81180f9777866db0799010e9fe75a0244d209924d1337946393f1682a5d52e07a738dc842891d97509',
        ];

        $response = $this->sendPostAsJson('/admin/token', $data);
        return $response['access_token'];
    }

    /**
     * Get Ui Api Authorization Token.
     *
     * Set is via $I->amBearerAuthenticated($I->takeAuthorizationCode());
     *
     * @return string
     */
    public function takeUiAuthorizationCode(): string
    {
        $data = [
            "username" => "biuro@sente.pl",
            "password" => "przykladowehaslo",
            "client_id" => "ff65a8109ad27bggggbe036d08b7abb9",
            "client_secret" => "6bgggaa06d4b9437b42c51e5ee57092a91b933450d1ce3d6d087b0855130df5b8cc188968aa357b355dfe4755c95e53cd0ea3b85ae47162e0637816736202b03"
        ];
        $response = $this->sendPostAsJson('/ui/auth/login', $data);
        return $response['access_token'];
    }
}
