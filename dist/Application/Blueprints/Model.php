<?php
    namespace Application\Blueprints;
    use Application\Objects\xORM\Select;
    use Application\Objects\xORM\ResultObject;

    /**
    * Default Model which allows for database interaction
    * using the built in ORM
    */
    class Model {
        /** @var string Name of the table of the model. By default the plural lowercase version of the class name is used */
        protected $_table = false;
        /** @var string Column name of the primary key */
        protected $_id_column = 'id';

        /**
        * Returns the table name if set or lowercases it and returns the plural
        *
        * @return string
        */
        private function tableName() {
            $table = $this->_table;
            if($table===false) {
                $class = get_called_class();
                $back_pos = strrpos($class, "\\");
                if($back_pos!==false) { $class=substr($class, $back_pos+1); }
                $table = strtolower($class."s");
            }
            return $table;
        }

        /**
        * Starts ORM select query
        *
        * @param array|string Columns or column to select
        * @param boolean Whether this column name is the id column
        */
        public function select($column) {
            return (new Select($column))->primary($this->_id_column)->from($this->tableName());
        }

        /**
        * Starts creation of a record
        */
        public function create($values = []) {
            return new ResultObject($values, $this->tableName(), [], $this->_id_column);
        }
    }