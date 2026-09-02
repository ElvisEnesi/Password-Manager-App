<?php
    // encryption file
    $method = "aes-256-cbc";
    $my_key = "MySuperSecretKey2026";
    // encryption function
    function encrypt($data) {
        global $method, $my_key;
        $iv_length = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encrypted = openssl_encrypt($data, $method, $my_key, 0, $iv);
        // store iv + encrypted key
        return base64_encode($iv . $encrypted);
    }
    // decryption function
    function decrypt($data) {
        global $method, $my_key;
        // decode data and split into encrypted data and iv
        $data = base64_decode($data);
        //
        $iv_length = openssl_cipher_iv_length($method);
        $iv = substr($data, 0, $iv_length);
        $encrypted_data = substr($data, $iv_length);
        return openssl_decrypt($encrypted_data, $method, $my_key, 0, $iv);
    }