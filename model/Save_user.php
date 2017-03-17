<?php

/**
 * Created by PhpStorm.
 * User: kapustis
 * Date: 13.03.2017
 * Time: 11:26
 */
class Save_user
{
		protected $mysqli;

		public function __construct()
		{
				require_once "./config.php";
				$this->mysqli = new mysqli(HOST, USER, PASSWORD, DB_NAME);
		}

		public function add_users($id_vk, $first_name, $last_name, $photo, $city)
		{
				$values = array(
						"first_name" => $first_name,
						"last_name" => $last_name,
						"city" => $city,
						"photo" => $photo
				);
				$count_u = $this->mysqli->query("SELECT id_vk FROM users WHERE id_vk=" . $id_vk . ";");

				if ($count_u->num_rows > 0) { // есть id обнавляем данные
						$sql = 'UPDATE users SET ';

						foreach ($values as $key => $val) {
								$sql .= $key . "='" . $val . "',";
						}
						$sql = rtrim($sql, ',');

						$sql .= " WHERE id_vk=" . $id_vk;
//			print_r($sql);
						$this->mysqli->query($sql);

				} else { //если нет сохраняем
						$sql = "INSERT INTO users (id_vk,first_name,last_name,city,photo) VALUES (";
						foreach ($values as $key => $val) {
								$sql .= $key . "='" . $val . "',";
						}
						$sql = rtrim($sql, ',');


						$result = $this->mysqli->query($sql);
						if ($result != true) {
								return false;
						}
						return true;
				}
		}


		public function get_users()
		{
				$sql = "SELECT * FROM users";

				$result = $this->mysqli->query($sql);
				return $result;
		}

}