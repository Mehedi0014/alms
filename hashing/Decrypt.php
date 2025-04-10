<?php
    class Decrypt{
        private $output= '';
    

        public function __construct($input){
            $secret_key = "mysupersecretpasswordnottoshare";
            $ciphering = "AES-256-CBC";
            $secret_iv = "54612348574892513216541223131";
            $options = 0;
            $ivLength = openssl_cipher_iv_length($ciphering);
            $cipher_iv = substr($secret_iv, 0, $ivLength);
            
            $result = openssl_decrypt(base64_decode($input), $ciphering, $secret_key, $options, $cipher_iv);
            $this->output = $result;
        }

        public function getOutput(){
            return $this->output;
        }

    }
?>