<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Перевіряємо, чи користувач увійшов
    if (!isset($_SESSION['login'])) {
        echo "Користувач не авторизований.";
        exit;
    }

    $login = $_SESSION['login'];
    $result = $_POST['result'];
    $date = date("Y-m-d"); // Поточна дата і час

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
        
        // Запис даних в таблицю reiting (колонки: id, login, slid, date)
        $stmt = $pdo->prepare("INSERT INTO reiting (login, slid, date) VALUES (?, ?, ?)");
        $stmt->execute([$login, $result, $date]);
        echo "Запис успішно додано.";
    } catch (PDOException $e) {
        echo "Помилка: " . $e->getMessage();
    }
} else {
    echo "Неправильний метод запиту.";
}
?>
