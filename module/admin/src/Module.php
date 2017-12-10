<?php

namespace Admin;

// Add these import statements:
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    // getConfig() method is here

    // Add this method:
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AdminTable::class => function($container) {
                    $tableGateway = $container->get(Model\AdminTableGateway::class);
                    return new Model\AdminTable($tableGateway);
                },
                Model\AdminTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Admin());
                    return new TableGateway('admin', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
     public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AdminController::class => function($container) {
                    return new Controller\AdminController(
                        $container->get(Model\AdminTable::class)
                    );
                },
            ],
        ];
    }
}