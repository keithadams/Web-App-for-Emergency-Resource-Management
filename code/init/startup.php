<?php
use Psr7Middlewares\Middleware\AuraSession;
use Psr7Middlewares\Middleware\BasePath;
use Psr7Middlewares\Middleware\ClientIp;
use Psr7Middlewares\Middleware\Csrf;
use Psr7Middlewares\Middleware\EncodingNegotiator;
use Psr7Middlewares\Middleware\Gzip;
use Psr7Middlewares\Middleware\TrailingSlash;
use Psr7Middlewares\Middleware\ResponseTime;

define('APP_COMPRESSION_LEVEL', 6);
define('ALLOW_GZIP_COMPRESSION', true);
define('APP_ROOT', dirname(__DIR__));
define('APP_CACHE', APP_ROOT.'/cache');
define('APP_LOGS', APP_ROOT.'/logs');

if (!file_exists(APP_ROOT.'/config/config.php')) {
	die("Please copy " . APP_ROOT.'/config/config.dist.php' . " and create a config.php file");
}

require APP_ROOT.'/config/config.php';

$container = new \Slim\Container($config);

$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);

    $handler = new Monolog\Handler\RotatingFileHandler($settings['logger']['path'], 30, Monolog\Logger::DEBUG);
    // the default date format is "Y-m-d H:i:s"
	$dateFormat = "Y-m-d H:i:s.u";
	// the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
	$output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
	// finally, create a formatter
	$formatter = new Monolog\Formatter\LineFormatter($output, $dateFormat);
    $handler->setFormatter($formatter);

    $logger->pushProcessor(new Monolog\Processor\WebProcessor(null, $extraFields = [
        'url'         => 'REQUEST_URI',
        'ip'          => 'REMOTE_ADDR',
        'http_method' => 'REQUEST_METHOD'
    ]));
    $logger->pushProcessor(new Monolog\Processor\MemoryUsageProcessor());
    $logger->pushProcessor(new Monolog\Processor\MemoryPeakUsageProcessor());
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler($handler);

    return $logger;
};

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new App\Helpers\Renderer($settings['template_path']);
};

$container['db'] = function ($c) {
	$db = $c->get('settings')['db'];
	$dsn = "mysql:host=" . $db['host'] . ";port=" . $db['port'] . ";dbname=" . $db['dbname'] . ";charset=" . $db['charset'];
    $pdo = new \PDO($dsn, $db['user'], $db['pass']);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    return $pdo;
};

$container['session'] = function ($c) {
	$sessionSettings = $c->get('settings')['session'];
	$factory = new \Aura\Session\SessionFactory;
	$session = $factory->newInstance($_COOKIE);
    return $session;
};

$app = new \Slim\App($container);
\App\Helpers\Queries::setContainer($container);

// Add an instance of the application to the renderer:
$app->getContainer()->renderer->setApp($app);

//$app->add(new Gzip());
//$app->add(new EncodingNegotiator());
$app->add(new BasePath());
$app->add(new ClientIp());
$app->add(new TrailingSlash(false));
$app->add(new ResponseTime());
$app->add(new \App\Middleware\RequireAuthentication($app));


require APP_ROOT.'/routes/app.php';

foreach (glob(APP_ROOT."/routes/controllers/*.php") as $filename)
{
    require $filename;
}