<?php
// ============================================================
//  Pagamentos (admin) - livro de receitas (estacionamento + mensalidades)
// ============================================================

$limit = 15;
$offset = ($PAGE['page_number'] - 1) * $limit;

// Livro unificado: saidas pagas (por hora) + mensalidades
$movimentos = query(
	"SELECT * FROM (
		SELECT r.id_registo AS id, 'Estacionamento' AS origem, u.nome_comp,
		       r.valor_pago AS valor, r.data_saida AS data_mov
		FROM registos r JOIN utentes u ON u.id_utente=r.id_utente
		WHERE r.estado='fora' AND r.valor_pago IS NOT NULL
		UNION ALL
		SELECT m.id_mensalidade AS id, 'Mensalidade' AS origem, u.nome_comp,
		       m.valor AS valor, m.data_pagamento AS data_mov
		FROM mensalidades m JOIN utentes u ON u.id_utente=m.id_utente
	) AS livro
	ORDER BY data_mov DESC LIMIT $limit OFFSET $offset"
);

$tot_hora = query_row("SELECT COALESCE(SUM(valor_pago),0) AS t FROM registos WHERE estado='fora'")['t'] ?? 0;
$tot_mens = query_row("SELECT COALESCE(SUM(valor),0) AS t FROM mensalidades")['t'] ?? 0;
?>

<h4><i class="bi bi-currency-exchange"></i> Pagamentos / Receitas</h4>

<div class="row g-3 my-2">
	<div class="col-md-4"><div class="card text-bg-primary"><div class="card-body">
		<h6>Total estacionamento</h6><h3><?= fmt_moeda($tot_hora) ?></h3></div></div></div>
	<div class="col-md-4"><div class="card text-bg-info"><div class="card-body">
		<h6>Total mensalidades</h6><h3><?= fmt_moeda($tot_mens) ?></h3></div></div></div>
	<div class="col-md-4"><div class="card text-bg-success"><div class="card-body">
		<h6>Receita total</h6><h3><?= fmt_moeda($tot_hora + $tot_mens) ?></h3></div></div></div>
</div>

<div class="table-responsive">
	<table class="table table-striped">
		<tr><th>#</th><th>Origem</th><th>Utente</th><th>Valor</th><th>Data</th></tr>
		<?php if (!empty($movimentos)) : ?>
			<?php foreach ($movimentos as $m) : ?>
				<tr>
					<td><?= $m['id'] ?></td>
					<td>
						<span class="badge <?= $m['origem'] == 'Mensalidade' ? 'bg-info' : 'bg-primary' ?>"><?= $m['origem'] ?></span>
					</td>
					<td><?= htmlspecialchars($m['nome_comp']) ?></td>
					<td><?= fmt_moeda($m['valor']) ?></td>
					<td><?= $m['data_mov'] ? date('d-m-Y H:i', strtotime($m['data_mov'])) : '—' ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr><td colspan="5" class="text-center text-muted">Sem pagamentos registados.</td></tr>
		<?php endif; ?>
	</table>

	<div class="col-md-12 mb-4">
		<a href="<?= $PAGE['first_link'] ?>"><button class="btn btn-primary">Primeira Pagina</button></a>
		<a href="<?= $PAGE['prev_link'] ?>"><button class="btn btn-primary">Anterior</button></a>
		<a href="<?= $PAGE['next_link'] ?>"><button class="btn btn-primary float-end">Proxima Pagina</button></a>
	</div>
</div>
