<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * {@inheritDoc}
     */
    public function registerBundles()
    {
        return [
            new Broadway\Bundle\BroadwayBundle\BroadwayBundle(),
            new BroadwayDemoBundle\BroadwayDemoBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $route = $routes->add('/basket', 'broadway_demo.controller.basket:pickUpBasketAction', 'create_basket');
        $route->setMethods('POST');

        $route = $routes->add('/basket/{basketId}/addProduct', 'broadway_demo.controller.basket:addProductToBasketAction', 'add_product');
        $route->setMethods('POST');

        $route = $routes->add('/basket/{basketId}/removeProduct', 'broadway_demo.controller.basket:removeProductFromBasketAction', 'remove_product');
        $route->setMethods('POST');

        $route = $routes->add('/basket/{basketId}/checkout', 'broadway_demo.controller.basket:checkoutAction', 'checkout');
        $route->setMethods('POST');


        $route = $routes->add('/advice/{productId}', 'broadway_demo.controller.people_that_bought_this_product:getAdviceAction', 'get_advice');
        $route->setMethods('GET');
    }

    /**
     * {@inheritDoc}
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', [
            'secret' => 'Everything is awesome, everything is cool when your part of a team',
            'test'   => true,
        ]);

        $c->loadFromExtension('doctrine', [
            'dbal' => [
                'connections' => [
                    'default' => [
                        'driver' => 'pdo_sqlite',
                        'path'   => '%kernel.cache_dir%.events.sqlite',
                    ],
                ],
            ],
        ]);

        $c->loadFromExtension('broadway', [
            'event_store' => [
                'dbal' => [
                    'enabled' => true,
                ]
            ],
            'read_model' => [
                'repository' => 'elasticsearch',
            ]
        ]);
    }
}
