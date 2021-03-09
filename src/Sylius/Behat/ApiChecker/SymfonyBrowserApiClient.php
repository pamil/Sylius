<?php

declare(strict_types=1);

namespace Sylius\Behat\ApiChecker;

use Symfony\Component\BrowserKit\AbstractBrowser;

final class SymfonyBrowserApiClient implements ApiClientInterface
{
    /** @var AbstractBrowser */
    private $browser;

    public function __construct(AbstractBrowser $browser)
    {
        $this->browser = $browser;
    }

    public function get(string $iri): ApiResponse
    {
        $this->browser->request('GET', $iri);

        return ApiResponse::fromJsonResponse($this, $this->browser->getResponse());
    }
}
