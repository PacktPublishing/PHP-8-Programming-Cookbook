<?php
namespace Cookbook\Services;
use Cookbook\Database\GenericRow;
use Cookbook\Database\GenericRowInterface;
use Cookbook\Database\TableInterface;
use Psr\Container\ContainerInterface;
#[GenericRowFactory("Uses ConnectionFactory produce a PostCode instance")]
class GenericRowFactory
{
    #[Connect\__construct(
        "@param ContainerInterface \$container : the services container"
    )]
    public function __construct(
        public ContainerInterface $container,
        public string $table_class  // name of the table class service needed for this row
    ) {}
    #[Connect\__invoke(
        "Returns GenericRowInterface instance or NULL",
    )]
    public function __invoke() : GenericRowInterface|null
    {
        return new GenericRow($this->container->get($this->table_class));
    }
}
