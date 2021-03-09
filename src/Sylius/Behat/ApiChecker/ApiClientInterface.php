<?php

declare(strict_types=1);

namespace Sylius\Behat\ApiChecker;

interface ApiClientInterface
{
    public function get(string $iri): ApiResponse;
}
