<?php

    class Request {
        
        function __construct($access_token) {
            $this->parameters = array(
                'access_token' => $access_token,
                'v' => '5.85'
            );
            $this->error = array(
                'error_msg' => 'You should perform this request before handling errors.',
                'error_code' => 700
            );
            $this->errno = false;
        }
        
        function set($method, $parameters) {
            $this->method = $method;
            $this->parameters = $parameters;
        }
        
        function setMethod($method) {
            $this->method = $method;
        }
        
        function addParameter($key, $parameter) {
            $this->parameters[$key] = $parameter;
        }
        
        function perform() {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->parameters));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_URL, 'https://api.vk.com/method/' . $this->method);
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, true);
            if(isset($response['error'])) {
                $this->errno = false;
                $this->error = $response['error'];
            } else {
                $this->errno = true;
                $this->error = array();
            }
            if(isset($response['response'])) {
                $this->response = $response['response'];
            } else {
                $this->response = array();
            }
            return $response;
        }
        
    }

?>