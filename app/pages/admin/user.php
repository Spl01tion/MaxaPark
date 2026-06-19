<?php if($action == 'add'):?>
  <div class="col-md-6 mx-auto">
    <form method="POST" enctype="multipart/form-data">
    <h1 class="h3 mb-3 fw-normal">Criar conta</h1>

    <?php if(!empty($errors)): ?>
      <div class="alert alert-danger">Por favor resolva os erros abaixo</div>
    <?php endif;?>

    <div class="my-2">
        <label class="d-block">
          <img class="mx-auto d-block image-preview-edit" src="<?=get_image($row['image'])?>" style="cursor:pointer;width:150px;height:150px;object-fit:cover;">
          <input onchange="display_image_edit(this.files[0])" type="file" name="image" id="image" class="d-none" >
        </label>
        <?php if(!empty($errors['image'])):?>
		      <div class="text-danger"><?=$errors['image']?></div>
		    <?php endif;?>
        
        <script>
          function display_image_edit(file){
            document.querySelector(".image-preview-edit").src =URL.createObjectURL(file);
          }
        </script>
      </div>

    <div class="form-floating">
      <input value="<?=valor_antigo('nome')?>" name="nome" type="text" class="form-control mb-2" id="floatingInput" placeholder="Nome">
      <label for="floatingInput">Nome</label>
    </div>

    <?php if(!empty($errors['nome'])): ?>
      <div class="text-danger"><?=$errors['nome']?></div>
    <?php endif;?>

    <div class="form-floating">
      <input value="<?=valor_antigo('apelido')?>" name="apelido" type="text" class="form-control mb-2" id="floatingInput" placeholder="Apelido">
      <label for="floatingInput">Apelido</label>
    </div>

    <?php if(!empty($errors['apelido'])): ?>
      <div class="text-danger"><?=$errors['apelido']?></div>
    <?php endif;?>

    <div class="form-floating">
      <input value="<?=valor_antigo('username')?>" name="username" type="text" class="form-control mb-2" id="floatingInput" placeholder="Username">
      <label for="floatingInput">Username</label>
    </div>

    <?php if(!empty($errors['username'])): ?>
      <div class="text-danger"><?=$errors['username']?></div>
    <?php endif;?>

    <div class="form-floating">
      <input value="<?=valor_antigo('email')?>" name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email</label>
    </div>

    <?php if(!empty($errors['email'])): ?>
      <div class="text-danger"><?=$errors['email']?></div>
    <?php endif;?>

    <div class="form-floating">
      <input value="<?=valor_antigo('password')?>" name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <?php if(!empty($errors['password'])): ?>
      <div class="text-danger"><?=$errors['password']?></div>
    <?php endif;?>

    <div class="form-floating">
      <input value="<?=valor_antigo('retype_password')?>" name="retype_password" type="password" class="form-control" id="floatingPassword" placeholder="Retype Password">
      <label for="floatingPassword">Confirmar Password</label>
    </div>

    <?php if(!empty($errors['retype_password'])): ?>
      <div class="text-danger"><?=$errors['retype_password']?></div>
    <?php endif;?>

    <a href="<?= ROOT ?>/admin/user/">
    <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
    </a>
    <button class="mt-4 btn btn-primary w-20 float-end" type="submit" id="btnSubmeter">Criar conta</button>
    </form>
    
  </div>


