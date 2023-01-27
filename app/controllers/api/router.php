<?php


use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;
use Utopia\App;

// TODO: Disable if project ID = console
// TOOD: dbForProject wont work (need to look into domain)
// TODO: Ensure usage stats work (function executions, bandwidth)
// TODO: Use executor with support for V3
// TODO: Implement execution itself, create execution document, updage usage
// TODO: Add all methods, ideally wildcard (change in utopia library framework)
// TODO: Get original path from X-Replaced-Path (and dont forward this header)
// TODO: Certificate generation (allow just-in-time, implement during creation, retry logic button in Console)

/*
App::init()
    ->groups(['router'])
    ->action(function () {
        // Any init logic
    });

App::shutdown()
    ->groups(['router'])
    ->action(function () {
        // Any shutdown logic
    });
*/

function routeRequest(string $type, SwooleRequest $request, SwooleResponse $response) {
    $response->end('<h1>Hello World!</h1>');
}

App::get('/v1/router')
    ->desc('Router Endpoint')
    ->groups(['router'])
    ->label('sdk.hide', true)
    ->inject('swooleRequest')
    ->inject('swooleResponse')
    ->label('scope', 'router.read')
    ->action(function (SwooleRequest $request, SwooleResponse $response) {
        routeRequest('GET', $request, $response);
    });

// TODO: post put delete options patch
// Missing: HEAD, CONNECT, TRACE