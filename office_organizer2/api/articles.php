<?php
require_once 'db.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Проверка авторизации
$token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (!isValidToken($token)) { // Функция из auth.php
    echo json_encode(['error' => 'Доступ запрещен']);
    exit;
}

// Получить все статьи
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM articles");
    echo json_encode($stmt->fetchAll());
}

// Создать статью
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO articles (title, content) VALUES (?, ?)");
    $stmt->execute([$data['title'], $data['content']]);
    echo json_encode(['success' => 'Статья создана!']);
}
?>