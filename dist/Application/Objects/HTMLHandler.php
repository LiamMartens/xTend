<?php
    namespace Application\Objects\HTMLHandler;
    /**
    * The HTMLElement class
    * wraps a HTML DOM element
    */
    class HTMLElement {
        /** @var string Contains the tag name of the element */
        private $_name;
        /** @var array Contains the attributes of the element */
        private $_attributes;
        /** @var string Contains the text of the element */
        private $_text;
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
        * @return xTend\Core\HTMLHandler\HTMLElement
        */
        public function createElement($name, $attributes=[]) {
            $el = new HTMLElement($name, $attributes);
            $this->_elements[] = $el;
            return $el;
        }

        /**
        * Adds an element to the current one
        *
        * @param xTend\Core\HTMLHandler\HTMLElement $el
        *
        * @return xTend\Core\HTMLHandler\HTMLElement|boolean
        */
        public function addElement($el) {
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
        * @return xTend\Core\HTMLHandler\HTMLElement
        */
        public function addText($text) {
            $this->_text .= $text;
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
            //add text content
            $html.=$this->_text;
            //add children
            foreach ($this->_elements as $el) {
                $html.=$el->write(); }
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