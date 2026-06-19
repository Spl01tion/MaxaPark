<?php

$id = $url[1] ?? 0;
$row = false;
$qrcode = '';

if ($id) {
  $query = "SELECT * FROM utentes WHERE id_utente=:id LIMIT 1";
  $row = query_row($query, ['id' => $id]);

  if ($row) {
    $path = 'uploads/qr_utente/';
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }
    $qrcode = $path . time() . ".png";
    QRcode::png($row['id_utente'], $qrcode, 'H', 4, 4);
  }
}

if (!$row) {
  echo '<!DOCTYPE html><html lang="pt"><head><meta charset="utf-8"><title>Cartão</title>'
    . '<link href="' . ROOT . '/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"></head>'
    . '<body class="p-5"><div class="alert alert-danger">Utente não encontrado.</div>'
    . '<a class="btn btn-primary" href="' . ROOT . '/home/imprimir">Voltar</a></body></html>';
  return;
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="utf-8">
  <title>Cartão - <?= htmlspecialchars($row['nome_comp'] ?? '') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Tamanho real de um cartao: padrao CR80 = 85,6 x 54 mm (aqui em retrato) */
    :root {
      --card-w: 54mm;
      --card-h: 85.6mm;
      --maxa: #9C1980;
    }

    body {
      background: #e9ecef;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    .cartao {
      width: var(--card-w);
      height: var(--card-h);
      background: #fff;
      border-radius: 3mm;
      border-bottom: 4mm solid var(--maxa);
      box-shadow: 0 4px 14px rgba(0, 0, 0, .25);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 3mm 2mm;
      box-sizing: border-box;
    }

    .cartao .logo {
      width: 12mm;
      height: auto;
    }

    .cartao .marca {
      color: var(--maxa);
      font-weight: 700;
      font-size: 5mm;
      margin: 1mm 0 0;
      letter-spacing: .3mm;
    }

    .cartao .nome {
      font-size: 3.2mm;
      font-weight: 600;
      margin: 1.5mm 0;
      line-height: 1.1;
    }

    .cartao .qr {
      width: 30mm;
      height: 30mm;
    }

    .cartao .numero {
      font-size: 5mm;
      font-weight: 700;
      letter-spacing: .5mm;
      margin-top: 1.5mm;
    }

    .cartao .rodape {
      font-size: 2.2mm;
      color: #666;
      margin-top: auto;
    }

    .barra-acoes {
      margin-top: 18px;
    }

    /* ----- Impressao: pagina do tamanho exacto do cartao ----- */
    @media print {
      @page {
        size: 54mm 85.6mm;
        margin: 0;
      }

      body {
        background: #fff;
        min-height: auto;
        display: block;
      }

      .cartao {
        box-shadow: none;
        border-radius: 0;
        margin: 0;
        page-break-after: avoid;
      }

      .barra-acoes {
        display: none;
      }
    }
  </style>
</head>

<body>
  <div class="cartao">
    <img class="logo" src="<?= ROOT ?>/assets/imgs/MaxaP.ico" alt="MaxaPark">
    <div class="marca">MaxaPark</div>
    <div class="nome"><?= htmlspecialchars($row['nome_comp'] ?? '') ?></div>
    <img class="qr" src="<?= ROOT ?>/<?= $qrcode ?>" alt="QRCode">
    <div class="numero"><?= $id ?></div>
    <div class="rodape">Cartão de Utente</div>
  </div>

  <div class="barra-acoes">
    <button class="btn btn-primary" onclick="window.print()">Imprimir Cartão</button>
    <button class="btn btn-outline-secondary" onclick="window.close()">Fechar</button>
  </div>

  <script>
    // Imprime automaticamente ao abrir
    window.addEventListener('load', function () {
      setTimeout(function () { window.print(); }, 400);
    });
  </script>
</body>

</html>
