<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Log in/Sign up screen animation</title>
  <link rel="stylesheet" href="css/style.css">
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Забрудненість країн</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
body {
    font-family: 'Open Sans', sans-serif;
}

#container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    width: 90%;
    margin: 20px auto;
}

#ranking {
    width: 30%;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#ranking h2 {
    text-align: center;
    color: #333;
}

#ranking-list {
    list-style: none;
    padding: 0;
}

#ranking-list li {
    padding: 8px;
    border-bottom: 1px solid #ddd;
}

#map {
    height: 700px;
    width: 65%;
    border: 2px solid #ccc;
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
      <li><a href="events.php">Події</a></li>
      <li><a href="calc.php">Калькулятор</a></li>
      <li><a href="top.php">Рейтинг</a></li>
      <li><a href="profile.php">Профіль</a></li>
      <li><a href="ai.php" class="active">Мапа забруднень</a></li>
    </ul>
  </nav>

  <div id="container">
    <div id="ranking">
      <h2>Інформаційний лист</h2>

      <!-- Опис джерела інформації -->
      <p>
          Інформація, яка відображена на карті, була отримана з сайту <a href="https://carbonmonitor.org/" target="_blank">Carbon Monitor</a>.
          Цей сайт відслідковує викиди CO2 у різних країнах, на основі чого формується статистика щодо рівня забруднення.
      </p>
  
      <p>
          Дані на карті вичисляються шляхом збору інформації про рівень викидів CO2 від кожної країни. Викиди CO2 вимірюються в <strong>тоннах на рік</strong> , 
          і статистика оновлюється регулярно, щоб відобразити останні зміни в глобальній ситуації. 
          Цей моніторинг допомагає оцінити викиди різних країн і їх вплив на глобальне потепління.
      </p>
  
      <p>
          Дані для картографічного відображення на сайті базуються на офіційних звітах урядів, міжнародних організацій і наукових дослідженнях, 
          що проводяться для оцінки змін клімату та викидів в атмосферу.
      </p>
      <ul id="ranking-list"></ul>
    </div>
    <div id="map"></div>
  </div>

  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
  <script src="script.js"></script>
</body>
</html>