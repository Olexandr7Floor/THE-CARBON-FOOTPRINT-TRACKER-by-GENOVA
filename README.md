﻿# The Carbon Footprint Tracker

## Огляд проєкту

**The Carbon Footprint Tracker** – це веб-застосунок, розроблений командою **GENOVA** у рамках хакатону **INFOMATRIX UKRAINE**. Головна мета проєкту – допомогти користувачам розраховувати свій особистий вуглецевий слід, підвищити рівень обізнаності про проблему викидів CO₂ і надати персоналізовані рекомендації щодо його зменшення.

### Вибрана ціль сталого розвитку ООН
Проєкт спрямований на досягнення **Цілі 13: Боротьба зі зміною клімату**, а саме підпункту **13.3**:
> Поліпшити просвітництво, поширення інформації і можливості людей та установ щодо пом’якшення гостроти та послаблення наслідків зміни клімату, адаптації до них і раннього попередження.

### Корисні посилання
- Відео користування сайтом (демонстрація функціональності): [YouTube THE CARBON FOOTPRINT TRACKER by GENOVA](https://youtu.be/JsHKe27PuYg)
- Презентація розробленого рішення на Хакатоні: [Presentation THE CARBON FOOTPRINT TRACKER by GENOVA](https://docs.google.com/presentation/d/1-_nAykOwCq9AdluKHT5fvy_DLu9Jk5tD/edit?usp=sharing&ouid=108218354257439160380&rtpof=true&sd=true)
- Репозиторій проекту розробленого на Хакатоні: [Repository THE CARBON FOOTPRINT TRACKER by GENOVA](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA)
- Документація до розробленого проекту: [Documentation THE CARBON FOOTPRINT TRACKER by GENOVA](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/README.md)

## Технології та середовище розгортання

### Використані технології
- **Frontend:** HTML, CSS (Vite, Tailwind CSS)
- **Backend:** PHP 8.x, JavaScript
-  **Шаблонізатор:** Blade
- **База даних:** MySQL
- **Сервер:** Apache (через XAMPP)
- **Контейнеризація:** Docker

### Розгортання сайту на локальному сервері (XAMPP)
Для розгортання веб-застосунку на локальному сервері використовується **XAMPP**.

#### Кроки для встановлення та запуску:
1. **Завантажте та встановіть XAMPP**  
   Перейдіть на [офіційний сайт XAMPP](https://www.apachefriends.org/) та виконайте завантаження згідно вашої ОС:
   ![Рис1](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images1.png)
   Дочекайтесь завантаження інсталятора:
   ![Рис2](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images2.png)
   Запустіть інсталятор та слідуйте інструкціям.
   > При встановленні обов’язково обрати компоненти для встановлення в склад XAMPP:
   > - Apache
   > - MySQL
   > - PHP

   > Можна скористатися відео на офіційному сайті де демонструється завантаження XAMPP.
2. **Запустіть Apache та MySQL у XAMPP Control Panel**.
![Рис3](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images3.png)
3. **Завантажте файли сайту з репозиторію GitHub: [Genova THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA)
4. **Помістіть файли сайту у папку `htdocs`**:
Скопіюйте весь вміст проєкту у каталог `C:\xampp\htdocs\`
![Рис4](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images4.png)
6. **Налаштування бази даних**:
   - У браузері відкрийте `http://localhost/phpmyadmin/`.
   - Створіть нову базу даних з назвою `carbon_tracker`.
   ![Рис5](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images5.png)
    - Імпортуйте структуру та дані:  
     *Перейдіть у вкладку `Import`, оберіть файл `hackathon.sql`, натисніть `Імпортувати`*.
     ![Рис6](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images6.png)
7. **Запуск веб-застосунку**:
   - Відкрийте браузер і перейдіть за адресою:  
     ```
     http://localhost/login_form.html
     ```
![Рис7](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images7.png)
*Повинна відкритися сторінка реєстрації, якщо все правильно налаштовано


## Користування сайтом

### 1. Реєстрація нового користувача
Форма реєстрації включає наступні поля:
![Рис8](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images8.png)
- **Логін** (унікальний ідентифікатор користувача)
- **Країна** (місце проживання користувача)
- **Місто** (детальніша геолокація)
- **Пароль** (шифрується перед збереженням)
- **Підтвердження пароля** (перевірка відповідності пароля)

### 2. Вхід у систему
Після успішної реєстрації користувач може здійснити вхід у систему, використовуючи логін і пароль.
![Рис9](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images9.png)

### 3. Головна сторінка
- **Інформаційні статті** щодо вуглецевого сліду та його впливу на клімат.
- **Освітні матеріали** з екологічних питань.
- **Медіа-контент** (відео та інфографіка) для підвищення обізнаності.
![Рис10](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images10.png)

Є можливість більш детально почитати про інформацію, яка зацікавила. Для цього потрібно натиснути на кнопку `Більше`, після чого розгорнеться повний текст статті:
![Рис11](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images11.png)

Щоб згорнути повний текст потрібно натиснути на кнопку `Менше`

### 4. Розділ "Події"
Містить інформацію про екологічні заходи, конференції та ініціативи, пов'язані зі зменшенням викидів CO₂.  
Користувачі можуть переглядати майбутні події та отримувати посилання на онлайн-заходи. За посиланнями можна відразу переходити на потрібну конференцію:
![Рис12](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images12.png)
Адміністратори мають можливість створювати та розповсюджувати нові конференції:
![Рис13](images/image13.png)

### 5. Калькулятор вуглецевого сліду
Надає можливість розрахувати особистий вуглецевий слід за такими категоріями:
- **Поїздки на автомобілі** (вибір типу пального та відстані)
- **Споживання їжі** (враховує вплив різних типів продуктів)
- **Споживана електроенергія** (вплив на основі країни проживання)
![Рис14](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images14.png)

### 6. Рейтинг користувачів
- Відображає порівняльну таблицю із вуглецевим слідом інших користувачів.
- Фільтрування за датою та географічним регіоном.
- Можливість додати друзів і відстежувати їхні результати.
![Рис15](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images15.png)

### 7. Карта забруднень
- Візуалізація глобальних даних про викиди CO₂ на основі даних із **Carbon Monitor**.
- Карта оновлюється динамічно та показує рівень викидів у різних країнах.
![Рис16](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images16.png)

### 8. Профіль користувача
- **Особиста статистика**: відображення загального вуглецевого сліду.
- **Деталізація споживання**: графік використання вуглецевого сліду за категоріями.
- **Історія внесків**: перегляд усіх попередніх записів розрахунку (рейтинг).
![Рис17](https://github.com/Olexandr7Floor/THE-CARBON-FOOTPRINT-TRACKER-by-GENOVA/blob/main/images/images18.png)

Заповнюючи поля з даними споживання вуглецевого сліду за кожний день, автоматично генерується динамічний графік споживання по критеріям, обраховується результат споживання згідно норм та дані додаються в загальну базу всіх користувачів для відображення в рейтинговій таблиці.

## Унікальність рішення
- **Комплексний підхід**: Розрахунок вуглецевого сліду на основі різних видів діяльності.
- **Просвітництво**: Надання інформації про вплив викидів CO₂.
- **Гейміфікація**: Мотивація користувачів через інтерактивні елементи.

## Висновок
Наше рішення допомагає людям усвідомити свій вплив на клімат та вжити заходів для його збереження (просвітництво та розрахунку  вуглецевого  сліду). Використовуючи The Carbon Footprint Tracker, кожен може зробити свій внесок у скорочення викидів CO₂.

---

© 2025, Команда **GENOVA**  
- **Олександр Бондаренко** – Team Lead  
- **Андрій Клячко** - Backend Developer 
- **Вікторія Поліщук** – Frontend Developer  
- **Михайло Шелельо** – Analyst  

