// Toggle Hamburger Menu
const hamburgerButton = document.getElementById('hamburgerButton');
const navMenu = document.getElementById('navMenu');

hamburgerButton.addEventListener('click', () => {
    navMenu.classList.toggle('hidden');
});

// Toggle User Dropdown
const userMenuButton = document.getElementById('userMenuButton');
const userMenu = document.getElementById('userMenu');

userMenuButton.addEventListener('click', (e) => {
    e.stopPropagation();
    userMenu.classList.toggle('hidden');
});

// Tutup dropdown saat klik di luar
window.addEventListener('click', () => {
    if (!userMenu.classList.contains('hidden')) {
        userMenu.classList.add('hidden');
    }
});

// Mencegah dropdown tertutup saat klik di dalamnya
userMenu.addEventListener('click', (e) => {
    e.stopPropagation();
});