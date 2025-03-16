// Ініціалізація карти
const map = L.map('map').setView([20, 0], 2);

// Додавання базового шару карти
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Функція для отримання кольору на основі рівня забруднення
function getColor(value) {
    return value > 20000 ? '#800026' :
           value > 10000  ? '#BD0026' :
           value > 8000  ? '#E31A1C' :
           value > 6000  ? '#FC4E2A' :
           value > 4000  ? '#FD8D3C' :
           value > 2000 ? '#FEB24C' :
           value > 1000 ? '#FED976' :
                        '#FFEDA0';
}

// Нормалізація назв країн
function normalizeCountryName(country) {
    const countryMappings = {
        "Russia": "Russia",
        "United States": "United States of America",
        "EU27 & UK": "European Union", // Приклад, якщо є такі дані
        "Brazil": "Brazil",
        "China": "China",
        "India": "India",
        "Japan": "Japan",
        "Germany": "Germany",
        "France": "France",
        "Italy": "Italy",
        "Spain": "Spain",
        "United Kingdom": "United Kingdom",
        "ROW": "Rest of the World", // Додаємо ROW
        "Canada": "Canada",
        "Australia": "Australia",
        "South Korea": "Korea, Republic of",
        "Mexico": "Mexico",
        "Indonesia": "Indonesia",
        "Turkey": "Turkey",
        "Saudi Arabia": "Saudi Arabia",
        "South Africa": "South Africa",
        "Argentina": "Argentina",
        "Poland": "Poland",
        "Netherlands": "Netherlands",
        "Belgium": "Belgium",
        "Switzerland": "Switzerland",
        "Sweden": "Sweden",
        "Austria": "Austria",
        "Norway": "Norway",
        "Denmark": "Denmark",
        "Finland": "Finland",
        "Portugal": "Portugal",
        "Greece": "Greece",
        "Ireland": "Ireland",
        "Czech Republic": "Czechia",
        "Hungary": "Hungary",
        "Ukraine": "Ukraine",
        "Thailand": "Thailand",
        "Malaysia": "Malaysia",
        "Singapore": "Singapore",
        "Philippines": "Philippines",
        "Vietnam": "Vietnam",
        "Pakistan": "Pakistan",
        "Bangladesh": "Bangladesh",
        "Egypt": "Egypt",
        "Nigeria": "Nigeria",
        "Iran": "Iran, Islamic Republic of",
        "Iraq": "Iraq",
        "United Arab Emirates": "United Arab Emirates",
        "Israel": "Israel",
        "New Zealand": "New Zealand",
        "Chile": "Chile",
        "Colombia": "Colombia",
        "Peru": "Peru",
        "Venezuela": "Venezuela",
        "Kazakhstan": "Kazakhstan",
        "Qatar": "Qatar",
        "Kuwait": "Kuwait",
        "Oman": "Oman",
        "Belarus": "Belarus",
        "Romania": "Romania",
        "Bulgaria": "Bulgaria",
        "Croatia": "Croatia",
        "Serbia": "Serbia",
        "Slovakia": "Slovakia",
        "Slovenia": "Slovenia",
        "Lithuania": "Lithuania",
        "Latvia": "Latvia",
        "Estonia": "Estonia",
        "Luxembourg": "Luxembourg",
        "Iceland": "Iceland",
        "Cyprus": "Cyprus",
        "Malta": "Malta",
        "Albania": "Albania",
        "North Macedonia": "North Macedonia",
        "Bosnia and Herzegovina": "Bosnia and Herzegovina",
        "Montenegro": "Montenegro",
        "Kosovo": "Kosovo",
        "Moldova": "Moldova",
        "Georgia": "Georgia",
        "Armenia": "Armenia",
        "Azerbaijan": "Azerbaijan",
        "Sri Lanka": "Sri Lanka",
        "Nepal": "Nepal",
        "Myanmar": "Myanmar",
        "Cambodia": "Cambodia",
        "Laos": "Lao People's Democratic Republic",
        "Mongolia": "Mongolia",
        "Brunei": "Brunei Darussalam",
        "Papua New Guinea": "Papua New Guinea",
        "Fiji": "Fiji",
        "Samoa": "Samoa",
        "Tonga": "Tonga",
        "Solomon Islands": "Solomon Islands",
        "Vanuatu": "Vanuatu",
        "Micronesia": "Micronesia, Federated States of",
        "Palau": "Palau",
        "Marshall Islands": "Marshall Islands",
        "Kiribati": "Kiribati",
        "Tuvalu": "Tuvalu",
        "Nauru": "Nauru",
        "Afghanistan": "Afghanistan",
        "Yemen": "Yemen",
        "Syria": "Syrian Arab Republic",
        "Jordan": "Jordan",
        "Lebanon": "Lebanon",
        "Palestine": "Palestine, State of",
        "Bahrain": "Bahrain",
        "Maldives": "Maldives",
        "Bhutan": "Bhutan",
        "Tajikistan": "Tajikistan",
        "Turkmenistan": "Turkmenistan",
        "Uzbekistan": "Uzbekistan",
        "Kyrgyzstan": "Kyrgyzstan",
        "Tanzania": "Tanzania, United Republic of",
        "Kenya": "Kenya",
        "Uganda": "Uganda",
        "Ethiopia": "Ethiopia",
        "Sudan": "Sudan",
        "South Sudan": "South Sudan",
        "Somalia": "Somalia",
        "Djibouti": "Djibouti",
        "Eritrea": "Eritrea",
        "Rwanda": "Rwanda",
        "Burundi": "Burundi",
        "Zambia": "Zambia",
        "Zimbabwe": "Zimbabwe",
        "Malawi": "Malawi",
        "Mozambique": "Mozambique",
        "Madagascar": "Madagascar",
        "Mauritius": "Mauritius",
        "Comoros": "Comoros",
        "Seychelles": "Seychelles",
        "Angola": "Angola",
        "Namibia": "Namibia",
        "Botswana": "Botswana",
        "Lesotho": "Lesotho",
        "Eswatini": "Eswatini",
        "Liberia": "Liberia",
        "Sierra Leone": "Sierra Leone",
        "Guinea": "Guinea",
        "Guinea-Bissau": "Guinea-Bissau",
        "Senegal": "Senegal",
        "Gambia": "Gambia",
        "Mali": "Mali",
        "Burkina Faso": "Burkina Faso",
        "Niger": "Niger",
        "Chad": "Chad",
        "Cameroon": "Cameroon",
        "Central African Republic": "Central African Republic",
        "Equatorial Guinea": "Equatorial Guinea",
        "Gabon": "Gabon",
        "Republic of the Congo": "Congo",
        "Democratic Republic of the Congo": "Congo, the Democratic Republic of the",
        "São Tomé and Príncipe": "Sao Tome and Principe",
        "Cape Verde": "Cabo Verde",
        "Benin": "Benin",
        "Togo": "Togo",
        "Ghana": "Ghana",
        "Côte d'Ivoire": "Cote d'Ivoire",
        "Cuba": "Cuba",
        "Jamaica": "Jamaica",
        "Haiti": "Haiti",
        "Dominican Republic": "Dominican Republic",
        "Puerto Rico": "Puerto Rico",
        "Trinidad and Tobago": "Trinidad and Tobago",
        "Barbados": "Barbados",
        "Bahamas": "Bahamas",
        "Guyana": "Guyana",
        "Suriname": "Suriname",
        "Belize": "Belize",
        "Costa Rica": "Costa Rica",
        "Panama": "Panama",
        "Nicaragua": "Nicaragua",
        "Honduras": "Honduras",
        "El Salvador": "El Salvador",
        "Guatemala": "Guatemala",
        "Ecuador": "Ecuador",
        "Bolivia": "Bolivia",
        "Paraguay": "Paraguay",
        "Uruguay": "Uruguay",
        "Falkland Islands": "Falkland Islands (Malvinas)",
        "Greenland": "Greenland",
        "Faroe Islands": "Faroe Islands",
        "Gibraltar": "Gibraltar",
        "Andorra": "Andorra",
        "Monaco": "Monaco",
        "Liechtenstein": "Liechtenstein",
        "San Marino": "San Marino",
        "Vatican City": "Holy See (Vatican City State)",
        "Antigua and Barbuda": "Antigua and Barbuda",
        "Dominica": "Dominica",
        "Grenada": "Grenada",
        "Saint Kitts and Nevis": "Saint Kitts and Nevis",
        "Saint Lucia": "Saint Lucia",
        "Saint Vincent and the Grenadines": "Saint Vincent and the Grenadines",
        "Samoa": "Samoa",
        "Tonga": "Tonga",
        "Solomon Islands": "Solomon Islands",
        "Vanuatu": "Vanuatu",
        "Micronesia": "Micronesia, Federated States of",
        "Palau": "Palau",
        "Marshall Islands": "Marshall Islands",
        "Kiribati": "Kiribati",
        "Tuvalu": "Tuvalu",
        "Nauru": "Nauru",
        "ROW": "Rest of the World" // Додаємо ROW
    };
    return countryMappings[country] || country;
}

