<?php

require_once __DIR__ . '/vendor/autoload.php';

use Amp\Injector\Application;
use Amp\Injector\Injector;
use Amp\Injector\Meta\Reflection\ReflectionFunctionExecutable;
use Amp\Injector\ProviderContext;
use Cspray\AmpInjectorAlias\FooImplementation;
use Cspray\AmpInjectorAlias\FooInterface;
use function Amp\Injector\any;
use function Amp\Injector\arguments;
use function Amp\Injector\automaticTypes;
use function Amp\Injector\definitions;
use function Amp\Injector\names;
use function Amp\Injector\object;
use function Amp\Injector\value;

$firstCallable = function(int $val) {
    return 1;
};

$secondCallable = function(FooInterface $foo, int $val) {
    return 2;
};

$definitions = definitions();
$definitions = $definitions->with(object(FooImplementation::class), FooImplementation::class);
$definitions = $definitions->with($definitions->get(FooImplementation::class), FooInterface::class);

$app = new Application(new Injector(any(automaticTypes($definitions))), $definitions);
$app->start();

var_dump($app->getContainer()->get(FooInterface::class));

var_dump($app->getInjector()->getExecutableProvider(
    new ReflectionFunctionExecutable(new ReflectionFunction($firstCallable)),
    arguments(any(automaticTypes($definitions), names()->with('val', value(1))))
)->get(new ProviderContext()));

var_dump($app->getInjector()->getExecutableProvider(
    new ReflectionFunctionExecutable(new ReflectionFunction($secondCallable)),
    arguments(any(automaticTypes($definitions), names()->with('val', value(2))))
)->get(new ProviderContext()));