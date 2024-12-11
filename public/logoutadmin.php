<?php
session_start();
session_unset();  // Limpa todas as variáveis de sessão
session_destroy(); // Destroi a sessão
header('Location: loginadmin.php');  // Redireciona para o login do admin
exit;