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
        "Returns TRUE if row count matches; FALSE otherwise",
    )]
    public function ingestRow(array $data, array $cols) : bool
    {
        $ok = FALSE;
        if (count($cols) == count($data)) {
            $this->row = array_combine($cols, $data);
            $ok = TRUE;
        }
        return $ok;
    }
    #[GenericRow\__get(
        "Returns internal array row element"
    )]
    public function __get(string $key) : string
    {
        return $this->row[$key] ?? '';
    }
}
