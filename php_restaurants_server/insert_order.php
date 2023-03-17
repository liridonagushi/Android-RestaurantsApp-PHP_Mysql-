<?php
          require_once('config.inc.php');

          $user = $connection->query('SELECT id_user, username FROM users WHERE username="'.$_POST['username'].'"');
          $row_user=$user->fetch(PDO::FETCH_ASSOC);

          if($_POST['product_details']==""){
               $prod_details="";
          }else{
               $prod_details=$_POST['product_details'];
          }

         $sql = $connection->exec('INSERT INTO tablet_orders(id_product, id_staff, qty, table_number, product_details)VALUES("'.$_POST['id_product'].'","'.$row_user['id_user'].'","1","'.$_POST['table_number'].'","'.$prod_details.'")');

         $insertId = $connection->lastInsertId();

         if($insertId>0){
              echo "New order inserted !";
         }
         else
         {
          echo "New order not inserted !";
         }
?>