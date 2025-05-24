<?php
namespace Cookbook\Geonames;
#[PostCodeView(
    "Displays PostCode data"
)]
class PostCodeView
{
    public function __construct(public iterable $list) {}
    public function showAsObj()
    {
        printf("%20s | %10s | %4s\n", 'City', 'State/Prov', 'Post Code');
        printf("%20s | %10s | %4s\n", '--------------------', '----------', '----');
        foreach ($this->list as $key => $obj) {
            printf( "%20s | %10s | %4s\n", 
                    $obj->getCityName(),
                    $obj->getStateProvCode(),
                    $key);
        }
    }
    public function showAsArr()
    {
        printf("%20s | %10s | %4s\n", 'City', 'State/Prov', 'Post Code');
        printf("%20s | %10s | %4s\n", '--------------------', '----------', '----');
        foreach ($this->list as $key => $row) {
            printf( "%20s | %10s | %4s\n", 
                    $row[2] ?? '',
                    $row[4] ?? '',
                    $key);
        }
    }
}
