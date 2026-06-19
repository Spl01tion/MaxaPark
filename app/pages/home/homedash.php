<?php
// ============================================================
//  Painel inicial do funcionario - resumo + acessos rapidos
// ============================================================
$dentro = query_row("SELECT COUNT(*) AS t FROM registos WHERE estado='dentro'")['t'] ?? 0;
$utentes = query_row("SELECT COUNT(*) AS t FROM utentes")['t'] ?? 0;
$aberto = parque_aberto();
?>

<div class="p-4 mb-4 rounded-4 bg-maxa shadow-sm">
	<div class="d-flex align-items-center justify-content-between flex-wrap">
		<div>
			<h2 class="mb-1">Bem-vindo ao MaxaPark</h2>
			<p class="mb-0 opacity-75">Gestão do parque de estacionamento — Município de Machaquene</p>
		</div>
		<span class="badge fs-6 <?= $aberto ? 'bg-success' : 'bg-danger' ?> px-3 py-2">
			<i class="bi bi-clock"></i> Parque <?= $aberto ? 'ABERTO' : 'FECHADO' ?>
		</span>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-6 col-lg-3">
		<div class="card lift text-center"><div class="card-body">
			<i class="bi bi-p-square-fill text-success fs-2"></i>
			<h3 class="mt-2 mb-0"><?= vagas_livres() ?>/<?= vagas_total() ?></h3>
			<small class="text-body-secondary">Vagas livres</small>
		</div></div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="card lift text-center"><div class="card-body">
			<i class="bi bi-car-front-fill text-primary fs-2"></i>
			<h3 class="mt-2 mb-0"><?= $dentro ?></h3>
			<small class="text-body-secondary">Viaturas dentro</small>
		</div></div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="card lift text-center"><div class="card-body">
			<i class="bi bi-person-badge-fill text-info fs-2"></i>
			<h3 class="mt-2 mb-0"><?= $utentes ?></h3>
			<small class="text-body-secondary">Utentes</small>
		</div></div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="card lift text-center"><div class="card-body">
			<i class="bi bi-clock-history text-warning fs-2"></i>
			<h3 class="mt-2 mb-0"><?= park_abertura() ?>h–<?= park_fecho() ?>h</h3>
			<small class="text-body-secondary">Horário</small>
		</div></div>
	</div>
</div>

<h5 class="mb-3">Acessos rápidos</h5>
<div class="row g-3">
	<?php
	$acoes = [
		['entrada', 'box-arrow-in-down', 'Entrada', 'Registar entrada de viatura', 'success'],
		['saida', 'box-arrow-up', 'Saída', 'Registar saída e pagamento', 'danger'],
		['pagamentos', 'cash-coin', 'Pagamentos', 'Pagar mensalidade', 'primary'],
		['reg_utente', 'person-plus-fill', 'Registar Utente', 'Cadastrar novo utente', 'primary'],
		['imprimir', 'printer-fill', 'Imprimir Cartão', 'Emitir cartão de utente', 'primary'],
		['ocorrencia', 'activity', 'Actividade', 'Ver viaturas no parque', 'primary'],
	];
	foreach ($acoes as $a) :
	?>
		<div class="col-6 col-md-4">
			<a href="<?= ROOT ?>/home/<?= $a[0] ?>" class="text-decoration-none">
				<div class="card lift h-100"><div class="card-body d-flex align-items-center gap-3">
					<span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-<?= $a[4] ?> text-white" style="width:48px;height:48px;">
						<i class="bi bi-<?= $a[1] ?> fs-4"></i>
					</span>
					<div>
						<div class="fw-semibold text-dark"><?= $a[2] ?></div>
						<small class="text-body-secondary"><?= $a[3] ?></small>
					</div>
				</div></div>
			</a>
		</div>
	<?php endforeach; ?>
</div>
