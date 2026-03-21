<?php
namespace Cookbook\REST;
#[Cookbook\REST\EnumMethod("Defines allowed HTTP methods")]
enum EnumMethod
{
    case GET;   // HTTP GET
    case POST;  // HTTP POST 
}
