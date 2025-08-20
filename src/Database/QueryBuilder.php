<?php
namespace Cookbook\Database;
#[QueryBuilder(
    "Builds an SQL query using OOP Builder pattern",
    "To create a prepared statement w/ placeholders, just supply the placeholders instead of values"
)]
class QueryBuilder
{
	public string $sql    = '';
	public string $prefix = '';
	public array $where   = [];
	public array $control = [];
    #[QueryBuilder\__construct(
        "array \$cols : desired table columns",
        "string \$table : name of the table",
        "string \$quoteColChar : character used to quote columns",
        "string \$quoteValCar : character used to quote values"
    )]
    public function __construct(
        public array $cols,
        public string $table,
        public string $quoteColChar = '`',
        public string $quoteValChar = '\'') 
    {}
    #[QueryBuilder\quoteCol("string \$a : column or table name to be quoted")]
    protected function quoteCol(string $a)
    {
        return (empty($a)) ? '' : $this->quoteColChar . $a . $this->quoteColChar;
    }
    #[QueryBuilder\quoteVal("string \$a : column or table name to be quoted")]
    protected function quoteVal(string $a)
    {
        return (empty($a)) ? '' : $this->quoteValChar . $a . $this->quoteValChar;
    }
    #[QueryBuilder\quoteExp("string \$a needs to take the form COL OPERATOR VALUE")]
    protected function quoteExp(string $a)
    {
        // get rid of double space
        $a = preg_replace('/  /', ' ', $a);
        // break up into column, operator, value
        $list = explode(' ', $a);
        $col  = (!empty($list)) ? array_shift($list) : '';
        $op   = (!empty($list)) ? array_shift($list) : '';
        $val  = (!empty($list)) ? implode(' ', $list ?? []) : '';
        return $this->quoteCol($col) . ' ' . $op . ' ' . $this->quoteVal($val);
    }
    #[QueryBuilder\select("array \$cols : columns to return; if empty, returns all cols")]
    public function select() : static
    {
        $this->sql = '';
        $this->where = [];
        $this->control = [];
        $this->prefix = 'SELECT ';
        foreach ($this->cols as $col)
            $this->prefix .= $this->quoteCol($col) . ',';
        // remove trailing comma
        $this->prefix = substr($this->prefix, 0, -1);
        $this->prefix .= ' FROM ' . $this->quoteCol($this->table) . ' ';
		return $this;
    }
    #[QueryBuilder\where("string \$a needs to take the form COL OPERATOR VALUE")]
    public function where(string $a = '') : static
    {
        $this->where[0] = 'WHERE ' 
                        . ((empty($a)) ? '' : $this->quoteExp($a))
                        . ' ';
		return $this;
    }
    #[QueryBuilder\and("string \$a needs to take the form COL OPERATOR VALUE")]
    public function and(string $a = '') : static
    {
        $this->where[] = $this->exp($a, 'AND');
		return $this;
    }
    #[QueryBuilder\or("string \$a needs to take the form COL OPERATOR VALUE")]
    public function or(string $a = '') : static
    {
        $this->where[] = $this->exp($a, 'OR');
		return $this;
    }
    #[QueryBuilder\not("string \$a needs to take the form COL OPERATOR VALUE")]
    public function not(string $a = '')
    {
        $this->where[] = $this->exp($a, 'NOT');
		return $this;
    }
    #[QueryBuilder\exp("string \$a needs to take the form COL OPERATOR VALUE",
                       " string \$exp is AND, OR, NOT")]
    public function exp(string $a = '', string $exp = 'AND')
    {
        return ' ' . $exp . ' ' . (((empty($a)) ? '' : $this->quoteExp($a))) . ' ';
    }
    #[QueryBuilder\like("string \$a : COL", "string \$b : VALUE")]
    public function like(string $a, string $b) : static
    {
        $this->where[] = $this->quoteCol($a) . ' LIKE ' . $this->quoteVal('%' . $b . '%') . ' ';
		return $this;
    }
    #[QueryBuilder\in(
        "string \$col : column name", 
        "array \$a items to be included in the IN clause"
    )]    
    public function in(string $col, array $arr) : static
    {
        $vals = '';
        foreach ($arr as $item) {
            $vals .= $this->quoteVal($item) . ',';
        }
        $this->where[] = $this->quoteCol($col) . ' IN ( ' . substr($vals, 0, -1) . ' )';
		return $this;
    }
    #[QueryBuilder\limit("int \$num : represents how many rows in the output")]    
    public function limit(int $num) : static
    {
        $this->control[0] = ' LIMIT ' . $num;
		return $this;
    }
    #[QueryBuilder\offset("int \$num : represents how many rows to skip")]    
    public function offset(int $num) : static
    {
        $this->control[1] = ' OFFSET ' . $num;
		return $this;
    }
    #[QueryBuilder\getSql("returns the SQL string")]    
	public function getSql() : string
	{
		$this->sql = $this->prefix
				. implode(' ', $this->where)
				. ' '
				. ($this->control[0] ?? '')
				. ' '
				. ($this->control[1] ?? '');
		$this->sql = str_replace(['  ','  '], ' ', $this->sql);
		return trim($this->sql);
	}
}
