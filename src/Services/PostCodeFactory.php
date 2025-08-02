<?php
namespace Cookbook\Services;
use PDO;
use Throwable;
use RuntimeException;
use Cookbook\Database\PostCode;
use Psr\Container\ContainerInterface;
#[PostCodeFactory("Uses ConnectionFactory produce a PostCode instance")]
class PostCodeFactory
{
    #[Connect\__construct(
        "@param ContainerInterface \$container : the services container"
    )]
    public function __construct(public ContainerInterface $container) 
    {}
    #[Connect\__invoke(
        "Returns PostCode instance or NULL",
    )]
    public function __invoke() : PostCode|null
    {
        return new PostCode($this->container->get('db_connect')(), 'id');
    }
}
