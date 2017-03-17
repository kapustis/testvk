<?php
/**
 * Created by PhpStorm.
 * User: kapustis
 * Date: 15.03.2017
 * Time: 15:46
 */

$mysqli = new mysqli("localhost", "root", "", "promo");

$sql = "SELECT * FROM messages";

$result = $mysqli->query($sql);

for($i = 0; $i < $result->num_rows; $i++) {
		$row[] = $result->fetch_assoc();
}

		echo "<pre>";
		print_r($row);
		echo "</pre>";
