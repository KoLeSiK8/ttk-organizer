<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Валидация логина (только латиница)
    if (!preg_match('/^[a-zA-Z]+$/', $data['login'])) {
        echo json_encode(['error' => 'Логин должен содержать только латинские буквы']);
        exit;
    }

    // Валидация ФИО (только кириллица)
    if (!preg_match('/^[а-яА-ЯёЁ\s]+$/u', $data['full_name'])) {
        echo json_encode(['error' => 'ФИО должно содержать только русские буквы']);
        exit;
    }

    // Проверка пароля
    if ($data['password'] !== $data['password_confirmation']) {
        echo json_encode(['error' => 'Пароли не совпадают']);
        exit;
    }

    // Хеширование пароля
    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

    // Добавление пользователя в БД
    $stmt = $pdo->prepare("INSERT INTO users (login, full_name, password) VALUES (?, ?, ?)");
    $stmt->execute([$data['login'], $data['full_name'], $hashedPassword]);

    echo json_encode(['success' => 'Пользователь зарегистрирован!']);
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['action']) && $data['action'] === 'login') {
    $login = $data['login'];
    $password = $data['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['error' => 'Неверный логин или пароль']);
        exit;
    }

    // Генерация токена (упрощённо)
    $token = bin2hex(random_bytes(32));
    echo json_encode(['token' => $token, 'role' => $user['role']]);
}
?>
?>