<?php
// ============================================================
//  Pagamento de mensalidades
//  Permite registar o pagamento mensal de um utente (25% desconto,
//  estudantes 50%). O mensalista nao paga por hora dentro do horario.
// ============================================================

$erro    = null;
$sucesso = null;

if (!empty($_POST['id_utente'])) {

	$id_utente = trim($_POST['id_utente']);
	$mes_ref   = $_POST['mes_ref'] ?? date('Y-m');

	$utente = query_row("SELECT * FROM utentes WHERE id_utente = :id LIMIT 1", ['id' => $id_utente]);

	if (!$utente) {
		$erro = "Utente não encontrado.";
	} elseif (tem_mensalidade_ativa($id_utente, $mes_ref)) {
		$erro = "Este utente já tem a mensalidade de {$mes_ref} paga.";
	} else {
		$valor = valor_mensalidade($utente['tipo']);
		query(
			"INSERT INTO mensalidades (id_utente, mes_ref, valor) VALUES (:id, :mes, :valor)",
			['id' => $id_utente, 'mes' => $mes_ref, 'valor' => $valor]
		);
		$sucesso = "Mensalidade de {$mes_ref} paga por {$utente['nome_comp']}: " . fmt_moeda($valor) . ".";
	}
}

// Pesquisa de utente para mostrar o valor antes de pagar
$utente_sel = null;
if (!empty($_GET['busca'])) {
	$utente_sel = query_row(
		"SELECT * FROM utentes WHERE id_utente = :id OR bi = :bi LIMIT 1",
		['id' => $_GET['busca'], 'bi' => $_GET['busca']]
	);
}

// Lista das ultimas mensalidades
$limit = 8;
$offset = ($PAGE['page_number'] - 1) * $limit;
$pagamentos = query(
	"SELECT m.*, u.nome_comp, u.tipo
	 FROM mensalidades m JOIN utentes u ON u.id_utente = m.id_utente
	 ORDER BY m.id_mensalidade DESC LIMIT $limit OFFSET $offset"
);
?>

<h3><i class="bi bi-cash-coin"></i> Pagamento de Mensalidades</h3>
<p class="text-muted">Mensalidade base: <?= fmt_moeda(TARIFA_MENSAL) ?> — desconto de 25% (geral) ou 50% (estudante).</p>

<?php if ($erro) : ?>
	<div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>
<?php if ($sucesso) : ?>
	<div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
<?php endif; ?>

<div class="row">
	<div class="col-md-5">
		<form method="GET" action="<?= ROOT ?>/home/pagamentos" class="card card-body shadow-sm mb-3">
			<label class="form-label">Pesquisar utente (Nº ou BI)</label>
			<div class="input-group">
				<input type="text" name="busca" class="form-control" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>" placeholder="Ex: 1001221">
				<button class="btn btn-primary"><i class="bi bi-search"></i></button>
			</div>
		</form>

		<?php if (!empty($_GET['busca']) && !$utente_sel) : ?>
			<div class="alert alert-warning">Utente não encontrado.</div>
		<?php endif; ?>

		<?php if ($utente_sel) : ?>
			<div class="card card-body shadow-sm">
				<h5><?= htmlspecialchars($utente_sel['nome_comp']) ?></h5>
				<p class="mb-1"><strong>Nº:</strong> <?= $utente_sel['id_utente'] ?></p>
				<p class="mb-1"><strong>Tipo:</strong> <span class="text-capitalize"><?= htmlspecialchars(trim($utente_sel['tipo'])) ?></span></p>
				<p class="mb-1"><strong>Mensalidade (<?= date('Y-m') ?>):</strong> <?= fmt_moeda(valor_mensalidade($utente_sel['tipo'])) ?></p>
				<?php if (tem_mensalidade_ativa($utente_sel['id_utente'])) : ?>
					<div class="alert alert-info mt-2 mb-0">Mensalidade do mês corrente já paga.</div>
				<?php else : ?>
					<form method="POST" class="mt-2">
						<input type="hidden" name="id_utente" value="<?= $utente_sel['id_utente'] ?>">
						<input type="hidden" name="mes_ref" value="<?= date('Y-m') ?>">
						<button class="btn btn-success w-100"><i class="bi bi-check-circle"></i> Registar Pagamento Mensal</button>
					</form>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="col-md-7">
		<h5>Últimos pagamentos</h5>
		<div class="table-responsive">
			<table class="table table-sm">
				<tr>
					<th>#</th>
					<th>Utente</th>
					<th>Mês</th>
					<th>Valor</th>
					<th>Data</th>
				</tr>
				<?php if (!empty($pagamentos)) : ?>
					<?php foreach ($pagamentos as $p) : ?>
						<tr>
							<td><?= $p['id_mensalidade'] ?></td>
							<td><?= htmlspecialchars($p['nome_comp']) ?></td>
							<td><?= $p['mes_ref'] ?></td>
							<td><?= fmt_moeda($p['valor']) ?></td>
							<td><?= date('d-m-Y H:i', strtotime($p['data_pagamento'])) ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr><td colspan="5" class="text-muted text-center">Sem pagamentos registados.</td></tr>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>
