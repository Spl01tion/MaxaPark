<?php

function query(string $query, array $data = [])
{

	$string = "mysql:hostname=".DBHOST.";dbname=". DBNAME;
	$con = new PDO($string, DBUSER, DBPASS);

	$stm = $con->prepare($query);
	$stm->execute($data);

	$result = $stm->fetchAll(PDO::FETCH_ASSOC);
	if(is_array($result) && !empty($result))
	{
		return $result;
	}

	return false;

}
function user($key = '')
{
	if(empty($key))
		return $_SESSION['USER'];

	if(!empty($_SESSION['USER'][$key]))
		return $_SESSION['USER'][$key];

	return '';
} 
function query_row(string $query, array $data = [])
{

	$string = "mysql:hostname=".DBHOST.";dbname=". DBNAME;
	$con = new PDO($string, DBUSER, DBPASS);

	$stm = $con->prepare($query);
	$stm->execute($data);

	$result = $stm->fetchAll(PDO::FETCH_ASSOC);
	if(is_array($result) && !empty($result))
	{
		return $result[0];
	}

	return false;

}

function redirect($page){

    header('Location: '.ROOT. '/' . $page);
    die;
}
function redi($page){

    header('Location: '.ROOT. '/' . $page);
    exit;
}
function valor_antigo($key, $default=''){
    if(!empty($_POST[$key]))
        return $_POST[ $key ];

    return $default;
}
function check_antigo($key, $default=''){
    if(!empty($_POST[$key]))
        return "checked";

    return "";
}

function authenticate($row){
    $_SESSION['USER']=$row;
}
function logado(){
    if(!empty($_SESSION['USER']))
        return true;
        
    return false;
}
function controlo_login(){
    if(logado() == false) {
        redirect("login");
      }
}
function controlo_admin(){
    // Exige sessao iniciada e perfil de administrador
    controlo_login();
    if(user('role') !== 'admin'){
        redirect("home");
    }
}
function str_to_url($url)
{

   $url = str_replace("'", "", $url);
   $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
   $url = trim($url, "-");
   $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
   $url = strtolower($url);
   $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
   
   return $url;
}

function get_image($file){
    $file = $file ?? '';
    if(file_exists($file)){
        return ROOT.'/'.$file;
    }

    return ROOT.'/assets/imgs/no-image.jpg';
}

//criar_tabelas();
function criar_tabelas()
{

    $string = "mysql:hostname=".DBHOST.";";
    $con = new PDO($string, DBUSER, DBPASS);

    $query = "create database if not exists " . DBNAME;
    $stm = $con->prepare($query);
    $stm->execute();

    $query = "use " . DBNAME;
    $stm = $con->prepare($query);
    $stm->execute();

    $query = "create table if not exists users(
        id_user INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        apelido VARCHAR(100) NOT NULL,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        image VARCHAR(1024) NULL,
        data DATETIME DEFAULT CURRENT_TIMESTAMP,
        role VARCHAR(50) NOT NULL

    )";
    $stm = $con->prepare($query);
    $stm->execute();
    $query = "create table if not exists vagas(
        id_vaga INT(4) AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(3) NOT NULL,
        sector VARCHAR(3) NOT NULL,
        estado VARCHAR(8) NOT NULL

    )";
    $stm = $con->prepare($query);
    $stm->execute();

    $stm = $con->prepare($query);
    $stm->execute();
    $query = "create table if not exists utentes(
        id_utente INT AUTO_INCREMENT,
        nome_comp VARCHAR(150) NOT NULL,
        bi VARCHAR(13) UNIQUE NOT NULL,
        data_emi DATE,
        data_exp DATE,
        contacto NUMERIC(9,0) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        tipo VARCHAR(50)NOT NULL,
        bi_pdf VARCHAR(1024) NOT NULL,
        qr_utente VARCHAR(1024) NOT NULL,
        data DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_utente)

    )AUTO_INCREMENT = 1001221;";
    $stm = $con->prepare($query);
    $stm->execute();


}
function resize_image($filename, $max_size = 1000)
{
	
	if(file_exists($filename))
	{
		$type = mime_content_type($filename);
		switch ($type) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg($filename);
				break;
			case 'image/png':
				$image = imagecreatefrompng($filename);
				break;
			case 'image/gif':
				$image = imagecreatefromgif($filename);
				break;
			case 'image/webp':
				$image = imagecreatefromwebp($filename);
				break;
			default:
				return;
				break;
		}

		$src_width 	= imagesx($image);
		$src_height = imagesy($image);

		if($src_width > $src_height)
		{
			if($src_width < $max_size)
			{
				$max_size = $src_width;
			}

			$dst_width 	= $max_size;
			$dst_height = ($src_height / $src_width) * $max_size;
		}else{
			
			if($src_height < $max_size)
			{
				$max_size = $src_height;
			}

			$dst_height = $max_size;
			$dst_width 	= ($src_width / $src_height) * $max_size;
		}

		$dst_height = round($dst_height);
		$dst_width 	= round($dst_width);

		$dst_image = imagecreatetruecolor($dst_width, $dst_height);
		imagecopyresampled($dst_image, $image, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
		
		switch ($type) {
			case 'image/jpeg':
				imagejpeg($dst_image, $filename, 90);
				break;
			case 'image/png':
				imagepng($dst_image, $filename, 90);
				break;
			case 'image/gif':
				imagegif($dst_image, $filename, 90);
				break;
			case 'image/webp':
				imagewebp($dst_image, $filename, 90);
				break;

		}

	}
}

