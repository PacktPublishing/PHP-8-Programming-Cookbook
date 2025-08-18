<?php
namespace Cookbook\Services;
use Cookbook\Database\PostCodeTable;
use Psr\Container\ContainerInterface;
#[PostCodeTableFactory("Uses ConnectionFactory produce a PostCodeTable class instance")]
class PostCodeTableFactory
{
    #[Connect\__construct(
        "@param ContainerInterface \$container : the services container"
    )]
    public function __construct(public ContainerInterface $container) 
    {}
    #[Connect\__invoke(
        "Returns PostCodeTable instance or NULL",
    )]
    public function __invoke() : PostCodeTable|null
    {
        return new PostCodeTable($this->container->get('db_connect'));
    }
}
