<?php
session_start();

// 1) Перевіряємо, чи користувач авторизований
if (!isset($_SESSION['login'])) {
    die("Ви не авторизовані! <a href='login_form.html'>Увійти</a>");
}

$login = $_SESSION['login'];

// 2) Підключаємося до бази даних
$host = 'localhost';
$db   = 'hackathon';
$user = 'root';
$pass = 'root';
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 3) Завантажуємо всі записи користувача з таблиці reiting
    $stmt = $pdo->prepare("SELECT slid, date FROM reiting WHERE login = ? ORDER BY date ASC");
    $stmt->execute([$login]);
    $userRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4) Підрахунок суми вуглецевого сліду та кількості записів
    $carbonFootprintSum = 0;
    foreach ($userRecords as $rec) {
        $carbonFootprintSum += (float)$rec['slid'];
    }
    $recordsCount = count($userRecords);

    // 5) Перевірка умов для «досягнень»
    $hasRecordUnder2 = false;   // хоча б 1 запис < 2 кг
    $has3DaysStreak  = false;   // записи 3 дні підряд
    $hasMoreThan10   = ($recordsCount > 10); // >10 записів
    $lowestCity      = false;   // найнижчий показник у місті
    $lowestCountry   = false;   // найнижчий показник у країні

    // (1) Запис < 2 кг
    foreach ($userRecords as $rec) {
        if ($rec['slid'] < 2) {
            $hasRecordUnder2 = true;
            break;
        }
    }

    // (2) 3 дні підряд
    if ($recordsCount >= 3) {
        $dates = array_map(fn($r)=>date('Y-m-d', strtotime($r['date'])), $userRecords);
        // відсортовано за ASC (ORDER BY date ASC)
        $consecutive = 1;
        for($i=1; $i<count($dates); $i++) {
            $prev = strtotime($dates[$i-1]);
            $curr = strtotime($dates[$i]);
            $diff = ($curr - $prev)/86400;
            if ($diff == 1) {
                $consecutive++;
                if($consecutive >= 3) {
                    $has3DaysStreak = true;
                    break;
                }
            } else {
                $consecutive = 1;
            }
        }
    }

    // Дізнаємося city, country користувача
    $stmt2 = $pdo->prepare("SELECT city, country FROM registration WHERE login = ?");
    $stmt2->execute([$login]);
    $userRow = $stmt2->fetch(PDO::FETCH_ASSOC);

    $userCity    = $userRow['city'] ?? null;
    $userCountry = $userRow['country'] ?? null;

    // Найнижчий slid у користувача
    $lowestUserSlid = null;
    if(!empty($userRecords)) {
        $lowestUserSlid = min(array_map(fn($r)=>$r['slid'], $userRecords));
    }

    // (4) Найнижчий по місту
    if($lowestUserSlid !== null && $userCity) {
        $stmt3 = $pdo->prepare("
            SELECT MIN(r.slid) AS min_slid
            FROM reiting r
            JOIN registration reg ON r.login = reg.login
            WHERE reg.city = ?
        ");
        $stmt3->execute([$userCity]);
        $cityMinRow = $stmt3->fetch(PDO::FETCH_ASSOC);
        $cityMin = $cityMinRow['min_slid'] ?? null;
        if($cityMin !== null && (float)$lowestUserSlid == (float)$cityMin) {
            $lowestCity = true;
        }
    }

    // (5) Найнижчий по країні
    if($lowestUserSlid !== null && $userCountry) {
        $stmt4 = $pdo->prepare("
            SELECT MIN(r.slid) AS min_slid
            FROM reiting r
            JOIN registration reg ON r.login = reg.login
            WHERE reg.country = ?
        ");
        $stmt4->execute([$userCountry]);
        $countryMinRow = $stmt4->fetch(PDO::FETCH_ASSOC);
        $countryMin = $countryMinRow['min_slid'] ?? null;
        if($countryMin !== null && (float)$lowestUserSlid == (float)$countryMin) {
            $lowestCountry = true;
        }
    }

    // Формуємо список досягнень
    $achievements = [];
    if ($hasRecordUnder2) {
        $achievements[] = [
          'title' => 'Запис < 2 кг',
          'img'   => 'achievUnder2.png', // змініть на реальну іконку
        ];
    }
    if ($has3DaysStreak) {
        $achievements[] = [
          'title' => '3 дні підряд',
          'img'   => 'achiev3days.png',
        ];
    }
    if ($hasMoreThan10) {
        $achievements[] = [
          'title' => 'Понад 10 записів!',
          'img'   => 'achievOver10.png',
        ];
    }
    if ($lowestCity) {
        $achievements[] = [
          'title' => 'Найнижчий показник у місті!',
          'img'   => 'achievCity.png',
        ];
    }
    if ($lowestCountry) {
        $achievements[] = [
          'title' => 'Найнижчий показник у країні!',
          'img'   => 'achievCountry.png',
        ];
    }

    $achievementsCount = count($achievements);

} catch (PDOException $e) {
    die("Помилка БД: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Особистий кабінет</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet" type="text/css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background: url("back.jpg") no-repeat center center fixed;
      background-size: cover;
    }
    .personal-cabinet .container {
      max-width: 1100px;
      margin: 40px auto;
    }
    .profile-header {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    /* Збільшимо фото, або залишимо 150x150, як хочете */
    .profile-photo img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      border: 4px solid #4caf50;
      object-fit: cover;
    }
    .profile-stats {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .profile-stats h2 {
      margin: 0 0 10px 0;
    }
    .stat-item {
      font-size: 16px;
      color: #333;
    }
    .achievements {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }
    .achievements img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 5px;
      border: 2px solid #4caf50;
    }
    .profile-content {
      display: flex;
      gap: 20px;
      margin-top: 30px;
    }
    .calculator {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      flex: 1;
    }
    .calculator h2 {
      margin-top: 0;
    }
    .calc-section {
      margin-bottom: 20px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
    }
    .calc-section:last-child {
      border-bottom: none;
    }
    input, select, button {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      margin: 5px 0;
    }
    button {
      background: #4caf50;
      color: #fff;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background: #43a047;
    }
    .carbon-graph {
      flex: 1;
      height: 300px;
    }
    .carbon-graph canvas {
      width: 100% !important;
      height: 300px !important;
    }
    #ecoStatus {
      margin-top: 15px;
      font-size: 18px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <!-- Меню -->
  <nav>
    <div class="btn-animate">
      <a href="login_form.html" class="btn-signin">Sign out</a>
    </div>
    <ul>
      <li><a href="main.php">Головна</a></li>
      <li><a href="events.php">Події</a></li>
      <li><a href="calc.php">Калькулятор</a></li>
      <li><a href="top.php">Рейтинг</a></li>
      <li><a href="profile.php" class="active">Профіль</a></li>
      <li><a href="ai.php">Мапа забруднень</a></li>
    </ul>
  </nav>

  <section class="personal-cabinet">
    <div class="container">
      <!-- Верхня частина профілю -->
      <div class="profile-header">
         <img src="photo.png" alt="Фото користувача" style="width: 300px; height: 300px; object-fit: cover;">
        <div class="profile-stats">
          <h2>Статистика акаунту</h2>
          <!-- ДИНАМІЧНІ ДАНІ: -->
          <div class="stat-item">
            Вуглецевий слід (сума записів): <strong><?php echo $carbonFootprintSum; ?></strong> кг
          </div>
          <div class="stat-item">
            Звітність (кількість записів): <strong><?php echo $recordsCount; ?></strong>
          </div>
          <div class="stat-item">
            Досягнення: <strong><?php echo $achievementsCount; ?></strong>
          </div>
          <!-- Іконки досягнень -->
          <?php if($achievementsCount > 0): ?>
            <div class="achievements">
              <?php foreach($achievements as $ach): ?>
                <div style="text-align:center;">
                  <img src="<?php echo $ach['img']; ?>" alt="<?php echo $ach['title']; ?>">
                  <p style="margin:0; font-size:12px;"><?php echo $ach['title']; ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="achievements">
              <p style="font-size:14px; color:#666;">Немає поки що досягнень</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Основний контент: калькулятор і графік -->
      <div class="profile-content">
        <!-- Калькулятор (ліворуч) -->
        <div class="calculator">
          <h2>Калькулятор вуглецевого сліду</h2>
          <div class="calc-section">
            <h3>Поїздка на автомобілі</h3>
            <input type="number" id="carDistance" placeholder="Відстань (км)" min="0">
            <input type="number" id="fuelConsumption" placeholder="Витрата пального (л/100 км)" min="0">
            <select id="fuelType">
              <option value="gasoline">Бензин</option>
              <option value="diesel">Дизель</option>
              <option value="cng">Газ (CNG)</option>
              <option value="lpg">Газ (LPG)</option>
              <option value="electric">Електро</option>
            </select>
            <button onclick="calculateCarEmissions()">Розрахувати</button>
            <p id="carResult"></p>
          </div>
          <div class="calc-section">
            <h3>Споживання їжі</h3>
            <div id="foodContainer"></div>
            <button onclick="addFoodItem()">Додати</button>
            <button onclick="calculateFoodEmissions()">Розрахувати</button>
            <p id="foodResult"></p>
          </div>
          <div class="calc-section">
            <h3>Споживана електроенергія</h3>
            <input type="number" id="electricityUsage" placeholder="Спожито кВт·год" min="0">
            <select id="country">
              <option value="romania">Румунія</option>
              <option value="ukraine">Україна</option>
              <option value="thailand">Таїланд</option>
              <option value="china">Китай</option>
              <option value="hong_kong">Гонконг</option>
              <option value="bosnia_and_herzegovina">Боснія та Герцеговина</option>
              <option value="azerbaijan">Азербайджан</option>
              <option value="albania">Албанія</option>
              <option value="macedonia">Македонія</option>
              <option value="moldova">Молдова</option>
              <option value="ecuador">Еквадор</option>
              <option value="bolivia">Болівія</option>
              <option value="turkmenistan">Туркменістан</option>
              <option value="kazakhstan">Казахстан</option>
              <option value="kosovo">Косово</option>
              <option value="mexico">Мексика</option>
              <option value="bulgaria">Болгарія</option>
            </select>
            <button onclick="calculateEnergyEmissions()">Розрахувати</button>
            <p id="energyResult"></p>
          </div>
          <div class="calc-section">
            <h3>Загальні викиди CO₂</h3>
            <button onclick="calculateTotalEmissions()">Розрахувати загальні викиди</button>
            <p id="totalResult"></p>
          </div>
        </div>
        <!-- Графік (праворуч) -->
        <div class="carbon-graph">
          <h2>Графік використання вуглецевого сліду</h2>
          <canvas id="emissionsChart"></canvas>
          <div id="ecoStatus"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <!-- Логіка обчислень -->
  <script>
    function calculateCarEmissions() {
      let distance = parseFloat(document.getElementById("carDistance").value);
      let fuelConsumption = parseFloat(document.getElementById("fuelConsumption").value);
      let fuelType = document.getElementById("fuelType").value;
      let emissionFactors = {
        gasoline: 2.31,
        diesel: 2.68,
        cng: 2.0,
        lpg: 1.51,
        electric: 0.52
      };
      let emissions = distance * (fuelConsumption / 100) * (emissionFactors[fuelType] || 0);
      document.getElementById("carResult").innerText = `Викиди CO₂: ${emissions.toFixed(2)} кг`;
      return emissions;
    }

    function calculateFoodEmissions() {
      let totalEmissions = 0;
      let foodList = document.querySelectorAll(".food-item");
      foodList.forEach(item => {
        let foodType = item.querySelector("select").value;
        let weight = parseFloat(item.querySelector("input").value) / 1000;
        let emissionFactors = {
          beef: 2.7,
          pork: 1.2,
          chicken: 0.69,
          fish: 0.51,
          eggs: 0.45,
          milk: 0.32,
          cheese: 0.84,
          bread: 0.8,
          potatoes: 0.04,
          vegetables: 0.05,
          fruits: 0.04,
          rice: 0.27,
          nuts: 0.09
        };
        totalEmissions += weight * (emissionFactors[foodType] || 0);
      });
      document.getElementById("foodResult").innerText = `Викиди CO₂: ${totalEmissions.toFixed(2)} кг`;
      return totalEmissions;
    }

    function calculateEnergyEmissions() {
      let usage = parseFloat(document.getElementById("electricityUsage").value);
      let country = document.getElementById("country").value;
      let emissionFactors = {
        romania: 0.3,
        ukraine: 0.6,
        thailand: 0.5,
        china: 0.65,
        hong_kong: 0.55,
        bosnia_and_herzegovina: 0.7,
        azerbaijan: 0.4,
        albania: 0.1,
        macedonia: 0.6,
        moldova: 0.55,
        ecuador: 0.45,
        bolivia: 0.5,
        turkmenistan: 0.6,
        kazakhstan: 0.65,
        kosovo: 0.7,
        mexico: 0.5,
        bulgaria: 0.4
      };
      let emissions = usage * (emissionFactors[country] || 0);
      document.getElementById("energyResult").innerText = `Викиди CO₂: ${emissions.toFixed(2)} кг`;
      return emissions;
    }

    function calculateTotalEmissions() {
      let carEmissions = calculateCarEmissions() || 0;
      let foodEmissions = calculateFoodEmissions() || 0;
      let energyEmissions = calculateEnergyEmissions() || 0;
      let totalEmissions = carEmissions + foodEmissions + energyEmissions;
      document.getElementById("totalResult").innerText = `Загальні викиди CO₂: ${totalEmissions.toFixed(2)} кг`;
      
      // Оновлюємо графік
      updateChart(carEmissions, foodEmissions, energyEmissions);

      // AJAX-запис у БД
      $.post('record_result.php', { result: totalEmissions.toFixed(2) }, function(response) {
          console.log("Record response: " + response);
      });

      // Еко-статус
      showEcoStatus(totalEmissions);
      return totalEmissions;
    }

    function addFoodItem() {
      let foodContainer = document.getElementById("foodContainer");
      let foodItem = document.createElement("div");
      foodItem.classList.add("food-item");
      foodItem.style.display = "flex";
      foodItem.style.alignItems = "center";
      foodItem.style.marginBottom = "5px";
      foodItem.style.width = "100%";
      foodItem.style.justifyContent = "space-between";
      foodItem.innerHTML = `
        <select style="margin-right: 10px; flex: 2;">
          <option value="beef">Яловичина</option>
          <option value="pork">Свинина</option>
          <option value="chicken">Курятина</option>
          <option value="fish">Риба</option>
          <option value="eggs">Яйця</option>
          <option value="milk">Молоко</option>
          <option value="cheese">Сир</option>
          <option value="bread">Хліб</option>
          <option value="potatoes">Картопля</option>
          <option value="vegetables">Овочі</option>
          <option value="fruits">Фрукти</option>
          <option value="rice">Рис</option>
          <option value="nuts">Горіхи</option>
        </select>
        <input type="number" placeholder="Грамів" min="0" style="width: 80px; flex: 1; margin-right: 10px;">
        <button onclick="this.parentElement.remove()" style="flex: 0.5;">❌</button>
      `;
      foodContainer.appendChild(foodItem);
      foodContainer.style.display = "flex";
      foodContainer.style.flexDirection = "column";
      foodContainer.style.alignItems = "flex-start";
      foodContainer.style.gap = "10px";
    }

    function updateChart(car, food, energy) {
      var ctx = document.getElementById('emissionsChart').getContext('2d');
      var data = {
         labels: ['Автомобіль', 'Їжа', 'Електроенергія'],
         datasets: [{
             data: [car, food, energy],
             backgroundColor: ['#4caf50', '#2196F3', '#FFC107']
         }]
      };

      if(window.myChart instanceof Chart) {
         window.myChart.data.datasets[0].data = [car, food, energy];
         window.myChart.update();
      } else {
         window.myChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
               responsive: true,
               maintainAspectRatio: false,
               title: {
                    display: true,
                    text: 'Розподіл викидів CO₂'
               }
            }
         });
      }
    }

    function showEcoStatus(total) {
      const ecoStatusDiv = document.getElementById('ecoStatus');
      let statusText = '';
      let statusColor = '';

      if (total > 10) {
        statusText = 'Погано справляєтесь! Перевищено нинішню норму.';
        statusColor = 'red';
      } else if (total >= 5 && total <= 10) {
        statusText = 'Гарно справляєтесь! Ви в межах прийнятної норми.';
        statusColor = '#2196F3';
      } else if (total >= 2 && total < 5) {
        statusText = 'Ви людина майбутнього! Дуже низькі викиди.';
        statusColor = 'green';
      } else {
        statusText = 'Ви входите у 0,01% еко людей! Неймовірний результат!';
        statusColor = 'linear-gradient(to right, red, orange, yellow, green, blue, indigo, violet)';
        ecoStatusDiv.style.backgroundImage = statusColor;
        ecoStatusDiv.style.webkitBackgroundClip = 'text';
        ecoStatusDiv.style.webkitTextFillColor = 'transparent';
        ecoStatusDiv.innerText = statusText;
        return;
      }

      ecoStatusDiv.style.backgroundImage = 'none';
      ecoStatusDiv.style.webkitBackgroundClip = 'none';
      ecoStatusDiv.style.webkitTextFillColor = 'inherit';
      ecoStatusDiv.style.color = statusColor;
      ecoStatusDiv.innerText = statusText;
    }
  </script>
</body>
</html>
