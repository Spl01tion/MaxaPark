<?php
// ============================================================
//  Saida de viaturas do parque
//  Simula a insercao do talao na ranhura: le o QRCode (nº do registo),
//  calcula o valor a pagar, regista a saida e liberta a vaga.
// ============================================================

$erro   = null;
$recibo = null;

if (!empty($_POST['id_registo'])) {

	$id_registo = trim($_POST['id_registo']);

	// Procurar o registo em aberto correspondente ao talao
	$reg = query_row(
		"SELECT r.*, u.nome_comp, u.tipo, v.nome AS vaga_nome, v.sector AS vaga_sector
		 FROM registos r
		 JOIN utentes u ON u.id_utente = r.id_utente
		 LEFT JOIN vagas v ON v.id_vaga = r.id_vaga
		 WHERE r.id_registo = :id AND r.estado = 'dentro' LIMIT 1",
		['id' => $id_registo]
	);

	if (!$reg) {
		$erro = "Talão inválido ou viatura já saiu (registo nº " . htmlspecialchars($id_registo) . ").";
	} else {
		$agora = date('Y-m-d H:i:s');
		$mensalista = ($reg['tipo_pagamento'] === 'mensal');

		$calc = calcular_pagamento($reg['data_entrada'], $agora, $reg['tipo'], $mensalista);

		// Registar a saida
		query(
			"UPDATE registos SET data_saida = :saida, valor_pago = :valor, estado = 'fora'
			 WHERE id_registo = :id LIMIT 1",
			['saida' => $agora, 'valor' => $calc['valor'], 'id' => $id_registo]
		);

		// Libertar a vaga (actualiza o painel de entrada)
		if (!empty($reg['id_vaga'])) {
			query("UPDATE vagas SET estado = 'livre' WHERE id_vaga = :id LIMIT 1", ['id' => $reg['id_vaga']]);
		}

		$recibo = [
			'reg'   => $reg,
			'saida' => $agora,
			'calc'  => $calc,
		];
	}
}
?>

