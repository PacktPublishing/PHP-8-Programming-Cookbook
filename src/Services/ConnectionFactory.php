<?php
namespace Cookbook\Services;
use PDO;
use Throwable;
use RuntimeException;
use Cookbook\Database\Connect;
use Psr\Container\ContainerInterface;
#[ConnectionFactory("Uses the database configuration array to produce a Connect instance")]
class ConnectionFactory
{
    #[Connect\__construct(
        "@param ContainerInterface \$container : the services container"
    )]
    public function __construct(public ContainerInterface $container) 
    {}
    #[Connect\__invoke(
        "Returns Connect instance or NULL",
    )]
    public function __invoke() : Connect|null
    {
        return new Connect($this->container->get('db_config')());
    }
}
