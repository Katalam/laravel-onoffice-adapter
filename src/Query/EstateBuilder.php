<?php

declare(strict_types=1);

namespace Katalam\OnOfficeAdapter\Query;

use Illuminate\Support\Collection;
use Katalam\OnOfficeAdapter\Enums\OnOfficeAction;
use Katalam\OnOfficeAdapter\Enums\OnOfficeResourceType;
use Katalam\OnOfficeAdapter\Exceptions\OnOfficeException;
use Katalam\OnOfficeAdapter\Services\OnOfficeService;

class EstateBuilder extends Builder
{
    public function __construct(
        private readonly OnOfficeService $onOfficeService,
    ) {}

    public function get(): Collection
    {
        $columns = $this->columns;
        $filter = $this->getFilters();
        $listLimit = $this->limit;
        $listOffset = $this->offset;
        $orderBy = $this->getOrderBy();

        return $this->onOfficeService->requestAll(/**
         * @throws OnOfficeException
         */ function (int $pageSize, int $offset) use ($filter, $orderBy, $columns) {
            return $this->onOfficeService->requestApi(
                OnOfficeAction::Read,
                OnOfficeResourceType::Estate,
                parameters: [
                    OnOfficeService::DATA => $columns,
                    OnOfficeService::FILTER => $filter,
                    OnOfficeService::LISTLIMIT => $pageSize,
                    OnOfficeService::LISTOFFSET => $offset,
                    OnOfficeService::SORTBY => $orderBy,
                    ...$this->customParameters,
                ]
            );
        }, pageSize: $listLimit, offset: $listOffset);
    }

    /**
     * @throws OnOfficeException
     */
    public function first(): array
    {
        $columns = $this->columns;
        $filter = $this->getFilters();
        $listLimit = $this->limit;
        $listOffset = $this->offset;
        $orderBy = $this->getOrderBy();

        $response = $this->onOfficeService->requestApi(
            OnOfficeAction::Read,
            OnOfficeResourceType::Estate,
            parameters: [
                OnOfficeService::DATA => $columns,
                OnOfficeService::FILTER => $filter,
                OnOfficeService::LISTLIMIT => $listLimit,
                OnOfficeService::LISTOFFSET => $listOffset,
                OnOfficeService::SORTBY => $orderBy,
                ...$this->customParameters,
            ]
        );

        return $response->json('response.results.0.data.records.0');
    }

    /**
     * @throws OnOfficeException
     */
    public function find(int $id): array
    {
        $columns = $this->columns;

        $response = $this->onOfficeService->requestApi(
            OnOfficeAction::Get,
            OnOfficeResourceType::Estate,
            $id,
            parameters: [
                OnOfficeService::DATA => $columns,
                ...$this->customParameters,
            ]
        );

        return $response->json('response.results.0.data.records.0');
    }

    public function each(callable $callback): void
    {
        $columns = $this->columns;
        $filter = $this->getFilters();
        $listLimit = $this->limit;
        $listOffset = $this->offset;
        $orderBy = $this->getOrderBy();

        $this->onOfficeService->requestAllChunked(/**
         * @throws OnOfficeException
         */ function (int $pageSize, int $offset) use ($filter, $orderBy, $columns) {
            return $this->onOfficeService->requestApi(
                OnOfficeAction::Read,
                OnOfficeResourceType::Estate,
                parameters: [
                    OnOfficeService::DATA => $columns,
                    OnOfficeService::FILTER => $filter,
                    OnOfficeService::LISTLIMIT => $pageSize,
                    OnOfficeService::LISTOFFSET => $offset,
                    OnOfficeService::SORTBY => $orderBy,
                    ...$this->customParameters,
                ]
            );
        }, $callback, pageSize: $listLimit, offset: $listOffset);
    }

    /**
     * @throws OnOfficeException
     */
    public function modify(int $id): bool
    {
        $this->onOfficeService->requestApi(
            OnOfficeAction::Modify,
            OnOfficeResourceType::Estate,
            $id,
            parameters: $this->modifies,
        );

        return true;
    }
}
