<?php
session_start();

// Destroi todas as variáveis da sessão
session_unset();

// Destroi a sessão
session_destroy();

// Redireciona para a página de login após o logout
header('Location: login.php');
exit;
?>
