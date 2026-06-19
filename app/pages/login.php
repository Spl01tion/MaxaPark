<?php

if (!empty($_POST)) {
  // Validação
  $errors = [];

  // Verifique se a chave 'password' existe em $_POST
  if (isset($_POST['password'])) {
      $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
      $row = query($query, ['username' => $_POST['username']]);

      if ($row) {
          $data = [];
          if (isset($_POST['password']) && password_verify($_POST['password'], $row[0]['password'])) {
              // Conceda o acesso
              authenticate($row[0]);
              $_SESSION['user_role'] = $row[0]['role'];
              
              if($_SESSION['user_role']== 'user'){
                redirect('home');
              }else{
                redirect('admin');
              }
              
          } else {
              $errors['username'] = 'Username ou Password incorrectos';
          }
      } else {
          $errors['username'] = 'Username ou Password incorrectos';
      }
  } else {
      $errors['username'] = 'Username ou Password incorretos';
  }
}
?>

<!doctype html>
<html lang="pt" data-bs-theme="auto">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Log in - MaxaPark</title>
    <link rel="icon" href="<?=ROOT?>/assets/imgs/MaxaP.ico" type="image/x-icon">
    <link href="<?=ROOT?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
      :root {
    --black: #000;
    --black1: #0b0b0c;
    --dk-black:#202020;
    --dk-white:#fff;
    --black2: rgb(18, 18, 18);
    --black3: #221f1f;
    --gray: #666;
    --red: #ef3a3d;
    --white2: #d9d9d9;
    --white: #fff;
    --gold: #f0131e;
    --gold1: #f0131e;

    --foreground:var(--dk-black);
    --background:var(--dk-white);
}
.darkmode{
    --black: #000;
    --black1: #0b0b0c;
    --dk-black:#202020;
    --dk-white:#fff;
    --black2: rgb(18, 18, 18);
    --black3: #221f1f;
    --gray: #666;
    --red: #ef3a3d;
    --white2: #d9d9d9;
    --white: #fff;
    --gold: #f0131e;
    --gold1: #f0131e;

    --foreground:var(--dk-white);
    --background:var(--dk-black);
}
::-webkit-scrollbar {
    width: 8px;
    
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(var(--black), var(--black2));
    border: 1px solid var(--white);
    border-radius: 6px;
    
}
      @font-face {
        font-family: Mont;
        src: url(../fonts/Montserrat-Regular.ttf);
      }
      *{
        font-family: Mont;
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
      .btn,h1{
        font-family: Mont;
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?=ROOT?>/assets/css/maxa.css" rel="stylesheet">
  </head>
  <body>

<div class="maxa-login-wrap">
  <main class="maxa-login-card">
    <form method="POST">
      <div class="text-center mb-4">
        <img class="mb-2" src="<?=ROOT?>/assets/imgs/MaxaP.png" alt="MaxaPark" width="90" height="auto">
        <h1 class="h3 maxa-brand mb-1">MaxaPark</h1>
        <p class="text-body-secondary small mb-0">Inicie sessão para continuar</p>
      </div>

      <?php if(!empty($errors['username'])): ?>
        <div class="alert alert-danger py-2"><?=$errors['username']?></div>
      <?php endif;?>

      <div class="form-floating mb-2">
        <input value="<?=valor_antigo('username')?>" name="username" type="text" class="form-control" id="floatingInput" placeholder="Username">
        <label for="floatingInput"><i class="bi bi-person"></i> Username</label>
      </div>

      <div class="form-floating mb-3">
        <input value="<?=valor_antigo('password')?>" name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword"><i class="bi bi-lock"></i> Password</label>
      </div>

      <button class="btn btn-primary w-100 py-2" type="submit"><i class="bi bi-box-arrow-in-right"></i> Entrar</button>
      <p class="mt-4 mb-0 text-center text-body-secondary small">&copy; <?= date("Y")?> - MaxaPark</p>
    </form>
  </main>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="<?=ROOT?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>
</html>
