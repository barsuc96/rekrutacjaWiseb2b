<?php

namespace Wise\Security\Tests\AdminApi;

use Codeception\Util\HttpCode;
use Wise\Security\Tests\Support\AdminApiTester;

class AuthorizationTokenCest
{
    public function shouldReturnValidAuthTokenForValidCredentials(AdminApiTester $I): void
    {
        $I->wantToTest('POST /admin/token - Endpoint returns correct data for correct input data');
        $I->sendPostAsJson('/token', [
            'client_id' => 'dea6d5hagv0c342d674o087Ef3e13E7g',
            'client_secret' => '8eefe0a060250c96cc9ee1bada11d4069ca6553b9dae4f81180f9777866db0799010e9fe75a0244d209924d1337946393f1682a5d52e07a738dc842891d97509',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'token_type' => 'string',
            'expires_in' => 'integer',
            'access_token' => 'string',
        ]);
        $I->dontSeeResponseJsonMatchesXpath('//error');
    }

    public function shouldReturnErrorAuthTokenForUnvalidCredentials(AdminApiTester $I): void
    {
        $I->wantToTest('POST /admin/token - Endpoint returns error for invalid input data');
        $I->sendPostAsJson('/token', [
            'client_id' => 'not_valid_client_id',
            'client_secret' => 'not_valid_client_secret',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'error' => 'string',
            'error_description' => 'string',
            'message' => 'string',
        ]);
        $I->seeResponseContainsJson([
            'error' => 'invalid_client',
            'error_description' => 'Client authentication failed',
            'message' => 'Client authentication failed',
        ]);
        $I->dontSeeResponseJsonMatchesXpath('//access_token');
    }

    public function shouldReturnErrorAuthTokenForMissingCredentials(AdminApiTester $I): void
    {
        $I->wantToTest('POST /admin/token - Endpoint returns error with missing input data');
        $I->sendPostAsJson('/token', [
            'client_secret' => 'not_valid_client_secret',
            'client_id' => 'not_valid_client_id',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'error' => 'string',
            'error_description' => 'string',
            'message' => 'string',
        ]);
        $I->seeResponseContainsJson([
            'error' => 'invalid_client',
            'error_description' => 'Client authentication failed',
            'message' => 'Client authentication failed',
        ]);
        $I->dontSeeResponseJsonMatchesXpath('//access_token');
    }

    public function shouldReturnErrorAuthTokenForEmptyCredentials(AdminApiTester $I): void
    {
        $I->wantToTest('POST /admin/token - Endpoint returns error with empty input data');
        $I->sendPostAsJson('/token', [
            'client_id' => '',
            'client_secret' => '',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'error' => 'string',
            'error_description' => 'string',
            'message' => 'string',
        ]);
        $I->seeResponseContainsJson([
            'error' => 'invalid_client',
            'error_description' => 'Client authentication failed',
            'message' => 'Client authentication failed',
        ]);
        $I->dontSeeResponseJsonMatchesXpath('//access_token');
    }

    public function shouldReturnErrorAuthTokenWithoutCredentials(AdminApiTester $I): void
    {
        $I->wantToTest('POST /admin/token - Endpoint returns error without input data');
        $I->sendPostAsJson('/token');

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);
        $I->dontSeeResponseJsonMatchesXpath('//access_token');
    }
}
