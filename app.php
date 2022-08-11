<?php

use Amp\Injector\Application;
use Amp\Injector\Injector;
use Cspray\AmpInjectorAlias\FooImplementation;
use Cspray\AmpInjectorAlias\FooInterface;
use function Amp\Injector\any;
use function Amp\Injector\automaticTypes;
use function Amp\Injector\definitions;
use function Amp\Injector\object;
use function Amp\Injector\singleton;
use function Amp\Injector\types;

require_once __DIR__ . '/vendor/autoload.php';

$definitions = definitions();

$definitions = $definitions->with(singleton(object(FooImplementation::class)), FooImplementation::class);

$types = types();
$types = $types->with(FooInterface::class, $definitions->get(FooImplementation::class));

$app = new Application(new Injector(any($types, automaticTypes($definitions))), $definitions);

$app->start();

var_dump($app->getContainer()->get(FooInterface::class));

// Results in
// PHP Fatal error:  Uncaught Amp\Injector\NotFoundException: Unknown identifier: Cspray\AmpInjectorAlias\FooInterface in /home/cspray/Code/personal/amphp-injector-alias/vendor/amphp/injector/src/RootContainer.php:28
// Stack trace:
// #0 /home/cspray/Code/personal/amphp-injector-alias/vendor/amphp/injector/src/RootContainer.php(20): Amp\Injector\RootContainer->getProvider()
// #1 /home/cspray/Code/personal/amphp-injector-alias/app.php(27): Amp\Injector\RootContainer->get()
// #2 {main}
// thrown in /home/cspray/Code/personal/amphp-injector-alias/vendor/amphp/injector/src/RootContainer.php on line 28