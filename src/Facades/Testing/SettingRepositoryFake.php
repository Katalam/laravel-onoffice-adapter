<?php

declare(strict_types=1);

namespace Katalam\OnOfficeAdapter\Facades\Testing;

use Katalam\OnOfficeAdapter\Query\Testing\ActionBuilderFake;
use Katalam\OnOfficeAdapter\Query\Testing\ImprintBuilderFake;
use Katalam\OnOfficeAdapter\Query\Testing\RegionBuilderFake;
use Katalam\OnOfficeAdapter\Query\Testing\UserBuilderFake;

class SettingRepositoryFake
{
    use FakeResponses;

    /**
     * Returns a new fake user builder instance.
     */
    public function users(): UserBuilderFake
    {
        return new UserBuilderFake($this->fakeResponses);
    }

    /**
     * Returns a new fake region builder instance.
     */
    public function regions(): RegionBuilderFake
    {
        return new RegionBuilderFake($this->fakeResponses);
    }

    /**
     * Returns a new fake imprint builder instance.
     */
    public function imprint(): ImprintBuilderFake
    {
        return new ImprintBuilderFake($this->fakeResponses);
    }

    /**
     * Returns a new fake actions builder instance.
     */
    public function actions(): ActionBuilderFake
    {
        return new ActionBuilderFake($this->fakeResponses);
    }
}
