<?php
    namespace xTend\Core;
    class SortHelper
    {
        public function sortByLength(&$arr, $asc=true) {
            if($asc)
                usort($arr, function($b, $a) { return strlen($b)-strlen($a); });
            else usort($arr, function($a, $b) { return strlen($b)-strlen($a); });
        }
        public function sortByNumberOfSlashes(&$arr, $asc=true) {
            if($asc) {
                usort($arr, function($b, $a) { return substr_count($b, "/")-substr_count($a, "/"); });
            } else usort($arr, function($a, $b) { return substr_count($b, "/")-substr_count($a, "/"); });
        }
    }