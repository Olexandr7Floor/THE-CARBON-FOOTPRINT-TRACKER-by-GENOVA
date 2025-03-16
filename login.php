<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    
    if (empty($login) || empty($password)) {
        header("Location: login_form.html?error=empty_fields");
        exit();
    }
    
    // Налаштування з'єднання з базою даних
    $host = 'localhost';
    $db   = 'hackathon';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    
    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Отримуємо користувача за логіном
        $stmt = $pdo->prepare("SELECT * FROM registration WHERE login = ?");
        $stmt->execute([$login]);
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userRow && password_verify($password, $userRow['password'])) {
            // Авторизація успішна
            $_SESSION['user_id'] = $userRow['id'];
            $_SESSION['login'] = $userRow['login'];
            header("Location: main.php");
            exit();
        } else {
            header("Location: login_form.html?error=invalid_credentials");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: login_form.html?error=database_error");
        exit();
    }
} else {
    header("Location: login_form.html?error=invalid_request");
    exit();
}
?>