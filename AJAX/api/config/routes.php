<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

use App\Middleware\AuthenticationMiddleware;

return static function (RouteBuilder $routes) {

    $routes->setRouteClass(DashedRoute::class);
	$routes->registerMiddleware('auth', new AuthenticationMiddleware());
    $routes->scope('/', function (RouteBuilder $builder) {

        $builder->connect('/', 'Api::index');

		$builder->scope('/receipt', function (RouteBuilder $builder) {
			$builder->applyMiddleware('auth');
			$builder->get('/', 'Receipts::list');
            $builder->put('/', 'Receipts::create');

			$builder->patch('/{id}', 'Receipts::edit')->setPass(['id']);
			$builder->get('/{id}', 'Receipts::get')->setPass(['id']);
			$builder->delete('/{id}', 'Receipts::delete')->setPass(['id']);
		});
		$builder->connect('/login', 'Users::login');

		$builder->scope('/user', function (RouteBuilder $builder) {
			$builder->applyMiddleware('auth');
			$builder->connect('/', 'Users::list');
			$builder->patch('/{id}', 'Users::edit')->setPass(['id']);
			$builder->get('/{id}', 'Users::get')->setPass(['id']);
		});
		// $builder->connect('/{controller}', ['action' => 'list']);

        // $builder->connect('/pages/*', 'Pages::display');

        // $builder->fallbacks();
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder) {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};
