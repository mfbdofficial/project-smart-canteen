//Untuk Navbar
const menuToggle = document.querySelector('.menu-toggle input');
console.log(menuToggle);
const navUl = document.querySelector('nav ul');
console.log(navUl);

menuToggle.addEventListener('click', function() {
    navUl.classList.toggle('slide');
});


//Untuk Modal Box Shopping Cart
let tombolModal = document.getElementById("tombolModal");
let modalKeranjang = document.getElementById("modalKeranjang");
let closeKeranjang = document.getElementsByClassName("closeKeranjang")[0];
console.log(tombolModal);
console.log(modalKeranjang);
console.log(closeKeranjang);
//kalo user klik tombol maka buka modal 
tombolModal.onclick = function() {
    modalKeranjang.style.display = "block";
}
//kalo user klik tombole closee
closeKeranjang.onclick = function() {
    modalKeranjang.style.display = "none";
}
//kalo user klik dimanapun di luar kotak modal
window.onclick = function(event) {
    if (event.target == modalKeranjang) {
        modalKeranjang.style.display = "none";
      }
}