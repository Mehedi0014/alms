<?php
    session_start();
    ob_start();
    include("dbh.php");
    $id = $_GET['id'];
    $sql = "DELETE FROM employee WHERE id = ?";
    $result = $conn->prepare($sql);
    $result->execute([$id]);
    ob_end_flush();
    header("Location:./../aloginwel.php");
    exit();
?>

