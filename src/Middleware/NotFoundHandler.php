<?php
namespace Cookbook\Middleware;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ {ResponseInterface,ServerRequestInterface};
class NotFoundHandler extends BaseHandler
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $resp = ['status' => 'Page Not Found'];
        return (new JsonResponse($resp))->withStatus(404);
    }
}
