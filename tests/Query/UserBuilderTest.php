<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Katalam\OnOfficeAdapter\Facades\SettingRepository;
use Katalam\OnOfficeAdapter\Tests\Stubs\ReadUserResponse;

it('works', function () {
    Http::preventStrayRequests();
    Http::fake([
        '*' => Http::sequence([
            // Each response will have 1500 users to simulate pagination
            ReadUserResponse::make(userId: 1, count: 1500),
            ReadUserResponse::make(userId: 2, count: 1500),
            ReadUserResponse::make(userId: 3, count: 1500),
        ]),
    ]);

    $users = SettingRepository::users()
        ->get();

    expect($users)
        ->toHaveCount(3)
        ->and($users->first()['id'])->toBe(1)
        ->and($users->last()['id'])->toBe(3);
});
