<?php
namespace Cookbook\REST;
#[Cookbook\REST\EnumExt("Defines allowed PHP extensions")]
enum EnumExt
{
    case STREAMS;   // standard I/O
    case CURL;      // cURL extension
}
