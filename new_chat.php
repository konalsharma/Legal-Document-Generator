<?php
session_start();
unset($_SESSION['chat_id']); // clear current chat
echo json_encode(['status' => 'new_chat_started']);
?>
