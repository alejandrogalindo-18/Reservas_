<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Sistema de Reservas - Aerolínea</title>
  <link rel="icon" type="image/png" href="../assets/favicon.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Paleta principal: rojo (#DC2626), negro (#0f172a), azul (#1e40af) */
  </style>
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-b from-slate-50 to-white">
