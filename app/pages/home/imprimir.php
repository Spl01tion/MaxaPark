<?php

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query to include a WHERE clause for searching by name
$limit = 5;
$offset = ($PAGE['page_number'] - 1) * $limit;

$query = "SELECT * FROM utentes WHERE nome_comp LIKE :search ORDER BY id_utente ASC LIMIT $limit OFFSET $offset";
$rows = query($query, ['search' => "%$searchQuery%"]);
?>
<head>
<link href="<?= ROOT ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= ROOT ?>/assets/css/bootstrap-icons.css" rel="stylesheet">
</head>
<style>
    .tipo{
        text-transform: capitalize;
    }
</style>
<h1>Imprimir Cartão</h1>
<form method="GET" action="<?= ROOT ?>/home/imprimir">
    <div class="mb-3 col-md-3 row">
        <label for="search" class="form-label">Pesquisa pelo Nome:</label>
        <input type="text" class="form-control" id="search" name="search" value="<?= htmlspecialchars($searchQuery) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Procurar</button>
</form>
<div class="table-responsive">
    <table class="table">

        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>BI</th>
            <th>Contacto</th>
            <th>Email</th>
            <th>Tipo</th>
            <th><i class="bi bi-gear-wide-connected"></i></th>
        </tr>
        <?php
        
        // $query = "SELECT * FROM utentes ORDER BY id_utente ASC";
        // $rows = query($query);
        ?>
        <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $row) : ?>
                <tr style="font-size: smaller;">
                    <td><?= $row['id_utente'] ?></td>
                    
                    <td><?= $row['nome_comp'] ?></td>
                    <td><?= $row['bi'] ?></td>
                    <td><?= $row['contacto'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td class="tipo"><?= $row['tipo'] ?></td>
                    <td>
                        <a href="<?= ROOT ?>/cartao/<?= $row['id_utente'] ?>" target="_blank">
                            <button class="btn btn-success text-white btn-sm"><i class="bi bi-printer"></i></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    <div class="col-md-12 mb-4">
		<a href="<?=$PAGE['first_link']?>">
			<button class="btn btn-primary">Primeira Pagina</button>
		</a>
		<a href="<?=$PAGE['prev_link']?>">
			<button class="btn btn-primary">Anterior</button>
		</a>
		<a href="<?=$PAGE['next_link']?>">
			<button class="btn btn-primary float-end">Proxima Pagina</button>
		</a>
	</div>

</div>