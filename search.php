<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=inline", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$comment_text = $_GET['comment_text'];
if (strlen($comment_text) >= 3) {
    $sql = "SELECT p.title, c.*
    FROM posts p
    JOIN comments c ON p.id = c.postId
    WHERE CONCAT(' ', c.body, ' ') LIKE :text";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['text' => "%$comment_text%"]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($posts);
}else{
    echo "Поисковая строка должна быть минимум 3-символьной";
}