<?php

declare(strict_types=1);

namespace Sylius\Bundle\AdminBundle\Controller\Dashboard;

use Sylius\Component\Core\Dashboard\SalesDataProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

final class ChartController
{
    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @var SalesDataProviderInterface
     */
    private $salesDataProvider;

    public function __construct(
        EngineInterface $templatingEngine,
        SalesDataProviderInterface $salesDataProvider
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->salesDataProvider = $salesDataProvider;
    }

    public function __invoke(ChannelInterface $channel): Response
    {
        return $this->templatingEngine->renderResponse('@SyliusAdmin/Dashboard/Chart/_template.html.twig', [
            'sales_summary' => $this->salesDataProvider->getLastYearSalesSummary($channel),
        ]);
    }
}
