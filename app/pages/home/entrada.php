<?php
// ============================================================
//  Entrada de viaturas no parque
//  Simula a passagem do cartao magnetico na ranhura da maquina.
//  Regista data/hora de entrada, atribui uma vaga e emite o talao com QRCode.
// ============================================================

$erro   = null;
$talao  = null;

if (!empty($_POST['id_utente'])) {

	$id_utente = trim($_POST['id_utente']);

	// 1. Validar utente
	$utente = query_row("SELECT * FROM utentes WHERE id_utente = :id LIMIT 1", ['id' => $id_utente]);

	if (!$utente) {
		$erro = "Cartao/Nº de utente inválido. Utente não encontrado.";
	} elseif (!parque_aberto()) {
		$erro = "O parque encontra-se FECHADO (horário: " . park_abertura() . "h às " . park_fecho() . "h). Entrada não permitida.";
	} else {
		// 2. Verificar se ja se encontra dentro do parque
		$aberto = query_row(
			"SELECT id_registo FROM registos WHERE id_utente = :id AND estado = 'dentro' LIMIT 1",
			['id' => $id_utente]
		);

		if ($aberto) {
			$erro = "Este utente já possui uma viatura dentro do parque (registo nº {$aberto['id_registo']}).";
		} else {
			// 3. Procurar uma vaga livre
			$vaga = query_row("SELECT * FROM vagas WHERE estado = 'livre' ORDER BY id_vaga ASC LIMIT 1");

			if (!$vaga) {
				$erro = "Não existem vagas disponíveis. O parque está lotado.";
			} else {
				// 4. Registar a entrada
				$agora = date('Y-m-d H:i:s');
				$mensalista = tem_mensalidade_ativa($id_utente);
				$tipo_pag = $mensalista ? 'mensal' : 'hora';

				query(
					"INSERT INTO registos (id_utente, id_vaga, data_entrada, tipo_pagamento, estado)
					 VALUES (:id_utente, :id_vaga, :data_entrada, :tipo_pagamento, 'dentro')",
					[
						'id_utente'      => $id_utente,
						'id_vaga'        => $vaga['id_vaga'],
						'data_entrada'   => $agora,
						'tipo_pagamento' => $tipo_pag,
					]
				);

				// Obter o id do registo recem criado
				$novo = query_row(
					"SELECT id_registo FROM registos WHERE id_utente = :id AND estado = 'dentro' ORDER BY id_registo DESC LIMIT 1",
					['id' => $id_utente]
				);
				$id_registo = $novo['id_registo'];

				// Ocupar a vaga
				query("UPDATE vagas SET estado = 'ocupado' WHERE id_vaga = :id LIMIT 1", ['id' => $vaga['id_vaga']]);

				// 5. Gerar o QRCode do talao (codifica o nº do registo para leitura na saida)
				$dir = 'uploads/qr_talao/';
				if (!file_exists($dir)) {
					mkdir($dir, 0777, true);
				}
				$qr_file = $dir . 'talao_' . $id_registo . '.png';
				QRcode::png((string)$id_registo, $qr_file, 'H', 4, 4);

				$talao = [
					'id_registo' => $id_registo,
					'utente'     => $utente,
					'vaga'       => $vaga,
					'data'       => $agora,
					'qr'         => $qr_file,
					'tipo_pag'   => $tipo_pag,
				];
			}
		}
	}
}
?>

