<?php
    namespace Application\Core;
    use Application\Objects\HTMLHandler\HTMLElement;
    use Application\Objects\HTMLHandler\HTMLDocument;

    class HTMLHandler
    {
        private static $_documents=[];

        /**
        * Creates a new document
        *
        * @param boolean $fullDoc
        *
        * @return xTend\Core\HTMLHandler\HTMLDocument
        */
        public static function createDocument($fullDoc = false) {
            $doc = new HTMLDocument($fullDoc);
            self::$_documents[] = $doc;
            return $doc;
        }

        /**
        * Writes out all documents
        *
        * @param boolean $output
        *
        * @return string|null
        */
        public static function write($output=false) {
            $html = '';
            foreach (self::$_documents as $doc) {
                $html .= $doc->write();
            }
            if($output)
                echo $html;
            else return $html;
        }
    }
