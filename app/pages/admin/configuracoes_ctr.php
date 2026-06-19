<?php
// ============================================================
//  Controlador das configuracoes (executa antes do HTML)
//  Permite alterar o horario de funcionamento do parque.
// ============================================================

$erro = null;
$sucesso = null;

if (!empty($_POST) && isset($_POST['park_abertura'], $_POST['park_fecho'])) {

	$abertura = (int) $_POST['park_abertura'];
	$fecho    = (int) $_POST['park_fecho'];

	if ($abertura < 0 || $abertura > 23) {
		$erro = "Hora de abertura inválida (deve estar entre 0 e 23).";
	} elseif ($fecho < 1 || $fecho > 24) {
		$erro = "Hora de fecho inválida (deve estar entre 1 e 24).";
	} elseif ($abertura >= $fecho) {
		$erro = "A hora de abertura deve ser anterior à hora de fecho.";
	} else {
		config_set('park_abertura', $abertura);
		config_set('park_fecho', $fecho);
		redirect('admin/configuracoes?ok=1');
	}
}
