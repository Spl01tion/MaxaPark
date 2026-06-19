<?php
//Adicionar Novo
if ($action == 'add') {
  if (!empty($_POST)) {
    //Validacao
    $errors = [];

    if (empty($_POST['sector'])) {
      $errors['sector'] = 'Sector é obrigatório';
    } else
        if (strlen($_POST['sector']) < 1) {
      $errors['sector'] = 'Sector invalido!';
    }

    $query = "SELECT id_vaga FROM vagas WHERE nome = :nome LIMIT 1";
    $username = query($query, ['nome' => $_POST['nome']]);
    if (empty($_POST['nome'])) {
      $errors['nome'] = 'Nome é obrigatório';
    } else
        if (strlen($_POST['nome']) < 1) {
      $errors['nome'] = 'Nome invalido!';
    } else
        if ($username) {
      $errors['nome'] = "Este nome já esta em uso!";
    }


    if (empty($errors)) {
      //salvar DB
      $data = [];
      $data['nome']     = $_POST['nome'];
      $data['sector']  = $_POST['sector'];
      $data['estado']     = "livre";


      $query = "INSERT INTO vagas (nome, sector, estado) VALUES (:nome, :sector, :estado);";

      query($query, $data);


      redirect('admin/vagas');
      }
    }
  } else
if ($action == 'edit') {
  $query = "SELECT * FROM vagas WHERE id_vaga=:id_vaga LIMIT 1";
  $row = query_row($query, ['id_vaga' => $id_vaga]); 
    if (!empty($_POST)) 
    {
      

      if ($row) {
        //Validacao
        $errors = [];

        if (empty($_POST['sector'])) {
            $errors['sector'] = 'Sector é obrigatório';
          } else
              if (strlen($_POST['sector']) < 1) {
            $errors['sector'] = 'Sector invalido!';
          }
      
          $query = "SELECT * FROM vagas WHERE nome = :nome LIMIT 1";
          $username = query($query, ['nome' => $_POST['nome']]);
          if (empty($_POST['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
          } else
              if (strlen($_POST['nome']) < 1) {
            $errors['nome'] = 'Nome invalido!';
          }
          
      

        if (empty($errors)) {
          //salvar DB
          $data = [];

          $data['nome']     = $_POST['nome'];
          $data['sector']  = $_POST['sector'];
          $data['estado']     = "livre";
          $data['id_vaga'] = $id_vaga;
          
        $query = "UPDATE vagas SET nome = :nome,sector=:sector,estado=:estado WHERE id_vaga=:id_vaga LIMIT 1";
                
         
          query($query, $data);

          redirect('admin/vagas');
        }
      }
    }
  } else
  if ($action == 'delete') {
    $query = "SELECT * FROM vagas WHERE id_vaga=:id_vaga LIMIT 1";
    $row = query_row($query, ['id_vaga' => $id_vaga]); 
      if ($_SERVER['REQUEST_METHOD']=="POST") 
      {
         
        if ($row) {
          //Validacao
          $errors = [];

          if (empty($errors)) {
            //Delete from DB
            $data = [];
            $data['id_vaga']    = $id_vaga;

            $query = "DELETE FROM vagas WHERE id_vaga=:id_vaga LIMIT 1";
            query($query, $data);
  
            redirect('admin/vagas');
          }
        }
      }
    }
