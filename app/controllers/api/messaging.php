<?php

use Appwrite\Utopia\Response;
use Utopia\App;
use Utopia\Config\Config;
use Utopia\Validator\Text;
use Utopia\Validator\WhiteList;

App::get('/v1/messaging/providers')
    ->desc('List Messaging Providers')
    ->groups(['api', 'messaging'])
    ->label('scope', 'messaging.read')
    ->label('sdk.auth', [APP_AUTH_TYPE_SESSION, APP_AUTH_TYPE_KEY, APP_AUTH_TYPE_JWT])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'listProviders')
    ->label('sdk.description', '/docs/references/messaging/list-providers.md')
    ->label('sdk.response.code', Response::STATUS_CODE_OK)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_PROVIDER_LIST)
    ->inject('response')
    ->action(function (Response $response) {
    });

App::get('/v1/messaging/providers/:provider')
    ->desc('Get Messaging Provider')
    ->groups(['api', 'messaging'])
    ->label('scope', 'messaging.read')
    ->label('sdk.auth', [APP_AUTH_TYPE_SESSION, APP_AUTH_TYPE_KEY, APP_AUTH_TYPE_JWT])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'getProvider')
    ->label('sdk.description', '/docs/references/messaging/get-provider.md')
    ->label('sdk.response.code', Response::STATUS_CODE_OK)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_PROVIDER)
    ->inject('response')
    ->action(function (Response $response) {
    });

App::post('/v1/messaging/subscriptions')
    ->desc('Create Messaging Subscription')
    ->groups(['api', 'messaging'])
    ->label('scope', 'subscriptions.write')
    ->label('sdk.auth', [APP_AUTH_TYPE_ADMIN, APP_AUTH_TYPE_KEY])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'createSubscription')
    ->label('sdk.description', '/docs/references/messaging/create-subscription.md')
    ->label('sdk.response.code', Response::STATUS_CODE_CREATED)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_SUBSCRIPTION)
    ->param('name', '', new Text(128), 'Subscription name.')
    ->param('provider', '', new WhiteList(\array_keys(Config::getParam('messagingProviders'))), 'Provider name.')
    ->inject('response')
    ->action(function ($name, $provider, $response) {
    });

App::get('/v1/messaging/subscriptions')
    ->desc('List Messaging Subscriptions')
    ->groups(['api', 'messaging'])
    ->label('scope', 'subscriptions.read')
    ->label('sdk.auth', [APP_AUTH_TYPE_ADMIN, APP_AUTH_TYPE_KEY])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'listSubscriptions')
    ->label('sdk.description', '/docs/references/messaging/list-subscriptions.md')
    ->label('sdk.response.code', Response::STATUS_CODE_OK)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_SUBSCRIPTION_LIST)
    ->inject('response')
    ->action(function ($response) {
    });

App::get('/v1/messaging/subscriptions/:subscriptionId')
    ->desc('Get Messaging Subscription')
    ->groups(['api', 'messaging'])
    ->label('scope', 'subscriptions.read')
    ->label('sdk.auth', [APP_AUTH_TYPE_ADMIN, APP_AUTH_TYPE_KEY])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'getSubscription')
    ->label('sdk.description', '/docs/references/messaging/get-subscription.md')
    ->label('sdk.response.code', Response::STATUS_CODE_OK)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_SUBSCRIPTION)
    ->inject('response')
    ->action(function ($subscriptionId, $response) {
    });

App::patch('/v1/messaging/subscriptions/:subscriptionId/name')
    ->desc('Update Messaging Subscription')
    ->groups(['api', 'messaging'])
    ->label('scope', 'subscriptions.write')
    ->label('sdk.auth', [APP_AUTH_TYPE_ADMIN, APP_AUTH_TYPE_KEY])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'updateSubscription')
    ->label('sdk.description', '/docs/references/messaging/update-subscription.md')
    ->label('sdk.response.code', Response::STATUS_CODE_OK)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_SUBSCRIPTION)
    ->param('name', '', new Text(128), 'Subscription name.')
    ->inject('response')
    ->action(function ($subscriptionId, $name, $response) {
    });

App::delete('/v1/messaging/subscriptions/:subscriptionId')
    ->desc('Delete Messaging Subscription')
    ->groups(['api', 'messaging'])
    ->label('scope', 'subscriptions.write')
    ->label('sdk.auth', [APP_AUTH_TYPE_ADMIN, APP_AUTH_TYPE_KEY])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'deleteSubscription')
    ->label('sdk.description', '/docs/references/messaging/delete-subscription.md')
    ->label('sdk.response.code', Response::STATUS_CODE_NOCONTENT)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_SUBSCRIPTION)
    ->param('subscriptionId', '', new Text(256), 'Subscription unique ID.')
    ->inject('response')
    ->action(function ($subscriptionId, $response) {
    });

App::post('/v1/messaging/subscriptions/:subscriptionId/subscriber')
    ->desc('Subscribe to Messaging Subscription')
    ->groups(['api', 'messaging'])
    ->label('scope', 'subscribers.write')
    ->label('sdk.auth', [APP_AUTH_TYPE_ADMIN, APP_AUTH_TYPE_KEY])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'subscribe')
    ->label('sdk.description', '/docs/references/messaging/subscribe.md')
    ->label('sdk.response.code', Response::STATUS_CODE_CREATED)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_SUBSCRIPTION)
    ->param('subscriptionId', '', new Text(256), 'Subscription unique ID.')
    ->inject('response')
    ->action(function ($subscriptionId, $response) {
    });

App::delete('/v1/messaging/subscriptions/:subscriptionId/subscriber/:subscriberId')
    ->desc('Unsubscribe from Messaging Subscription')
    ->groups(['api', 'messaging'])
    ->label('scope', 'subscribers.write')
    ->label('sdk.auth', [APP_AUTH_TYPE_ADMIN, APP_AUTH_TYPE_KEY])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'unsubscribe')
    ->label('sdk.description', '/docs/references/messaging/unsubscribe.md')
    ->label('sdk.response.code', Response::STATUS_CODE_NOCONTENT)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_SUBSCRIPTION)
    ->param('subscriptionId', '', new Text(256), 'Subscription unique ID.')
    ->inject('response')
    ->action(function ($subscriptionId, $response) {
    });

App::post('/v1/messaging/messages')
    ->desc('Get Messaging Provider')
    ->groups(['api', 'messaging'])
    ->label('scope', 'messaging.read')
    ->label('sdk.auth', [APP_AUTH_TYPE_SESSION, APP_AUTH_TYPE_KEY, APP_AUTH_TYPE_JWT])
    ->label('sdk.namespace', 'messaging')
    ->label('sdk.method', 'send')
    ->label('sdk.description', '/docs/references/messaging/send-message.md')
    ->label('sdk.response.code', Response::STATUS_CODE_OK)
    ->label('sdk.response.type', Response::CONTENT_TYPE_JSON)
    ->label('sdk.response.model', Response::MODEL_MESSAGING_MESSAGE)
    ->inject('response')
    ->action(function (Response $response) {
    });
