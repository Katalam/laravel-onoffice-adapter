<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Innobrain\OnOfficeAdapter\Dtos\OnOfficeRequest;
use Innobrain\OnOfficeAdapter\Dtos\OnOfficeResponse;
use Innobrain\OnOfficeAdapter\Enums\OnOfficeAction;
use Innobrain\OnOfficeAdapter\Enums\OnOfficeResourceType;
use Innobrain\OnOfficeAdapter\Facades\Testing\RecordFactories\BaseFactory;
use Innobrain\OnOfficeAdapter\Repositories\BaseRepository;

describe('stray requests', function () {
    it('will set the preventStrayRequests property when calling preventStrayRequests default', function () {
        $builder = new BaseRepository;

        $builder->preventStrayRequests();

        $m = new ReflectionProperty($builder, 'preventStrayRequests');
        $m->setAccessible(true);

        expect($m->getValue($builder))->toBe(true);
    });

    it('will set the preventStrayRequests property when calling preventStrayRequests with values', function ($value) {
        $builder = new BaseRepository;

        $builder->preventStrayRequests($value);

        $m = new ReflectionProperty($builder, 'preventStrayRequests');
        $m->setAccessible(true);

        expect($m->getValue($builder))->toBe($value);
    })->with([true, false]);

    it('will set the preventStrayRequest property when calling allowStrayRequests', function () {
        $builder = new BaseRepository;

        $builder->preventStrayRequests();
        $builder->preventStrayRequests(false);

        $m = new ReflectionProperty($builder, 'preventStrayRequests');
        $m->setAccessible(true);

        expect($m->getValue($builder))->toBe(false);
    });
});

describe('record', function () {
    it('will set the recording property when calling record default', function () {
        $builder = new BaseRepository;

        $builder->record();

        $m = new ReflectionProperty($builder, 'recording');
        $m->setAccessible(true);

        expect($m->getValue($builder))->toBe(true);
    });

    it('will set the recording property when calling stop recording', function () {
        $builder = new BaseRepository;

        $builder->record();
        $builder->stopRecording();

        $m = new ReflectionProperty($builder, 'recording');
        $m->setAccessible(true);

        expect($m->getValue($builder))->toBe(false);
    });

    it('will set the recording property when calling record with values', function ($value) {
        $builder = new BaseRepository;

        $builder->record($value);

        $m = new ReflectionProperty($builder, 'recording');
        $m->setAccessible(true);

        expect($m->getValue($builder))->toBe($value);
    })->with([true, false]);

    it('will add the request and response to the recorded property', function () {
        $builder = new BaseRepository;

        $builder->record();
        $builder->recordRequestResponsePair(new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate), ['response']);

        $m = new ReflectionProperty($builder, 'recorded');
        $m->setAccessible(true);

        expect($m->getValue($builder))->toBeArray()
            ->and($m->getValue($builder)[0][0]->toArray())->toBe((new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate))->toArray())
            ->and($m->getValue($builder)[0][1])->toBe(['response']);
    });

    it('will not add the request and response to the recorded property when recording is off', function () {
        $builder = new BaseRepository;

        $builder->recordRequestResponsePair(new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate), ['response']);

        $m = new ReflectionProperty($builder, 'recorded');
        $m->setAccessible(true);

        expect($m->getValue($builder))->toBe([]);
    });
});

