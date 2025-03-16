<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Log in/Sign up screen animation</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>

  <nav>
    <div class="btn-animate">
 <a href="login.php" class="btn-signin">Sign out</a>
    </div>
    <ul>
      <li><a href="main.php" >Головна</a></li>
      <li><a href="events.php">Події</a></li>
      <li><a href="calc.php" class="active">Калькулятор</a></li>
      <li><a href="top.php">Рейтинг</a></li>
      <li><a href="profile.php">Профіль</a></li>
      <li><a href="ai.php">Мапа забруднень</a></li>


    </ul>
  </nav>
  <div class="container">
<h1 style="text-align: center; font-size: 36px; font-weight: bold; color: #000; margin-bottom: 20px;">
  Калькулятор вуглецевого сліду
</h1>


    <div class="section">
        <h2>Поїздка на автомобілі</h2>
        <div>
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
    </div>

    <div class="section">
        <h2>Споживання їжі</h2>
        <div id="foodContainer"></div>
        <button onclick="addFoodItem()">Додати</button>
        <button onclick="calculateFoodEmissions()">Розрахувати</button>
        <p id="foodResult"></p>
    </div>

    <div class="section">
        <h2>Споживана електроенергія</h2>
        <div>
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
    </div>

    <div class="section">
        <h2>Загальні викиди CO₂</h2>
        <button onclick="calculateTotalEmissions()">Розрахувати загальні викиди</button>
        <p id="totalResult"></p>
    </div>
</div>
<script src="logic.js"></script>

<style>
    body {
font-family: Arial, sans-serif;
background-color: #f8f9fa;
justify-content: center;
align-items: center;
height: 120vh;
}
.container {
background: white;
padding: 50px;
border-radius: 8px;
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
max-width: 1100px;
width: 100%;
flex-direction: column;
align-items: center;
margin-top: 100px;
height: 500px;
background-color: rgb(240, 249, 240);
}
.section {
display: flex;
align-items: center;
justify-content: space-between;
width: 100%;
margin-bottom: 15px;
}
.section div {
display: flex;
align-items: center;
gap: 10px;
}
h1 {
font-size: 24px;
margin-bottom: 15px;
}
h2 {
font-size: 18px;
margin-bottom: 10px;
font-weight: bold;
}
input, select, button {
padding: 8px;
border: 1px solid #ccc;
border-radius: 5px;
font-size: 14px;
}
button {
background: #4caf50;
color: white;
font-weight: bold;
cursor: pointer;
border: none;
}
button:hover {
background: #4caf50;
}
.food-item {
display: flex;
gap: 10px;
align-items: center;
margin-bottom: 5px;
}
</style>

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
    let weight = parseFloat(item.querySelector("input").value) / 1000; // Переводимо в кг

    let emissionFactors = {
          beef: 0.27,
          pork: 0.12,
          chicken: 0.069,
          fish: 0.051,
          eggs: 0.045,
          milk: 0.032,
          cheese: 0.084,
          bread: 0.08,
          potatoes: 0.004,
          vegetables: 0.005,
          fruits: 0.004,
          rice: 0.027,
          nuts: 0.009
    };

    totalEmissions += (weight * (emissionFactors[foodType] || 0));
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
</script>

    
</body>
</html>