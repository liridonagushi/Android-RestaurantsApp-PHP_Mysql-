<?php
          require_once('config.inc.php');
          $Exist=0;

          $user = $connection->query('SELECT id_user, username FROM users WHERE username="'.$_POST['username'].'"');
          $row_user=$user->fetch(PDO::FETCH_ASSOC);

          $orders = $connection->query('SELECT * FROM tablet_orders WHERE id_staff="'.$row_user['id_user'].'" AND table_number="'.$_POST['table_number'].'"');

          $pos = $connection->query('SELECT InvoiceNo, number_table, StaffID FROM pos WHERE StaffID="'.$row_user['id_user'].'" AND number_table="'.$_POST['table_number'].'" AND end_session=0');

          if($pos->rowCount()){
            $findInvoice=$pos->fetch(PDO::FETCH_ASSOC);
            $InvNr=$findInvoice['InvoiceNo'];
            $Exist=1;
          }else if(!$pos->rowCount()){
            $Exist=0;
            $pos = $connection->query('SELECT max(InvoiceNo)+1 AS maxNr FROM pos');
            $rowInv=$pos->fetch(PDO::FETCH_ASSOC);
            $InvNr=$rowInv['maxNr'];
          }

            $orders_transfer = $connection->query('SELECT tablet_orders.id_product, SUM(tablet_orders.qty) AS qty, products.sold_price FROM tablet_orders LEFT JOIN products ON tablet_orders.id_product=products.id_product WHERE tablet_orders.id_staff="'.$row_user['id_user'].'" AND tablet_orders.table_number="'.$_POST['table_number'].'" GROUP BY tablet_orders.id_product');
          //If Invoice number not registered to database
          if($Exist==0){

            $calc=0;
            $find_qty=0;
            $item_price=0;
            $itemQtyPrice=0;
            $total_price=0;

            while($row_orders=$orders_transfer->fetch(PDO::FETCH_ASSOC)){

                $find_qty=$row_orders['qty'];
                $item_price=$row_orders['sold_price'];
                $itemQtyPrice=$find_qty * $item_price;

                $create_posdetails = $connection->exec('INSERT INTO posdetails(InvoiceNo, id_product, quantity, total_amount)VALUES("'.$InvNr.'","'.$row_orders['id_product'].'", "'.$row_orders['qty'].'", "'.$itemQtyPrice.'")');

                $total_price +=$itemQtyPrice;
            }

            $create_pos = $connection->exec('INSERT INTO pos(number_table, StaffID, TotalAmount, end_session, payment_status)VALUES("'.$_POST['table_number'].'","'.$row_user['id_user'].'", "'.$total_price.'", "0", "3")');
            $DeleteTabletOrders = $connection->exec('DELETE FROM tablet_orders WHERE table_number="'.$_POST['table_number'].'"');
            $AlterTabletOrders = $connection->exec('ALTER TABLE tablet_orders AUTO_INCREMENT = 1');

          }else{
              //If Invoice number already registered to database
            $calc=0;
            $find_qty=0;
            $item_price=0;
            $itemQtyPrice=0;
            $total_price=0;

            while($row_orders=$orders_transfer->fetch(PDO::FETCH_ASSOC)){

                $find_qty=$row_orders['qty'];
                $item_price=$row_orders['sold_price'];
                $itemQtyPrice=$find_qty * $item_price;

                $create_posdetails = $connection->exec('INSERT INTO posdetails(InvoiceNo, id_product, quantity, total_amount)VALUES("'.$InvNr.'","'.$row_orders['id_product'].'", "'.$row_orders['qty'].'", "'.$itemQtyPrice.'")');

                $total_price +=$itemQtyPrice;
            }

          $posAmount=0;
          $totalAmount=0;

          $currentInvoice = $connection->query('SELECT InvoiceNo, total_amount FROM posdetails WHERE InvoiceNo="'.$InvNr.'"');
          while($row_current=$currentInvoice->fetch(PDO::FETCH_ASSOC)){
            $posAmount=$row_current['total_amount'];
            $totalAmount +=$posAmount;
          }

            $create_pos = $connection->exec('UPDATE pos SET TotalAmount="'.$totalAmount.'" WHERE InvoiceNo="'.$InvNr.'"');
            
            $DeleteTabletOrders = $connection->exec('DELETE FROM tablet_orders WHERE table_number="'.$_POST['table_number'].'"');
            $AlterTabletOrders = $connection->exec('ALTER TABLE tablet_orders AUTO_INCREMENT = 1');
          }

         //  require_once('config.inc.php');

         //  $user = $connection->query('SELECT id_user, username FROM users WHERE username="'.$_POST['username'].'"');
         //  $row_user=$user->fetch(PDO::FETCH_ASSOC);

         //  $orders = $connection->query('SELECT * FROM tablet_orders WHERE id_staff="'.$row_user['id_user'].'" AND table_number="'.$_POST['table_number'].'"');

         // if($orders->rowCount()){
         //  $row_order=$orders->fetch(PDO::FETCH_ASSOC);
         //  $sql = $connection->exec('UPDATE tablet_orders SET session_end="1" WHERE id_staff="'.$row_user['id_user'].'" AND table_number="'.$_POST['table_number'].'"');
         // }

         // if($orders->rowCount()){
         //      echo "Products ordered successfully !";
         // }
         // elseif(!$orders->rowCount())
         // {
         //  echo "Products not ordered !";
         // }
?>