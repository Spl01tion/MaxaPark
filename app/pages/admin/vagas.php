<style>
  .nome,.estado,.sector{
    text-transform: capitalize
    ;
  }
  
</style>
<?php if($action == 'add'):?>
  <div class="col-md-6 mx-auto">
    <form method="POST" enctype="multipart/form-data">
    <h1 class="h3 mb-3 fw-normal">Nova Vaga</h1>

    <?php if(!empty($errors)): ?>
      <div class="alert alert-danger">Por favor resolva os erros abaixo</div>
    <?php endif;?>

    <div class="form-floating">
      <input value="<?=valor_antigo('nome')?>" name="nome" type="text" class="form-control mb-2" id="floatingInput" placeholder="Nome">
      <label for="floatingInput">Nome</label>
    </div>

    <?php if(!empty($errors['nome'])): ?>
      <div class="text-danger"><?=$errors['nome']?></div>
    <?php endif;?>

    <div class="form-floating">
      <input value="<?=valor_antigo('sector')?>" name="sector" type="text" class="form-control mb-2" id="floatingInput" placeholder="Sector">
      <label for="floatingInput">Sector</label>
    </div>

    <?php if(!empty($errors['sector'])): ?>
      <div class="text-danger"><?=$errors['sector']?></div>
    <?php endif;?>

    <a href="<?= ROOT ?>/admin/vagas/">
    <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
    </a>
    <button class="mt-4 btn btn-primary w-20 float-end" type="submit" id="btnSubmeter">Adicionar</button>
    </form>
    
  </div>


<?php elseif($action == 'edit'):?>
  <div class="col-md-6 mx-auto">
    <form method="POST" enctype="multipart/form-data">
  
    <h1 class="h3 mb-3 fw-normal">Editar vaga</h1>
    <?php if(!empty($row)):?>

      <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">Por favor resolva os erros abaixo</div>
      <?php endif;?>

      <div class="form-floating">
      <input value="<?=valor_antigo('nome',$row['nome'])?>" name="nome" type="text" class="form-control mb-2" id="floatingInput" placeholder="Nome">
      <label for="floatingInput">Nome</label>
    </div>

    <?php if(!empty($errors['nome'])): ?>
      <div class="text-danger"><?=$errors['nome']?></div>
    <?php endif;?>

    <div class="form-floating">
      <input value="<?=valor_antigo('sector',$row['sector'])?>" name="sector" type="text" class="form-control mb-2" id="floatingInput" placeholder="Sector">
      <label for="floatingInput">Sector</label>
    </div>

    <?php if(!empty($errors['sector'])): ?>
      <div class="text-danger"><?=$errors['sector']?></div>
    <?php endif;?>

      <a href="<?= ROOT ?>/admin/vagas/">
        <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
      </a>
      <button class="mt-4 btn btn-primary w-20" type="submit" id="btnSubmeter">Atualizar</button>
    <?php else:?>
       <div class="alert alert-danger text-center">Registo nao encontrado</div>
    <?php endif;?>
  </form>
</div>
        

<?php elseif($action == 'delete'):?>

  <div class="col-md-6 mx-auto" enctype="multipart/form-data">
    <form method="POST">
  
    <h1 class="h3 mb-3 fw-normal">Eliminar vaga</h1>
    <?php if(!empty($row)):?>

      <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">Por favor resolva os erros abaixo</div>
      <?php endif;?>

      <div class="form-floating">
        <input value="<?=valor_antigo('nome',$row['nome'])?>" name="nome" type="text" class="form-control mb-2" id="floatingInput" placeholder="Nome">
        <label for="floatingInput">Nome</label>
       </div>

      <a href="<?= ROOT ?>/admin/vagas/">
        <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
      </a>
      <button class="mt-4 btn btn-danger w-20" type="submit" id="btnSubmeter">Eliminar</button>
    <?php else:?>
       <div class="alert alert-danger text-center">Registo nao encontrado</div>
    <?php endif;?>
  </form>
</div>
<?php else:?>
<h4>Vagas
    <a href="<?= ROOT ?>/admin/vagas/add">
        <button class="btn btn-primary">Adicionar Vaga</button>
    </a>
</h4>
<div class="table-responsive">
    <table class="table">

        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Sector</th>
            <th>Estado</th>

            <th><i class="bi bi-gear-wide-connected"></i></th>
        </tr>
        <?php
        $limit = 10;
        $offset = ($PAGE['page_number'] - 1) * $limit;
        
        $query = "SELECT * FROM vagas ORDER BY id_vaga ASC LIMIT $limit OFFSET $offset";
        $rows = query($query);
        ?>
        <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $row) : ?>
                <tr style="font-size: smaller;">
                    <td><?= $row['id_vaga'] ?></td>
                    <td class="nome"><?= $row['nome'] ?></td>
                    <td class="sector"><?= $row['sector'] ?></td>
                    <td class="estado" style=""><?= $row['estado'] ?></td>
                    <td>
                        <a href="<?= ROOT ?>/admin/vagas/edit/<?= $row['id_vaga'] ?>">
                            <button class="btn btn-warning text-white btn-sm"><i class="bi bi-pencil-square"></i></button>
                        </a>
                        <a href="<?= ROOT ?>/admin/vagas/delete/<?= $row['id_vaga'] ?>">
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </a>
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
<?php endif; ?>