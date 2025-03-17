<?php

namespace Wise\Core\Tests\Nelmio;

use Codeception\Util\HttpCode;
use Wise\Core\Tests\Support\NelmioTester;

class NelmioWorkCest
{

    public function workingUiApi(NelmioTester $I){
        $I->wantToTest('ui-api/nelmio - Nelmio UiAPI działa poprawnie');
        $I->sendGet('ui-api/nelmio');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function correctUiApiJSON(NelmioTester $I){
        $I->wantToTest('ui-api/doc.json - UiAPI zwraca poprawny JSON OpenAPI');
        $I->sendGet('ui-api/doc.json');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function workingAdminApi(NelmioTester $I){
        $I->wantToTest('admin-api/nelmio - Nelmio AdminAPI działa poprawnie');
        $I->sendGet('admin-api/nelmio');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function correctAdminApiJSON(NelmioTester $I){
        $I->wantToTest('admin-api/doc.json - AdminAPI zwraca poprawny JSON OpenAPI ');
        $I->sendGet('admin-api/doc.json');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}