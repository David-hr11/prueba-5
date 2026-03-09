<?php
// procesar_login.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $recordar = isset($_POST['recordar']);
    
    // Validaciones básicas
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=" . urlencode("Todos los campos son obligatorios"));
        exit();
    }
    
    // Validar formato de contraseña
    if (!preg_match('/^[0-9]{8}$/', $password)) {
        header("Location: login.php?error=" . urlencode("La contraseña debe tener 8 dígitos numéricos"));
        exit();
    }
    
    // Ruta al archivo de miembros
    $archivo_miembros = __DIR__ . '/miembros.json';
    
    if (!file_exists($archivo_miembros)) {
        header("Location: login.php?error=" . urlencode("No hay usuarios registrados"));
        exit();
    }
    
    // Leer miembros
    $miembros_json = file_get_contents($archivo_miembros);
    $miembros = json_decode($miembros_json, true) ?: [];
    
    // Buscar usuario por email
    $usuario_encontrado = null;
    foreach ($miembros as $miembro) {
        if ($miembro['email'] === $email) {
            $usuario_encontrado = $miembro;
            break;
        }
    }
    
    // Verificar si existe y la contraseña coincide
    if ($usuario_encontrado && password_verify($password, $usuario_encontrado['password'])) {
        // Iniciar sesión
        $_SESSION['usuario_id'] = $usuario_encontrado['numero_cuenta'] ?? uniqid();
        $_SESSION['usuario_nombre'] = $usuario_encontrado['nombre_completo'] ?? $usuario_encontrado['nombre'];
        $_SESSION['usuario_email'] = $usuario_encontrado['email'];
        
        // Si marcó "Recordarme", crear cookie (30 días)
        if ($recordar) {
            setcookie('usuario_email', $email, time() + (86400 * 30), "/"); // 30 días
        }
        
        header("Location: Miembros.php?success=1");
        exit();
    } else {
        header("Location: login.php?error=" . urlencode("Correo o contraseña incorrectos"));
        exit();
    }
    
} else {
    header("Location: login.php");
    exit();
}
?>