<?php
namespace Cookbook\Middleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
class DispatchHandler implements MiddlewareInterface
{
    public function __construct(public ContainerInterface $container)
    {}
    public function process(ServerRequestInterface $request,
        RequestHandlerInterface $handler) : ResponseInterface
    {
        $action = strtolower(trim(strip_tags($request->getParsedBody()['action'] ?? '')));
        if (empty($action)) {
            // delegate to next handler
            return $handler->handle($request);
        }
        // invoke microservice middleware
        $middleware = $this->container->get($action);
        if (empty($middleware)) {
            // delegate to next handler
            return $handler->handle($request);
        }
        return $middleware->handle($request);
    }
}
