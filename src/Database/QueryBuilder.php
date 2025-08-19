<?php
namespace Cookbook\Database;
#[QueryBuilder(
    "Builds an SQL query using OOP Builder pattern",
    "To create a prepared statement w/ placeholders, just supply the placeholders instead of values"
)]
class QueryBuilder
{
	public string $sql      = '';
	public string $prefix   = '';
	public array $where    = [];
	public array $control  = ['', ''];
    #[QueryBuilder\__construct("TableInterface \$table : Table class instance")]
    public function __construct(public TableInterface $table) {}
    #[QueryBuilder\select("array \$cols : columns to return; if empty, returns all cols")]
    public function select(array $cols = [])
    {
        $this->prefix = 'SELECT ';
        $this->prefix .= (empty($cols)) 
                         ? implode(',', $this->table->getCols())
                         : implode(',', $cols);
        $this->prefix .= ' FROM ' . $this->table::TABLE;
		return $this;
    }
    #[QueryBuilder\quote("string \$a needs to take the form COL OPERATOR VALUE")]
    protected function quote(string $a)
    {
        // get rid of double space
        $a = preg_replace('/  /', ' ', $a);
        // break up
        $list = explode(' ', $a);
        $list = explode(' ', $a);
        $col  = array_shift($list);
        $op   = array_shift($list);
        $val  = implode(' ', $list ?? []);
        return $this->table::QUOTE . $col . $this->table::QUOTE . ' ' . $op . ' ' . $this->quote($val);
    }
    #[QueryBuilder\where("string \$a needs to take the form COL OPERATOR VALUE")]
    public function where(string $a = '')
    {
        $this->where[0] = ' WHERE ' . $this->quote($a);
		return $this;
    }
    #[QueryBuilder\like("string \$a : COL", "string \$b : VALUE")]
    public function like(string $a, string $b)
    {
        $this->where[] = trim($table::QUOTE . $a . $table::QUOTE . ' LIKE %' . $b . '%');
		return $this;
    }
    #[QueryBuilder\where("string \$a needs to take the form COL OPERATOR VALUE")]
    public function and(string $a = '')
    {
        $this->where[] = trim('AND ' . $this->quote($a));
		return $this;
    }
    #[QueryBuilder\or("string \$a needs to take the form COL OPERATOR VALUE")]
    public function or($a = NULL)
    {
        $this->where[] = trim('OR ' . $this->quote($a));
		return $this;
    }
    #[QueryBuilder\in("array \$a items to be included in the IN clause")]    
    public function in(array $a)
    {
        $vals = '';
        foreach ($a as $item) {
            $vals .= $this->quote($item) . ',';
        }
        $this->where[] = 'IN ( ' . substr($vals, 0, -1) . ' )';
		return $this;
    }
    #[QueryBuilder\not("string \$a needs to take the form COL OPERATOR VALUE")]
    public function not($a = NULL)
    {
        $this->where[] = trim('NOT ' . $this->quote($a));
		return $this;
    }
    #[QueryBuilder\limit("int \$num : represents how many rows in the output")]    
    public function limit(int $num)
    {
        $this->control[0] = 'LIMIT ' . $num;
		return $this;
    }
    #[QueryBuilder\offset("int \$num : represents how many rows to skip")]    
    public function offset(int $num)
    {
        $this->control[1] = 'OFFSET ' . $num;
		return $this;
    }
    #[QueryBuilder\getSql("returns the SQL string")]    
	public function getSql()
	{
		$this->sql = $this->prefix
				. implode(' ', $this->where)
				. ' '
				. $this->control[0]
				. ' '
				. $this->control[1];
		$this->sql = preg_replace('/  /', ' ', $this->sql);
		return trim($this->sql);
	}
}
