<?php
    namespace Application\Core;
    use \PDO;
    use \Exception;
    use Application\Objects\xORM\Select;
    use Application\Objects\xORM\Raw;
    use Application\Objects\xORM\ResultObject;
    use Application\Core\StatusCodeHandler;
    use Application\Core\LogHandler;

    class xORM {
        const DRIVER_MYSQL = 'mysql';
        const DRIVER_SQLITE = 'sqlite';
        /** @var PDO PDO instance of database connection */
        private static $_instance = false;

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
        public static function configure($driver, $location, $options = []) {
            $dsn=$driver.":";
            try {
                switch($driver) {
                    case xORM::DRIVER_MYSQL:
                        $dsn.="host=".$location.";dbname=".$options['db'];
                        self::$_instance = new PDO($dsn, $options['user'], $options['password']);
                        break;
                    case xORM::DRIVER_SQLITE:
                        $dsn.=$location;
                        self::$_instance = new PDO($dsn);
                        break;
                    default:
                        return false;
                }
            } catch(Exception $ex) {
                LogHandler::write(StatusCodeHandler::find(0x0005), $ex->getMessage());
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
        public static function execute($query, $values) {
            $q=self::$_instance->prepare($query);
            $q->execute($values);
            $info = $q->errorInfo();
            if($info[0]!='00000') { throw (new Exception($info[2], $info[1])); }
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
        public static function findOne($query, $values) {
            return self::execute($query, $values)->fetch(PDO::FETCH_ASSOC);
        }

        /**
        * Executes a statements and fetches all results
        *
        * @param string $query
        * @param array $values
        *
        * @return array
        */
        public static function findMany($query, $values) {
            return self::execute($query, $values)->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
        * Starts ORM select query
        *
        * @param array|string Columns or column to select
        * @param boolean Whether this column name is the id column
        *
        * @return xTend\Core\xORM\Select
        */
        public static function select($column, $is_id_col = false) {
            return new Select($column, $is_id_col);
        }

        /**
        * Starts creation of a record
        */
        public static function create($table, $values = [], $id_column = 'id') {
            return new ResultObject($values, $table, [], $id_column);
        }


        /**
        * Execute a transaction. If the supplied function retuerns false it will rollback
        * Otherwise it will commit
        *
        * @param function $fn
        *
        * @return boolean
        */
        public static function transaction($fn) {
            self::$_instance->beginTransaction();
            if($fn()) {
                return self::$_instance->commit();
            }
            return self::$_instance->rollBack();
        }

        /**
        * Execute a raw sql statement
        *
        * @param string $sql
        * @param array $params
        *
        * @return PDOStatement
        */
        public static function raw($sql, $params = []) {
            return new Raw($sql, $params);
        }
    }