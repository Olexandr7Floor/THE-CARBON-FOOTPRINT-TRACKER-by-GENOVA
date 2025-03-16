$(function() {
	$(".btn").click(function() {
		$(".form-signin").toggleClass("form-signin-left");
    $(".form-signup").toggleClass("form-signup-left");
    $(".frame").toggleClass("frame-long");
    $(".signup-inactive").toggleClass("signup-active");
    $(".signin-active").toggleClass("signin-inactive");
    $(".forgot").toggleClass("forgot-left");   
    $(this).removeClass("idle").addClass("active");
	});
});

$(function() {
	$(".btn-signup").click(function() {
  $(".nav").toggleClass("nav-up");
  $(".form-signup-left").toggleClass("form-signup-down");
  $(".success").toggleClass("success-left"); 
  $(".frame").toggleClass("frame-short");
	});
});

$(function() {
	$(".btn-signin").click(function() {
  $(".btn-animate").toggleClass("btn-animate-grow");
  $(".welcome").toggleClass("welcome-left");
  $(".cover-photo").toggleClass("cover-photo-down");
  $(".frame").toggleClass("frame-short");
  $(".profile-photo").toggleClass("profile-photo-down");
  $(".btn-goback").toggleClass("btn-goback-up");
  $(".forgot").toggleClass("forgot-fade");
	});
});
document.addEventListener('DOMContentLoaded', function() {
  const currentPage = window.location.pathname.split('/').pop(); // Отримуємо ім'я поточного файлу
  const navLinks = document.querySelectorAll('nav ul li a');

  navLinks.forEach(link => {
      const linkPage = link.getAttribute('href').split('/').pop(); // Отримуємо ім'я файлу з посилання
      if (linkPage === currentPage) {
          link.classList.add('active');
      }
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const dateFilter = document.getElementById('date-filter');
  const locationFilter = document.getElementById('location-filter');
  const friendsFilter = document.getElementById('friends-filter');

  dateFilter.addEventListener('change', applyFilters);
  locationFilter.addEventListener('change', applyFilters);
  friendsFilter.addEventListener('change', applyFilters);

  function applyFilters() {
    const selectedDate = dateFilter.value;
    const selectedLocation = locationFilter.value;
    const selectedFriends = friendsFilter.value;

    // Тут можна додати логіку фільтрації даних
    console.log('Фільтр по даті:', selectedDate);
    console.log('Фільтр по локації:', selectedLocation);
    console.log('Фільтр по друзях:', selectedFriends);
  }
});


document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('carbonChart').getContext('2d');
  const carbonChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень'],
      datasets: [{
        label: 'Вуглецевий слід (кг CO₂)',
        data: [120, 150, 130, 140, 160, 110],
        borderColor: '#4CAF50',
        borderWidth: 2,
        fill: false
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
});

