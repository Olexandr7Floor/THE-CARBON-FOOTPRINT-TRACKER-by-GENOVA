<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $country = trim($_POST['country']);
    $city = trim($_POST['city']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];
    
    if (empty($login) || empty($country) || empty($city) || empty($password) || empty($confirmPassword)) {
        header("Location: register_form.html?error=empty_fields");
        exit();
    }
    
    if ($password !== $confirmPassword) {
        header("Location: register_form.html?error=password_mismatch");
        exit();
    }
    
    // Хешування пароля
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
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
        
        // Перевірка наявності користувача з таким логіном
        $stmt = $pdo->prepare("SELECT * FROM registration WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->rowCount() > 0) {
            header("Location: register_form.html?error=login_exists");
            exit();
        }
        
        // Запис даних у таблицю registration
        $stmt = $pdo->prepare("INSERT INTO registration (login, password, country, city, friend_room) VALUES (?, ?, ?, ?, ?)");
        $friend_room = null; // можна встановити за замовчуванням
        $stmt->execute([$login, $hashedPassword, $country, $city, $friend_room]);
        
        header("Location: login_form.html?success=registration_successful");
        exit();
    } catch (PDOException $e) {
        header("Location: register_form.html?error=database_error");
        exit();
    }
} else {
    header("Location: register_form.html?error=invalid_request");
    exit();
}
?>
