document.addEventListener("DOMContentLoaded", function () {
  const btnMostrarAgregar = document.getElementById("btn-mostrar-agregar");
  const btnMostrarVer = document.getElementById("btn-mostrar-ver");
  const seccionAgregar = document.getElementById("seccion-agregar");
  const seccionVer = document.getElementById("seccion-ver");
  const tituloPagina = document.getElementById("titulo-pagina");
  const loadingIndicator = document.getElementById("loading-indicator");
  const sidebar = document.querySelector(".sidebar");

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

  // Función para cambiar a la vista de agregar productos con animaciones
  btnMostrarAgregar.addEventListener("click", function (e) {
    e.preventDefault();

    // Configurar los estilos y las clases de animación
    if (seccionVer.style.display !== "none") {
      // Ocultar la sección de ver productos con animación
      seccionVer.classList.remove("slide-in");
      seccionVer.classList.add("slide-out");

      // Esperar a que termine la animación antes de ocultar completamente
      setTimeout(() => {
        seccionVer.style.display = "none";
        // Mostrar la sección de agregar con animación
        seccionAgregar.style.display = "block";
        seccionAgregar.classList.remove("slide-out");
        seccionAgregar.classList.add("slide-in");
      }, 500); // Este tiempo debe coincidir con la duración de la animación
    }

    // Cambiar el título con animación
    tituloPagina.classList.remove("fade-in");
    tituloPagina.classList.add("fade-out");
    setTimeout(() => {
      tituloPagina.textContent = "AGREGAR PRODUCTOS";
      tituloPagina.classList.remove("fade-out");
      tituloPagina.classList.add("fade-in");
    }, 300);

    // Cambiar colores de los botones con transición (ya definida en CSS)
    btnMostrarAgregar.style.backgroundColor = "#ff9900";
    btnMostrarVer.style.backgroundColor = "#1c2b39";
  });

  // Función para cambiar a la vista de ver productos con animaciones
  btnMostrarVer.addEventListener("click", function (e) {
    e.preventDefault();

    // Mostrar indicador de carga
    loadingIndicator.style.display = "block";

    // Configurar los estilos y las clases de animación
    if (seccionAgregar.style.display !== "none") {
      // Ocultar la sección de agregar con animación
      seccionAgregar.classList.remove("slide-in");
      seccionAgregar.classList.add("slide-out");

      // Esperar a que termine la animación antes de ocultar completamente
      setTimeout(() => {
        seccionAgregar.style.display = "none";
        // Mostrar la sección de ver con animación
        seccionVer.style.display = "block";
        seccionVer.classList.remove("slide-out");
        seccionVer.classList.add("slide-in");

        // Cargar productos (con un pequeño retraso para mostrar la animación)
        setTimeout(() => {
          loadingIndicator.style.display = "none";
          cargarProductos();
        }, 600);
      }, 500);
    } else {
      // Si ya estaba oculto, solo cargar productos
      setTimeout(() => {
        loadingIndicator.style.display = "none";
        cargarProductos();
      }, 600);
    }

    // Cambiar el título con animación
    tituloPagina.classList.remove("fade-in");
    tituloPagina.classList.add("fade-out");
    setTimeout(() => {
      tituloPagina.textContent = "VER PRODUCTOS";
      tituloPagina.classList.remove("fade-out");
      tituloPagina.classList.add("fade-in");
    }, 300);

    // Cambiar colores de los botones con transición (ya definida en CSS)
    btnMostrarVer.style.backgroundColor = "#ff9900";
    btnMostrarAgregar.style.backgroundColor = "#1c2b39";
  });

  // Función para cargar los productos
  function cargarProductos() {
    const tablaBody = document.getElementById("tabla-datos-productos");

    // Limpiar la tabla
    tablaBody.innerHTML = "";

    // Añadir un pequeño retraso para que se vea la animación de carga
    setTimeout(() => {
      // Llenar la tabla con los productos
      productos.forEach((producto) => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${producto.id}</td>
          <td class="foto-cell">
              <img src="../reference/img/productos/${producto.imagen}" alt="${producto.nombre}">
          </td>
          <td>${producto.nombre}</td>
          <td>${producto.descripcion}</td>
          <td>$${producto.precio}</td>
          <td class="eliminar-cell">
              <button class="btn-eliminar" data-id="${producto.id}">
                  <span class="icono-basura">🗑️</span>
              </button>
          </td>
        `;
        tablaBody.appendChild(row);
      });

      // Agregar eventos a los botones de eliminar
      document.querySelectorAll(".btn-eliminar").forEach((btn) => {
        btn.addEventListener("click", function () {
          const id = this.getAttribute("data-id");
          if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
            // Animar la eliminación de la fila
            const row = this.closest("tr");
            row.style.transition = "all 0.5s ease";
            row.style.opacity = "0";
            row.style.transform = "translateX(20px)";

            setTimeout(() => {
              // Aquí implementarías la lógica para eliminar el producto
              alert(`Producto ${id} eliminado`);
              // Actualizar la tabla
              cargarProductos();
            }, 500);
          }
        });
      });
    }, 300);
  }
});
