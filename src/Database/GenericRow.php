<?php
namespace Cookbook\Database;

use function count;
use function array_slice;
use function array_combine;
#[GenericRow("Represents a single row in a table")]
class GenericRow
{
    public array $row  = [];
    public string $sql = '';
    #[GenericRow\__construct(
        "array \$data : row to be ingested"
    )]
    public function __construct(array $data = []) 
    {
        $this->ingestRow($data);
    }
    #[GenericRow\ingestRow(
        "Ingests row from CSV",
        "IMPORTANT: column order needs to match!",
        "array \$data: actual data from CSV file",
        "array \$cols: column names to be ingested",
        "Returns current value of \$this->row",
    )]
    public function ingestRow(array $data = [], array $cols = []) : array
    {
        // check to see if it's a numeric array 
        // using new PHP 8.1 function array_is_list()
        if (!empty($data)) {
            if (array_is_list($data)) {
                foreach ($cols as $i => $key) {
                    $this->row[$key] = $data[$i] ?? '';
                }
            } else {
                $this->row = $data;
            }
        }
        return $this->row;
    }
    #[GenericRow\__get(
        "Returns internal array row element"
    )]
    public function __get(string $key) : string
    {
        return $this->row[$key] ?? '';
    }
}
