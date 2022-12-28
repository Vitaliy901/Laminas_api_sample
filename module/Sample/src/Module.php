<?php

declare(strict_types=1);

namespace Sample;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Sample\Auth\AuthManager;
use Sample\Controller\v1\AuthController;
use Sample\Controller\v1\UserController;
use Sample\Model\User;
use Sample\Repositories\UserRepository;

class Module implements ConfigProviderInterface
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                UserRepository::class => function ($container) {
                    $tableGateway = $container->get(TableGateway::class);
                    return new UserRepository($tableGateway);
                },
                TableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User);
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig(): array
    {
        return [
            'factories' => [
                UserController::class => function ($container) {
                    return new UserController($container->get(UserRepository::class));
                },
                AuthController::class => function ($container) {
                    return new AuthController($container->get(UserRepository::class));
                },
            ],
        ];
    }
}
