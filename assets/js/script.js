let currentSlide = 0;
const slideElements = document.querySelectorAll('.slide');
const slidesContainer = document.querySelector('.slides');
const dotsContainer = document.querySelector('.dots-container');

// Crear los puntos debajo del slider
slideElements.forEach((_, i) => {
    const dot = document.createElement('span');
    dot.classList.add('dot');
    dot.addEventListener('click', () => goToSlide(i));
    dotsContainer.appendChild(dot);
});

function showSlide(index) {
    if (index < 0) {
        currentSlide = slideElements.length - 1;
    } else if (index >= slideElements.length) {
        currentSlide = 0;
    } else {
        currentSlide = index;
    }

    // Mover el contenedor de slides
    slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;

    // Actualizar los puntos
    const dots = document.querySelectorAll('.dot');
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === currentSlide);
    });
}

function prevSlide() {
    showSlide(currentSlide - 1);
}

function nextSlide() {
    showSlide(currentSlide + 1);
}

function goToSlide(index) {
    showSlide(index);
}

// Mostrar primer slide al inicio
showSlide(currentSlide);

// Slider automático cada 3 segundos
setInterval(() => {
    nextSlide();
}, 3000);
