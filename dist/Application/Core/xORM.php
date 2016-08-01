<?php
    namespace xTend\Core\xORM {
        use \ArrayAccess;
        use \xTend\Core\xORM;
        class Where {
            const OPERATOR_GT = '>';
            const OPERATOR_EQ = '=';
            const OPERATOR_LT = '<';
            const OPERATOR_LIKE = ' LIKE ';
            const OPERATOR_IN = ' IN ';
            const OPERATOR_BETWEEN = ' BETWEEN ';

            /** @var string Left hand side of the where clause */
            private $_column_left;
            /** @var string Operator between the columns */
            private $_operator;
            /** @var string Right hand side of the where clause */
            private $_value;

            public function __construct($col_left, $operator, $value) {
                //check column left ` characters
                $this->_column_left = $col_left;
                $this->_operator = $operator;
                $this->_value = $value;
            }

            /**
            * Builds the query and returns it
            *
            * @return string
            */
            public function query() {
                return xORM::addBrackets($this->_column_left).$this->_operator.'?';
            }

            /**
            * Returns the value of the where clause
            *
            * @return mixed
            */
            public function value() {
                return $this->_value;
            }
        }

        class Join {
            const TYPE_INNER  = 'INNER JOIN';
            const TYPE_LEFT = 'LEFT JOIN';
            const TYPE_RIGHT = 'RIGHT JOIN';
            const TYPE_FULL = 'FULL OUTER JOIN';
            const TYPE_LEFT_OUTER = 'LEFT OUTER JOIN';
            const TYPE_RIGHT_OUTER = 'RIGHT OUTER JOIN';

            /** @var string Type of the join */
            private $_join;
            /** @var string Name of the table to join */
            private $_table;
            /** @var arrray Contains an array of the ON parameter, [column, operator, column]  */
            private $_on;
            /** @var string|boolean Contains an alias or false */
            private $_alias;

            public function __construct($type, $table, $on, $alias = false) {
                $this->_join = $type;
                $this->_table = $table;
                $this->_on = $on;
                $this->_alias = $alias;
            }

            public function  query() {
                return $this->_join." ".
                        xORM::addBrackets($this->_table).
                        (($this->_alias!==false) ? " AS ".xORM::addBrackets($this->_alias) : '').
                        " ON ".xORM::addBrackets($this->_on[0]).
                        $this->_on[1].xORM::addBrackets($this->_on[2]);
            }
        }

        class Order {
            const ASC = 'ASC';
            const DESC = 'DESC';

            /** @var string Name of the column to order by */
            private $_column;
            /** @var string Type of the order (ASC or DESC) */
            private $_type;

            public function __construct($column, $type) {
                $this->_column = $column;
                $this->_type = $type;
            }

            public function query() {
                return xORM::addBrackets($this->_column)." ".$this->_type;
            }
        }

        class Aggregate {
            const TYPE_AVG = 'AVG';
            const TYPE_COUNT = 'COUNT';
            const TYPE_MAX = 'MAX';
            const TYPE_MIN = 'MIN';
            const TYPE_SUM = 'SUM';
            const TYPE_LEN = 'LEN';
            const TYPE_UPPER = 'UPPER';
            const TYPE_LOWER = 'LOWER';

            /** @var string Type of function */
            private $_type;
            /** @var string Name of column to take aggregate of */
            private $_column;
            /** @var string Alias of the function */
            private $_alias;

            public function __construct($type, $column, $alias = false) {
                $this->_type = $type;
                $this->_column = $column;
                $this->_alias = $alias;
            }

            public function hasAlias() {
                return ($this->_alias!==false);
            }

            public function query() {
                return $this->_type."(".xORM::addBrackets($this->_column).") ".
                        (($this->_alias!==false) ? "AS ".xORM::addBrackets($this->_alias) : '');
            }
        }

        abstract class WhereGroup {
            /** @var array Contains wheres */
            protected $_wheres=[];

            public function __construct($wheres=[]) {
                $this->_wheres = $wheres;
            }

            /**
            * Returns the count of the wheres
            *
            * @return int
            */
            public function count() {
                return count($this->_wheres);
            }

            /**
            * Cuts of the last couple of elements starting at index and returns it
            *
            * @param int $start
            *
            * @return array
            */
            public function pop($start) {
                return array_splice($this->_wheres, $start);
            }

            /**
            * Adds a where clause
            *
            * @param xTend\Core\xORM\Where $where
            */
            public function where($where) {
                $this->_wheres[] = $where;
                return $this;
            }

            /**
            * Returns the values of the where clauses
            *
            * @return array
            */
            public function values() {
                $array_map_result=[]; foreach($this->_wheres as $w) {
                    $array_map_result[] = $w->value();
                } return $array_map_result;
            }
        }

        class WhereGroupOr extends WhereGroup {
            /**
            * Builds the query and returns it
            *
            * @return string
            */
            public function query() {
                if(count($this->_wheres)>0) {
                    $array_map_result=[]; foreach($this->_wheres as $w) {
                        $array_map_result[] = $w->query();
                    } return implode(" OR ", $array_map_result);
                } else { return ' 0 '; }
            }
        }

        class WhereGroupAnd extends WhereGroup {
            /**
            * Builds the query and returns it
            *
            * @return string
            */
            public function query() {
                if(count($this->_wheres)>0) {
                    $array_map_result=[]; foreach($this->_wheres as $w) {
                        $array_map_result[] = $w->query();
                    } return implode(" AND ", $array_map_result);
                } else { return ' 1 '; }
            }
        }

        class ResultObject implements ArrayAccess {
            /** @var xTend\Core\App Current application instance */
            protected $_app;
            /** @var array The data of the result */
            protected $_values;
            /** @var array The original column names */
            protected $_column_bindings;
            /** @var string Name of the table where the data came from */
            protected $_table;
            /** @var string Name of the primary key column */
            protected $_id_column = 'id';

            public function __construct($app, $data, $table, $columns, $id_column) {
                $this->_app = $app;
                $this->_values = $data;
                $this->_table = $table;
                $this->_column_bindings = $columns;
                $this->_id_column = $id_column;
            }

            /**
            * Returns a data member
            *
            * @param string $name
            *
            * @return mixed
            */
            public function __get($name) {
                if(isset($this->_values[$name])) {
                    return $this->_values[$name];
                }
                return $this->{$name};
            }

            /**
            * Sets a data member
            *
            * @param string $name
            * @param mixed $value
            *
            * @return boolean
            */
            public function __set($name, $value) {
                return $this->_values[$name] = $value;
            }

            /**
            * For ArrayAccess setting
            *
            * @param mixed $offset
            * @param mixed $value
            */
            public function offsetSet($offset, $value) {
                if (!is_null($offset)) {
                    $this->_values[$offset] = $value;
                }
            }

            /**
            * For ArrayAccess isset
            *
            * @param mixed $offset
            *
            * @return boolean
            */
            public function offsetExists($offset) {
                return isset($this->_values[$offset]);
            }

            /**
            * For ArrayAccess unset
            *
            * @param mixed $offset
            */
            public function offsetUnset($offset) {
                unset($this->_values[$offset]);
            }

            /**
            * For ArrayAccess get
            *
            * @param mixed $offset
            *
            * @return mixed
            */
            public function offsetGet($offset) {
                return $this->_values[$offset];
            }

            private function primaryKeyValue() {
                $col_name = $this->_id_column; if(isset($this->_column_bindings[$col_name])) {
                    $col_name = $this->_column_bindings[$col_name];
                }
                if(isset($this->_values[$col_name])) {
                    return $this->_values[$col_name];
                }
                return false;
            }

            /**
            * Return the update query of the result
            *
            * @return string
            */
            public function query_update() {
                $set_arr=[]; foreach($this->_values as $key => $value) {
                    $col_name=$key; $search=array_search($key, $this->_column_bindings);
                    if($search!==false) { $col_name = $search; }
                    $set_arr[] = xORM::addBrackets($col_name).'=?';;
                }
                return "UPDATE ".xORM::addBrackets($this->_table)." SET ".
                        implode(',', $set_arr)." WHERE ".xORM::addBrackets($this->_id_column)."=?";
            }

            /**
            * Returns the delete query of the result
            *
            * @return string
            */
            public function query_delete() {
                return "DELETE FROM ".xORM::addBrackets($this->_table).
                        " WHERE ".xORM::addBrackets($this->_id_column)."=?";
            }

            /**
            * Returns the insert statement query of the result
            *
            * @return string
            */
            public function query_insert() {
                $column_names=array_keys($this->_values); foreach($column_names as $i => $col) {
                    $search=array_search($col, $this->_column_bindings);
                    if($search!==false) { $column_names[$i]=xORM::addBrackets($search); }
                    else { $column_names[$i]=xORM::addBrackets($column_names[$i]); }
                }
                return "INSERT INTO ".xORM::addBrackets($this->_table).
                        " (".implode(",", $column_names).") VALUES (".
                        trim(str_repeat("?,", count($this->_values)), ",").")";
            }

            /**
            * Returns the query parameters
            *
            * @return array
            */
            public function values() {
                $vals = array_values($this->_values);
                $pk_val = $this->primaryKeyValue();
                if($pk_val) { $vals[] = $pk_val; }
                return $vals;
            }

            public function save() {
                return $this->_app->orm()->execute($this->query_update(), $this->values());
            }

            public function delete() {
                return $this->_app->orm()->execute($this->query_delete(), [$this->primaryKeyValue()]);
            }

            public function insert() {
                return $this->_app->orm()->execute($this->query_insert(), $this->values());
            }
        }

        abstract class Query {
            /** @var xTend\Core\App Current application instance */
            protected $_app;
            /** @var string The table name to select from */
            protected $_table='';
            /** @var string Primary key column name */
            protected $_id_column='id';
            /** @var array The columns to select */
            protected $_columns=[];
            /** @var array Aliases of the columns */
            protected $_alias=[];
            /** @var array where statements */
            protected $_wheresAnd=false;
            /** @var array where statements */
            protected $_wheresOr=false;
            /** @var array wrapped where and groups */
            protected $_wheresWrapAnd=[];
            /** @var array wrapped where or groups */
            protected $_wheresWrapOr=[];

            public function __construct($app) {
                $this->_app = $app;
                $this->_wheresAnd = new WhereGroupAnd();
                $this->_wheresOr = new WhereGroupOr();
            }

            /**
            * Sets the primary key of the query for saving records after
            *
            * @param string|array $column
            *
            * @return xTend\Core\xORM\Select
            */
            public function primary($column) {
                if(is_array($column)) {
                    $this->_id_column = $column[0];
                } else { $this->_id_column = $column; }
                return $this;
            }

            /**
            * Base where wrap function
            *
            * @param array reference Array to put where groups in
            * @param function $fn Function to execute for where registering
            *
            * @return xTend\Core\xORM\Query Own instance
            */
            private function _wrap(&$array, $fn) {
                //get start indexes
                $start_index_and = $this->_wheresAnd->count() ;
                $start_index_or = $this->_wheresOr->count() ;
                //run function
                $fn($this);
                //remove and and or statements
                //and put them in the wrap groups
                $and_tail = $this->_wheresAnd->pop($start_index_and);
                $or_tail = $this->_wheresOr->pop($start_index_or);
                $group=[];
                if(count($and_tail)>0) { $group[] = new WhereGroupAnd($and_tail); }
                if(count($or_tail)>0) { $group[] = new WhereGroupOr($or_tail); }
                $array[]=$group;
                return $this;
            }

            /**
            * Starts a where wrap and
            *
            * @param function $fn
            *
            * @return xTend\Core\xORM\Query Own instance
            */
            public function wrap($fn) {
                return $this->_wrap($this->_wheresWrapAnd, $fn);
            }

            /**
            * Starts a where wrap or
            *
            * @param function $fn
            *
            * @return xTend\Core\xORM\Query Own instance
            */
            public function orWrap($fn) {
                return $this->_wrap($this->_wheresWrapOr, $fn);
            }

            /**
            * Adds where statement =
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function where($column, $value) {
                $this->_wheresAnd->where(new Where($column, Where::OPERATOR_EQ, $value));
                return $this;
            }

            /**
            * Adds where statement >
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function whereGt($column, $value) {
                $this->_wheresAnd->where(new Where($column, Where::OPERATOR_GT, $value));
                return $this;
            }

            /**
            * Adds where statement <
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function whereLt($column, $value) {
                $this->_wheresAnd->where(new Where($column, Where::OPERATOR_LT, $value));
                return $this;
            }

            /**
            * Adds where statement LIKE
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function whereLike($column, $value) {
                $this->_wheresAnd->where(new Where($column, Where::OPERATOR_LIKE, $value));
                return $this;
            }

            /**
            * Adds where statement IN
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function whereIn($column, $value) {
                $this->_wheresAnd->where(new Where($column, Where::OPERATOR_IN, $value));
                return $this;
            }

                    /**
            * Adds where statement BETWEEN
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function whereBetween($column, $value) {
                $this->_wheresAnd->where(new Where($column, Where::OPERATOR_BETWEEN, $value));
                return $this;
            }

            /**
            * Adds where statement =
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function orWhere($column, $value) {
                $this->_wheresOr->where(new Where($column, Where::OPERATOR_EQ, $value));
                return $this;
            }

            /**
            * Adds where statement >
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function orWhereGt($column, $value) {
                $this->_wheresOr->where(new Where($column, Where::OPERATOR_GT, $value));
                return $this;
            }

            /**
            * Adds where statement <
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function orWhereLt($column, $value) {
                $this->_wheresOr->where(new Where($column, Where::OPERATOR_LT, $value));
                return $this;
            }

            /**
            * Adds where statement LIKE
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function orWhereLike($column, $value) {
                $this->_wheresOr->where(new Where($column, Where::OPERATOR_LIKE, $value));
                return $this;
            }

            /**
            * Adds where statement IN
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function orWhereIn($column, $value) {
                $this->_wheresOr->where(new Where($column, Where::OPERATOR_IN, $value));
                return $this;
            }

                    /**
            * Adds where statement BETWEEN
            *
            * @param string $column
            * @param mixed $value
            *
            * @return xTend\Core\xORM\Select
            */
            public function orWhereBetween($column, $value) {
                $this->_wheresOr->where(new Where($column, Where::OPERATOR_BETWEEN, $value));
                return $this;
            }

            /**
            * Returns the values of the query parameters
            *
            * @return array
            */
            public function values() {
                return array_merge($this->_wheresAnd->values(), $this->_wheresOr->values());
            }
        }

        class Select extends Query {
            /** @var boolean Whether the select should execute distinct */
            private $_is_distinct = false;
            /** @var array Contains the order statements */
            private $_orders = [];
            /** @var string Contains the LIMIT expression */
            private $_limit = false;
            /** @var string Contains the OFFSET expression */
            private $_offset = false;
            /** @var array Contains join statements */
            private $_join = [];
            /** @var array Aggregate functions */
            private $_aggregate = [];
            /** @var array Group By columns */
            private $_group = [];

            /**
            * @param string|array If the column is an array the second element is the alias
            */
            public function __construct($app, $column) {
                parent::__construct($app);
                if(is_array($column)) {
                    $this->_columns[] = $column[0];
                    $this->_alias[$column[0]] = $column[1];
                } else { $this->_columns[] = $column; }
            }

            /**
            * Adds columns to the select statement
            *
            * @param array|string Columns to add (or 1 col as string)
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function select($column, $is_id_col = false) {
               if(is_array($column)) {
                    $this->_columns[] = $column[0];
                    $this->_alias[$column[0]] = $column[1];
                } else { $this->_columns[] = $column; }
                if($is_id_col) { $this->primary($column); }
                return $this;
            }

            /**
            * Enables distinct
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function distinct() {
                $this->_is_distinct = true;
                return $this;
            }

            /**
            * Adds order by asc
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function orderAsc($column) {
                $this->_orders[] = new Order($column, Order::ASC);
                return $this;
            }

            /**
            * Adds order by desc
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function orderDesc($column) {
                $this->_orders[] = new Order($column, Order::DESC);
                return $this;
            }

            /**
            * Sets SQL's LIMIT keyword
            *
            * @param integer $value
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function limit($value) {
                $this->_limit = $value;
                return $this;
            }

            /**
            * Sets SQL's OFFSET keyword
            *
            * @param integer $value
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function offset($value) {
                $this->_offset = $value;
                return $this;
            }

            /**
            * Adds INNER JOIN
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function join($table, $on, $alias = false) {
                $this->_join[] = new Join(Join::TYPE_INNER, $table, $on, $alias);
                return $this;
            }

            /**
            * Adds LEFT JOIN
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function leftJoin($table, $on, $alias = false) {
                $this->_join[] = new Join(Join::TYPE_LEFT, $table, $on, $alias);
                return $this;
            }

            /**
            * Adds LEFT OUTER JOIN
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function leftOuterJoin($table, $on, $alias = false) {
                $this->_join[] = new Join(Join::TYPE_LEFT_OUTER, $table, $on, $alias);
                return $this;
            }

            /**
            * Adds RIGHT JOIN
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function rightJoin($table, $on, $alias = false) {
                $this->_join[] = new Join(Join::TYPE_RIGHT, $table, $on, $alias);
                return $this;
            }

            /**
            * Adds RIGHT OUTER JOIN
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function rightOuterJoin($table, $on, $alias = false) {
                $this->_join[] = new Join(Join::TYPE_RIGHT_OUTER, $table, $on, $alias);
                return $this;
            }

            /**
            * Adds FULL OUTER JOIN
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function fullOuterJoin($table, $on, $alias = false) { return $this->fullJoin($table, $on, $alias); }
            public function fullJoin($table, $on, $alias = false) {
                $this->_join[] = new Join(Join::TYPE_FULL, $table, $on, $alias);
                return $this;
            }

            /**
            * Adds AVG function
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function avg($column, $alias = false) {
                $this->_aggregate[$column] = new Aggregate(Aggregate::TYPE_AVG, $column, $alias);
                return $this;
            }

            /**
            * Adds COUNT function
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function count($column, $alias = false) {
                $this->_aggregate[$column] = new Aggregate(Aggregate::TYPE_COUNT, $column, $alias);
                return $this;
            }

            /**
            * Adds MAX function
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function max($column, $alias = false) {
                $this->_aggregate[$column] = new Aggregate(Aggregate::TYPE_MAX, $column, $alias);
                return $this;
            }

            /**
            * Adds MIN function
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function min($column, $alias = false) {
                $this->_aggregate[$column] = new Aggregate(Aggregate::TYPE_MIN, $column, $alias);
                return $this;
            }

            /**
            * Adds SUM function
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function sum($column, $alias = false) {
                $this->_aggregate[$column] = new Aggregate(Aggregate::TYPE_SUM, $column, $alias);
                return $this;
            }

            /**
            * Adds LEN function
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function len($column, $alias = false) {
                $this->_aggregate[$column] = new Aggregate(Aggregate::TYPE_LEN, $column, $alias);
                return $this;
            }

            /**
            * Adds UPPER function
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function upper($column, $alias = false) {
                $this->_aggregate[$column] = new Aggregate(Aggregate::TYPE_UPPER, $column, $alias);
                return $this;
            }

            /**
            * Adds LOWER function
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function lower($column, $alias = false) {
                $this->_aggregate[$column] = new Aggregate(Aggregate::TYPE_LOWER, $column, $alias);
                return $this;
            }

            /**
            * Adds group by column
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function group($column) {
                $this->_group[] = $column;
                return $this;
            }

            /**
            * Sets the table member of the select query
            *
            * @param string $tableName
            *
            * @return xTend\Core\xORM\Select Own instance
            */
            public function from($tableName) {
                $this->_table = $tableName;
                return $this;
            }

            /**
            * Builds the query and returns it
            *
            * @return string
            */
            public function query() {
                $query = "SELECT ".(($this->_is_distinct) ? "DISTINCT " : '');
                $array_map_result=[]; foreach($this->_columns as $col) {
                    $column_string;
                    $agg_found = true; $agg_alias = false;
                    if(isset($this->_aggregate[$col])) {
                        $column_string=$this->_aggregate[$col]->query();
                        $agg_alias=$this->_aggregate[$col]->hasAlias();
                    } else {
                        $agg_found = false;
                        $column_string = xORM::addBrackets($col);
                    }
                    if(!$agg_alias&&isset($this->_alias[$col])) {
                        $column_string .= " AS ".xORM::addBrackets($this->_alias[$col]);
                    } $array_map_result[] = $column_string;
                }
                $query.=implode(",", $array_map_result)." FROM ".xORM::addBrackets($this->_table);
                $array_map_result=[]; foreach($this->_join as $join) {
                    $array_map_result[] = $join->query();
                } $query.=" ".implode(" ", $array_map_result);;
                if($this->_limit!==false) { $query.=" LIMIT ".$this->_limit; }
                if($this->_offset!==false) { $query.=" OFFSET ".$this->_offset; }
                //insert where statements here
                //wrapped where and groups
                $total_group_count=0;
                $array_map_result=[]; foreach($this->_wheresWrapAnd as $group) {
                    $q = "(".$group[0]->query();
                    if(count($group)>1) {
                        if($group[1] instanceof WhereGroupOr) { $q.=" OR "; }
                        elseif($group[1] instanceof WhereGroupAnd) { $q.=" AND "; }
                        $q.=$group[1]->query();
                    } $array_map_result[] = ($q.")");
                    $total_group_count++;
                }
                $query.=" WHERE ".implode(" AND ", $array_map_result);
                if(count($this->_wheresWrapOr)>0) {
                    $query.=" OR ";
                    $array_map_result=[]; foreach($this->_wheresWrapOr as $group) {
                        $q = "(".$group[0]->query();
                        if(count($group)>1) {
                            if($group[1] instanceof WhereGroupOr) { $q.=" OR "; }
                            elseif($group[1] instanceof WhereGroupAnd) { $q.=" AND "; }
                            $q.=$group[1]->query();
                        } $array_map_result[] = ($q.")");
                        $total_group_count++;
                    }
                    $query.=implode(" OR ", $array_map_result);
                }
                
                //non wrapped groups + group / order
                if($total_group_count>0) {
                    $query.=" AND ";
                }
                $query.=$this->_wheresAnd->query()." OR ".$this->_wheresOr->query();
                $array_map_result=[]; foreach($this->_orders as $ord) {
                    $array_map_result[] = $ord->query();
                }
                $query.=((count($this->_orders)>0) ? " ORDER BY ".implode(',', $array_map_result) : '');
                $query.=((count($this->_group)>0) ? " GROUP BY ".implode(",", $this->_group) : '');
                return $query;
            }


            /**
            * Finds 1 record
            *
            * @return xTend\Core\xORM\ResultObject
            */
            public function findOne() {
                $values=$this->_app->orm()->findOne($this->query(), $this->values());
                if($values===false) { return false; }
                return new ResultObject(
                                $this->_app,
                                $values,
                                $this->_table,
                                $this->_alias,
                                $this->_id_column);
            }


            /**
            * Finds multiple records
            *
            * @return array
            */
            public function findMany() {
                $results = $this->_app->orm()->findMany($this->query(), $this->values());
                $array_map_result=[]; foreach($results as $rs) {
                    $array_map_result[] = new ResultObject(
                                            $this->_app,
                                            $rs,
                                            $this->_table,
                                            $this->_alias,
                                            $this->_id_column);
                } return $array_map_result;
            }
        }

        class Raw {
            /** @var xTend\COre\App Current application instance */
            protected $_app;
            /** @var string containing the table name if you need to save afterwards */
            protected $_table = false;
            /** @var array Alias bindings */
            protected $_column_bindings = [];
            /** @var string primary key column name */
            protected $_id_column = 'id';
            /** @var string Contains the SQL statement to execute */
            protected $_sql;
            /** @var array Contains the PDO params */
            protected $_parameters;

            public function __construct($app, $sql, $params=[]) {
                $this->_app = $app;
                $this->_sql = $sql;
                $this->_parameters = $params;
            }

            /**
            * Sets the table name
            *
            * @param string $table
            *
            * @return xTend\Core\xORM\Raw Own instance
            */
            public function table($table) {
                $this->_table = $table;
                return $this;
            }

            /**
            * Binds an alias
            *
            * @param string $column
            * @param string $alias
            *
            * @return xTend\Core\xORM\Raw Own instance
            */
            public function alias($column, $alias) {
                $this->_column_bindings[$column] = $alias;
                return $this;
            }

            /**
            * Sets the id column
            *
            * @param string $column
            *
            * @return xTend\Core\xORM\Raw Own instance
            */
            public function primary($column) {
                $this->_id_column = $column;
                return $this;
            }

            public function execute() {
                return $this->_app->orm()->execute($this->_sql, $this->_parameters);
            }

            public function findOne() {
                $values=$this->_app->orm()->findOne($this->_sql, $this->_parameters);
                if($values===false) { return false; }
                return new ResultObject(
                    $this->_app,
                    $values,
                    $this->_table,
                    $this->_column_bindings,
                    $this->_id_column);

            }

            public function findMany() {
                $results = $this->_app->orm()->findMany($this->_sql, $this->_parameters);
                $array_map_result=[]; foreach($results as $rs) {
                    $array_map_result[] = new ResultObject(
                                                $this->_app,
                                                $rs,
                                                $this->_table,
                                                $this->_column_bindings,
                                                $this->_id_column);
                } return $array_map_result;
            }
        }
    }
    namespace xTend\Core {
        use \PDO;
        use \Exception;
        use xTend\Core\xORM\Select;
        use xTend\Core\xORM\Raw;
        use xTend\Core\xORM\ResultObject;

        class xORM {
            const DRIVER_MYSQL = 'mysql';
            const DRIVER_SQLITE = 'sqlite';
            /** @var PDO PDO instance of database connection */
            private $_instance = false;
            /** @var xTend\Core\App Current application instance */
            protected $_app;

            public function __construct($app) {
                $this->_app = $app;
            }

            /**
            * Adds ` brackets where necessary
            *
            * @param string $str
            *
            * @return string
            */
            public static function addBrackets($str) {
                $str_arr=explode('.', $str);
                return '`'.implode('`.`', $str_arr).'`';
            }

            /**
            * Configures the PDO connection
            *
            * @param string $driver The type of database
            * @param array $options Other options
            *
            * @return boolean
            */
            public function configure($driver, $location, $options = []) {
                $dsn=$driver.":";
                try {
                    switch($driver) {
                        case xORM::DRIVER_MYSQL:
                            $dsn.="host=".$location.";dbname=".$options['db'];
                            $this->_instance = new PDO($dsn, $options['user'], $options['password']);
                            break;
                        case xORM::DRIVER_SQLITE:
                            $dsn.=$location;
                            $this->_instance = new PDO($dsn);
                            break;
                        default:
                            return false;
                    }
                } catch(Exception $ex) {
                    if($this->_app->getDevelopmentStatus()) { throw $ex; }
                    return false;
                }
                return true;
            }

            /**
            * Preps and executes a statement
            *
            * @param string $query
            * @param array $values
            *
            * @return PDOStatement
            */
            public function execute($query, $values) {
                $q = $this->_instance->prepare($query);
                $q->execute($values);
                if($this->_app->getDevelopmentStatus()) {
                    $info = $q->errorInfo();
                    if($info[0]!="00000") { throw (new Exception($info[2], $info[1])); }
                }
                return $q;
            }

            /**
            * Executes a statements and fetches 1 result
            *
            * @param string $query
            * @param array $values
            *
            * @return array
            */
            public function findOne($query, $values) {
                return $this->execute($query, $values)->fetch(PDO::FETCH_ASSOC);
            }

            /**
            * Executes a statements and fetches all results
            *
            * @param string $query
            * @param array $values
            *
            * @return array
            */
            public function findMany($query, $values) {
                return $this->execute($query, $values)->fetchAll(PDO::FETCH_ASSOC);
            }

            /**
            * Starts ORM select query
            *
            * @param array|string Columns or column to select
            * @param boolean Whether this column name is the id column
            *
            * @return xTend\Core\xORM\Select
            */
            public function select($column, $is_id_col = false) {
                $s = new Select($this->_app, $column);
                if($is_id_col) { $s->primary($column); }
                return $s;
            }

            /**
            * Starts creation of a record
            */
            public function create($table, $values = [], $id_column = 'id') {
                return new ResultObject($this->_app, $values, $table, [], $id_column);
            }


            /**
            * Execute a transaction. If the supplied function retuerns false it will rollback
            * Otherwise it will commit
            *
            * @param function $fn
            *
            * @return boolean
            */
            public function transaction($fn) {
                $this->_instance->beginTransaction();
                if($fn()) {
                    return $this->_instance->commit();
                }
                return $this->_instance->rollBack();
            }

            /**
            * Execute a raw sql statement
            *
            * @param string $sql
            * @param array $params
            *
            * @return PDOStatement
            */
            public function raw($sql, $params = []) {
                return new Raw($this->_app, $sql, $params);
            }
        }
    }