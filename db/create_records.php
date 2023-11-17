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

function add_comments(): string
{
    global $conn, $tables;
    $url = "https://jsonplaceholder.typicode.com/comments";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);
    foreach ($response as $record) {
        $sql = "INSERT INTO comments (postId, name, email, body) VALUES (:value1, :value2, :value3, :value4)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'value1' => $record['postId'],
            'value2' => $record['name'],
            'value3' => $record['email'],
            'value4' => $record['body'],
        ]);
    }
    return count($response);

}
function add_posts(): string
{
    global $conn, $tables;
    $url = "https://jsonplaceholder.typicode.com/posts";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);
    foreach ($response as $record) {
        $sql = "INSERT INTO posts (userId, title, body) VALUES (:value1, :value2, :value3)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'value1' => $record['userId'],
            'value2' => $record['title'],
            'value3' => $record['body']
        ]);
    }
    return count($response);
}

$posts = add_posts();
$comments = add_comments();
echo "Загружено ". $posts . " постов и " . $comments . " комментариев";
echo "<script>console.log('Загружено ". $posts . " постов и " . $comments . " комментариев' );</script>";