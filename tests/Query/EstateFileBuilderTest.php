<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Katalam\OnOfficeAdapter\Facades\EstateRepository;
use Katalam\OnOfficeAdapter\Tests\Stubs\GetEstatePicturesResponse;

it('works', function () {
    Http::preventStrayRequests();
    Http::fake([
        '*' => Http::sequence([
            // Each response will have 600 estates to simulate pagination
            GetEstatePicturesResponse::make(count: 1500),
            GetEstatePicturesResponse::make(count: 1500),
            GetEstatePicturesResponse::make(count: 1500),
        ]),
    ]);

    $estates = EstateRepository::files(1)
        ->get();

    expect($estates)
        ->toHaveCount(6);
});