<div class="row">
	<div class="col-md-5">
		<h3><i class="bi bi-box-arrow-up"></i> Saída de Viaturas</h3>
		<p class="text-muted">Insira o talão (nº do registo) ou leia o QRCode para registar a saída.</p>

		<div class="mb-3">
			<span class="badge bg-primary">Vagas livres: <?= vagas_livres() ?>/<?= vagas_total() ?></span>
		</div>

		<?php if ($erro) : ?>
			<div class="alert alert-danger"><?= $erro ?></div>
		<?php endif; ?>

		<form method="POST" id="saidaForm" class="card card-body shadow-sm">
			<label for="id_registo" class="form-label">Nº do Talão</label>
			<div class="input-group">
				<span class="input-group-text"><i class="bi bi-ticket-detailed"></i></span>
				<input type="text" class="form-control form-control-lg" id="id_registo" name="id_registo"
					placeholder="Ex: 1" autofocus required>
			</div>
			<button type="submit" class="btn btn-danger btn-lg mt-3">
				<i class="bi bi-box-arrow-up"></i> Registar Saída
			</button>
			<button type="button" id="btnScan" class="btn btn-outline-secondary mt-2" onclick="toggleScan()">
				<i class="bi bi-qr-code-scan"></i> Ler talão com câmara
			</button>
		</form>

		<div id="reader" class="mt-3 d-none" style="width:100%;max-width:340px;"></div>
		<div id="scanMsg" class="form-text mt-1"></div>
	</div>

	<div class="col-md-7">
		<?php if ($recibo) : ?>
			<?php $reg = $recibo['reg']; $calc = $recibo['calc']; ?>
			<div id="recibo" class="card mx-auto shadow" style="max-width:360px;border-top:6px solid #dc3545;">
				<div class="card-body text-center">
					<img src="<?= ROOT ?>/assets/imgs/MaxaP.png" width="60" alt="MaxaPark">
					<h4 class="mt-2" style="color:#9C1980;">MaxaPark</h4>
					<p class="mb-1 fw-bold">TALÃO DE SAÍDA / RECIBO</p>
					<hr>
					<div class="text-start px-2">
						<p class="mb-1"><strong>Nº Utente:</strong> <?= $reg['id_utente'] ?></p>
						<p class="mb-1"><strong>Nome:</strong> <?= htmlspecialchars($reg['nome_comp']) ?></p>
						<p class="mb-1"><strong>Modalidade:</strong> <?= $reg['tipo_pagamento'] == 'mensal' ? 'Mensalista' : 'Por hora' ?></p>
						<p class="mb-1"><strong>Entrada:</strong> <?= date('d-m-Y H:i:s', strtotime($reg['data_entrada'])) ?></p>
						<p class="mb-1"><strong>Saída:</strong> <?= date('d-m-Y H:i:s', strtotime($recibo['saida'])) ?></p>
						<hr>
						<p class="mb-1 small text-muted"><?= htmlspecialchars($calc['detalhe']) ?></p>
						<p class="h4 mt-2 <?= $calc['valor'] > 0 ? 'text-danger' : 'text-success' ?>">
							A PAGAR: <?= fmt_moeda($calc['valor']) ?>
						</p>
					</div>
				</div>
				<div class="card-footer text-center bg-white border-0">
					<button class="btn btn-primary" onclick="imprimirRecibo()"><i class="bi bi-printer"></i> Imprimir Recibo</button>
				</div>
			</div>
		<?php else : ?>
			<div class="card card-body h-100 d-flex align-items-center justify-content-center text-muted">
				<div class="text-center">
					<i class="bi bi-receipt" style="font-size:3rem;"></i>
					<p>O recibo de saída será apresentado aqui.</p>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
	var html5QrCode = null;
	var scanning = false;

	function toggleScan() {
		scanning ? stopScan() : startScan();
	}

	function startScan() {
		var reader = document.getElementById('reader');
		var msg = document.getElementById('scanMsg');
		var btn = document.getElementById('btnScan');
		reader.classList.remove('d-none');
		msg.textContent = 'A iniciar câmara... aponte para o QRCode do talão.';

		html5QrCode = new Html5Qrcode('reader');
		html5QrCode.start(
			{ facingMode: 'environment' },
			{ fps: 10, qrbox: { width: 220, height: 220 } },
			function (decodedText) {
				document.getElementById('id_registo').value = decodedText.trim();
				msg.textContent = 'Talão lido: ' + decodedText.trim();
				stopScan(function () { document.getElementById('saidaForm').submit(); });
			},
			function () { /* ignora frames sem QR */ }
		).then(function () {
			scanning = true;
			btn.innerHTML = '<i class="bi bi-x-circle"></i> Parar câmara';
		}).catch(function (err) {
			msg.textContent = 'Não foi possível aceder à câmara: ' + err + '. Introduza o nº manualmente.';
			reader.classList.add('d-none');
		});
	}

	function stopScan(callback) {
		var reader = document.getElementById('reader');
		var btn = document.getElementById('btnScan');
		if (html5QrCode && scanning) {
			html5QrCode.stop().then(function () {
				html5QrCode.clear();
				scanning = false;
				reader.classList.add('d-none');
				btn.innerHTML = '<i class="bi bi-qr-code-scan"></i> Ler talão com câmara';
				if (typeof callback === 'function') callback();
			}).catch(function () { if (typeof callback === 'function') callback(); });
		} else if (typeof callback === 'function') {
			callback();
		}
	}

	function imprimirRecibo() {
		var conteudo = document.getElementById('recibo').outerHTML;
		var win = window.open('', '', 'width=400,height=600');
		win.document.write('<html><head><title>Recibo</title>');
		win.document.write('<link href="<?= ROOT ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"></head><body>');
		win.document.write(conteudo);
		win.document.write('</body></html>');
		win.document.close();
		setTimeout(function () { win.print(); win.close(); }, 600);
	}
</script>
