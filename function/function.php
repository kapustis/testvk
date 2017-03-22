<?php
/**
 * Created by PhpStorm.
 * User: kapustis
 * Date: 16.03.2017
 * Time: 19:56
 */
if ($_GET['do'] == 'logout') { // выход
	unset($_SESSION['user']);
	header("Location: http://testvk");
}

function print_arr($arr){
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
}