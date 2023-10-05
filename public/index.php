<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Bridge\Slim\Bridge as AppFactory;
use Dotenv\Dotenv as Env;

define ('T', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

try {
    if (php_sapi_name() == 'cli-server' && preg_match('#^/assets.*$#Ui', $_SERVER["REQUEST_URI"])) return false;

    define('APP_ROOT', __DIR__);

    $env = Env::createImmutable(APP_ROOT . '/../');
    $env->load();
    $env->required(['BASE_PATH', 'AUTH_TOKEN', 'DEBUG']);
    $env->required(['DATA_DIR', 'DTD_DIR', 'TEMPLATE_DIR'])->notEmpty();

    $app = AppFactory::create();
    $app->setBasePath($_ENV['BASE_PATH']);
    $app->addErrorMiddleware($_ENV['DEBUG'], false, false);

    $datadir = realpath(__DIR__ . '/' . $_ENV['DATA_DIR']);
    if (false === $datadir || !is_dir($datadir)) throw new Exception('invalid DATA_DIR');
    $_ENV['DATA_DIR'] = $datadir;
    $dtddir = realpath(__DIR__ . '/' . $_ENV['DTD_DIR']);
    if (false === $dtddir || !is_dir($dtddir)) throw new Exception('invalid DTD_DIR');
    $_ENV['DTD_DIR'] = $dtddir;

    $app->get('/{database}/{entry}/{attachment}', Tellico\Web\Attachment::class)->setName('attachment');
    $app->get('/{database}/{entry}', Tellico\Web\Details::class)->setName('details');
    $app->get('/{database}', Tellico\Web\Contents::class)->setName('contents');
    $app->get('/', Tellico\Web\Overview::class)->setName('overview');
    $app->run();

} catch (Exception $e) {
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo 'There has been an error in your setup:', PHP_EOL, PHP_EOL;
    echo $e->getMessage(), PHP_EOL, PHP_EOL;
    echo 'Please adjust your settings and try again!', PHP_EOL;
}
