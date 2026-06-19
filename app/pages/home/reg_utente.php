<?php
if(!empty($_POST)){
  //Validacao
  $errors=[];

  if(empty($_POST['nome_comp'])){
    $errors['nome_comp']= 'Nome é obrigatório';
  }else
  if(strlen($_POST['nome_comp']) < 3)
  {
    $errors['nome_comp']= 'Nome invalido!';
  }
  if(empty($_POST['contacto'])){
      $errors['contacto']= 'Contacto é obrigatório';
    }else
    if(strlen($_POST['contacto']) < 9 )
    {
      $errors['contacto']= 'Contacto invalido!';
    }

    $query = "SELECT id_utente FROM utentes WHERE bi = :bi LIMIT 1";
    $bi = query($query, ['bi' => $_POST['bi']]); 
    if(empty($_POST['bi'])){
      $errors['bi']= 'BI é obrigatório';
    }else
    if(strlen($_POST['bi']) < 13 || strlen($_POST['bi']) > 13)
    {
      $errors['bi']= 'BI invalido!';
    }else
    if($bi){
      $errors['bi'] = "Este BI já esta em uso!";
    }

  $query = "SELECT id_utente FROM utentes WHERE contacto = :contacto LIMIT 1";
  $contacto = query($query, ['contacto' => $_POST['contacto']]); 
  if(empty($_POST['contacto'])){
    $errors['contacto']= 'Contacto é obrigatório';
  }else
  if(strlen($_POST['contacto']) < 9 || strlen($_POST['contacto']) > 9)
  {
    $errors['contacto']= 'Contacto deve possuir 9 digitos';
  }else
  if($contacto){
    $errors['contacto'] = "Este contacto já esta em uso!";
  }
  
  $query = "SELECT id_utente FROM utentes WHERE email = :email LIMIT 1";
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



  if(empty($errors)){
      
  //salvar DB
  if ($_FILES['bifile']['error'] === UPLOAD_ERR_OK) {
      $uploadDirectory = 'uploads/bi_utente/'; // Define the directory where PDF files will be stored
      $uploadPath = $uploadDirectory . $_FILES['bifile']['name'];

      if (move_uploaded_file($_FILES['bifile']['tmp_name'], $uploadPath)) {
          // File upload successful

          // Now, save the file path in the database
          $data = [];
          $data['nome_comp'] = $_POST['nome_comp'];
          $data['bi'] = $_POST['bi'];
          $data['data_emi'] = $_POST['data_emi'];
          $data['data_exp'] = $_POST['data_exp'];
          $data['contacto'] = $_POST['contacto'];
          $data['email'] = $_POST['email'];
          $data['tipo'] = $_POST['tipo'];
          $data['bi_pdf'] = $uploadPath; // Store the file path

          $query = "INSERT INTO utentes (nome_comp, bi, data_emi, data_exp, contacto, email, tipo, bi_pdf) 
                    VALUES (:nome_comp, :bi, :data_emi, :data_exp, :contacto, :email, :tipo, :bi_pdf)";

          if (query($query, $data)) {
              echo "Data inserted successfully.";
              echo '<script>clearForm();</script>';
          } else {
            $errors=[];
            $_POST['nome_comp']='';
            $_POST['bi']='';
            $_POST['data_emi']='';
            $_POST['data_exp']='';
            $_POST['contacto']='';
            $_POST['email']='';
            $_POST['tipo']='';
          $_POST['bi_pdf'] = '';
            echo '<div class="alert alert-success col-md-2">Inserido com Sucesso!</div>';
            echo '<script>clearForm();</script>';
          }
      } else {
        
          echo "Error uploading the PDF file.";
      }


  } 
}
}

//$redi=ROOT .'/home/imprimir';



?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="<?= ROOT ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar {
            width: 8px;

        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(black, black);
            border: 1px solid var(--white);
            border-radius: 6px;

        }

        html,
        body {
            width: 100%;
            height: 100%;
        }

        .form-signin {
            max-width: 630px;
            padding: 1rem;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
    <!-- <link href="<?= ROOT ?>/assets/css/sign-in.css" rel="stylesheet"> -->
</head>

<body>

<div class="col-md-6 mx-auto">
    <form id="utenteform"method="POST" enctype="multipart/form-data">
      <h1 class="h3 mb-3 fw-normal">Novo Utente</h1>
      <hr class="my-4" />

      <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">Por favor resolva os erros abaixo </div>
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
          <div class="form-check custom-control custom-radio"><input class="form-check-input custom-control-input" type="radio" name="tipo" id="tipo_Estudante" checked value="Estudante"><label class="form-check-label custom-control-label" for="tipo4_2">Estudante</label></div>
          <div class="form-check custom-control custom-radio"><input class="form-check-input custom-control-input" type="radio" name="tipo" id="tipo_Outro" value=" Outro"><label class="form-check-label custom-control-label" for="tipo4_3"> Outro</label></div>
        </div>
      </div>

      <a href="<?= ROOT ?>/admin/utentes/">
        <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar">Voltar</button>
      </a>
      <button class="mt-4 btn btn-primary w-20" type="button" id="btvoltar" onclick="clearForm()">Limpar</button>
      <button  class="mt-4 btn btn-primary w-20 float-end" type="submit" id="btnSubmeter">Adicionar</button>
    </form>

  </div>
  <script>
        // Function to clear the form
        function clearForm() {
            document.getElementById("utenteform").reset();
            document.getElementById("nome_comp").value = "";
            document.getElementById("email").value = "";
            document.getElementById("bi").value = "";
            document.getElementById("data_emi").value = "";
            document.getElementById("data_exp").value = "";
            document.getElementById("contacto").value = "";
        }
    </script>

</body>

</html>