// ============================================================
//  Funcoes do parque de estacionamento (MaxaPark)
// ============================================================

// Le uma configuracao da base de dados (com cache); cai no valor por omissao se nao existir
function config_get($chave, $default = null)
{
	static $cache = null;
	if ($cache === null) {
		$cache = [];
		$rows = query("SELECT chave, valor FROM configuracoes");
		if (is_array($rows)) {
			foreach ($rows as $r) {
				$cache[$r['chave']] = $r['valor'];
			}
		}
	}
	return array_key_exists($chave, $cache) ? $cache[$chave] : $default;
}

// Grava/actualiza uma configuracao e limpa a cache estatica (forca releitura)
function config_set($chave, $valor)
{
	query(
		"INSERT INTO configuracoes (chave, valor) VALUES (:c, :v)
		 ON DUPLICATE KEY UPDATE valor = :v2",
		['c' => $chave, 'v' => $valor, 'v2' => $valor]
	);
}

// Hora de abertura/fecho do parque (configuravel; por omissao usa as constantes)
function park_abertura()
{
	return (int) config_get('park_abertura', PARK_ABERTURA);
}
function park_fecho()
{
	return (int) config_get('park_fecho', PARK_FECHO);
}

// Verifica se o utente e estudante (normaliza o valor guardado: "Estudante", " Outro", etc.)
function eh_estudante($tipo)
{
	return strtolower(trim((string)$tipo)) === 'estudante';
}

// Verifica se o parque esta aberto (dentro do horario de funcionamento configurado)
function parque_aberto($ts = null)
{
	$h = (int)date('H', $ts ?? time());
	return ($h >= park_abertura() && $h < park_fecho());
}

// Verifica se o utente tem mensalidade paga para o mes indicado (por omissao o mes corrente)
function tem_mensalidade_ativa($id_utente, $mes = null)
{
	$mes = $mes ?? date('Y-m');
	$row = query_row(
		"SELECT id_mensalidade FROM mensalidades WHERE id_utente = :id AND mes_ref = :mes LIMIT 1",
		['id' => $id_utente, 'mes' => $mes]
	);
	return $row ? true : false;
}

// Valor da mensalidade consoante o tipo de utente (estudante = 50%, outro = 25% de desconto)
function valor_mensalidade($tipo_utente)
{
	$valor = TARIFA_MENSAL;
	if (eh_estudante($tipo_utente)) {
		$valor = $valor * (1 - DESCONTO_ESTUDANTE);
	} else {
		$valor = $valor * (1 - DESCONTO_MENSAL);
	}
	return (float)$valor;
}

/**
 * Calcula o valor a pagar a saida do parque.
 *
 * Regras (enunciado):
 *  - Mensalista: dentro do horario nada paga (ja pagou a mensalidade).
 *  - Por hora dentro do horario: paga por hora, estudante tem 50% de desconto.
 *  - Apos as 23h (parque fechado) paga por hora SEM descontos:
 *      * por hora -> todo o tempo que esteve no parque;
 *      * mensalista -> das 23h ate a hora de retirada.
 *
 * @return array ['valor'=>float, 'horas'=>int, 'detalhe'=>string]
 */
