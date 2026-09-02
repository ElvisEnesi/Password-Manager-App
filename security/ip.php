<?php
    // report error
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Ip address function
    function get_ip_address () {
        // declare ip address
        $ip_adddress = '';
        // condition statements
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip_adddress = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_adddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip_adddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else {
            $ip_adddress = 'UNKNOWN';
        } 
        return $ip_adddress;
    }
    $user_ip = get_ip_address();

    