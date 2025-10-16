<?php

declare(strict_types=1);

namespace Agenceweb\MercanetBnpParibasPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('agenceweb_mercanet_bnp_paribas');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