<div class="row">
	<div class="col-md-5">
		<h3><i class="bi bi-box-arrow-in-down"></i> Entrada de Viaturas</h3>
		<p class="text-muted">Passe o cartão na ranhura (insira o nº de utente) para registar a entrada.</p>

		<div class="mb-3">
			<?php if (parque_aberto()) : ?>
				<span class="badge bg-success">Parque ABERTO</span>
			<?php else : ?>
				<span class="badge bg-danger">Parque FECHADO</span>
			<?php endif; ?>
			<span class="badge bg-primary">Vagas livres: <?= vagas_livres() ?>/<?= vagas_total() ?></span>
		</div>

		<?php if ($erro) : ?>
			<div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
		<?php endif; ?>

		<form method="POST" id="entradaForm" class="card card-body shadow-sm">
			<label for="id_utente" class="form-label">Nº de Utente (cartão)</label>
			<div class="input-group">
				<span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
				<input type="text" class="form-control form-control-lg" id="id_utente" name="id_utente"
					placeholder="Ex: 1001221" autofocus required>
			</div>
			<button type="submit" class="btn btn-success btn-lg mt-3">
				<i class="bi bi-box-arrow-in-down"></i> Registar Entrada
			</button>
			<button type="button" id="btnScan" class="btn btn-outline-secondary mt-2" onclick="toggleScan()">
				<i class="bi bi-qr-code-scan"></i> Ler cartão com câmara
			</button>
		</form>

		<div id="reader" class="mt-3 d-none" style="width:100%;max-width:340px;"></div>
		<div id="scanMsg" class="form-text mt-1"></div>
	</div>

	<div class="col-md-7">
		<?php if ($talao) : ?>
			<div id="talao" class="card mx-auto shadow" style="max-width:360px;border-top:6px solid #9C1980;">
				<div class="card-body text-center">
					<img src="<?= ROOT ?>/assets/imgs/MaxaP.png" width="60" alt="MaxaPark">
					<h4 class="mt-2" style="color:#9C1980;">MaxaPark</h4>
					<p class="mb-1 fw-bold">TALÃO DE ENTRADA</p>
					<hr>
					<div class="text-start px-2">
						<p class="mb-1"><strong>Nº Utente:</strong> <?= $talao['utente']['id_utente'] ?></p>
						<p class="mb-1"><strong>Nome:</strong> <?= htmlspecialchars($talao['utente']['nome_comp']) ?></p>
						<p class="mb-1"><strong>Data:</strong> <?= date('d-m-Y', strtotime($talao['data'])) ?></p>
						<p class="mb-1"><strong>Hora de Entrada:</strong> <?= date('H:i:s', strtotime($talao['data'])) ?></p>
						<p class="mb-1"><strong>Vaga:</strong> <?= $talao['vaga']['sector'] ?> - <?= $talao['vaga']['nome'] ?></p>
						<p class="mb-1"><strong>Modalidade:</strong> <?= $talao['tipo_pag'] == 'mensal' ? 'Mensalista' : 'Por hora' ?></p>
					</div>
					<div class="my-2">
						<img src="<?= ROOT ?>/<?= $talao['qr'] ?>" alt="QRCode" width="150">
					</div>
					<small class="text-muted">Talão nº <?= $talao['id_registo'] ?> — guarde para a saída.</small>
				</div>
				<div class="card-footer text-center bg-white border-0">
					<button class="btn btn-primary" onclick="imprimirTalao()"><i class="bi bi-printer"></i> Imprimir Talão</button>
				</div>
			</div>
		<?php else : ?>
			<div class="card card-body h-100 d-flex align-items-center justify-content-center text-muted">
				<div class="text-center">
					<i class="bi bi-ticket-perforated" style="font-size:3rem;"></i>
					<p>O talão de entrada será apresentado aqui.</p>
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
		msg.textContent = 'A iniciar câmara... aponte para o QRCode do cartão.';

		html5QrCode = new Html5Qrcode('reader');
		html5QrCode.start(
			{ facingMode: 'environment' },
			{ fps: 10, qrbox: { width: 220, height: 220 } },
			function (decodedText) {
				// Leitura efectuada: preenche e submete
				document.getElementById('id_utente').value = decodedText.trim();
				msg.textContent = 'Cartão lido: ' + decodedText.trim();
				stopScan(function () { document.getElementById('entradaForm').submit(); });
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
				btn.innerHTML = '<i class="bi bi-qr-code-scan"></i> Ler cartão com câmara';
				if (typeof callback === 'function') callback();
			}).catch(function () { if (typeof callback === 'function') callback(); });
		} else if (typeof callback === 'function') {
			callback();
		}
	}

	function imprimirTalao() {
		var conteudo = document.getElementById('talao').outerHTML;
		var win = window.open('', '', 'width=400,height=600');
		win.document.write('<html><head><title>Talão</title>');
		win.document.write('<link href="<?= ROOT ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"></head><body>');
		win.document.write(conteudo);
		win.document.write('</body></html>');
		win.document.close();
		setTimeout(function () { win.print(); win.close(); }, 600);
	}
</script>
