<?php
          require_once('config.inc.php');

          $user = $connection->query('SELECT id_user, username FROM users WHERE username="'.$_POST['username'].'"');
          $row_user=$user->fetch(PDO::FETCH_ASSOC);

          $orders = $connection->query('SELECT * FROM tablet_orders WHERE id_product="'.$_POST['id_product'].'" AND id_staff="'.$row_user['id_user'].'" AND table_number="'.$_POST['table_number'].'" AND session_end=0');

         if($orders->rowCount()>0){
          $row_order=$orders->fetch(PDO::FETCH_ASSOC);
          $sql = $connection->exec('DELETE FROM tablet_orders WHERE id_product="'.$_POST['id_product'].'" AND id_staff="'.$row_user['id_user'].'" AND table_number="'.$_POST['table_number'].'" AND session_end=0 LIMIT 1');
         }

         if($orders->rowCount()){
              echo "Product removed successfully !";
         }
         elseif(!$orders->rowCount())
         {
          echo "Product could not be removed !";
         }
?>