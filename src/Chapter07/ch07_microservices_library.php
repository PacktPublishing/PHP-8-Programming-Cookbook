<?php
// TO DEMO: ./admin.sh shell
// php -S 0.0.0.0:9999 src/Chapter07/ch07_microservices_library.php
// sample CURL requests (from another shell):
// curl -X POST -F 'translate={"lang_from":"en","lang_to":"km","phrase":"Hello, how are you today?"}' http://localhost:9999
// curl -X POST -F 'distance={"city_from":"Bangkok","city_to":"Siem Reap","iso2_from":"th","iso2_to":"kh","units":"km"}' http://localhost:9999

include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\REST\GenAiConnect;
use Laminas\Diactoros\ServerRequestFactory;
$services_list = new FilesystemIterator(__DIR__ . '/../REST/Library/');
// load microservices (defined as functions)
$micro_services = [];
foreach ($services_list as $fn => $obj) {
    if ($obj->getExtension() === 'php') {
        $micro_services[strtolower(substr($obj->getBasename(), 0, -4))] = $fn;
    }
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
    }
    // grab POST data
    $data = $request->getParsedBody();
    if (empty($data)) {
        http_response_code(406);
        throw new InvalidArgumentException('Invalid arguments');
    }
    // determine the service requested
    $service = strtolower(key($data));
    if (!isset($micro_services[$service])) {
        http_response_code(406);
        throw new BadFunctionCallException('Invalid service');
    }
    // validate the JSON request
    $json = current($data);
    if (!json_validate($json)) {
        http_response_code(400);
        error_log(__FILE__ . ':' . var_export($json, TRUE));
        throw new RuntimeException('Invalid JSON');
    }
    // extract a PHP array from the JSON request
    $value = json_decode($json, TRUE, flags:JSON_THROW_ON_ERROR);
    if (!is_array($value)) {
        http_response_code(400);
        throw new RuntimeException('Invalid request');
    }
    // sanitize the data
    foreach ($data as $key => $val) $data[$key] = trim(strip_tags($val));
    // load the microservice
    require $micro_services[$service];
    $svc_name = '\\Cookbook\\REST\\Library\\' . $service;
    // make the GenAI call
    $ai = new GenAiConnect($config);
    $output = (new $svc_name())($ai, $value);
    // process results
    $arr = json_decode($output, TRUE, flags:JSON_THROW_ON_ERROR);
    $response = [
        'success' => TRUE,
        'message' => $arr['choices'][0]['message']['content'] ?? 'No response'
    ];
} catch (Throwable $t) {
    error_log(__FILE__ . ':' . get_class($t) . ':' . $t->getMessage());
    $response = [
        'success' => FALSE,
        'error'   => $t->getMessage()
    ];
} finally {
    echo json_encode($response);
}
exit;
