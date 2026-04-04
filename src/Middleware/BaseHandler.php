<?php
namespace Cookbook\Middleware;
use ArrayObject;
use FilesystemIterator;
use Cookbook\REST\GenAiConnect;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
abstract class BaseHandler implements RequestHandlerInterface
{
    public Traversable $middleware;
    #[Cookbook\Middleware\BaseHandler(
        "Builds iteration of handlers",
        "@param GenAiConnect : class that connects to GenAI",
        "@param string \$path : path to handlers"
    )]
    public function __construct(public ContainerInterface $container)
    {}
}
