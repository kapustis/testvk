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