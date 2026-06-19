<style>
  .tipo {
    text-transform: capitalize;
  }
</style>
<?php if ($action == 'add') : ?>
  <div class="col-md-6 mx-auto">
    <form method="POST" enctype="multipart/form-data">
      <h1 class="h3 mb-3 fw-normal">Novo Utente</h1>
      <hr class="my-4" />

      <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">Por favor resolva os erros abaixo</div>
      <?php endif; ?>

      <div class="form-group mb-3 row"><label  class="col-md-5 col-form-label">Nome e Apelido</label>
        <div class="col-md-7"><input value="<?=valor_antigo('nome_comp')?>" type="text" class="form-control" id="nome_comp" name="nome_comp" required></div>
      </div>
      <?php if(!empty($errors['nome_comp'])): ?>
    <div class="text-danger"><?=$errors['nome_comp']?></div>
    <?php endif;?>

      <div class="form-group mb-3 row"><label for="email" class="col-md-5 col-form-label">Email</label>
        <div class="col-md-7"><input value="<?=valor_antigo('email')?>"type="email" class="form-control" id="email" name="email" required></div>
      </div>
      <?php if(!empty($errors['email'])): ?>
    <div class="text-danger"><?=$errors['email']?></div>
    <?php endif;?>

      <div class="form-group mb-3 row"><label for="bi" class="col-md-5 col-form-label">B.I Nº:</label>
        <div class="col-md-7"><input value="<?=valor_antigo('bi')?>" type="text" class="form-control" id="bi" name="bi" required></div>
      </div>
      <?php if(!empty($errors['bi'])): ?>
    <div class="text-danger"><?=$errors['bi']?></div>
    <?php endif;?>

      <div class="form-group mb-4 row justify-content-center">
        <div class="form-group col-md-4">
          <label for="data_emi">Data de Emissão</label>
          <input value="<?=valor_antigo('data_emi')?>" type="date" class="form-control" id="data_emi" name="data_emi" required>
        </div>
        <div class="form-group col-md-4">
          <label for="data_exp">Valido Até</label>
          <input value="<?=valor_antigo('data_exp')?>" type="date" class="form-control" id="data_exp" name="data_exp" required>
        </div>
        <?php if(!empty($errors['data_exp'])): ?>
    <div class="text-danger"><?=$errors['data_exp']?></div>
    <?php endif;?>
      </div>

      <div class="form-group mb-3 row"><label for="contacto" class="col-md-5 col-form-label">Contacto</label>
        <div class="col-md-7"><input value="<?=valor_antigo('contacto')?>" type="tel" class="form-control" id="contacto" name="contacto" required></div>
      </div>
      <?php if(!empty($errors['contacto'])): ?>
    <div class="text-danger"><?=$errors['contacto']?></div>
    <?php endif;?>

      <hr class="my-4" />
      <div class="form-group">
        <label for="bifile">PDF do Bilhete de Identidade</label>
        <input type="file" class="form-control-file" name="bifile" id="bifile" required>
      </div>

      <hr class="my-4" />
      <div class="form-group mb-3 row">
        <label for="tipo" class="col-md-5 col-form-label">Tipo</label>
        <div class="col-md-1">
          <div class="form-check custom-control custom-radio"><input class="form-check-input custom-control-input" type="radio" name="tipo" id="tipo_estudante" checked value="Estudante"><label class="form-check-label custom-control-label" for="tipo4_2">Estudante</label></div>
          <div class="form-check custom-control custom-radio"><input class="form-check-input custom-control-input" type="radio" name="tipo" id="tipo_outro" value=" Outro"><label class="form-check-label custom-control-label" for="tipo4_3"> Outro</label></div>
        </div>
      </div>

      <a href="<?= ROOT ?>/admin/utentes/">
        <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
      </a>
      <button class="mt-4 btn btn-primary w-20 float-end" type="submit" id="btnSubmeter">Adicionar</button>
    </form>

  </div>


