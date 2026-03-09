<?php
// miembros.php

// Iniciar sesión para ver si hay usuario logueado
session_start();

// Cargar miembros desde el archivo JSON
$archivo_miembros = 'miembros.json';
$miembros = [];

if (file_exists($archivo_miembros)) {
    $miembros_json = file_get_contents($archivo_miembros);
    $miembros = json_decode($miembros_json, true) ?: [];
}

// Ordenar por puntos para el ranking
usort($miembros, function($a, $b) {
    return $b['puntos'] - $a['puntos'];
});

// Verificar si hay usuario logueado
$usuario_logueado = isset($_SESSION['usuario_id']);
$nombre_usuario = $usuario_logueado ? $_SESSION['usuario_nombre'] : '';

// Preparar variables para mensajes
$success = isset($_GET['success']) ? true : false;
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;

// Incluir el template HTML
include 'miembros.html';
?>