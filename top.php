<?php
session_start();

// Якщо користувач увійшов, перевіримо, чи є дані в сесії
if (isset($_SESSION['login'])) {
    if (!isset($_SESSION['country']) || !isset($_SESSION['city']) || !isset($_SESSION['friend_room'])) {
        // Підключення до БД
        $host = 'localhost';
        $db   = 'hackathon';
        $user = 'root';
        $pass = 'root';
        $charset = 'utf8';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $pdoForSession = new PDO($dsn, $user, $pass);
            $pdoForSession->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmtSession = $pdoForSession->prepare("SELECT country, city, friend_room FROM registration WHERE login = ?");
            $stmtSession->execute([$_SESSION['login']]);
            $userData = $stmtSession->fetch(PDO::FETCH_ASSOC);
            if ($userData) {
                $_SESSION['country'] = $userData['country'];
                $_SESSION['city'] = $userData['city'];
                $_SESSION['friend_room'] = $userData['friend_room'];
            }
        } catch (PDOException $e) {
            die("Помилка завантаження даних користувача: " . $e->getMessage());
        }
    }
}

// Отримуємо фільтри з GET
$date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : 'all';
$location_filter = isset($_GET['location_filter']) ? $_GET['location_filter'] : 'world';
$custom_date = isset($_GET['custom_date']) ? $_GET['custom_date'] : '';
$friends_filter = isset($_GET['friends']) ? trim($_GET['friends']) : '';

// Формуємо умови для SQL
$conditions = [];
$params = [];

// Фільтр за датою
if ($date_filter == 'day') {
    $conditions[] = "DATE(r.date) = CURDATE()";
} elseif ($date_filter == 'month') {
    $conditions[] = "MONTH(r.date) = MONTH(CURDATE()) AND YEAR(r.date) = YEAR(CURDATE())";
} elseif ($date_filter == 'year') {
    $conditions[] = "YEAR(r.date) = YEAR(CURDATE())";
} elseif ($date_filter == 'custom' && !empty($custom_date)) {
    $conditions[] = "DATE(r.date) = ?";
    $params[] = $custom_date;
}

// Фільтр за локацією
if ($location_filter != 'world') {
    if ($location_filter == 'country' && isset($_SESSION['country'])) {
        $conditions[] = "reg.country = ?";
        $params[] = $_SESSION['country'];
    } elseif ($location_filter == 'city' && isset($_SESSION['city'])) {
        $conditions[] = "reg.city = ?";
        $params[] = $_SESSION['city'];
    } elseif ($location_filter == 'friend' && isset($_SESSION['friend_room'])) {
        $conditions[] = "reg.friend_room = ?";
        $params[] = $_SESSION['friend_room'];
    }
}

// Фільтр за друзями
if (!empty($friends_filter)) {
    $friends = array_map('trim', explode(',', $friends_filter));
    $placeholders = implode(',', array_fill(0, count($friends), '?'));
    $conditions[] = "r.login IN ($placeholders)";
    $params = array_merge($params, $friends);
}

// Формуємо умову WHERE
$where_clause = "";
if (count($conditions) > 0) {
    $where_clause = "WHERE " . implode(" AND ", $conditions);
}

// Підключення до БД
$host = 'localhost';
$db   = 'hackathon';
$user = 'root';
$pass = 'root';
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Виконуємо запит
    $sql = "SELECT r.*, reg.country, reg.city, reg.friend_room
            FROM reiting r
            LEFT JOIN registration reg ON r.login = reg.login
            $where_clause
            ORDER BY r.slid ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Помилка бази даних: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Рейтинг користувачів</title>
  <link rel="stylesheet" href="css/style.css">
