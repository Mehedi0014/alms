<?php
    session_start();
    ob_start();
    include("dbh.php");
    $id = $_GET['id'];
    $sql = "DELETE FROM alogin WHERE id = ?";
    $result = $conn->prepare($sql);

    $result->execute([$id]);

    ob_end_flush();

    header("Location:./../setting.php");

    exit();

?>

