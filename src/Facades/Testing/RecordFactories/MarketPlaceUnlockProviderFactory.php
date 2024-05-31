<?php

namespace Katalam\OnOfficeAdapter\Facades\Testing\RecordFactories;

class MarketPlaceUnlockProviderFactory extends BaseFactory
{
    public function id(int $id): static
    {
        return $this;
    }

    public function type(string $type): static
    {
        return $this;
    }

    public function elements(): static
    {
        return $this;
    }

    public function success(bool $success): static
    {
        $this->elements['success'] = $success ? 'success' : 'error';

        return $this;
    }

    public function ok(): static
    {
        $this->elements['success'] = 'success';

        return $this;
    }

    public function error(): static
    {
        $this->elements['success'] = 'error';

        return $this;
    }
}