<?php elseif($action == 'edit'):?>
  <div class="col-md-6 mx-auto">
    <form method="POST" enctype="multipart/form-data">
  
    <h1 class="h3 mb-3 fw-normal">Editar conta</h1>
    <?php if(!empty($row)):?>

      <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">Por favor resolva os erros abaixo</div>
      <?php endif;?>

      <div class="my-2">
        <label class="d-block">
          <img class="mx-auto d-block image-preview-edit" src="<?=get_image($row['image'])?>" style="cursor:pointer;width:150px;height:150px;object-fit:cover;">
          <input onchange="display_image_edit(this.files[0])" type="file" name="image" id="image" class="d-none" >
        </label>

        <script>
          function display_image_edit(file){
            document.querySelector(".image-preview-edit").src =URL.createObjectURL(file);
          }
        </script>
      </div>

      <div class="form-floating">
        <input value="<?=valor_antigo('nome',$row['nome'])?>" name="nome" type="text" class="form-control mb-2" id="floatingInput" placeholder="Nome">
        <label for="floatingInput">Nome</label>
      </div>

      <?php if(!empty($errors['nome'])): ?>
        <div class="text-danger"><?=$errors['nome']?></div>
      <?php endif;?>

      <div class="form-floating">
        <input value="<?=valor_antigo('apelido',$row['apelido'])?>" name="apelido" type="text" class="form-control mb-2" id="floatingInput" placeholder="Apelido">
        <label for="floatingInput">Apelido</label>
      </div>

      <?php if(!empty($errors['apelido'])): ?>
        <div class="text-danger"><?=$errors['apelido']?></div>
      <?php endif;?>

      <div class="form-floating">
        <input value="<?=valor_antigo('username',$row['username'])?>" name="username" type="text" class="form-control mb-2" id="floatingInput" placeholder="Username">
        <label for="floatingInput">Username</label>
      </div>

      <?php if(!empty($errors['username'])): ?>
        <div class="text-danger"><?=$errors['username']?></div>
      <?php endif;?>

      <div class="form-floating">
        <input value="<?=valor_antigo('email',$row['email'])?>" name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Email</label>
      </div>

      <?php if(!empty($errors['email'])): ?>
        <div class="text-danger"><?=$errors['email']?></div>
      <?php endif;?>

      <div class="form-floating">
        <input value="<?=valor_antigo('password')?>" name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>
        
      <?php if(!empty($errors['password'])): ?>
        <div class="text-danger"><?=$errors['password']?></div>
      <?php endif;?>

      <div class="form-floating">
        <input value="<?=valor_antigo('retype_password')?>" name="retype_password" type="password" class="form-control" id="floatingPassword" placeholder="Retype Password">
        <label for="floatingPassword">Confirmar Password</label>
      </div>

      <?php if(!empty($errors['retype_password'])): ?>
        <div class="text-danger"><?=$errors['retype_password']?></div>
      <?php endif;?>
      <div class="form-group mb-3 row">
        <label for="role" class="col-md-5 col-form-label">Tipo</label>
          <div class="col-md-1">
            <div class="form-check custom-control custom-radio"><input class="form-check-input custom-control-input" 
            type="radio" name="tipo" id="tipo_user" value="user" <?php if($row['role']=='user') echo 'checked';?>><label class="form-check-label custom-control-label" >Funcionario</label></div>
            <div class="form-check custom-control custom-radio">
              <input class="form-check-input custom-control-input" type="radio" name="tipo" id="tipo_admin" value="admin" <?php if($row['role']=='admin') echo 'checked';?>><label class="form-check-label custom-control-label"> Administrador</label></div>
          
          </div>
      </div>

      <a href="<?= ROOT ?>/admin/user/">
        <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
      </a>
      <button class="mt-4 btn btn-primary w-20" type="submit" id="btnSubmeter">Atualizar</button>
    <?php else:?>
       <div class="alert alert-danger text-center">Registo nao encontrado</div>
    <?php endif;?>
  </form>
</div>
        

<?php elseif($action == 'delete'):?>

  <div class="col-md-6 mx-auto">
    <form method="POST" enctype="multipart/form-data">
  
    <h1 class="h3 mb-3 fw-normal">Editar conta</h1>
    <?php if(!empty($row)):?>

      <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">Por favor resolva os erros abaixo</div>
      <?php endif;?>

      <div class="form-floating">
        <div class="form-control mb-2"><?=valor_antigo('username',$row['username'])?></div>
      </div>

      <?php if(!empty($errors['username'])): ?>
        <div class="text-danger"><?=$errors['username']?></div>
      <?php endif;?>

      <div class="form-floating">
        <div class="form-control""><?=valor_antigo('email',$row['email'])?></div>
      </div>

      <?php if(!empty($errors['email'])): ?>
        <div class="text-danger"><?=$errors['email']?></div>
      <?php endif;?>


      <a href="<?= ROOT ?>/admin/user/">
        <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
      </a>
      <button class="mt-4 btn btn-danger w-20" type="submit" id="btnSubmeter">Eliminar</button>
    <?php else:?>
       <div class="alert alert-danger text-center">Registo nao encontrado</div>
    <?php endif;?>
  </form>
</div>
<?php else:?>
<h4>Usuários
    <a href="<?= ROOT ?>/admin/user/add">
        <button class="btn btn-primary">Adicionar Novo</button>
    </a>
</h4>
<div class="table-responsive">
    <table class="table">

        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Imagem</th>
            <th>Data</th>
            <th><i class="bi bi-gear-wide-connected"></i></th>
        </tr>
        <?php
        $limit = 10;
        $offset = ($PAGE['page_number'] - 1) * $limit;

        $query = "SELECT * FROM users ORDER BY id_user ASC LIMIT $limit OFFSET $offset";
        $rows = query($query);
        ?>
        <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $row) : ?>
                <tr style="font-size: smaller;">
                    <td><?= $row['id_user'] ?></td>
                    <td><?= $row['nome'] ?> <?= $row['apelido'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['role'] ?></td>
                    <td>
                      <img src="<?=get_image($row['image'])?>" style="width:70px;height:70px;object-fit:cover;">
                    </td>
                    <td><?= date("d-m-Y", strtotime($row['data'])); ?></td>
                    <td>
                        <a href="<?= ROOT ?>/admin/user/edit/<?= $row['id_user'] ?>">
                            <button class="btn btn-warning text-white btn-sm"><i class="bi bi-pencil-square"></i></button>
                        </a>
                        <a href="<?= ROOT ?>/admin/user/delete/<?= $row['id_user'] ?>">
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
