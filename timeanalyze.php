<?php

    class TimeAnalyze {
        
        function __construct($time) {
            $this->timestamp = $time;
            $this->current_timestamp = time();
            $struct = 'G:i:j:n:N:Y';
            $data = explode(':', date($struct, $this->timestamp));
            $current_data = explode(':', date($struct));
            $struct = explode(':', $struct);
            for($i = 0; $i < count($struct); $i++) {
                $this->data[$struct[$i]] = $data[$i];
                $this->current_data[$struct[$i]] = $current_data[$i];
            }
        }
        
        function get($whatToGet) {
            return $this->data[$whatToGet];
        }
        
        function getFormattedTime() {
            $marks = array();
            $months = array('янв', 'фев', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек');
            $toret = '';
            
            if(intval($this->current_data['Y']) > intval($this->data['Y'])) {
                $marks = array($this->data['Y'], $months[ intval($this->data['n']) - 1 ], $this->data['j']);
            } else if(intval($this->current_data['Y']) == intval($this->data['Y'])) {
                if(intval($this->current_data['j']) == intval($this->data['j']) and intval($this->current_data['n']) == intval($this->data['n'])) {
                    $marks = array($this->data['G'] . ':' . $this->data['i']);
                } else if(intval($this->current_data['n']) == intval($this->data['n'])) {
                    if(intval($this->current_data['j']) - 1 == intval($this->data['j'])) {
                        $marks = array('накануне');
                    } else if(intval($this->current_data['j']) - 1 > intval($this->data['j'])) {
                        $marks = array($months[ intval($this->data['n']) - 1 ], $this->data['j']);
                    }
                } else if(intval($this->current_data['n']) > intval($this->data['n'])) {
                    $marks = array($months[ intval($this->data['n']) - 1 ], $this->data['j']);
                }
            }
            
            for($i = count($marks) - 1; $i >= 0; $i--) {
                $toret .= $marks[$i] . (isset($marks[$i - 1])?' ':'');
            }
            return $toret;
        }
        
        function getTime() {
            return date('G:i', $this->timestamp);
        }
        
        function getDate() {
            if(intval($this->current_data['j']) == intval($this->data['j']) and intval($this->current_data['n']) == intval($this->data['n']) and intval($this->current_data['Y']) == intval($this->data['Y'])) {
                echo 'Сегодня';
            }
        }
    }

?>