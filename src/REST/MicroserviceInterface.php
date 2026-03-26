<?php
namespace Cookbook\REST;
use Cookbook\REST\GenAiConnect;
interface MicroserviceInterface
{
    public function __invoke(GenAiConnect $connect, iterable $args) : string;
}
