<?php
// ============================================================
//  MaxaPark - Script de instalacao da base de dados
//  Acede a /setup para criar/actualizar as tabelas e dados iniciais.
// ============================================================

function ddl($sql)
{
	$string = "mysql:hostname=" . DBHOST . ";dbname=" . DBNAME;
	$con = new PDO($string, DBUSER, DBPASS);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$con->exec($sql);
}

$log = [];

// ---- Garantir que a base de dados existe ----
try {
	$con = new PDO("mysql:hostname=" . DBHOST, DBUSER, DBPASS);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$con->exec("CREATE DATABASE IF NOT EXISTS " . DBNAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
	$log[] = "Base de dados '" . DBNAME . "' pronta.";
} catch (Exception $e) {
	die("Erro ao criar a base de dados: " . $e->getMessage());
}

// ---- Tabelas ----
try {
	ddl("CREATE TABLE IF NOT EXISTS users(
		id_user INT AUTO_INCREMENT PRIMARY KEY,
		nome VARCHAR(100) NOT NULL,
		apelido VARCHAR(100) NOT NULL,
		username VARCHAR(50) UNIQUE NOT NULL,
		email VARCHAR(100) UNIQUE NOT NULL,
		password VARCHAR(255) NOT NULL,
		image VARCHAR(1024) NULL,
		data DATETIME DEFAULT CURRENT_TIMESTAMP,
		role VARCHAR(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	ddl("CREATE TABLE IF NOT EXISTS vagas(
		id_vaga INT AUTO_INCREMENT PRIMARY KEY,
		nome VARCHAR(3) NOT NULL,
		sector VARCHAR(3) NOT NULL,
		estado VARCHAR(8) NOT NULL DEFAULT 'livre'
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	ddl("CREATE TABLE IF NOT EXISTS utentes(
		id_utente INT AUTO_INCREMENT PRIMARY KEY,
		nome_comp VARCHAR(150) NOT NULL,
		bi VARCHAR(13) UNIQUE NOT NULL,
		data_emi DATE NULL,
		data_exp DATE NULL,
		contacto NUMERIC(9,0) UNIQUE NOT NULL,
		email VARCHAR(100) UNIQUE NOT NULL,
		tipo VARCHAR(50) NOT NULL,
		bi_pdf VARCHAR(1024) NULL,
		qr_utente VARCHAR(1024) NULL,
		data DATETIME DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT = 1001221");

	// Registos de entrada/saida (sessoes de estacionamento)
	ddl("CREATE TABLE IF NOT EXISTS registos(
		id_registo INT AUTO_INCREMENT PRIMARY KEY,
		id_utente INT NOT NULL,
		id_vaga INT NULL,
		data_entrada DATETIME NOT NULL,
		data_saida DATETIME NULL,
		tipo_pagamento VARCHAR(10) NOT NULL DEFAULT 'hora',
		valor_pago DECIMAL(10,2) NULL,
		estado VARCHAR(10) NOT NULL DEFAULT 'dentro',
		data DATETIME DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	// Mensalidades pagas
	ddl("CREATE TABLE IF NOT EXISTS mensalidades(
		id_mensalidade INT AUTO_INCREMENT PRIMARY KEY,
		id_utente INT NOT NULL,
		mes_ref VARCHAR(7) NOT NULL,
		valor DECIMAL(10,2) NOT NULL,
		data_pagamento DATETIME DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY uq_utente_mes (id_utente, mes_ref)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	// Configuracoes do sistema (chave/valor) - ex: horario de funcionamento
	ddl("CREATE TABLE IF NOT EXISTS configuracoes(
		chave VARCHAR(50) PRIMARY KEY,
		valor VARCHAR(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	$log[] = "Tabelas criadas/verificadas (users, vagas, utentes, registos, mensalidades, configuracoes).";
} catch (Exception $e) {
	die("Erro ao criar tabelas: " . $e->getMessage());
}

// ---- Dados iniciais ----

// Utilizadores (admin + funcionario)
if (!query("SELECT id_user FROM users LIMIT 1")) {
	$admin = password_hash('admin123', PASSWORD_DEFAULT);
	$func  = password_hash('func123', PASSWORD_DEFAULT);
	query("INSERT INTO users (nome,apelido,username,email,password,role) VALUES
		('Administrador','Sistema','admin','admin@maxapark.mz',:p1,'admin'),
		('Funcionario','Parque','func','func@maxapark.mz',:p2,'user')",
		['p1' => $admin, 'p2' => $func]);
	$log[] = "Utilizadores criados: admin/admin123 (admin) e func/func123 (funcionario).";
} else {
	$log[] = "Utilizadores ja existem (nao alterado).";
}

// Vagas (2 sectores x 10 lugares)
if (!query("SELECT id_vaga FROM vagas LIMIT 1")) {
	foreach (['A', 'B'] as $sector) {
		for ($i = 1; $i <= 10; $i++) {
			query("INSERT INTO vagas (nome, sector, estado) VALUES (:nome, :sector, 'livre')",
				['nome' => $sector . $i, 'sector' => $sector]);
		}
	}
	$log[] = "20 vagas criadas (sectores A e B).";
} else {
	$log[] = "Vagas ja existem (nao alterado).";
}

// Configuracoes por omissao (horario de funcionamento)
$defaults = [
	'park_abertura' => PARK_ABERTURA,
	'park_fecho'    => PARK_FECHO,
];
foreach ($defaults as $chave => $valor) {
	query("INSERT IGNORE INTO configuracoes (chave, valor) VALUES (:c, :v)", ['c' => $chave, 'v' => $valor]);
}
$log[] = "Configuracoes por omissao verificadas (horario " . PARK_ABERTURA . "h-" . PARK_FECHO . "h).";

// Utentes de exemplo
if (!query("SELECT id_utente FROM utentes LIMIT 1")) {
	query("INSERT INTO utentes (nome_comp, bi, data_emi, data_exp, contacto, email, tipo) VALUES
		('Joao Mucavele','110100000000A','2020-01-10','2030-01-10',840000001,'joao@example.mz','Estudante'),
		('Maria Sitoe','110100000001B','2019-05-20','2029-05-20',840000002,'maria@example.mz','Outro')");
	$log[] = "2 utentes de exemplo criados.";
} else {
	$log[] = "Utentes ja existem (nao alterado).";
}
?>
<!doctype html>
<html lang="pt">
<head>
	<meta charset="utf-8">
	<title>Setup - MaxaPark</title>
	<link href="<?= ROOT ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
	<div class="container py-5" style="max-width:680px;">
		<div class="card shadow-sm">
			<div class="card-body">
				<h1 class="h3 mb-3">Instalacao do MaxaPark</h1>
				<ul class="list-group mb-3">
					<?php foreach ($log as $linha) : ?>
						<li class="list-group-item"><i class="bi bi-check-circle text-success"></i> <?= htmlspecialchars($linha) ?></li>
					<?php endforeach; ?>
				</ul>
				<div class="alert alert-info">
					Credenciais de acesso: <strong>admin / admin123</strong> (administrador) e
					<strong>func / func123</strong> (funcionario).
				</div>
				<a href="<?= ROOT ?>/login" class="btn btn-primary">Ir para o Login</a>
			</div>
		</div>
	</div>
</body>
</html>
