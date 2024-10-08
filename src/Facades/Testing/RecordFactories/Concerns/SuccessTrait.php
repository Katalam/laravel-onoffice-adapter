<?php

declare(strict_types=1);

namespace Innobrain\OnOfficeAdapter\Facades\Testing\RecordFactories\Concerns;

trait SuccessTrait
{
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
