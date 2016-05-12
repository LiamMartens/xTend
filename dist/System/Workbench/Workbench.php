<?php
    namespace xTend\Workbench;
    class Command {
        private $_app;
        private $_rx;
        private $_call;
        public function __construct($app, $rx, $call) {
            $this->_app = $app;
            $this->_rx = $rx;
            $this->_call = $call;
        }
        public function isMatch(&$command) {
            return preg_match($this->_rx, $command);
        }
        public function execute(&$arguments) {
            return call_user_func($this->_call, $this->_app, $arguments);
        }
    }

    class Workbench {
        private $_app;
        private $_argv;
        private $_command;
        private $_commands;
        public function __construct($app, $argv) {
            $this->_app = $app;
            $this->_argv = $argv;
            $this->_command='';
            for($i=1;$i<count($this->_argv);$i++) {
                if($i>2) $this->_command.=' ';
                $this->_command.=$this->_argv[$i];
            }
            $this->_commands = [];
        }

        public function registerCommand($rx, $call) {
            $this->_commands[] = new Command($this->_app, $rx, $call);
        }

        public function run() {
            foreach($this->_commands as $command) {
                if($command->isMatch($this->_command)) {
                    return $command->execute($this->_argv);
                }
            }
            //execute help
            return false;
        }
    }
    /**
		Global functions for initializing and retrieving workbench instances
	**/
	if(!function_exists("getCurrentBench")) {
		function getCurrentBench($ns) {
			//get system directory
			global $benches;
			if(is_array($benches)&&isset($benches[$ns])) {
				return $benches[$ns];
			} return false;
		}
	}
	if(!function_exists("createNewBench")) {
		function createNewBench($ns, $app, $argv) {
			global $benches;
			if(!is_array($benches))
				$benches=[];
			//create new app instance
			$benches[$ns]=new Workbench($app, $argv);
			return $benches[$ns];
		}
	}
