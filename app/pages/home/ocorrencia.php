<?php
// ============================================================
//  Actividade do parque
//  Viaturas actualmente dentro do parque e ultimos movimentos.
// ============================================================

$dentro = query(
	"SELECT r.*, u.nome_comp, u.tipo, v.nome AS vaga_nome, v.sector AS vaga_sector
	 FROM registos r
	 JOIN utentes u ON u.id_utente = r.id_utente
	 LEFT JOIN vagas v ON v.id_vaga = r.id_vaga
	 WHERE r.estado = 'dentro'
	 ORDER BY r.data_entrada ASC"
);

$ultimos = query(
	"SELECT r.*, u.nome_comp
	 FROM registos r JOIN utentes u ON u.id_utente = r.id_utente
	 WHERE r.estado = 'fora'
	 ORDER BY r.data_saida DESC LIMIT 10"
);
?>

<h3><i class="bi bi-activity"></i> Actividade do Parque</h3>

<div class="row mb-3">
	<div class="col-md-3">
		<div class="card text-bg-success"><div class="card-body"><h6>Vagas livres</h6><h2><?= vagas_livres() ?></h2></div></div>
	</div>
	<div class="col-md-3">
		<div class="card text-bg-danger"><div class="card-body"><h6>Vagas ocupadas</h6><h2><?= vagas_total() - vagas_livres() ?></h2></div></div>
	</div>
	<div class="col-md-3">
		<div class="card text-bg-primary"><div class="card-body"><h6>Viaturas dentro</h6><h2><?= is_array($dentro) ? count($dentro) : 0 ?></h2></div></div>
	</div>
	<div class="col-md-3">
		<div class="card <?= parque_aberto() ? 'text-bg-success' : 'text-bg-secondary' ?>"><div class="card-body"><h6>Estado</h6><h2><?= parque_aberto() ? 'ABERTO' : 'FECHADO' ?></h2></div></div>
	</div>
</div>

<h5>Viaturas dentro do parque</h5>
<div class="table-responsive mb-4">
	<table class="table table-striped">
		<tr>
			<th>Talão</th>
			<th>Nº Utente</th>
			<th>Nome</th>
			<th>Tipo</th>
			<th>Vaga</th>
			<th>Modalidade</th>
			<th>Entrada</th>
			<th>Permanência</th>
		</tr>
		<?php if (!empty($dentro)) : ?>
			<?php foreach ($dentro as $r) : ?>
				<?php
					$segs = time() - strtotime($r['data_entrada']);
					$h = floor($segs / 3600);
					$m = floor(($segs % 3600) / 60);
				?>
				<tr>
					<td><?= $r['id_registo'] ?></td>
					<td><?= $r['id_utente'] ?></td>
					<td><?= htmlspecialchars($r['nome_comp']) ?></td>
					<td class="text-capitalize"><?= htmlspecialchars(trim($r['tipo'])) ?></td>
					<td><?= $r['vaga_sector'] ?>-<?= $r['vaga_nome'] ?></td>
					<td><?= $r['tipo_pagamento'] == 'mensal' ? 'Mensalista' : 'Por hora' ?></td>
					<td><?= date('d-m-Y H:i', strtotime($r['data_entrada'])) ?></td>
					<td><?= $h ?>h <?= $m ?>m</td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr><td colspan="8" class="text-center text-muted">Nenhuma viatura dentro do parque.</td></tr>
		<?php endif; ?>
	</table>
</div>

<h5>Últimas saídas</h5>
<div class="table-responsive">
	<table class="table table-sm">
		<tr>
			<th>Talão</th>
			<th>Utente</th>
			<th>Entrada</th>
			<th>Saída</th>
			<th>Valor pago</th>
		</tr>
		<?php if (!empty($ultimos)) : ?>
			<?php foreach ($ultimos as $r) : ?>
				<tr>
					<td><?= $r['id_registo'] ?></td>
					<td><?= htmlspecialchars($r['nome_comp']) ?></td>
					<td><?= date('d-m-Y H:i', strtotime($r['data_entrada'])) ?></td>
					<td><?= date('d-m-Y H:i', strtotime($r['data_saida'])) ?></td>
					<td><?= fmt_moeda($r['valor_pago']) ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr><td colspan="5" class="text-center text-muted">Sem saídas registadas.</td></tr>
		<?php endif; ?>
	</table>
</div>
