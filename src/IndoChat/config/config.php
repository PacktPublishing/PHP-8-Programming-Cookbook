<?php
require __DIR__ . '/../../../vendor/autoload.php';
define('USERS_FILE',   str_replace('//', '/', realpath(__DIR__ . '/../data') . '/users.json'));
define('AI_KEY_FN',    __DIR__ . '/../../../secure/open_ai_api_key.txt');
define('API_KEY',      file_get_contents(AI_KEY_FN));
define('AI_MODEL',     'gpt-5.4-nano'); // per 1M tokens: $0.20 input / $1.25 output
define('API_ENDPOINT', 'https://api.openai.com/v1/responses');
define('WS_PORT',      8081);
define('WS_HOST',      '0.0.0.0');
define('API_CALLBACK', new Cookbook\IndoChat\Platform\OpenAi());
