<?php

class DirectAdminAPI {
    private $username;
    private $password;
    private $hostname;
    private $port;

    public function __construct($username, $password, $hostname, $port = 2222) {
        $this->username = $username;
        $this->password = $password;
        $this->hostname = $hostname;
        $this->port = $port;
    }

    public function authenticate() {
        $url = "https://{$this->hostname}:{$this->port}/CMD_API_LOGIN_TEST";
    
        $response = $this->sendRequest($url);
    
        return isset($response['error']) && $response['error'] === '0';
    }                   

    public function getDomainList() {
        $url = "https://{$this->hostname}:{$this->port}/CMD_API_SHOW_DOMAINS";

        $response = $this->sendRequest($url);

        if (isset($response['error']) || !isset($response['list'])) {
            return [];
        }

        return array_values($response['list']);
    }

    public function getEmailAccounts($domain) {
        $url = "https://{$this->hostname}:{$this->port}/CMD_API_POP?action=list&domain={$domain}";

        $response = $this->sendRequest($url);

        if (isset($response['error']) || !isset($response['list'])) {
            return [];
        }

        return array_values($response['list']);
    }

    private function sendRequest($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
    
        $response = curl_exec($curl);
    
        // Check for errors and display them
        if (curl_errno($curl)) {
            $error_number = curl_errno($curl);
            $error_message = 'Curl error (' . $error_number . '): ' . curl_error($curl);
            echo $error_message;
        }
    
        curl_close($curl);
    
        //echo "Raw Response: " . $response;
    
        parse_str($response, $parsedResponse);
        return $parsedResponse;
    }       

    private function parseDirectAdminResponse($response) {
        $result = [];
    
        parse_str($response, $result);
    
        return $result;
    }    

    private $lastError;
        public function getLastError() {
        return $this->lastError;
    }
}

$directAdmin = new DirectAdminAPI($_SESSION['username'], $_SESSION['password'], 'arrow.mxrouting.net', 2222);