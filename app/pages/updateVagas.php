<?php
    $servername="localhost";
    $username="root";
    $password="";
    $db_name="maxa_park";
    
    
    $conn=new mysqli($servername,$username,$password,$db_name);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
    
     $sql="SELECT * FROM `vagas` where estado='livre' ORDER BY id_vaga ASC";
     $vagas_livre = $conn->query($sql);

    if ($vagas_livre->num_rows === 0) {
        echo '<div class="painel-vazio text-center">Sem vagas disponíveis — parque lotado.</div>';
    }

    while($row = mysqli_fetch_assoc($vagas_livre)){
        echo '<div class="painel-vaga">';
        echo '<p class="codigo">Lugar #'.$row["id_vaga"].'</p>';
        echo '<h1 class="nome">'.$row["nome"].'</h1>';
        echo '<p class="sector">Sector '.$row["sector"].'</p>';
        echo '</div>';
    }
?>