<style>
  .rating-container {
    max-width: 1100px;
    margin: 40px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .rating-container h2 {
    color: #4caf50;
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 24px;
  }

  /* Область фільтрів */
  .filters {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    align-items: flex-end;
  }

  .filter-group {
    display: flex;
    flex-direction: column;
  }

  .filter-group label {
    color: #000; /* чорний колір */
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 14px;
  }

  .filter-group select,
  .filter-group input[type="date"],
  .filter-group input[type="text"] {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    width: 160px;
  }

  .filters button[type="submit"] {
    background: #4caf50;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    font-weight: bold;
    cursor: pointer;
  }

  .filters button[type="submit"]:hover {
    background: #43a047;
  }

  /* Таблиця рейтингу */
  .rating-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fcfcfc; /* Ніжний блідий фон для всієї таблиці */
  }

  .rating-table thead th {
    background-color: #f2f2f2;
    font-weight: bold;
    text-align: left;
    color: #000; /* заголовки колонок чорні */
  }

  .rating-table th, .rating-table td {
    border: 1px solid #ddd;
    padding: 8px;
    font-size: 14px;
  }

  .rating-table tr:nth-child(even) {
    background-color: #f8f8f8; /* трохи світліший фон для парних рядків */
  }

  .rating-table tr:hover {
    background-color: #f2f2f2;
  }
</style>

</head>
<body>
  <!-- Припускаємо, що у вас уже є меню / шапка, взята зі style.css -->
  <nav>
    <div class="btn-animate">
      <a href="login.php" class="btn-signin">Sign out</a>
    </div>
    <ul>
      <li><a href="main.php">Головна</a></li>
      <li><a href="events.php">Події</a></li>
      <li><a href="calc.php">Калькулятор</a></li>
      <li><a href="top.php" class="active">Рейтинг</a></li>
      <li><a href="profile.php">Профіль</a></li>
      <li><a href="ai.php">Мапа забруднень</a></li>
    </ul>
  </nav>

  <section class="rating">
    <div class="rating-container">
      <h2>Рейтинг</h2>
      <!-- Форма фільтра -->
      <form method="GET" action="top.php">
        <div class="filters">
          <!-- Фільтр по даті -->
          <div class="filter-group">
            <label for="date-filter">Фільтр по даті:</label>
            <select id="date-filter" name="date_filter">
              <option value="all"    <?php if($date_filter=='all')   echo 'selected'; ?>>Весь час</option>
              <option value="day"    <?php if($date_filter=='day')   echo 'selected'; ?>>За день</option>
              <option value="month"  <?php if($date_filter=='month') echo 'selected'; ?>>За місяць</option>
              <option value="year"   <?php if($date_filter=='year')  echo 'selected'; ?>>За рік</option>
              <option value="custom" <?php if($date_filter=='custom')echo 'selected'; ?>>Обрати дату</option>
            </select>
            <?php if($date_filter=='custom'): ?>
              <input type="date" name="custom_date" value="<?php echo htmlspecialchars($custom_date); ?>">
            <?php endif; ?>
          </div>
          <!-- Додатковий фільтр -->
          <div class="filter-group">
            <label for="location-filter">Додатковий фільтр:</label>
            <select id="location-filter" name="location_filter">
              <option value="world"   <?php if($location_filter=='world')   echo 'selected'; ?>>Весь світ</option>
              <option value="country" <?php if($location_filter=='country') echo 'selected'; ?>>Країна</option>
              <option value="city"    <?php if($location_filter=='city')    echo 'selected'; ?>>Місто</option>
              <option value="friend"  <?php if($location_filter=='friend')  echo 'selected'; ?>>Друзі</option>
            </select>
          </div>
          <!-- Фільтр "друзі" -->
          <div class="filter-group">
            <label for="friends-filter">Друзі:</label>
            <input type="text" id="friends-filter" name="friends"
                   placeholder="Введіть логіни друзів"
                   value="<?php echo htmlspecialchars($friends_filter); ?>">
          </div>
          <!-- Кнопка Фільтрувати -->
          <button type="submit">Фільтрувати</button>
        </div>
      </form>

      <!-- Таблиця рейтингу -->
      <table class="rating-table">
        <thead>
          <tr>
            <th>Ім'я</th>
            <th>Вуглецевий слід</th>
            <th>Дата</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($results)): ?>
            <?php foreach ($results as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['login']); ?></td>
                <td><?php echo htmlspecialchars($row['slid']); ?> кг CO₂</td>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3">Немає даних для відображення.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Підключаємо ваш js, якщо треба -->
  <script src="js/index.js"></script>
</body>
</html>
