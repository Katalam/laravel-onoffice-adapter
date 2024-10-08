<?php

declare(strict_types=1);

use Innobrain\OnOfficeAdapter\Query\FieldBuilder;
use Innobrain\OnOfficeAdapter\Repositories\FieldRepository;

describe('withModules', function () {
    it('should set the modules property to the given modules', function () {
        $builder = new FieldBuilder;
        $builder->setRepository(app(FieldRepository::class));

        $builder->withModules(['estate']);

        expect($builder->modules)->toBe(['estate']);
    });

    it('should wrap the given modules in an array if it is a string', function () {
        $builder = new FieldBuilder;
        $builder->setRepository(app(FieldRepository::class));

        $builder->withModules('estate');

        expect($builder->modules)->toBe(['estate']);
    });

    it('should return the builder instance', function () {
        $builder = new FieldBuilder;
        $builder->setRepository(app(FieldRepository::class));

        $result = $builder->withModules('estate');

        expect($result)->toBeInstanceOf(FieldBuilder::class);
    });

    it('should add multiple modules to the modules property', function () {
        $builder = new FieldBuilder;
        $builder->setRepository(app(FieldRepository::class));

        $builder->withModules('estate');
        $builder->withModules('address');

        expect($builder->modules)->toBe(['estate', 'address']);
    });
});
