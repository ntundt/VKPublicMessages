<?php 

    class Finder {
        
        function __construct($list) {
            $this->list = $list;
        }
        
        function find($parameter, $needed_value) {
            for($i = 0; $i < count($this->list); $i++) {
                if($this->list[$i][$parameter] == $needed_value) {
                    return $this->list[$i];
                }
            }
            return -1;
        }
        
    }

?>