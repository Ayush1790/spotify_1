<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use MyApp\component\GetData;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream as StreamSession;
use Phalcon\Http\Response\Cookies;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);
$loader->registerNamespaces([
    "MyApp\component" => APP_PATH . '/component',
    "MyApp\Models" => APP_PATH . '/models',
    "MyApp\Controllers"=>APP_PATH.'/controllers',
]);


$loader->register();

$container = new FactoryDefault();

$container->set(
    'getData',
    function () {
        return new GetData();
    }
);

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);


$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'spotify',
                ]
            );
        }
);

$container->set(
    'mongo',
    function () {
        $mongo = new MongoClient();

        return $mongo->selectDB('phalt');
    },
    true
);
$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new StreamSession(
            [
                'savePath' => '/tmp',
            ]
        );
        $session
            ->setAdapter($files)
            ->start();
        return $session;
    }
);
$container->set(
    'cookies',
    function () {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    }
);
$application = new Application($container);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