function calcular_pagamento($entrada, $saida, $tipo_utente, $eh_mensalista)
{
	$ts_entrada = strtotime($entrada);
	$ts_saida   = strtotime($saida);
	if ($ts_saida < $ts_entrada) {
		$ts_saida = $ts_entrada;
	}

	// Hora de fecho referente ao dia da entrada (configuravel)
	$fecho = park_fecho();
	$ts_fecho = strtotime(date('Y-m-d', $ts_entrada) . ' ' . $fecho . ':00:00');
	$fechado  = $ts_saida >= $ts_fecho;

	$estudante = eh_estudante($tipo_utente);

	if ($eh_mensalista) {
		if (!$fechado) {
			return ['valor' => 0.0, 'horas' => 0, 'detalhe' => 'Mensalista - mensalidade paga, nada a pagar.'];
		}
		// Das 23h ate a saida, por hora, sem descontos
		$segundos = max(0, $ts_saida - $ts_fecho);
		$horas = max(1, (int)ceil($segundos / 3600));
		$valor = $horas * TARIFA_HORA;
		$detalhe = "Mensalista - parque fechado: {$horas}h x " . TARIFA_HORA . " " . MOEDA . " (das " . $fecho . "h a saida, sem desconto).";
		return ['valor' => (float)$valor, 'horas' => $horas, 'detalhe' => $detalhe];
	}

	// Pagamento por hora
	$segundos = max(0, $ts_saida - $ts_entrada);
	$horas = max(1, (int)ceil($segundos / 3600));

	if ($fechado) {
		// Apos as 23h: paga por hora, sem descontos, todo o periodo
		$valor = $horas * TARIFA_HORA;
		$detalhe = "Parque fechado: {$horas}h x " . TARIFA_HORA . " " . MOEDA . " (todo o periodo, sem desconto).";
		return ['valor' => (float)$valor, 'horas' => $horas, 'detalhe' => $detalhe];
	}

	$valor = $horas * TARIFA_HORA;
	if ($estudante) {
		$valor = $valor * (1 - DESCONTO_ESTUDANTE);
		$detalhe = "{$horas}h x " . TARIFA_HORA . " " . MOEDA . " - 50% (estudante).";
	} else {
		$detalhe = "{$horas}h x " . TARIFA_HORA . " " . MOEDA . ".";
	}
	return ['valor' => (float)$valor, 'horas' => $horas, 'detalhe' => $detalhe];
}

// Numero de vagas livres
function vagas_livres()
{
	$row = query_row("SELECT COUNT(*) AS total FROM vagas WHERE estado = 'livre'");
	return $row ? (int)$row['total'] : 0;
}

// Numero total de vagas
function vagas_total()
{
	$row = query_row("SELECT COUNT(*) AS total FROM vagas");
	return $row ? (int)$row['total'] : 0;
}

// Formata um valor monetario
function fmt_moeda($v)
{
	return number_format((float)$v, 2, ',', '.') . ' ' . MOEDA;
}

function get_pagination_vars()
{

	/** set pagination vars **/
	$page_number = $_GET['page'] ?? 1;
	$page_number = empty($page_number) ? 1 : (int)$page_number;
	$page_number = $page_number < 1 ? 1 : $page_number;

	$current_link = $_GET['url'] ?? 'home';
	$current_link = ROOT . "/" . $current_link;
	$query_string = "";

	foreach ($_GET as $key => $value)
	{
		if($key != 'url')
			$query_string .= "&".$key."=".$value;
	}

	if(!strstr($query_string, "page="))
	{
		$query_string .= "&page=".$page_number;
	}

	$query_string = trim($query_string,"&");
	$current_link .= "?".$query_string;

	$current_link = preg_replace("/page=.*/", "page=".$page_number, $current_link);
	$next_link = preg_replace("/page=.*/", "page=".($page_number+1), $current_link);
	$first_link = preg_replace("/page=.*/", "page=1", $current_link);
	$prev_page_number = $page_number < 2 ? 1 : $page_number - 1;
	$prev_link = preg_replace("/page=.*/", "page=".$prev_page_number, $current_link);

	$result = [
		'current_link'	=>$current_link,
		'next_link'		=>$next_link,
		'prev_link'		=>$prev_link,
		'first_link'	=>$first_link,
		'page_number'	=>$page_number,
	];

	return $result;
}