// Функція для завантаження та обробки CSV-файлу
function loadCSVData() {
    Papa.parse("carbonmonitor-global_datas_2025-03-15.csv", {
        download: true,
        header: true,
        dynamicTyping: true,
        complete: function(results) {
            const data = results.data;

            // Агрегування даних по країнах (сума значень для кожної країни)
            const aggregatedData = {};
            data.forEach(row => {
                const country = normalizeCountryName(row.country);
                if (!aggregatedData[country]) {
                    aggregatedData[country] = 0;
                }
                aggregatedData[country] += row.value;
            });

            // Завантаження геоданих для країн (використовуємо GeoJSON)
            fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json')
                .then(response => response.json())
                .then(geoData => {
                    // Додавання шару з геоданими
                    L.geoJSON(geoData, {
                        style: function(feature) {
                            const countryName = feature.properties.name;
                            const countryValue = aggregatedData[countryName];
                            return {
                                fillColor: countryValue ? getColor(countryValue) : '#ccc',
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            const countryName = feature.properties.name;
                            const countryValue = aggregatedData[countryName];
                            if (countryValue) {
                                layer.bindPopup(`<b>${countryName}</b><br>Забрудненість: ${countryValue.toFixed(2)}`);
                            } else {
                                layer.bindPopup(`<b>${countryName}</b><br>Немає даних`);
                            }
                        }
                    }).addTo(map);
                });
        }
    });
}

// Завантаження даних з CSV
loadCSVData();