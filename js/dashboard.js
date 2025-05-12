document.addEventListener("DOMContentLoaded", function () {
  // Obtener elementos del DOM
  const mainContentArea = document.querySelector(".main-content-area");
  const titulo = document.querySelector("h1");
  const imageContainer = document.querySelector(".image-container");
  const sidebar = document.querySelector(".sidebar");

  // Función para añadir animaciones secuenciales
  function animarElementos() {
    // Primero anima el título
    setTimeout(() => {
      titulo.classList.add("fade-in");
    }, 300);

    // Luego anima la imagen
    setTimeout(() => {
      imageContainer.classList.add("slide-in");
    }, 600);
  }

  // Animación para la barra lateral
  sidebar.querySelectorAll(".nav-link").forEach((link, index) => {
    link.style.opacity = "0";
    link.style.transform = "translateX(-20px)";
    link.style.transition = "opacity 0.3s ease, transform 0.3s ease";

    setTimeout(() => {
      link.style.opacity = "1";
      link.style.transform = "translateX(0)";
    }, 100 * (index + 1));
  });

  // Añadir efecto de hover a los enlaces del sidebar
  sidebar.querySelectorAll(".nav-link").forEach((link) => {
    link.addEventListener("mouseenter", function () {
      const icon = this.querySelector(".icon");
      if (icon) {
        icon.style.transition = "transform 0.3s ease";
        icon.style.transform = "translateX(5px)";
      }
    });

    link.addEventListener("mouseleave", function () {
      const icon = this.querySelector(".icon");
      if (icon) {
        icon.style.transform = "translateX(0)";
      }
    });
  });

  // Aplicar animaciones adicionales al área de contenido principal
  mainContentArea.style.opacity = "0";
  mainContentArea.style.transform = "translateY(20px)";
  mainContentArea.style.transition = "opacity 0.5s ease, transform 0.5s ease";

  setTimeout(() => {
    mainContentArea.style.opacity = "1";
    mainContentArea.style.transform = "translateY(0)";
    animarElementos();
  }, 200);

  // Añadir animación al footer
  const footer = document.querySelector(".main-footer");
  footer.style.opacity = "0";
  footer.style.transition = "opacity 1s ease";

  setTimeout(() => {
    footer.style.opacity = "1";
  }, 1000);
});
