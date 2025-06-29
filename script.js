
//DASHBOARD NAVMENU
function navbar() {
  let sidebar = document.querySelector('.sidebar');
  sidebar.classList.toggle('close');
}

// DARK MODE
function lightMode() {
  console.log("clicked");
  let body = document.querySelector('body');
  let sidebar = document.querySelectorAll('.sidebar');
  let navnav = document.querySelectorAll('.navnav');
  let items = document.querySelectorAll('.items');
  let card = document.querySelectorAll('.card');
  let btn = document.querySelectorAll('.btn');
  let icon = document.querySelector('#icon');
  let lmode = document.querySelector('#lmode');

  if (body.classList.contains('dark')) {
      body.classList.remove('dark');
      sidebar.forEach(el => el.classList.remove('dark'))
      navnav.forEach(el => el.classList.remove('dark'))
      items.forEach(el => el.classList.remove('dark'));
      card.forEach(el => el.classList.remove('dark'));
      btn.forEach(el => el.classList.remove('dark'));
      lmode.textContent = "dark mode"
      icon.classList.replace('fa-moon', 'fa-sun');
      localStorage.setItem('mode', 'light');
  } else {
      body.classList.add('dark');
      sidebar.forEach(el => el.classList.add('dark'));
      navnav.forEach(el => el.classList.add('dark'))
      items.forEach(el => el.classList.add('dark'));
      card.forEach(el => el.classList.add('dark'));
      btn.forEach(el => el.classList.add('dark'));
      lmode.textContent = "light mode"
      icon.classList.replace('fa-sun', 'fa-moon');
      localStorage.setItem('mode', 'dark');

  }
}
window.addEventListener('DOMContentLoaded', function() {
  let mode = localStorage.getItem('mode');
  let body = document.querySelector('body');
  let sidebar = document.querySelectorAll('.sidebar');
  let navnav = document.querySelectorAll('.navnav');
  let items = document.querySelectorAll('.items');
  let card = document.querySelectorAll('.card');
  let btn = document.querySelectorAll('.btn');
  let icon = document.querySelector('#icon');
  let lmode = document.querySelector('#lmode');

  if (mode === 'dark') {

      body.classList.add('dark');
      sidebar.forEach(el => el.classList.add('dark'));
      navnav.forEach(el => el.classList.add('dark'));
      items.forEach(el => el.classList.add('dark'));
      card.forEach(el => el.classList.add('dark'));
      btn.forEach(el => el.classList.add('dark'));

      lmode.textContent = "light mode"
      icon.classList.replace('fa-sun', 'fa-moon');
      console.log(mode);

  }
})

// CHART EVENTS
let data = {
  labels: ['Credit', 'Debit'],
  datasets: [{
      data: [150, 200],
      backgroundColor: ['green', 'yellow'],
      label: 'Account Balance'

  }]

}
const ctx = document.getElementById('flowchart').getContext('2d');

new Chart(ctx, {
  type: 'pie',
  data: data
})