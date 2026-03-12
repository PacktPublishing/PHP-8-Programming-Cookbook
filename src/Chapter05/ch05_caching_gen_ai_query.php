<?php
define('DB_CONFIG_FN', __DIR__ . '/../../config/db.config.php');
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Container;
use Cookbook\Services\ConnectionFactory;
use Cookbook\Database\GenAiCache;
use Cookbook\Database\GenAiRequest;
$container = Container::getInstance();
$container->add('db_config', function () { return require DB_CONFIG_FN; });
$container->add('db_connect', new ConnectionFactory($container));
$container->add('ai_config', function () { return [
        'AI_KEY_FN'   => __DIR__ . '/../../secure/monica_api_key.txt',
        'AI_MODEL'    => 'gpt-4.1-nano',     // $0.10 / 1M input tokens | $0.40 / 1M output tokens
        'AI_API_URL'  => 'https://openapi.monica.im/v1/chat/completions',
        'AI_SYS_TEXT' => 'Keep your responses concise and limited to 256 words or less.',
    ];
});
$request = new GenAiRequest($container);
$start = microtime(TRUE);
$prompt = $argv[1] ?? '';
if (empty($prompt)) {
    exit('Usage:  php ' . basename(__FILE__) . ' "PROMPT"' . PHP_EOL);
}
echo $request($prompt);
echo PHP_EOL;
echo 'Elapsed Time: ' . (microtime(TRUE) - $start);
echo PHP_EOL;
