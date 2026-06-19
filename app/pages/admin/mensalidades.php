<?php
// ============================================================
//  Gestao de mensalidades (admin) - listagem
//  O processamento (registar/eliminar) e feito em mensalidades_ctr.php
// ============================================================

$erro = $erro ?? null;
$sucesso = $sucesso ?? null;

$total = query_row("SELECT COALESCE(SUM(valor),0) AS t, COUNT(*) AS c FROM mensalidades WHERE DATE_FORMAT(data_pagamento,'%Y-%m')=:m", ['m' => date('Y-m')]);

$limit = 12;
$offset = ($PAGE['page_number'] - 1) * $limit;
$rows = query(
	"SELECT m.*, u.nome_comp, u.tipo FROM mensalidades m
	 JOIN utentes u ON u.id_utente = m.id_utente
	 ORDER BY m.id_mensalidade DESC LIMIT $limit OFFSET $offset"
);
?>

<h4>Mensalidades</h4>

<?php if ($erro) : ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<?php if ($sucesso) : ?><div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div><?php endif; ?>

<div class="row mb-3">
	<div class="col-md-4">
		<div class="card text-bg-success"><div class="card-body">
			<h6>Recebido este mês (mensalidades)</h6>
			<h3><?= fmt_moeda($total['t'] ?? 0) ?></h3>
			<small><?= $total['c'] ?? 0 ?> pagamento(s)</small>
		</div></div>
	</div>
	<div class="col-md-8">
		<form method="POST" class="card card-body">
			<div class="row g-2 align-items-end">
				<div class="col-md-5">
					<label class="form-label">Nº de Utente</label>
					<input type="text" name="id_utente" class="form-control" placeholder="Ex: 1001221" required>
				</div>
				<div class="col-md-4">
					<label class="form-label">Mês</label>
					<input type="month" name="mes_ref" class="form-control" value="<?= date('Y-m') ?>">
				</div>
				<div class="col-md-3">
					<button class="btn btn-primary w-100"><i class="bi bi-plus-circle"></i> Registar</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-striped">
		<tr>
			<th>#</th>
			<th>Utente</th>
			<th>Tipo</th>
			<th>Mês</th>
			<th>Valor</th>
			<th>Data Pagamento</th>
			<th></th>
		</tr>
		<?php if (!empty($rows)) : ?>
			<?php foreach ($rows as $r) : ?>
				<tr>
					<td><?= $r['id_mensalidade'] ?></td>
					<td><?= htmlspecialchars($r['nome_comp']) ?> <small class="text-muted">(<?= $r['id_utente'] ?>)</small></td>
					<td class="text-capitalize"><?= htmlspecialchars(trim($r['tipo'])) ?></td>
					<td><?= $r['mes_ref'] ?></td>
					<td><?= fmt_moeda($r['valor']) ?></td>
					<td><?= date('d-m-Y H:i', strtotime($r['data_pagamento'])) ?></td>
					<td>
						<a href="<?= ROOT ?>/admin/mensalidades/delete/<?= $r['id_mensalidade'] ?>" onclick="return confirm('Eliminar este pagamento?')">
							<button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr><td colspan="7" class="text-center text-muted">Sem mensalidades registadas.</td></tr>
		<?php endif; ?>
	</table>

	<div class="col-md-12 mb-4">
		<a href="<?= $PAGE['first_link'] ?>"><button class="btn btn-primary">Primeira Pagina</button></a>
		<a href="<?= $PAGE['prev_link'] ?>"><button class="btn btn-primary">Anterior</button></a>
		<a href="<?= $PAGE['next_link'] ?>"><button class="btn btn-primary float-end">Proxima Pagina</button></a>
	</div>
</div>
