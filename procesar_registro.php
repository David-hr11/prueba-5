<?php
// procesar_registro.php

// Verificar que los datos fueron enviados por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recoger datos del formulario (TODOS los campos de tu registro)
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $carrera = $_POST['carrera'] ?? '';
    $numero_cuenta = $_POST['numero_cuenta'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validaciones básicas
    $errores = [];
    
    if (empty($nombre)) $errores[] = "El nombre es obligatorio";
    if (empty($apellidos)) $errores[] = "Los apellidos son obligatorios";
    if (empty($carrera)) $errores[] = "La carrera es obligatoria";
    if (empty($numero_cuenta)) $errores[] = "El número de cuenta es obligatorio";
    if (empty($email)) $errores[] = "El correo es obligatorio";
    if (empty($password)) $errores[] = "La contraseña es obligatoria";
    
    // Validar formato de contraseña (8 dígitos)
    if (!preg_match('/^[0-9]{8}$/', $password)) {
        $errores[] = "La contraseña debe tener exactamente 8 dígitos numéricos";
    }
    
    // Validar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        $errores[] = "Las contraseñas no coinciden";
    }
    
    // Si hay errores, redirigir al registro
    if (!empty($errores)) {
        $mensaje_error = implode("\\n", $errores); // Usamos \n para saltos de línea en alert
        header("Location: Registro.php?error=" . urlencode($mensaje_error));
        exit();
    }
    
    // Archivo donde guardaremos los miembros
    $archivo_miembros = 'miembros.json';
    
    // Leer miembros existentes
    if (file_exists($archivo_miembros)) {
        $miembros_json = file_get_contents($archivo_miembros);
        $miembros = json_decode($miembros_json, true) ?: [];
    } else {
        $miembros = [];
    }
    
    // Verificar si el correo ya está registrado
    foreach ($miembros as $miembro) {
        if ($miembro['email'] == $email) {
            header("Location: Registro.php?error=Este correo ya está registrado");
            exit();
        }
    }
    
    // Verificar si el número de cuenta ya está registrado
    foreach ($miembros as $miembro) {
        if ($miembro['numero_cuenta'] == $numero_cuenta) {
            header("Location: Registro.php?error=Este número de cuenta ya está registrado");
            exit();
        }
    }
    
    // Generar foto aleatoria para el miembro (como en tu ejemplo de Miembros)
    $genero = (rand(0, 1) == 0) ? 'women' : 'men';
    $num_foto = rand(1, 99);
    $foto = "https://randomuser.me/api/portraits/$genero/$num_foto.jpg";
    
    // Asignar puntos iniciales (basado en tu ejemplo de miembros)
    $puntos_iniciales = 100; // Todos comienzan con 100 puntos
    
    // Crear nuevo miembro con TODOS los campos
    $nuevo_miembro = [
        'nombre_completo' => $nombre . ' ' . $apellidos,
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'carrera' => $carrera,
        'numero_cuenta' => $numero_cuenta,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT), // Encriptar contraseña
        'foto' => $foto,
        'puntos' => $puntos_iniciales,
        'fecha_registro' => date('Y-m-d H:i:s')
    ];
    
    // Agregar a la lista
    $miembros[] = $nuevo_miembro;
    
    // Ordenar por puntos (para el ranking)
    usort($miembros, function($a, $b) {
        return $b['puntos'] - $a['puntos'];
    });
    
    // Guardar en el archivo
    if (file_put_contents($archivo_miembros, json_encode($miembros, JSON_PRETTY_PRINT))) {
        // Registro exitoso
        header("Location: Registro.php?success=1");
    } else {
        header("Location: Registro.php?error=Error al guardar el registro");
    }
    exit();
    
} else {
    // Si alguien intenta acceder directamente sin POST
    header("Location: Registro.php");
    exit();
}
?>