<?php elseif ($action == 'edit') : ?>
  <div class="col-md-6 mx-auto">
    <form method="POST" enctype="multipart/form-data">

      <h1 class="h3 mb-3 fw-normal">Editar Utente</h1>
      <?php if (!empty($row)) : ?>

        <?php if (!empty($errors)) : ?>
          <div class="alert alert-danger">Por favor resolva os erros abaixo</div>
        <?php endif; ?>

        <div class="form-group mb-3 row"><label  class="col-md-5 col-form-label">Nome e Apelido</label>
        <div class="col-md-7"><input value="<?=valor_antigo('nome_comp',$row['nome_comp'])?>" type="text" class="form-control" id="nome_comp" name="nome_comp" required></div>
      </div>
      <?php if(!empty($errors['nome_comp'])): ?>
    <div class="text-danger"><?=$errors['nome_comp']?></div>
    <?php endif;?>

      <div class="form-group mb-3 row"><label for="email" class="col-md-5 col-form-label">Email</label>
        <div class="col-md-7"><input value="<?=valor_antigo('email',$row['email'])?>"type="email" class="form-control" id="email" name="email" required></div>
      </div>
      <?php if(!empty($errors['email'])): ?>
    <div class="text-danger"><?=$errors['email']?></div>
    <?php endif;?>

      <div class="form-group mb-3 row"><label for="bi" class="col-md-5 col-form-label">B.I Nº:</label>
        <div class="col-md-7"><input value="<?=valor_antigo('bi',$row['bi'])?>" type="text" class="form-control" id="bi" name="bi" required></div>
      </div>
      <?php if(!empty($errors['bi'])): ?>
    <div class="text-danger"><?=$errors['bi']?></div>
    <?php endif;?>

      <div class="form-group mb-4 row justify-content-center">
        <div class="form-group col-md-4">
          <label for="data_emi">Data de Emissão</label>
          <input value="<?=valor_antigo('data_emi',$row['data_emi'])?>" type="date" class="form-control" id="data_emi" name="data_emi" required>
        </div>
        <div class="form-group col-md-4">
          <label for="data_exp">Valido Até</label>
          <input value="<?=valor_antigo('data_exp',$row['data_exp'])?>" type="date" class="form-control" id="data_exp" name="data_exp" required>
        </div>
        <?php if(!empty($errors['data_exp'])): ?>
    <div class="text-danger"><?=$errors['data_exp']?></div>
    <?php endif;?>
      </div>

      <div class="form-group mb-3 row"><label for="contacto" class="col-md-5 col-form-label">Contacto</label>
        <div class="col-md-7"><input value="<?=valor_antigo('contacto',$row['contacto'])?>" type="tel" class="form-control" id="contacto" name="contacto" required></div>
      </div>
      <?php if(!empty($errors['contacto'])): ?>
    <div class="text-danger"><?=$errors['contacto']?></div>
    <?php endif;?>

    <hr class="my-4" />
      <div class="form-group mb-3 row">
        <label for="tipo" class="col-md-5 col-form-label">Tipo</label>
        <div class="col-md-1">
          <div class="form-check custom-control custom-radio">
            <input class="form-check-input custom-control-input" type="radio" name="tipo" id="tipo_estudante" value="estudante" <?php if($row['tipo']=='estudante') echo 'checked';?>>
          <label class="form-check-label custom-control-label">Estudante</label>
        </div>
          <div class="form-check custom-control custom-radio">
            <input class="form-check-input custom-control-input" type="radio" name="tipo" id="tipo_outro" value="outro" <?php if($row['tipo']=='outro') echo 'checked';?> >
          <label class="form-check-label custom-control-label">Outro</label>
        </div>
        </div>
      </div>

        <a href="<?= ROOT ?>/admin/utentes/">
          <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
        </a>
        <button class="mt-4 btn btn-primary w-20" type="submit" id="btnSubmeter">Atualizar</button>
      <?php else : ?>
        <div class="alert alert-danger text-center">Registo nao encontrado</div>
      <?php endif; ?>
    </form>
  </div>


<?php elseif ($action == 'delete') : ?>

  <div class="col-md-6 mx-auto" enctype="multipart/form-data">
    <form method="POST">

      <h1 class="h3 mb-3 fw-normal">Eliminar Utente</h1>
      <?php if (!empty($row)) : ?>

        <?php if (!empty($errors)) : ?>
          <div class="alert alert-danger">Por favor resolva os erros abaixo</div>
        <?php endif; ?>

        <div class="form-floating">
          <input value="<?= valor_antigo('nome_comp', $row['nome_comp']) ?>" name="nome_comp" type="text" class="form-control mb-2" id="floatingInput" placeholder="Nome">
          <label for="floatingInput">Nome</label>
        </div>

        <a href="<?= ROOT ?>/admin/utentes/">
          <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
        </a>
        <button class="mt-4 btn btn-danger w-20" type="submit" id="btnSubmeter">Eliminar</button>
      <?php else : ?>
        <div class="alert alert-danger text-center">Registo nao encontrado</div>
      <?php endif; ?>
    </form>
  </div>
<?php else : ?>
  <h4>Utente
    <a href="<?= ROOT ?>/admin/utentes/add">
      <button class="btn btn-primary">Adicionar Utente</button>
    </a>
  </h4>
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

      $limit = 10;
      $offset = ($PAGE['page_number'] - 1) * $limit;

      $query = "SELECT * FROM utentes ORDER BY id_utente ASC LIMIT $limit OFFSET $offset";
      $rows = query($query);
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
              <a href="<?= ROOT ?>/admin/utentes/edit/<?= $row['id_utente'] ?>">
                <button class="btn btn-warning text-white btn-sm"><i class="bi bi-pencil-square"></i></button>
              </a>
              <a href="<?= ROOT ?>/admin/utentes/delete/<?= $row['id_utente'] ?>">
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