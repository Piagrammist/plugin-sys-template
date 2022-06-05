<?php

use danog\MadelineProto\Settings;
use danog\MadelineProto\Stream\Proxy\SocksProxy;

use Piagrammist\PluginSys\Robot;
use function Piagrammist\PluginSys\path;


if (is_file(__DIR__.'/../plugin-sys.phar')) {
    require __DIR__.'/../plugin-sys.phar';
} else {
    require __DIR__.'/../vendor/autoload.php';
}
require __DIR__.'/CustomEventHandler.php';


$settings = new Settings;
$settings->getAppInfo()
    ->setApiId(1510795)
    ->setApiHash('503505b5a38dd0a992afab42dea79cc5');
$settings->getConnection()
    ->addProxy(SocksProxy::class, ['address' => '127.0.0.1', 'port' => 10808]);

$robot = new Robot(
    path(__DIR__, 'session'),
    $settings,
    CustomEventHandler::class  // Use your Custom EventHandler
);
$robot->noReport();
#$robot->setOwner(123456789);
$robot->setLoopDir(path(__DIR__, 'loops'));
$robot->setCommandDir(path(__DIR__, 'commands'));

$robot->onStart(function () {
    // '$this' refers to the EventHandler object here
    $this->logger("\n\nHello World!\n");
});

$robot->loop();
