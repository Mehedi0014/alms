<?php
    ob_flush();
    session_start();
    session_destroy();
    ob_end_flush();
    header("location: index.php");
    exit;

?>