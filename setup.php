<?php
// Script para configurar el entorno de desarrollo

// Definir la ruta para las imágenes de productos
$productsImgPath = __DIR__ . '/reference/img/productos';

// Crear el directorio si no existe
if (!file_exists($productsImgPath)) {
    mkdir($productsImgPath, 0777, true);
    echo "Directorio para imágenes de productos creado: $productsImgPath <br>";
}

// Verificar permisos de escritura
if (!is_writable($productsImgPath)) {
    chmod($productsImgPath, 0777);
    echo "Permisos de escritura aplicados a: $productsImgPath <br>";
}

echo "Configuración completada con éxito. <br>";
echo "<a href='login/login.php'>Ir al login</a>";
