<?php
// ============================================================
//  Configuracoes do sistema (admin) - horario de funcionamento
//  O processamento e feito em configuracoes_ctr.php
// ============================================================

$erro = $erro ?? null;
$sucesso = $sucesso ?? null;
if (!empty($_GET['ok'])) {
	$sucesso = "Horário de funcionamento actualizado com sucesso.";
}

$abertura = park_abertura();
$fecho    = park_fecho();
?>

<h4><i class="bi bi-gear-wide-connected"></i> Configurações</h4>
<p class="text-muted">Defina o horário em que o parque está aberto. Fora deste período não são permitidas entradas e aplica-se a tarifa de parque fechado.</p>

<?php if ($erro) : ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<?php if ($sucesso) : ?><div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div><?php endif; ?>

<div class="row">
	<div class="col-md-6">
		<form method="POST" class="card card-body shadow-sm">
			<h5 class="mb-3">Horário de Funcionamento</h5>

			<div class="row g-3">
				<div class="col-6">
					<label for="park_abertura" class="form-label">Hora de Abertura</label>
					<select name="park_abertura" id="park_abertura" class="form-select">
						<?php for ($h = 0; $h <= 23; $h++) : ?>
							<option value="<?= $h ?>" <?= $h == $abertura ? 'selected' : '' ?>><?= sprintf('%02d:00', $h) ?></option>
						<?php endfor; ?>
					</select>
				</div>
				<div class="col-6">
					<label for="park_fecho" class="form-label">Hora de Fecho</label>
					<select name="park_fecho" id="park_fecho" class="form-select">
						<?php for ($h = 1; $h <= 24; $h++) : ?>
							<option value="<?= $h ?>" <?= $h == $fecho ? 'selected' : '' ?>><?= sprintf('%02d:00', $h) ?></option>
						<?php endfor; ?>
					</select>
				</div>
			</div>

			<div class="alert alert-info mt-3 mb-0">
				Horário actual: <strong><?= sprintf('%02d:00', $abertura) ?> às <?= sprintf('%02d:00', $fecho) ?></strong>
				— estado agora: <strong><?= parque_aberto() ? 'ABERTO' : 'FECHADO' ?></strong>.
			</div>

			<button class="btn btn-primary mt-3"><i class="bi bi-save"></i> Guardar Alterações</button>
		</form>
	</div>
</div>
