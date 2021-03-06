<?php
    namespace Application\Core;

    /**
    * VersionCheck handles checking
    * packagist versions
    */
    class VersionCheck {
        /** @var string Contains the registered version expression */
        private $_expression;
        /** @var string Contains the version to match */
        private $_version;
        /** @var string Contains the exact version string */
        private $_exact;

        /**
        * @param string $expression
        * @param string $version
        */
        public function __construct($expression, $version) {
            $this->_expression = trim($expression);
            $version_match = []; preg_match('/((([0-9]\.)+[0-9])|([0-9]+))/', $version, $version_match);
            if(isset($version_match[0])) {
                $this->_version = $version_match[0];
                if(substr($version, 0, 4)=='dev-') {
                    $this->_version='dev-'.$this->_version;
                }
            } else { $this->_version = $version; }
            $this->_exact = $version;
        }

        /**
        * Checks whether the versions are exact matches
        *
        * @return boolean
        */
        private function exact() {
            return ($this->_expression==$this->_exact);
        }

        /**
        * Checks whether the versions are a range match
        *
        * @return boolean
        */
        private function range() {
            $or_expressions = explode('||', $this->_expression);
            foreach($or_expressions as $or_exp) {
                $and_expressions = preg_split('/( |,)/', trim($or_exp));
                $and_match = true;
                foreach($and_expressions as $and_exp) {
                    $rx_match = []; preg_match('/(\>|\<|\!|\=){1,2}/', $and_exp, $rx_match);
                    if(!isset($rx_match[0])) { return false; }
                    $operator=$rx_match[0];
                    $number=substr($and_exp, strlen($operator));;
                    if(!version_compare($this->_version, $number, $operator)) {
                        $and_match = false;
                        break;
                    }
                }
                if($and_match==true) { return true; }
            }
            return false;
        }

        /**
        * Checks whether the versions are a hyphen match
        *
        * @return boolean
        */
        private function hyphen() {
            $parts = explode('-', $this->_expression);
            if(!isset($parts[1])) { return false; }
            $rx_matches=[]; preg_match('/\.([0-9]+)/', $parts[1], $rx_matches);
            if(!isset($rx_matches[1])) { return false; }
            $parts[1]=preg_replace('/\.([0-9]+)/', '.'.(doubleval($rx_matches[1])+1), $parts[1]);
            return (version_compare($this->_version, trim($parts[0]), '>=')
                &&version_compare($this->_version, trim($parts[1]), '<'));

        }

        /**
        * Checks whether the versions are a wildcard match
        *
        * @return boolean
        */
        private function wildcard() {
            $version_start = trim($this->_expression, '.*');
            $rx_matches=[]; preg_match('/([0-9]+)\.\*/', $this->_expression, $rx_matches);
            if(!isset($rx_matches[1])) { return false; }
            $version_end = preg_replace('/([0-9]+)\.\*/', (doubleval($rx_matches[1])+1), $this->_expression);
            return (version_compare($this->_version, trim($version_start), '>=')
                &&version_compare($this->_version, trim($version_end), '<'));
        }

        /**
        * Checks whether the versions are a tilde match
        *
        * @return boolean
        */
        private function tilde() {
            if(substr($this->_expression, 0, 1)!=='~') { return false; }
            $version_start = substr($this->_expression, 1);
            $rx_matches=[]; preg_match('/([0-9]+)\.([0-9])+$/', $version_start, $rx_matches);
            if(!isset($rx_matches[2])) { return false; }
            $version_end = preg_replace('/([0-9]+)\.([0-9])+$/', (doubleval($rx_matches[1])+1).".0", $version_start);
            return (version_compare($this->_version, trim($version_start), '>=')
                    &&version_compare($this->_version, trim($version_end), '<'));
        }

        /**
        * Checks whether the versions are a caret match
        *
        * @return boolean
        */
        private function caret() {
            if(substr($this->_expression, 0, 1)!=='^') { return false; }
            $version_start = substr($this->_expression, 1);
            $rx_matches=[]; preg_match('/([1-9][0-9]+|[1-9])/', $version_start, $rx_matches);
            if(!isset($rx_matches[1])) { return false; }
            $version_end = preg_replace('/([1-9][0-9]+|[1-9])(\.[0-9]+)*/', (doubleval($rx_matches[1])+1).'.0', $version_start);
            return (version_compare($this->_version, trim($version_start), '>=')
                    &&version_compare($this->_version, trim($version_end), '<'));
        }

        /**
        * @return boolean
        */
        public function match() {
            $multiple=explode('|', $this->_expression);
            if((strpos($this->_expression, '||')===false)&&(count($multiple)>1)) {
                foreach($multiple as $exp) {
                    if((new VersionCheck($exp, $this->_version))->match()) {
                        return true;
                    }
                }
                return false;
            }
            return ($this->exact()||
                    $this->range()||
                    $this->hyphen()||
                    $this->wildcard()||
                    $this->tilde()||
                    $this->caret());
        }
    }
