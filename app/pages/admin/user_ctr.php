<?php
//Adicionar Novo
if ($action == 'add') {
  if (!empty($_POST)) {
    //Validacao
    $errors = [];

    if (empty($_POST['nome'])) {
      $errors['nome'] = 'Nome é obrigatório';
    } else
        if (strlen($_POST['nome']) < 3) {
      $errors['nome'] = 'Nome invalido!';
    }

    if (empty($_POST['apelido'])) {
      $errors['apelido'] = 'apelido é obrigatório';
    } else
        if (strlen($_POST['apelido']) < 3) {
      $errors['apelido'] = 'apelido invalido!';
    }

    $query = "SELECT id_user FROM users WHERE username = :username LIMIT 1";
    $username = query($query, ['username' => $_POST['username']]);
    if (empty($_POST['username'])) {
      $errors['username'] = 'Username é obrigatório';
    } else
        if (!preg_match('/[a-zA-Z.]+$/', $_POST['username'])) {
      $errors['username'] = 'Username so pode ter letras e sem espaços';
    } else
        if ($username) {
      $errors['username'] = "Este username já esta em uso!";
    }

    $query = "SELECT id_user FROM users WHERE email = :email LIMIT 1";
    $email = query($query, ['email' => $_POST['email']]);

    if (empty($_POST['email'])) {
      $errors['email'] = "Email é obrigatório";
    } else
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "Email invalido!";
    } else
        if ($email) {
      $errors['email'] = "Este email já esta em uso!";
    }

    if (empty($_POST['password'])) {
      $errors['password'] = 'Password é obrigatório';
    } else if (strlen($_POST['password']) < 6) {
      $errors['password'] = 'Password deve conter pelo menos 6 ou mais caracteres';
    } else if ($_POST['password'] !== $_POST['retype_password']) {
      $errors['password'] = 'Passwords não conferem';
    }


    if (empty($errors)) {
      //salvar DB
      $data = [];
      $data['nome']     = $_POST['nome'];
      $data['apelido']  = $_POST['apelido'];
      $data['username'] = $_POST['username'];
      $data['email']    = $_POST['email'];
      $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $data['role']     = "user";


      $query = "INSERT INTO users (nome,apelido,username,email,password,role) VALUES (:nome,:apelido,:username,:email,:password,:role)";

      if(!empty($destination))
      {
        $data['image']     = $destination;
        $query = "INSERT INTO users (nome,apelido,username,email,password,role,image) VALUES (:nome,:apelido,:username,:email,:password,:role,:image)";
      }

      if (query($query, $data)) {
        echo "Data inserted successfully.";
      } else {
        echo "Error inserting data.";
      }

      redirect('admin/user');
      }
    }
  } else
if ($action == 'edit') {
  $query = "SELECT * FROM users WHERE id_user=:id_user LIMIT 1";
  $row = query_row($query, ['id_user' => $id_user]); 
    if (!empty($_POST)) 
    {
      

      if ($row) {
        //Validacao
        $errors = [];

        if (empty($_POST['nome'])) {
          $errors['nome'] = 'Nome é obrigatório';
        } else
        if (strlen($_POST['nome']) < 3) {
          $errors['nome'] = 'Nome invalido!';
        }

        if (empty($_POST['apelido'])) {
          $errors['apelido'] = 'apelido é obrigatório';
        } else
        if (strlen($_POST['apelido']) < 3) {
          $errors['apelido'] = 'apelido invalido!';
        }

        $query = "SELECT id_user FROM users WHERE username = :username && id_user != :id_user LIMIT 1";
        $username = query($query, ['username' => $_POST['username'], 'id_user' => $id_user]);
        if (empty($_POST['username'])) {
          $errors['username'] = 'Username é obrigatório';
        } else
        if (!preg_match('/[a-zA-Z.]+$/', $_POST['username'])) {
          $errors['username'] = 'Username so pode ter letras e sem espaços';
        } else
        if ($username) {
          $errors['username'] = "Este username já esta em uso!";
        }

        $query = "SELECT id_user FROM users WHERE email = :email LIMIT 1";
        $email = query($query, ['email' => $_POST['email']]);

        if (empty($_POST['email'])) {
          $errors['email'] = "Email é obrigatório";
        } else
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          $errors['email'] = "Email invalido!";
        } 

        if (empty($_POST['password'])) {
        } else if (strlen($_POST['password']) < 6) {
          $errors['password'] = 'Password deve conter pelo menos 6 ou mais caracteres';
        } else if ($_POST['password'] !== $_POST['retype_password']) {
          $errors['password'] = 'Passwords não conferem';
        }
        //validar Imagem

        $allowed = ['image/jpeg','image/png','image/webp'];
        if(!empty($_FILES['image']['name']))
        {
          $destination = "";
          if(!in_array($_FILES['image']['type'], $allowed))
          {
            $errors['image'] = "Formato não suportado";
          }else
          {
            $folder = "uploads/";
            if(!file_exists($folder))
            {
              mkdir($folder, 0777, true);
            }

            $destination = $folder . time() . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $destination);
            resize_image($destination);
          }


        }

        if (empty($errors)) {
          //salvar DB
          $data = [];
          $data['nome']     = $_POST['nome'];
          $data['apelido']  = $_POST['apelido'];
          $data['username'] = $_POST['username'];
          $data['email']    = $_POST['email'];
          $data['id_user']    = $id_user;
        
          $data['tipo']     = $_POST['tipo'];

          if(!empty($_POST['password']))
                {
                  $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                  $password_str = "password = :password, ";
                }

                if(!empty($destination))
                {
                  $image_str = "image = :image, ";
                  $data['image']       = $destination;
                }
                $query = "UPDATE users SET nome = :nome,apelido=:apelido ,username=:username,email=:email,$password_str  $image_str role=:tipo WHERE id_user=:id_user LIMIT 1";
                
         
          query($query, $data);

          redirect('admin/user');
        }
      }
    }
  } else
  if ($action == 'delete') {
    $query = "SELECT * FROM users WHERE id_user=:id_user LIMIT 1";
    $row = query_row($query, ['id_user' => $id_user]); 
      if ($_SERVER['REQUEST_METHOD']=="POST") 
      {
         
        if ($row) {
          //Validacao
          $errors = [];

          if (empty($errors)) {
            //Delete from DB
            $data = [];
            $data['id_user']    = $id_user;

            $query = "DELETE FROM users WHERE id_user=:id_user LIMIT 1";
            query($query, $data);

            if(file_exists($row['image']))
            unlink($row['image']);
  
            redirect('admin/user');
          }
        }
      }
    }
