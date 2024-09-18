<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Katalam\OnOfficeAdapter\Facades\SettingRepository;
use Katalam\OnOfficeAdapter\Facades\Testing\RecordFactories\UserFactory;
use Katalam\OnOfficeAdapter\Tests\Stubs\ReadUserResponse;

describe('fake responses', function () {
    test('get', function () {
        SettingRepository::fake(SettingRepository::response([
            SettingRepository::page(recordFactories: [
                UserFactory::make()
                    ->id(1),
            ]),
        ]));

        $response = SettingRepository::users()->get();

        expect($response->count())->toBe(1)
            ->and($response->first()['id'])->toBe(1);

        SettingRepository::assertSentCount(1);
    });
});

describe('real responses', function () {
    test('get', function () {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.onoffice.de/api/stable/api.php/' => Http::sequence([
                ReadUserResponse::make(),
            ]),
        ]);

        SettingRepository::record();

        $response = SettingRepository::users()->get();

        expect($response->count())->toBe(1);

        SettingRepository::assertSentCount(1);
    });
});
