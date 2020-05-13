<?php

declare(strict_types=1);

namespace Sylius\Bundle\AdminBundle\Controller\Dashboard;

use Sylius\Component\Core\Dashboard\DashboardStatisticsProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

final class StatisticsController
{
    /** @var EngineInterface */
    private $templatingEngine;

    /** @var DashboardStatisticsProviderInterface */
    private $statisticsProvider;

    public function __construct(
        EngineInterface $templatingEngine,
        DashboardStatisticsProviderInterface $statisticsProvider
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->statisticsProvider = $statisticsProvider;
    }

    public function __invoke(ChannelInterface $channel): Response
    {
        return $this->templatingEngine->renderResponse(
            '@SyliusAdmin/Dashboard/Statistics/_template.html.twig',
            ['statistics' => $this->statisticsProvider->getStatisticsForChannel($channel)]
        );
    }
}
