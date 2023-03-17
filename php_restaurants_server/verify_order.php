<?php
     if(isset($_POST['number_table']))
     {
	 	  require_once('config.inc.php');
	 	  if($_POST['type']=='finish_order'){

	    $sql = $connection->exec('INSERT INTO finish_orders(table_number)VALUES('.$_POST['number_table'].')');
 		$insertId = $connection->lastInsertId();

	    if($sql->rowCount())
	    {
		    echo 'Ok';
		}
	    elseif(!$sql->rowCount())
	    {
	    	echo "NotOk";
	    }
	    //elseif cancel_order
	  }else if($_POST['type']=='cancel_order'){
	    $sql = $connection->exec('DELETE FROM tablet_orders WHERE table_number="'.$_POST['number_table'].'" AND session_end="0"');
        $sql = $connection->exec('ALTER TABLE tablet_orders AUTO_INCREMENT = "1"');
	    echo 'Ok';
	  }
	// if !post -> number_table
	}else{
	echo 'NotOk';
	}
?>