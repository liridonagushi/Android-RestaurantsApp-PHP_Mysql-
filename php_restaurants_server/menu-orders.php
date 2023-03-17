<?php
     if(isset($_POST['searchQuery']))
     {
	        require_once('config.inc.php');
	    	$search_query=$_POST['searchQuery'];

          $user = $connection->query('SELECT id_user, username FROM users WHERE username="'.$_POST['username'].'"');
          $row_user=$user->fetch(PDO::FETCH_ASSOC);


          $findOrders = $connection->query('SELECT * FROM pos WHERE number_table="'.$_POST['number_table'].'" AND StaffID="'.$row_user['id_user'].'"');
          if($findOrders->rowCount()>0){

	          $sql = $connection->query('SELECT posdetails.id_product, categories.category_name, products.product_name, products.sold_price, SUM(posdetails.quantity) AS qty, pos.number_table FROM posdetails LEFT JOIN products ON posdetails.id_product=products.id_product LEFT JOIN categories ON products.id_category=categories.id_category LEFT JOIN pos ON posdetails.InvoiceNo=pos.InvoiceNo WHERE pos.number_table = "'.$search_query.'" GROUP BY posdetails.id_product');

	          if($sql->rowCount())
	          {
	              $row_all = $sql->fetchall(PDO::FETCH_ASSOC);
	                   header('Content-type: application/json');
	                   echo json_encode($row_all);
	          }
	          elseif(!$sql->rowCount())
	          {
	          	echo "no_rows";
	          }

          }else{
          echo "used_table";
          }
     }
?>