describe('fake', function () {
    it('will add the response to the stubCallables property', function () {
        $builder = new BaseRepository;

        $builder->fake(new OnOfficeResponse(collect()));

        $m = new ReflectionProperty($builder, 'stubCallables');
        $m->setAccessible(true);

        expect($m->getValue($builder)->toArray()[0])->toBeInstanceOf(OnOfficeResponse::class);
    });

    it('will add the response to the stubCallables property when calling fake with an array', function () {
        $builder = new BaseRepository;

        $builder->fake([new OnOfficeResponse(collect())]);

        $m = new ReflectionProperty($builder, 'stubCallables');
        $m->setAccessible(true);

        expect($m->getValue($builder)->toArray()[0])->toBeInstanceOf(OnOfficeResponse::class);
    });

    it('can fake a sequence', function () {
        $builder = new BaseRepository;

        $builder->fake($builder->sequence(
            new OnOfficeResponse(collect()),
            20,
        ));

        $m = new ReflectionProperty($builder, 'stubCallables');
        $m->setAccessible(true);

        expect($m->getValue($builder)->toArray())->toHaveCount(20);
    });

    it('can fake a response with more than one page', function () {
        $builder = new BaseRepository;

        $builder->fake([
            $builder->response([
                $builder->page(recordFactories: [
                    BaseFactory::make(),
                ]),
                $builder->page(recordFactories: [
                    BaseFactory::make(),
                ]),
            ]),
        ]);

        $result = $builder->query()->call(new OnOfficeRequest(
            OnOfficeAction::Read,
            OnOfficeResourceType::Estate,
        ));

        expect($result->count())->toBe(2);
    });

    it('can fake a response with more than one page and another response', function () {
        $builder = new BaseRepository;

        $builder->fake([
            $builder->response([
                $builder->page(recordFactories: [
                    BaseFactory::make(),
                ]),
                $builder->page(recordFactories: [
                    BaseFactory::make(),
                ]),
            ]),
            $builder->response([
                $builder->page(recordFactories: [
                    BaseFactory::make(),
                ]),
            ]),
        ]);

        $result = $builder->query()->call(new OnOfficeRequest(
            OnOfficeAction::Read,
            OnOfficeResourceType::Estate,
        ));

        expect($result->count())->toBe(2);

        $result = $builder->query()->call(new OnOfficeRequest(
            OnOfficeAction::Read,
            OnOfficeResourceType::Estate,
        ));

        expect($result->count())->toBe(1);
    });

    it('can fake a response with more than one page and another response on each page', function () {
        $builder = new BaseRepository;

        $builder->fake([
            $builder->response([
                $builder->page(recordFactories: [
                    BaseFactory::make(),
                ]),
                $builder->page(recordFactories: [
                    BaseFactory::make(),
                ]),
            ]),
            $builder->response([
                $builder->page(recordFactories: [
                    BaseFactory::make(),
                ]),
            ]),
        ]);

        $result = [];
        $builder->query()->chunked(new OnOfficeRequest(
            OnOfficeAction::Read,
            OnOfficeResourceType::Estate,
        ), function (array $records) use (&$result) {
            $result[] = count($records);
        });

        expect($result[0])->toBe(1)
            ->and($result[1])->toBe(1);

        $builder->query()->chunked(new OnOfficeRequest(
            OnOfficeAction::Read,
            OnOfficeResourceType::Estate,
        ), function (array $records) use (&$result) {
            $result[] = count($records);
        });

        expect($result[2])->toBe(1);
    });
});

describe('assert', function () {
    it('can find a record by callable', function () {
        $builder = new BaseRepository;

        $builder->record();
        $builder->recordRequestResponsePair(new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate), ['response']);

        $filteredRecordings = $builder->recorded(function (OnOfficeRequest $request, array $response) {
            return $request->actionId === OnOfficeAction::Read;
        });

        expect($filteredRecordings)->toBeInstanceOf(Collection::class)
            ->and($filteredRecordings[0][0]->toArray())->toBe((new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate))->toArray())
            ->and($filteredRecordings[0][1])->toBe(['response']);
    });

    it('can assert sent', function () {
        $builder = new BaseRepository;

        $builder->record();
        $builder->recordRequestResponsePair(new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate), ['response']);

        $builder->assertSent(function (OnOfficeRequest $request) {
            return $request->actionId === OnOfficeAction::Read;
        });
    });

    it('can assert sent with a response', function () {
        $builder = new BaseRepository;

        $builder->record();
        $builder->recordRequestResponsePair(new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate), ['response']);

        $builder->assertSent(function (OnOfficeRequest $request, array $response) {
            return $request->actionId === OnOfficeAction::Read && $response === ['response'];
        });
    });

    it('can assert not sent', function () {
        $builder = new BaseRepository;

        $builder->record();
        $builder->recordRequestResponsePair(new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate), ['response']);

        $builder->assertNotSent(function (OnOfficeRequest $request) {
            return $request->actionId === OnOfficeAction::Create;
        });
    });

    it('can assert not sent with a response', function () {
        $builder = new BaseRepository;

        $builder->record();
        $builder->recordRequestResponsePair(new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate), ['response']);

        $builder->assertNotSent(function (OnOfficeRequest $request, array $response) {
            return $request->actionId === OnOfficeAction::Create && $response === ['response'];
        });
    });

    it('can assert sent count', function () {
        $builder = new BaseRepository;

        $builder->record();
        $builder->recordRequestResponsePair(new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate), ['response']);
        $builder->recordRequestResponsePair(new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate), ['response']);

        $builder->assertSentCount(2);
    });
});

describe('request', function () {
    it('will call once', function () {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.onoffice.de/api/stable/api.php/' => Http::response([
                'status' => [
                    'code' => 200,
                ],
            ]),
        ]);

        $builder = new BaseRepository;

        $request = new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate);

        $builder->query()->once($request);

        Http::assertSentCount(1);
    });

    it('will call call', function () {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.onoffice.de/api/stable/api.php/' => Http::response([
                'status' => [
                    'code' => 200,
                ],
            ]),
        ]);

        $builder = new BaseRepository;

        $request = new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate);

        $builder->query()->call($request);

        Http::assertSentCount(1);
    });

    it('will call chunked', function () {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.onoffice.de/api/stable/api.php/' => Http::response([
                'status' => [
                    'code' => 200,
                ],
            ]),
        ]);

        $builder = new BaseRepository;

        $request = new OnOfficeRequest(OnOfficeAction::Read, OnOfficeResourceType::Estate);

        $builder->query()->chunked($request, function () {
            return true;
        });

        Http::assertSentCount(1);
    });
});
