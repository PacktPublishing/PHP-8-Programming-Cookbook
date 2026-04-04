<?php
namespace Cookbook\Middleware;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ {ResponseInterface,ServerRequestInterface};
use Psr\Http\Server\ {MiddlewareInterface,RequestHandlerInterface};
class Logger implements MiddlewareInterface 
{
    const ERR_LOG = 'ERROR: unable to log entry';
    const OK_LOG  = 'SUCCESS: entry logged';
    const LOG_FILE = __DIR__ . '/../Chapter07/middleware.log';
    public function process(ServerRequestInterface $request,
        RequestHandlerInterface $handler) : ResponseInterface
    {
        $text = sprintf('%20s : %10s : %30s : %s' . PHP_EOL,
            date('Y-m-d H:i:s'),
            ($request->getParsedBody()['action'] ?? 'Unknown'),
            ($request->getParsedBody()['data'] ?? 'No Data'),
            ($request->getServerParams()['REMOTE_ADDR']) ?? 'Command Line');
        if (file_put_contents(self::LOG_FILE, $text, FILE_APPEND)) {
            $msg = ['status' => 'success', 'message' => self::OK_LOG];
            return new JsonResponse($msg, 200);
        } else {
            $msg = ['status' => 'fail', 'message' => self::ERR_LOG];
            return new JsonResponse($msg, 500);
        }
    }
}
