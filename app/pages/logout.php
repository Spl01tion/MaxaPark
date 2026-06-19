<?php
// Termina a sessao do utilizador (funcionario ou administrador)
$_SESSION = [];
session_unset();
session_destroy();

redirect('login');