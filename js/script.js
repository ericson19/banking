

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
function Eamount() {
  const mainAmount = document.getElementById('mainAmount');
  const Eamount = document.getElementById('Eamount');


  mainAmount.value = Eamount.value;
}

function two() {
  const mainAmount = document.getElementById('mainAmount');
  const Eamount = document.getElementById('Eamount');
  Eamount.value = 200;
  mainAmount.value = Eamount.value;
}

function five() {
  const mainAmount = document.getElementById('mainAmount');
  const Eamount = document.getElementById('Eamount');
  Eamount.value = 500;
  mainAmount.value = Eamount.value;
}

function one() {
  const mainAmount = document.getElementById('mainAmount');
  const Eamount = document.getElementById('Eamount');
  Eamount.value = 1000;
  mainAmount.value = Eamount.value;
}

function two0() {
  const mainAmount = document.getElementById('mainAmount');
  const Eamount = document.getElementById('Eamount');
  Eamount.value = 2000;
  mainAmount.value = Eamount.value;
}

function five0() {
  const mainAmount = document.getElementById('mainAmount');
  const Eamount = document.getElementById('Eamount');
  Eamount.value = 5000;
  mainAmount.value = Eamount.value;
}

function one0() {
  const mainAmount = document.getElementById('mainAmount');
  const Eamount = document.getElementById('Eamount');
  Eamount.value = 10000;
  mainAmount.value = Eamount.value;
}


