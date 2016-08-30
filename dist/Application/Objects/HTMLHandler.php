<?php
    namespace Cargo\Objects\HTMLHandler;
    /**
    * The HTMLElement class
    * wraps a HTML DOM element
    */
    class HTMLElement {
        /** @var string Contains the tag name of the element */
        private $_name;
        /** @var array Contains the attributes of the element */
        private $_attributes;
        /** @var string Contains the text of the element (before children) */
        private $_text_before;
        /** @var string Contains the text of the element (after children) */
        private $_text_after;
        /** @var array Contains eventual child elements */
        protected $_elements;


        /**
        * @param string $name
        * @param array $attributes
        */
        public function __construct($name, $attributes=[]) {
            $this->_name = $name;
            $this->_attributes = $attributes;
            $this->_text = "";
            $this->_elements = [];
        }


        /**
        * Creates an HTML element
        *
        * @param string $name
        * @param array $attributes
        *
        * @return HTMLElement
        */
        public function create($name, $attributes=[]) {
            $el = new HTMLElement($name, $attributes);
            $this->_elements[] = $el;
            return $el;
        }


        /**
        * Adds an element to the current one
        *
        * @param HTMLElement $el
        *
        * @return HTMLElement|boolean
        */
        public function add($el) {
            if($el instanceof HTMLElement) {
                $this->_elements[] = $el;
                return $el;
            } return false;
        }


        /**
        * Adds text to the element
        *
        * @param string $text
        *
        * @return HTMLElement
        */
        public function text($text, $after = false) {
            if($after===false) {
                $this->_text_before = $text;
            } else { $this->_text_after = $text; }
            return $this;
        }

        /**
        * Adds an attribute to the element
        *
        * @param string $name
        * @param mixed $value
        *
        * @return HTMLElement
        */
        public function attribute($name, $value) {
            $this->_attributes[$name]=$value;
            return $this;
        }

        /**
        * Writes the HTML element either echoing directory or just returning the text
        *
        * @param boolean $output
        *
        * @return string|null
        */
        public function write($output=false) {
            $html="";
            if($this->_name!==false) {
                $html = "<".$this->_name;
                //add attributes
                foreach ($this->_attributes as $key => $value) {
                    $html.=" $key=";
                    if(is_numeric($value))
                        $html.="$value";
                    else $html.="\"$value\"";
                } $html.=">";
            }
            //add text content before
            $html.=$this->_text_before;
            //add children
            foreach ($this->_elements as $el) {
                $html.=$el->write(); }
            //add text content after
            $html.=$this->_text_after;
            //close
            $html.= ($this->_name===false) ? "" : "</".$this->_name.">";
            if($output)
                echo $html;
            else return $html;
        }
    }
    class HTMLDocument extends HTMLElement {
        private $_fullDoc;


        /**
        * @param boolean $fullDoc
        */
        public function __construct($fullDoc = false) {
            $this->_fullDoc = $fullDoc;
            $this->_elements = []; }


        /**
        * Writes out the document
        *
        * @param boolean $output
        *
        * @return string|null
        */
        public function write($output=false) {
            $html = ($this->_fullDoc) ? "<!DOCTYPE html><html>" : "";
            foreach ($this->_elements as $el) {
                $html.=$el->write(); }
            if($this->_fullDoc) $html.="</html>";
            if($output)
                echo $html;
            else return $html;
        }
    }
