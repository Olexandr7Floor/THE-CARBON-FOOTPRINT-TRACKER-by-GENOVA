<?php
// events.php
session_start();

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

    // Створюємо таблицю conferences, якщо вона ще не існує
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS conferences (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            event_date DATETIME,
            link VARCHAR(255)
        )
        ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";
    $pdo->exec($createTableSQL);

    // Якщо форму відправлено методом POST — додаємо новий запис
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $dateTime = trim($_POST['event_date']); // формат YYYY-MM-DD HH:MM:SS
        $link = trim($_POST['link']);

        // Простий приклад валідації
        if (!empty($title) && !empty($dateTime) && !empty($link)) {
            $insertSQL = "INSERT INTO conferences (title, description, event_date, link)
                          VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($insertSQL);
            $stmt->execute([$title, $description, $dateTime, $link]);
        } else {
            // Можна вивести повідомлення про помилку, якщо поля не заповнені
            echo "<p style='color:red;'>Будь ласка, заповніть обов'язкові поля (Назва, Дата/час, Посилання)!</p>";
        }
    }

    // Зчитуємо всі конференції з таблиці
    $selectSQL = "SELECT * FROM conferences ORDER BY event_date DESC";
    $stmt = $pdo->query($selectSQL);
    $conferences = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Помилка бази даних: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Відеоконференції</title>
  <link rel="stylesheet" href="css/style.css">
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>

  <style>
    /* Приклад базових стилів для форми */
    .add-conference .container {
      max-width: 600px; /* обмеження ширини форми */
      margin: 20px auto; /* центровано */
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .add-conference h2 {
      margin-top: 0;
      color: #4caf50;
    }

    /* Стилізація кожного поля (label + input/textarea) */
    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #333;
    }

    .form-group input[type="text"],
    .form-group textarea {
      width: 100%;
      padding: 8px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      outline: none;
    }
    .form-group input[type="text"]:focus,
    .form-group textarea:focus {
      border-color: #4caf50;
    }

    /* Кнопка "Додати конференцію" */
    .add-conference button[type="submit"] {
      background: #4caf50;
      color: #fff;
      padding: 10px 16px;
      border: none;
      border-radius: 4px;
      font-weight: bold;
      cursor: pointer;
    }
    .add-conference button[type="submit"]:hover {
      background-color: #43a047;
    }
  </style>
</head>
<body>
  <nav>
    <div class="btn-animate">
      <a href="login.php" class="btn-signin">Sign out</a>
    </div>
    <ul>
      <li><a href="main.php">Головна</a></li>
      <li><a href="events.php" class="active">Події</a></li>
      <li><a href="calc.php">Калькулятор</a></li>
      <li><a href="top.php">Рейтинг</a></li>
      <li><a href="profile.php">Профіль</a></li>
      <li><a href="ai.php">Мапа забруднень</a></li>
    </ul>
  </nav>

  <!-- Виводимо список конференцій з БД -->
  <?php if (!empty($conferences)): ?>
    <?php foreach ($conferences as $conf): ?>
      <section class="carbon-footprint">
        <div class="container">
          <h2><?php echo htmlspecialchars($conf['title']); ?></h2>
          <p>
            <strong>Дата та час:</strong> 
            <?php echo htmlspecialchars($conf['event_date']); ?><br>
            <strong>Опис:</strong> 
            <?php echo nl2br(htmlspecialchars($conf['description'])); ?>
          </p>
          <p>
            <strong>Посилання:</strong>
            <a href="<?php echo htmlspecialchars($conf['link']); ?>" target="_blank">
              <?php echo htmlspecialchars($conf['link']); ?>
            </a>
          </p>
        </div>
      </section>
    <?php endforeach; ?>
  <?php else: ?>
    <section class="carbon-footprint">
      <div class="container">
        <p>Наразі немає доданих конференцій.</p>
      </div>
    </section>
  <?php endif; ?>

  <!-- Форма для додавання нової конференції -->
  <section class="add-conference">
    <div class="container">
      <h2>Додати нову відеоконференцію</h2>
      <form method="POST" action="events.php">
        
        <div class="form-group">
          <label for="title">Назва конференції (обов'язково):</label>
          <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
          <label for="description">Опис (необов'язково):</label>
          <textarea id="description" name="description" rows="3"></textarea>
        </div>

        <div class="form-group">
          <label for="event_date">Дата та час (формат: YYYY-MM-DD HH:MM:SS, обов'язково):</label>
          <input type="text" id="event_date" name="event_date" placeholder="2025-03-14 17:30:00" required>
        </div>

        <div class="form-group">
          <label for="link">Посилання (обов'язково):</label>
          <input type="text" id="link" name="link" placeholder="https://meet.google.com/..." required>
        </div>

        <button type="submit">Додати конференцію</button>
      </form>
    </div>
  </section>

</body>
</html>
