<?php
// login.php - Inicio de sesión para usuarios registrados
session_start();

// Verificar si ya hay una sesión activa
if (isset($_SESSION['usuario_id'])) {
    header("Location: Miembros.php");
    exit();
}

// Mostrar mensajes de error
if (isset($_GET['error'])) {
    echo "<script>alert('" . htmlspecialchars($_GET['error']) . "');</script>";
}
if (isset($_GET['success'])) {
    echo "<script>alert('Sesión cerrada correctamente');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar Sesión - Comunidad Académica</title>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="styleRegistro.css">
<style>
/* Estilos adicionales específicos para login */
.left-panel-login {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.welcome-message {
    text-align: center;
    padding: 20px;
}

.welcome-message h2 {
    font-size: 2em;
    margin-bottom: 15px;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin-top: 30px;
}

.feature-list li {
    margin: 15px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.feature-list li:before {
    content: "✓";
    background: rgba(255,255,255,0.2);
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.forgot-password {
    text-align: right;
    margin: 10px 0;
}

.forgot-password a {
    color: #667eea;
    text-decoration: none;
    font-size: 0.9em;
}

.forgot-password a:hover {
    text-decoration: underline;
}

.demo-credentials {
    margin-top: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 0.9em;
    border: 1px dashed #667eea;
}

.demo-credentials p {
    margin: 5px 0;
    color: #666;
}

.demo-credentials code {
    background: #e9ecef;
    padding: 2px 6px;
    border-radius: 4px;
    color: #495057;
}
</style>
</head>
<body>
  <!-- Navbar exactamente igual al original -->
  <nav class="navbar">
    <a href="index.html" class="logo">
      <img src="Multimedia/About/logoHW.png" alt="HelloWorld Logo">
    </a>
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">☰</label>
    <ul class="nav-links">
      <li><a href="historia.html">Historia</a></li>
      <li><a href="about.html">Quiénes somos</a></li>
      <li><a href="projects.html">Proyectos</a></li>
      <li><a href="Miembros.php">Miembros</a></li>
      <li><a href="EVENTOS.html">Eventos</a></li>
      <li><a href="QUIZ.html">Quiz interactivo</a></li>
      <li class="join"><a href="Registro.php">Unirse</a></li>
    </ul>
  </nav>

  <!-- Contenido principal con el formulario de login -->
  <div class="main-content">
    <div class="register-card">
      <!-- Lado izquierdo: Información visual (modificada para login) -->
      <div class="left-panel left-panel-login">
        <div class="welcome-message">
          <h3>👋 ¡Bienvenido de nuevo!</h3>
          <p>Accede a tu cuenta para conectarte con la comunidad, ver eventos exclusivos y participar en actividades.</p>
          
          <ul class="feature-list">
            <li>Acceso a eventos privados</li>
            <li>Networking con profesionales</li>
            <li>Participación en quizzes</li>
            <li>Seguimiento de tu progreso</li>
          </ul>
        </div>
      </div>

      <!-- Lado derecho: Formulario de inicio de sesión -->
      <div class="right-panel">
        <h2>Iniciar Sesión</h2>
        <p class="subtitle">Ingresa tus credenciales para acceder</p>

        <form action="procesar_login.php" method="POST" id="loginForm">
          <!-- Campo: Correo institucional -->
          <div class="form-group">
            <label for="email">Correo institucional <span class="required">*</span></label>
            <input type="email" id="email" name="email" placeholder="ejemplo@universidad.edu" required>
            <span class="helper-text">Usa el correo con el que te registraste</span>
          </div>

          <!-- Campo: Contraseña -->
          <div class="form-group">
            <label for="password">Contraseña <span class="required">*</span></label>
            <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
            <div class="password-info">
              <strong>Formato:</strong> 8 dígitos (AAAAMMDD)
            </div>
          </div>

          <!-- Opción "Recordarme" -->
          <div style="display: flex; justify-content: space-between; align-items: center; margin: 15px 0;">
            <label style="display: flex; align-items: center; gap: 5px;">
              <input type="checkbox" name="recordar" value="1"> Recordarme
            </label>
            <div class="forgot-password">
              <a href="recuperar_password.php">¿Olvidaste tu contraseña?</a>
            </div>
          </div>

          <!-- Botón de inicio de sesión -->
          <button type="submit" class="btn-submit">Iniciar Sesión</button>
        </form>

        <!-- Enlace a registro -->
        <div class="login-redirect">
          ¿No tienes una cuenta?
          <a href="Registro.php">Regístrate aquí</a>
        </div>

        <!-- Credenciales de demostración -->
        <div class="demo-credentials">
          <p><strong>🔐 Credenciales de prueba:</strong></p>
          <p>📧 Email: prueba@universidad.edu</p>
          <p>🔑 Contraseña: <code>20050115</code></p>
          <p style="font-size: 0.8em; color: #999;">*Crea una cuenta primero para probar</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      // Validar que los campos no estén vacíos
      if (!email || !password) {
        e.preventDefault();
        alert('❌ Por favor, completa todos los campos.');
        return;
      }

      // Validar formato básico de email
      if (!email.includes('@') || email.split('@')[1].length < 3) {
        e.preventDefault();
        alert('❌ Por favor, ingresa un correo electrónico válido.');
        return;
      }

      // Validar que la contraseña tenga 8 dígitos
      if (password.length !== 8 || !/^\d+$/.test(password)) {
        e.preventDefault();
        alert('❌ La contraseña debe tener exactamente 8 dígitos numéricos.');
        return;
      }
    });
  </script>
</body>
</html>