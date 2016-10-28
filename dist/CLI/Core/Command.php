<?php
    namespace xTend\Workbench;
    /**
    * The Command class handles executing and
    * matching a command
    */
    class Command {
        /** @var string Contains the regex for the command */
        public $_rx;
        /** @var callable Contains the command function */
        private $_call;

        /**
        * @param string $rx
        * @param function $call
        */
        public function __construct($rx, $call) {
            $this->_rx = '/'.$rx.'/';
            $this->_call = $call;
        }

        /**
        * Checks whether the command is a match
        *
        * @param string reference $command
        *
        * @return boolean
        */
        public function match(&$command) {
            return preg_match($this->_rx, $command);
        }

        /**
        * Executes a command
        *
        * @param array reference $arguments
        *
        * @return mixed
        */
        public function execute(&$arguments) {
            return call_user_func($this->_call, $arguments);
        }
    }