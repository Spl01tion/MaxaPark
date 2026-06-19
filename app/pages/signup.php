<?php

if(!empty($_POST)){
  //Validacao
  $errors=[];

  if(empty($_POST['nome'])){
    $errors['nome']= 'Nome é obrigatório';
  }else
  if(strlen($_POST['nome']) < 3)
  {
    $errors['nome']= 'Nome invalido!';
  }

  if(empty($_POST['apelido'])){
    $errors['apelido']= 'apelido é obrigatório';
  }else
  if(strlen($_POST['apelido']) < 3)
  {
    $errors['apelido']= 'apelido invalido!';
  }

  $query = "SELECT id_user FROM users WHERE username = :username LIMIT 1";
  $username = query($query, ['username' => $_POST['username']]); 
  if(empty($_POST['username'])){
    $errors['username']= 'Username é obrigatório';
  }else
  if(!preg_match('/[a-zA-Z.]+$/',$_POST['username']))
  {
    $errors['username']= 'Username so pode ter letras e sem espaços';
  }else
  if($username){
    $errors['username'] = "Este username já esta em uso!";
  }
  
  $query = "SELECT id_user FROM users WHERE email = :email LIMIT 1";
  $email = query($query, ['email' => $_POST['email']]);
  
  if(empty($_POST['email']))
  {
    $errors['email'] = "Email é obrigatório";
  }else
  if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
  {
    $errors['email'] = "Email invalido!";
  }else
  if($email)
  {
    $errors['email'] = "Este email já esta em uso!";
  }

  if(empty($_POST['password'])){
    $errors['password']= 'Password é obrigatório';
  }else if(strlen($_POST['password']) < 6)
  {
    $errors['password']= 'Password deve conter pelo menos 6 ou mais caracteres';
  }else if($_POST['password']!== $_POST['retype_password'])
  {
    $errors['password']= 'Passwords não conferem';
  }   


  if(empty($errors)){
  //salvar DB
  $data = [];
  $data['nome']     = $_POST['nome'];
  $data['apelido']  = $_POST['apelido'];
  $data['username'] = $_POST['username'];
  $data['email']    = $_POST['email'];
  $data['password'] = password_hash( $_POST['password'],PASSWORD_DEFAULT);
  $data['role']     = "user";

 
  $query="INSERT INTO users (nome,apelido,username,email,password,role) VALUES (:nome,:apelido,:username,:email,:password,:role)";
  if (query($query, $data)) {

    
    echo "Error inserting data.";
} else {


  echo "Data inserted successfully.";
}

  redirect('login');
  }
}
?>
<!doctype html>
<html lang="pt"
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Criar conta - MaxaPark</title>
    <link rel="icon" href="<?=ROOT?>/assets/imgs/MaxaP.ico" type="image/x-icon">
    <link href="<?=ROOT?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>

::-webkit-scrollbar {
    width: 8px;
    
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(var(--black), var(--black2));
    border: 1px solid var(--white);
    border-radius: 6px;
    
}
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .btn-bd-primary {
        --bd-violet-bg: #f0131e;
        --bd-violet-rgb: #f0131e;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #f0131e;
        --bs-btn-hover-border-color: #f0131e;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #a31219;
        --bs-btn-active-border-color: #a31219;
      }
      .bd-mode-toggle {
        z-index: 1500;
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="<?=ROOT?>/assets/css/sign-in.css" rel="stylesheet">
  </head>
  <body class="d-flex align-items-center text-center py-4 bg-body-tertiary">    
<main class="form-signin w-100 m-auto">
  <form method="post">
  <img href="home" class="mb-4" src="assets/imgs/MaxaP.png" alt="" width="30%" height="auto">
    <!-- <h1 class="h3 mb-3 fw-normal">Criar conta</h1> -->
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
    <div class="my-2">Ja possui uma conta? <a href="<?=ROOT?>/login.php">Entrar</a></div>
    <!-- <div class="form-check text-start my-3">
      <input name="aceitarTermos" class="form-check-input" type="checkbox" value="termos_condicoes" id="aceitarTermos">
      <label class="form-check-label" for="aceitarTermos">
        Eu li e concordo com os <a href="termos_condicoes.php">Termos & Condições</a> 
      </label> -->
    </div>
    <button class="btn btn-primary w-100 py-2" type="submit" id="btnSubmeter">Criar conta</button>
    <p class="mt-5 mb-3 text-body-secondary">&copy; <?= date("Y")?> - <?=APP_NAME?></p>
  </form>
</main>
<script>
    const acceptTermsCheckbox = document.getElementById('aceitarTermos');
    const submitButton = document.getElementById('btnSubmeter');

    acceptTermsCheckbox.addEventListener('change', function () {
      submitButton.disabled = !this.checked;
    });
</script>
<script src="<?=ROOT?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>
</html>
