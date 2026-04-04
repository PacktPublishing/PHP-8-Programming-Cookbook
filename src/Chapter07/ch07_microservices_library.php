<?php
// TO DEMO: ./admin.sh shell
// php composer.phar require psr/http-server-middleware
// php -S 0.0.0.0:8889 src/Chapter07/ch07_microservices_library.php
// sample CURL requests (from a terminal window on your host):
// curl -X POST -F 'action=translate' -F 'data={"lang_from":"en","lang_to":"fr","phrase":"How do I get to Paris?"}' http://localhost:8889
// curl -X POST -F 'action=distance' -F 'data={"city_from":"Bangkok","city_to":"Siem Reap","iso2_from":"th","iso2_to":"kh","units":"km"}' http://localhost:8889

include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Middleware\Logger;
use Cookbook\Middleware\Translate;
use Cookbook\Middleware\Distance;
use Cookbook\Middleware\DispatchHandler;
use Cookbook\Middleware\NotFoundHandler;
use Cookbook\Middleware\DoNothingHandler;
use Cookbook\Services\Container;
use Cookbook\Services\GenAiConnect;
use Cookbook\Services\MonicaPlatform;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\JsonResponse;
// GenAI configuration
$config = [
    'ai_api_key' => trim(file_get_contents(__DIR__ . '/../../secure/monica_api_key.txt')),
    'ai_model'   => 'gpt-4.1-nano',     // $0.10 / 1M input tokens | $0.40 / 1M output tokens
    'ai_api_url' => 'https://openapi.monica.im/v1/chat/completions',   
];
// get service container instance
$container = Container::getInstance();
$container->add('ai_config', function () use ($config) { return $config; });
$container->add('GenAiConnect', function () use ($container) { return new GenAiConnect($container); });
$container->add('translate', function () use ($container) { return new Translate($container); });
$container->add('distance', function () use ($container) { return new Distance($container); });
$container->add('platform', function () { return new MonicaPlatform(); });
// build the pipe
$pipe = [
    Logger::class => DoNothingHandler::class,
    DispatchHandler::class => NotFoundHandler::class,
];
// build a PSR-7 Request object
$request  = ServerRequestFactory::fromGlobals();
try {
    // run the pipe
    foreach ($pipe as $key => $val) {
        $middleware = new $key($container);
        $handler    = (!empty($val)) ? new $val($container) : NULL;
        if (method_exists($middleware, 'process')) {
            $response = $middleware->process($request, $handler);
        } else {
            $response = $middleware->handle($request);
        }
    }
    echo $container->get('platform')($response->getBody());
} catch (Throwable $t) {
    echo (new JsonResponse(['success' => false, 'message' => $t->getMessage()]))->withStatus(400)->getBody();
}
