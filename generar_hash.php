<?php
// Función para generar una contraseña aleatoria
function generarPassword($longitud = 12, $mayusculas = true, $minusculas = true, $numeros = true, $simbolos = true)
{
    $caracteres = '';
    $password = '';

    // Definir los conjuntos de caracteres
    if ($mayusculas) $caracteres .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($minusculas) $caracteres .= 'abcdefghijklmnopqrstuvwxyz';
    if ($numeros) $caracteres .= '0123456789';
    if ($simbolos) $caracteres .= '!@#$%^&*()-_=+[]{};:,.<>?';

    // Verificar que al menos un conjunto de caracteres esté seleccionado
    if (empty($caracteres)) {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    }

    $caracteresLength = strlen($caracteres);

    // Generar la contraseña
    for ($i = 0; $i < $longitud; $i++) {
        $password .= $caracteres[rand(0, $caracteresLength - 1)];
    }

    return $password;
}

// Inicializar variables
$password = '';
$mensaje = '';
$longitud = 12;
$mayusculas = true;
$minusculas = true;
$numeros = true;
$simbolos = true;

// Variables para hash personalizado
$password_manual = '';
$hash_resultado = '';
$sql_insert = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determinar qué formulario se envió
    if (isset($_POST['generar'])) {
        $longitud = (int) ($_POST['longitud'] ?? 12);
        $mayusculas = isset($_POST['mayusculas']);
        $minusculas = isset($_POST['minusculas']);
        $numeros = isset($_POST['numeros']);
        $simbolos = isset($_POST['simbolos']);

        // Validar longitud
        if ($longitud < 6) {
            $longitud = 6;
            $mensaje = 'La longitud mínima es 6 caracteres.';
        } elseif ($longitud > 32) {
            $longitud = 32;
            $mensaje = 'La longitud máxima es 32 caracteres.';
        }

        // Generar contraseña
        $password = generarPassword($longitud, $mayusculas, $minusculas, $numeros, $simbolos);
    } elseif (isset($_POST['hashear'])) {
        $password_manual = $_POST['password_manual'] ?? '';
        if (!empty($password_manual)) {
            $hash_resultado = password_hash($password_manual, PASSWORD_DEFAULT);
            $email = $_POST['email'] ?? 'usuario@ejemplo.com';
            $sql_insert = "INSERT INTO usuarios (email, password) VALUES ('$email', '$hash_resultado');";
        } else {
            $mensaje = 'Por favor, ingresa una contraseña para generar su hash.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Contraseñas - Amazon Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f5f8;
            padding-top: 20px;
        }

        .amazon-color {
            background-color: #FF9900;
            border-color: #FF9900;
        }

        .amazon-color:hover {
            background-color: #e68a00;
            border-color: #e68a00;
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }

        .copy-icon {
            cursor: pointer;
        }

        .nav-tabs .nav-link {
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            color: #FF9900;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" alt="Amazon"
                        class="logo">
                    <h2>Herramientas de Contraseñas</h2>
                </div>

                <!-- Pestañas de navegación -->
                <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="generator-tab" data-bs-toggle="tab"
                            data-bs-target="#generator" type="button">
                            Generar Contraseña
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="hash-tab" data-bs-toggle="tab" data-bs-target="#hash"
                            type="button">
                            Generar Hash
                        </button>
                    </li>
                </ul>

                <!-- Contenido de las pestañas -->
                <div class="tab-content" id="myTabContent">
                    <!-- Pestaña del generador de contraseñas -->
                    <div class="tab-pane fade show active" id="generator" role="tabpanel"
                        aria-labelledby="generator-tab">
                        <div class="card">
                            <div class="card-body">
                                <?php if ($mensaje && isset($_POST['generar'])): ?>
                                    <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
                                <?php endif; ?>

                                <form method="post">
                                    <!-- Opciones de configuración -->
                                    <div class="mb-3">
                                        <label for="longitud" class="form-label">Longitud:</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="longitud" name="longitud"
                                                min="6" max="32" value="<?= $longitud ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="mayusculas"
                                                name="mayusculas" <?= $mayusculas ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="mayusculas">Incluir mayúsculas
                                                (A-Z)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="minusculas"
                                                name="minusculas" <?= $minusculas ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="minusculas">Incluir minúsculas
                                                (a-z)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="numeros" name="numeros"
                                                <?= $numeros ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="numeros">Incluir números (0-9)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="simbolos"
                                                name="simbolos" <?= $simbolos ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="simbolos">Incluir símbolos
                                                (!@#$...)</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" name="generar" class="btn btn-primary amazon-color w-100">
                                            Generar Contraseña
                                        </button>
                                    </div>
                                </form>

                                <?php if ($password): ?>
                                    <div class="mt-4">
                                        <label class="form-label">Contraseña Generada:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="password"
                                                value="<?= htmlspecialchars($password) ?>" readonly>
                                            <button class="btn btn-outline-secondary copy-icon" type="button"
                                                onclick="copiarTexto('password')">
                                                📋 Copiar
                                            </button>
                                        </div>
                                        <div class="form-text">Esta contraseña es segura y no se almacena en ningún lugar.
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña del generador de hash -->
                    <div class="tab-pane fade" id="hash" role="tabpanel" aria-labelledby="hash-tab">
                        <div class="card">
                            <div class="card-body">
                                <?php if ($mensaje && isset($_POST['hashear'])): ?>
                                    <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
                                <?php endif; ?>

                                <form method="post">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email (opcional):</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="usuario@ejemplo.com">
                                        <div class="form-text">Para generar el SQL de inserción de usuario.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_manual" class="form-label">Contraseña a hashear:</label>
                                        <input type="text" class="form-control" id="password_manual"
                                            name="password_manual" placeholder="Escribe tu contraseña" required>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" name="hashear" class="btn btn-primary amazon-color w-100">
                                            Generar Hash
                                        </button>
                                    </div>
                                </form>

                                <?php if ($hash_resultado): ?>
                                    <div class="mt-4">
                                        <label class="form-label">Contraseña Original:</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control"
                                                value="<?= htmlspecialchars($password_manual) ?>" readonly>
                                        </div>

                                        <label class="form-label">Hash generado:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="hash_resultado"
                                                value="<?= htmlspecialchars($hash_resultado) ?>" readonly>
                                            <button class="btn btn-outline-secondary copy-icon" type="button"
                                                onclick="copiarTexto('hash_resultado')">
                                                📋 Copiar
                                            </button>
                                        </div>

                                        <div class="mt-4">
                                            <label class="form-label">SQL para insertar en la base de datos:</label>
                                            <div class="input-group">
                                                <textarea class="form-control" id="sql_insert" rows="3"
                                                    readonly><?= htmlspecialchars($sql_insert) ?></textarea>
                                                <button class="btn btn-outline-secondary copy-icon" type="button"
                                                    onclick="copiarTexto('sql_insert')">
                                                    📋 Copiar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="http://localhost/DashboardAmazon/login/login.php"
                        class="text-decoration-none text-secondary">Volver al Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function copiarTexto(elementId) {
            const elemento = document.getElementById(elementId);
            elemento.select();
            elemento.setSelectionRange(0, 99999);
            document.execCommand('copy');

            // Mostrar mensaje de copiado
            const copyBtn = document.activeElement;
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '✓ Copiado';

            setTimeout(() => {
                copyBtn.innerHTML = originalText;
            }, 2000);
        }
    </script>
</body>

</html>