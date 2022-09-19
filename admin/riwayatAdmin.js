const menuToggle = document.querySelector('.menu-toggle input');
console.log(menuToggle);
const navUl = document.querySelector('nav ul');
console.log(navUl);

menuToggle.addEventListener('click', function() {
    navUl.classList.toggle('slide');
});