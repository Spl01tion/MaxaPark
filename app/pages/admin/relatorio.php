<?php
// ============================================================
//  Relatorios contabilisticos (admin)
//  Receitas e utilizacao do parque por periodo.
// ============================================================

$de  = $_GET['de']  ?? date('Y-m-01');
$ate = $_GET['ate'] ?? date('Y-m-d');

// Receita por hora (saidas no periodo)
$rec_hora = query_row(
	"SELECT COALESCE(SUM(valor_pago),0) AS total, COUNT(*) AS qtd
	 FROM registos WHERE estado='fora' AND DATE(data_saida) BETWEEN :de AND :ate",
	['de' => $de, 'ate' => $ate]
);

// Receita de mensalidades no periodo
$rec_mens = query_row(
	"SELECT COALESCE(SUM(valor),0) AS total, COUNT(*) AS qtd
	 FROM mensalidades WHERE DATE(data_pagamento) BETWEEN :de AND :ate",
	['de' => $de, 'ate' => $ate]
);

$entradas = query_row(
	"SELECT COUNT(*) AS qtd FROM registos WHERE DATE(data_entrada) BETWEEN :de AND :ate",
	['de' => $de, 'ate' => $ate]
)['qtd'] ?? 0;

$total_receita = ($rec_hora['total'] ?? 0) + ($rec_mens['total'] ?? 0);

// Receita por dia (grafico simples em tabela)
$por_dia = query(
	"SELECT DATE(data_saida) AS dia, SUM(valor_pago) AS total, COUNT(*) AS qtd
	 FROM registos WHERE estado='fora' AND DATE(data_saida) BETWEEN :de AND :ate
	 GROUP BY DATE(data_saida) ORDER BY dia DESC",
	['de' => $de, 'ate' => $ate]
);

// Utentes com mais utilizacao no periodo
$top = query(
	"SELECT u.nome_comp, u.tipo, COUNT(*) AS visitas, COALESCE(SUM(r.valor_pago),0) AS gasto
	 FROM registos r JOIN utentes u ON u.id_utente=r.id_utente
	 WHERE r.estado='fora' AND DATE(r.data_saida) BETWEEN :de AND :ate
	 GROUP BY r.id_utente ORDER BY visitas DESC LIMIT 10",
	['de' => $de, 'ate' => $ate]
);
?>

<div class="d-flex justify-content-between align-items-center">
	<h4><i class="bi bi-file-earmark-spreadsheet"></i> Relatório Contabilístico</h4>
	<button class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir</button>
</div>

<form method="GET" action="<?= ROOT ?>/admin/relatorio" class="row g-2 align-items-end my-2">
	<div class="col-md-3">
		<label class="form-label">De</label>
		<input type="date" name="de" class="form-control" value="<?= htmlspecialchars($de) ?>">
	</div>
	<div class="col-md-3">
		<label class="form-label">Até</label>
		<input type="date" name="ate" class="form-control" value="<?= htmlspecialchars($ate) ?>">
	</div>
	<div class="col-md-2">
		<button class="btn btn-primary w-100">Filtrar</button>
	</div>
</form>

<p class="text-muted">Período: <strong><?= date('d-m-Y', strtotime($de)) ?></strong> a <strong><?= date('d-m-Y', strtotime($ate)) ?></strong></p>

<div class="row g-3 mb-4">
	<div class="col-md-3">
		<div class="card border-success"><div class="card-body">
			<h6 class="text-muted">Receita total</h6><h3 class="text-success"><?= fmt_moeda($total_receita) ?></h3>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card"><div class="card-body">
			<h6 class="text-muted">Receita por hora</h6><h4><?= fmt_moeda($rec_hora['total'] ?? 0) ?></h4>
			<small class="text-muted"><?= $rec_hora['qtd'] ?? 0 ?> saída(s)</small>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card"><div class="card-body">
			<h6 class="text-muted">Receita mensalidades</h6><h4><?= fmt_moeda($rec_mens['total'] ?? 0) ?></h4>
			<small class="text-muted"><?= $rec_mens['qtd'] ?? 0 ?> pagamento(s)</small>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card"><div class="card-body">
			<h6 class="text-muted">Entradas no período</h6><h4><?= $entradas ?></h4>
		</div></div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<h5>Receita por dia (estacionamento)</h5>
		<div class="table-responsive">
			<table class="table table-sm">
				<tr><th>Dia</th><th>Saídas</th><th>Receita</th></tr>
				<?php if (!empty($por_dia)) : ?>
					<?php foreach ($por_dia as $d) : ?>
						<tr>
							<td><?= date('d-m-Y', strtotime($d['dia'])) ?></td>
							<td><?= $d['qtd'] ?></td>
							<td><?= fmt_moeda($d['total']) ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr><td colspan="3" class="text-center text-muted">Sem dados no período.</td></tr>
				<?php endif; ?>
			</table>
		</div>
	</div>

	<div class="col-md-6">
		<h5>Utentes mais frequentes</h5>
		<div class="table-responsive">
			<table class="table table-sm">
				<tr><th>Utente</th><th>Tipo</th><th>Visitas</th><th>Gasto</th></tr>
				<?php if (!empty($top)) : ?>
					<?php foreach ($top as $t) : ?>
						<tr>
							<td><?= htmlspecialchars($t['nome_comp']) ?></td>
							<td class="text-capitalize"><?= htmlspecialchars(trim($t['tipo'])) ?></td>
							<td><?= $t['visitas'] ?></td>
							<td><?= fmt_moeda($t['gasto']) ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr><td colspan="4" class="text-center text-muted">Sem dados no período.</td></tr>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>
