const icon = document.getElementById('menu-icon');
const menu = document.getElementById('dropdown-menu');

icon.addEventListener('click', (e) => {
  e.stopPropagation();
  menu.classList.toggle('active');
});

document.addEventListener('click', (e) => {
  if (!menu.contains(e.target) && !icon.contains(e.target)) {
    menu.classList.remove('active');
  }
});
