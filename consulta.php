<?php
/**
 * Created by PhpStorm.
 * User: Cristian
 * Date: 03-05-2017
 * Time: 23:19
 */

//include("../libs_php/db/db.php");
include("db.php");


	
				$db = new MySQL_Database();
	
	//var_dump($Os);
		
	 //agrego contrato tipo ingreso

		   $qOptClie = "SELECT * FROM `recorridos`";

		   

		   
		   $res = $db->ExecuteQuery($qOptClie);
		   
		   
		   echo '<pre>';
		   var_dump($res);
		  
		   
?>