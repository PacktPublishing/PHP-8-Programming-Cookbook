<?php
namespace Cookbook\Middleware;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ {ResponseInterface,ServerRequestInterface};
use Psr\Http\Server\ {MiddlewareInterface,RequestHandlerInterface};
class DoNothingHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // do nothing
    }
}
