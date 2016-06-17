<?php
    namespace xTend\Core;
    class VersionCheck {
        private $_expression;
        private $_version;
        public function __construct($expression, $version) {
            $this->_expression = trim($expression);
            $this->_version = trim(trim($version), 'v');
        }

        private function isExact() {
            return ($this->_expression==$this->_version);
        }

        private function isRange() {
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

        private function isHyphenRange() {
            $parts = explode('-', $this->_expression);
            if(!isset($parts[1])) { return false; }
            $rx_matches=[]; preg_match('/\.([0-9]+)/', $parts[1], $rx_matches);
            if(!isset($rx_matches[1])) { return false; }
            $parts[1]=preg_replace('/\.([0-9]+)/', '.'.(doubleval($rx_matches[1])+1), $parts[1]);
            return (version_compare($this->_version, trim($parts[0]), '>=')
                &&version_compare($this->_version, trim($parts[1]), '<'));

        }

        private function isWildcard() {
            $version_start = trim($this->_expression, '.*');
            $rx_matches=[]; preg_match('/([0-9]+)\.\*/', $this->_expression, $rx_matches);
            if(!isset($rx_matches[1])) { return false; }
            $version_end = preg_replace('/([0-9]+)\.\*/', (doubleval($rx_matches[1])+1), $this->_expression);
            return (version_compare($this->_version, trim($version_start), '>=')
                &&version_compare($this->_version, trim($version_end), '<'));
        }

        private function isTilde() {
            if(substr($this->_expression, 0, 1)!=='~') { return false; }
            $version_start = substr($this->_expression, 1);
            $rx_matches=[]; preg_match('/([0-9]+)\.([0-9])+$/', $version_start, $rx_matches);
            if(!isset($rx_matches[2])) { return false; }
            $version_end = preg_replace('/([0-9]+)\.([0-9])+$/', (doubleval($rx_matches[1])+1).".0", $version_start);
            return (version_compare($this->_version, trim($version_start), '>=')
                    &&version_compare($this->_version, trim($version_end), '<'));
        }

        private function isCaret() {
            if(substr($this->_expression, 0, 1)!=='^') { return false; }
            $version_start = substr($this->_expression, 1);
            $rx_matches=[]; preg_match('/([1-9][0-9]+|[1-9])/', $version_start, $rx_matches);
            if(!isset($rx_matches[1])) { return false; }
            $version_end = preg_replace('/([1-9][0-9]+|[1-9])(\.[0-9]+)*/', (doubleval($rx_matches[1])+1).'.0', $version_start);
            return (version_compare($this->_version, trim($version_start), '>=')
                    &&version_compare($this->_version, trim($version_end), '<'));
        }

        public function isMatch() {
            return ($this->isExact()||
                    $this->isRange()||
                    $this->isHyphenRange()||
                    $this->isWildcard()||
                    $this->isTilde()||
                    $this->isCaret());
        }
    }
