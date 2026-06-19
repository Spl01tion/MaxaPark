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
              
          } else {
              echo "Error inserting data.";
              
              
          }
      } else {
          echo "Error uploading the PDF file.";
      }
  }
  
}
}
?>