<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\REST\GenAiConnect;
use Laminas\Diactoros\ServerRequestFactory;
$services_list = glob(__DIR__ . '/library/*.php');
// load microservices (defined as functions)
$micro_services = [];
foreach ($services_list as $fn) {
    $micro_services[substr(basename($fn), 0, -4)] = $fn;
}
// create GenAiConnect instance
$config = [
    'ai_api_key' => trim(file_get_contents(__DIR__ . '/../../secure/monica_api_key.txt')),
    'ai_model'   => 'gpt-4.1-nano',     // $0.10 / 1M input tokens | $0.40 / 1M output tokens
    'ai_api_url' => 'https://openapi.monica.im/v1/chat/completions',   
];
// grab incoming request data
try {
    $response = [];
    // creates PSR-7 ServerRequestInterface instance
    $request = ServerRequestFactory::fromGlobals();
    // accept POST requests only
    if (strtolower($request->getMethod()) !== 'post') {
        http_response_code(405);
        throw new UnexpectedValueException('Unsupported HTTP method');
    } else {
        $data = $request->getParsedBody();
        if (empty($data)) {
            http_response_code(406);
            throw new InvalidArgumentException('Invalid arguments');
        }
        $service = strtolower(key($data));
        if (!isset($micro_services[$service])) {
            http_response_code(406);
            throw new BadFunctionCallException('Invalid service');
        }
        $ai = new GenAiConnect($config);
        $value = json_decode(current($data), TRUE);
        require $micro_services[$service];
        $response = [
            'success' => TRUE,
            'message' => $service($ai, $value)
        ];
    }
} catch (Throwable $t) {
    error_log(__FILE__ . ':' . get_class($t) . ':' . $t->getMessage());
    if ($t instanceof InvalidArgumentException) {
        $response = [
            'success' => FALSE,
            'error'   => $t->getMessage()
        ];
    } else {
        $response = [
            'success' => FALSE,
            'error'   => $t->getMessage()
        ];
    }
} finally {
    echo json_encode($response);
}
exit;
