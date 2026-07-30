<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

/**
 * GHSA-prrm-3g6v-35vv: frontend berita write routes must not accept mutations.
 */
class BeritaAccessCest
{
    public function createIsForbidden(FunctionalTester $I)
    {
        $I->amOnRoute('berita/create');
        $I->seeResponseCodeIs(403);
    }

    public function updateIsForbidden(FunctionalTester $I)
    {
        $I->amOnRoute('berita/update', ['id' => 1]);
        $I->seeResponseCodeIs(403);
    }

    public function deleteIsForbidden(FunctionalTester $I)
    {
        $I->amOnRoute('berita/delete', ['id' => 1]);
        $I->seeResponseCodeIs(403);
    }

    public function indexRemainsPublic(FunctionalTester $I)
    {
        $I->amOnRoute('berita/index');
        $I->seeResponseCodeIs(200);
    }
}
