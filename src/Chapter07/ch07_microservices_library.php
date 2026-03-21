<?php
include __DIR__ . '/../../vendor/autoload.php';
use Laminas\Diactoros\ServerRequestFactory;
$services_list = glob(__DIR__ . '/library/*.php');
// load microservices (defined as functions)
$micro_services = [];
foreach ($services_list as $fn) {
    $micro_services[substr(basename($fn), 0, -4] = $fn;
}
// grab incoming request data
try {
    $request = ServerRequestFactory::fromGlobals();
} catch (Throwable $t) {
    error_log(__FILE__ . ':' . $t->getMessage());
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

