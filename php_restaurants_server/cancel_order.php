<?php
          require_once('config.inc.php');

          $user = $connection->query('SELECT id_user, username FROM users WHERE username="'.$_POST['username'].'"');
          $row_user=$user->fetch(PDO::FETCH_ASSOC);

          $orders = $connection->query('SELECT * FROM tablet_orders WHERE id_staff="'.$row_user['id_user'].'" AND table_number="'.$_POST['table_number'].'"');

         if($orders->rowCount()){
          $row_order=$orders->fetch(PDO::FETCH_ASSOC);
          $sql = $connection->exec('DELETE FROM tablet_orders WHERE id_staff="'.$row_user['id_user'].'" AND table_number="'.$_POST['table_number'].'" AND session_end="0"');
         }

         if($orders->rowCount()){
              echo "Order cancelled successfully !";
         }
         elseif(!$orders->rowCount())
         {
          echo "Order not cancelled";
         }
?>