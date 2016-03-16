<?php
	namespace xTend\Core\HTMLHandler
	{
		class HTMLElement {
			private $_name;
			private $_attributes;
			private $_text;

			protected $_elements;

			public function __construct($name, $attributes=[]) {
				$this->_name = $name;
				$this->_attributes = $attributes;
				$this->_text = "";
				$this->_elements = [];
			}

			public function createElement($name, $attributes=[]) {
				$el = new HTMLElement($name, $attributes);
				$this->_elements[] = $el;
				return $el;
			}

			public function addElement($el) {
				if($el instanceof HTMLElement) {
					$this->_elements[] = $el;
					return $el;
				} return false;
			}

			public function addText($text) {
				$this->_text .= $text;
				return $this;
			}

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
			public function __construct($fullDoc = false) {
				$this->_fullDoc = $fullDoc;
				$this->_elements = []; }
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
	}
	namespace xTend\Core
	{
		class HTMLHandler extends HTMLHandler\HTMLElement
		{
			private $_app;
			private $_documents;
			public function __construct($app) {
				$this->_app = $app;
				$this->_documents = [];
				$this->_elements = [];
			}

			public function createDocument($fullDoc = false) {
				$doc = new HTMLHandler\HTMLDocument($fullDoc);
				$this->_documents[] = $doc;
				return $doc;
			}

			public function write($output=false) {
				$html = "";
				foreach ($this->_documents as $doc) {
					$html .= $doc->write();
				}
				if($output)
					echo $html;
				else return $html;
			}
		}
	}
