<?php
// ============================================================
//  Painel de administracao - resumo geral
// ============================================================

$hoje = date('Y-m-d');
$mes  = date('Y-m');

$tot_utentes = query_row("SELECT COUNT(*) AS t FROM utentes")['t'] ?? 0;
$tot_users   = query_row("SELECT COUNT(*) AS t FROM users")['t'] ?? 0;
$dentro      = query_row("SELECT COUNT(*) AS t FROM registos WHERE estado='dentro'")['t'] ?? 0;

// Receitas (estacionamento por hora + mensalidades)
$rec_hora_hoje = query_row("SELECT COALESCE(SUM(valor_pago),0) AS t FROM registos WHERE estado='fora' AND DATE(data_saida)=:d", ['d' => $hoje])['t'] ?? 0;
$rec_mens_hoje = query_row("SELECT COALESCE(SUM(valor),0) AS t FROM mensalidades WHERE DATE(data_pagamento)=:d", ['d' => $hoje])['t'] ?? 0;
$rec_hora_mes  = query_row("SELECT COALESCE(SUM(valor_pago),0) AS t FROM registos WHERE estado='fora' AND DATE_FORMAT(data_saida,'%Y-%m')=:m", ['m' => $mes])['t'] ?? 0;
$rec_mens_mes  = query_row("SELECT COALESCE(SUM(valor),0) AS t FROM mensalidades WHERE DATE_FORMAT(data_pagamento,'%Y-%m')=:m", ['m' => $mes])['t'] ?? 0;

$receita_hoje = $rec_hora_hoje + $rec_mens_hoje;
$receita_mes  = $rec_hora_mes + $rec_mens_mes;

$entradas_hoje = query_row("SELECT COUNT(*) AS t FROM registos WHERE DATE(data_entrada)=:d", ['d' => $hoje])['t'] ?? 0;
$saidas_hoje   = query_row("SELECT COUNT(*) AS t FROM registos WHERE estado='fora' AND DATE(data_saida)=:d", ['d' => $hoje])['t'] ?? 0;
?>

<div class="row g-3 mb-4">
	<div class="col-md-3">
		<div class="card text-bg-success shadow-sm"><div class="card-body">
			<h6><i class="bi bi-p-square"></i> Vagas livres</h6><h2><?= vagas_livres() ?>/<?= vagas_total() ?></h2>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card text-bg-primary shadow-sm"><div class="card-body">
			<h6><i class="bi bi-car-front"></i> Viaturas dentro</h6><h2><?= $dentro ?></h2>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card text-bg-info shadow-sm"><div class="card-body">
			<h6><i class="bi bi-person-badge"></i> Utentes</h6><h2><?= $tot_utentes ?></h2>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card text-bg-secondary shadow-sm"><div class="card-body">
			<h6><i class="bi bi-people"></i> Funcionários</h6><h2><?= $tot_users ?></h2>
		</div></div>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-md-3">
		<div class="card border-success shadow-sm"><div class="card-body">
			<h6 class="text-muted">Receita de hoje</h6><h3 class="text-success"><?= fmt_moeda($receita_hoje) ?></h3>
			<small class="text-muted">Horas: <?= fmt_moeda($rec_hora_hoje) ?> | Mensal: <?= fmt_moeda($rec_mens_hoje) ?></small>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card border-primary shadow-sm"><div class="card-body">
			<h6 class="text-muted">Receita do mês</h6><h3 class="text-primary"><?= fmt_moeda($receita_mes) ?></h3>
			<small class="text-muted">Horas: <?= fmt_moeda($rec_hora_mes) ?> | Mensal: <?= fmt_moeda($rec_mens_mes) ?></small>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card shadow-sm"><div class="card-body">
			<h6 class="text-muted">Entradas hoje</h6><h3><?= $entradas_hoje ?></h3>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card shadow-sm"><div class="card-body">
			<h6 class="text-muted">Saídas hoje</h6><h3><?= $saidas_hoje ?></h3>
		</div></div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<h5>Movimentos recentes</h5>
		<?php
		$recentes = query(
			"SELECT r.*, u.nome_comp FROM registos r JOIN utentes u ON u.id_utente=r.id_utente
			 ORDER BY r.id_registo DESC LIMIT 10"
		);
		?>
		<div class="table-responsive">
			<table class="table table-sm">
				<tr><th>Talão</th><th>Utente</th><th>Entrada</th><th>Saída</th><th>Estado</th><th>Valor</th></tr>
				<?php if (!empty($recentes)) : ?>
					<?php foreach ($recentes as $r) : ?>
						<tr>
							<td><?= $r['id_registo'] ?></td>
							<td><?= htmlspecialchars($r['nome_comp']) ?></td>
							<td><?= date('d-m-Y H:i', strtotime($r['data_entrada'])) ?></td>
							<td><?= $r['data_saida'] ? date('d-m-Y H:i', strtotime($r['data_saida'])) : '—' ?></td>
							<td><span class="badge <?= $r['estado'] == 'dentro' ? 'bg-primary' : 'bg-secondary' ?>"><?= $r['estado'] ?></span></td>
							<td><?= $r['valor_pago'] !== null ? fmt_moeda($r['valor_pago']) : '—' ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr><td colspan="6" class="text-center text-muted">Sem movimentos.</td></tr>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>
