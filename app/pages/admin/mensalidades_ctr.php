<?php
// ============================================================
//  Controlador das mensalidades (executa antes do HTML)
// ============================================================

$erro = null;
$sucesso = null;

// Registar pagamento de mensalidade
if (!empty($_POST['id_utente'])) {
	$pid   = trim($_POST['id_utente']);
	$mes_ref = $_POST['mes_ref'] ?? date('Y-m');
	$utente = query_row("SELECT * FROM utentes WHERE id_utente = :id LIMIT 1", ['id' => $pid]);
	if (!$utente) {
		$erro = "Utente não encontrado.";
	} elseif (tem_mensalidade_ativa($pid, $mes_ref)) {
		$erro = "Mensalidade de {$mes_ref} já paga por este utente.";
	} else {
		$valor = valor_mensalidade($utente['tipo']);
		query("INSERT INTO mensalidades (id_utente, mes_ref, valor) VALUES (:id,:mes,:valor)",
			['id' => $pid, 'mes' => $mes_ref, 'valor' => $valor]);
		$sucesso = "Mensalidade de {$mes_ref} registada: {$utente['nome_comp']} — " . fmt_moeda($valor) . ".";
	}
}

// Eliminar mensalidade ( /admin/mensalidades/delete/{id} )
if ($action == 'delete' && $id_utente) {
	query("DELETE FROM mensalidades WHERE id_mensalidade = :id LIMIT 1", ['id' => $id_utente]);
	redirect('admin/mensalidades');
}
