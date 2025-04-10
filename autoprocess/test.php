<?php

    $file = fopen("test.txt", "a+");
    
    fwrite($file, php_sapi_name()."\n");
    fclose($file);
?>