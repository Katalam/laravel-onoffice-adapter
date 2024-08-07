<?php

declare(strict_types=1);

namespace Katalam\OnOfficeAdapter\Query;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Katalam\OnOfficeAdapter\Enums\OnOfficeAction;
use Katalam\OnOfficeAdapter\Enums\OnOfficeResourceType;
use Katalam\OnOfficeAdapter\Exceptions\OnOfficeException;
use Katalam\OnOfficeAdapter\Services\OnOfficeService;

class FieldBuilder extends Builder
{
    public array $modules = [];

    public function __construct(
        private readonly OnOfficeService $onOfficeService,
    ) {}

    public function get(): Collection
    {
        return $this->onOfficeService->requestAll(/**
         * @throws OnOfficeException
         */ function () {
            return $this->onOfficeService->requestApi(
                OnOfficeAction::Get,
                OnOfficeResourceType::Fields,
                parameters: [
                    'modules' => $this->modules,
                    ...$this->customParameters,
                ],
            );
        });
    }

    /**
     * @throws OnOfficeException
     */
    public function first(): array
    {
        $response = $this->onOfficeService->requestApi(
            OnOfficeAction::Get,
            OnOfficeResourceType::Fields,
            parameters: [
                'modules' => $this->modules,
                ...$this->customParameters,
            ],
        );

        return $response->json('response.results.0.data.records.0');
    }

    /**
     * @throws OnOfficeException
     */
    public function find(int $id): array
    {
        throw new OnOfficeException('Not implemented in onOffice');
    }

    public function each(callable $callback): void
    {
        $this->onOfficeService->requestAllChunked(/**
         * @throws OnOfficeException
         */ function () {
            return $this->onOfficeService->requestApi(
                OnOfficeAction::Get,
                OnOfficeResourceType::Fields,
                parameters: [
                    'modules' => $this->modules,
                    ...$this->customParameters,
                ],
            );
        }, $callback);
    }

    public function withModules(array|string $modules): static
    {
        $this->modules = array_merge($this->modules, Arr::wrap($modules));

        return $this;
    }

    /**
     * @throws OnOfficeException
     */
    public function modify(int $id): bool
    {
        throw new OnOfficeException('Not implemented');
    }
}
