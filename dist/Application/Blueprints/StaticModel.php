<?php
    namespace Application\Blueprints;
    use Application\Objects\xORM\Select;
    use Application\Objects\xORM\ResultObject;

    /**
    * Default Model which allows for database interaction
    * using the built in ORM
    */
    class StaticModel {
        /** @var string Name of the table of the model. By default the plural lowercase version of the class name is used */
        protected static $_table = false;
        /** @var string Column name of the primary key */
        protected static $_id_column = 'id';

        /**
        * Returns the table name if set or lowercases it and returns the plural
        *
        * @return string
        */
        private static function tableName() {
            $table = self::$_table;
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
        public static function select($column) {
            return (new Select($column))->primary(self::$_id_column)->from(self::tableName());
        }

        /**
        * Starts creation of a record
        */
        public static function create($values = []) {
            return new ResultObject($values, self::tableName(), [], self::$_id_column);
        }
    }