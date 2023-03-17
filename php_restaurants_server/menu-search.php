<?php

     if(isset($_POST['searchQuery']))
     {
     	  require_once('config.inc.php');
	  	  $search_query=$_POST['searchQuery'];

          $user = $connection->query('SELECT id_user, username FROM users WHERE username="'.$_POST['Username'].'"');
          $row_user=$user->fetch(PDO::FETCH_ASSOC);

          $sql = $connection->query('SELECT DISTINCT products.id_product, categories.category_name, products.product_name, products.sold_price, tablet_orders.id_staff, SUM(tablet_orders.qty) AS qty FROM products LEFT JOIN tablet_orders ON (products.id_product=tablet_orders.id_product AND tablet_orders.id_staff="'.$row_user['id_user'].'" AND tablet_orders.table_number="'.$_POST['table_number'].'") LEFT JOIN categories ON products.id_category=categories.id_category WHERE products.product_name LIKE "%'.$search_query.'%" OR categories.category_name LIKE "%'.$search_query.'%" GROUP BY products.id_product');

          if($sql->rowCount())
          {
		    $row_all = $sql->fetchall(PDO::FETCH_ASSOC);
			    header('Content-type: application/json');
		   	    echo json_encode($row_all);
          }

          elseif(!$sql->rowCount())
          {
	    	echo "no rows";
          }
     }
?>