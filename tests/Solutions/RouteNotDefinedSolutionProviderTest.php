<?php

use QuantaQuirk\Support\Facades\Route;
use QuantaQuirk\Support\Str;
use QuantaQuirk\QuantaQuirkIgnition\Solutions\SolutionProviders\RouteNotDefinedSolutionProvider;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

it('can solve a route not defined exception', function () {
    $canSolve = app(RouteNotDefinedSolutionProvider::class)->canSolve(getRouteNotDefinedException());

    expect($canSolve)->toBeTrue();
});

it('can recommend changing the route name', function () {
    Route::get('/test', 'TestController@typo')->name('test.typo');

    /** @var \QuantaQuirk\Ignition\Contracts\Solution $solution */
    $solution = app(RouteNotDefinedSolutionProvider::class)->getSolutions(getRouteNotDefinedException())[0];

    expect(Str::contains($solution->getSolutionDescription(), 'Did you mean `test.typo`?'))->toBeTrue();
});

it('wont recommend another route if the names are too different', function () {
    Route::get('/test', 'TestController@typo')->name('test.typo');

    /** @var \QuantaQuirk\Ignition\Contracts\Solution $solution */
    $solution = app(RouteNotDefinedSolutionProvider::class)->getSolutions(getRouteNotDefinedException('test.is-too-different'))[0];

    expect(Str::contains($solution->getSolutionDescription(), 'Did you mean'))->toBeFalse();
});

// Helpers
function getRouteNotDefinedException(string $route = 'test.typoo'): RouteNotFoundException
{
    return new RouteNotFoundException("Route [{$route}] not defined.");
}
