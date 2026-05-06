<?php
include "include/connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['title'])) {
    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE chats SET title='$title' WHERE session_id=$id AND user_id=$user_id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    }
}
?>
