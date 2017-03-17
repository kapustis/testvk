<?php

/**
 * Created by PhpStorm.
 * User: kapustis
 * Date: 13.03.2017
 * Time: 11:43
 */
class Message
{
		protected $db;

		public $mysqli;

		public function __construct()
		{
				require_once "./config.php";
				$this->mysqli = new mysqli(HOST, USER, PASSWORD, DB_NAME);
		}


		public function get_message()
		{
				$arr_cat = array();
				$sql = "SELECT * FROM messages";

				$result = $this->mysqli->query($sql);

				for ($i = 0; $i < $result->num_rows; $i++) {
						$row = ($result->fetch_assoc());
						if (!isset($arr_cat[$row['parent_id']])) {
								$arr_cat[$row['parent_id']] = array();
						}
						$arr_cat[$row['parent_id']][] = $row;
				}

				return $arr_cat;
		}

		function view_message($arr, $parent_id = 0)
		{
				//Условия выхода из рекурсии
				if (empty($arr[$parent_id])) {
						return;
				}
				echo '<ul>';
				//перебираем в цикле массив и выводим на экран
				for ($i = 0; $i < count($arr[$parent_id]); $i++) {
						echo '<li><p><strong>' . $arr[$parent_id][$i]['created'] . '</strong>&nbsp;&nbsp;' . $arr[$parent_id][$i]['text'] . '</p>';
						//рекурсия - проверяем нет ли дочерних категорий
						$this->view_message($arr, $arr[$parent_id][$i]['id']);
						echo '</li>';
				}
				echo '</ul>';

		}


		public function add_message($text, $user_id, $created, $parent_id)
		{
				$sql = "INSERT INTO messages (text,user_id,created,parent_id ) VALUES(";
				$values = array($text, $user_id, $created, $parent_id);

				foreach ($values as $val) {
						$sql .= "'" . $val . "'" . ",";
				}

				$sql = rtrim($sql, ',') . ")";

//				print_r($sql);
				$result = $this->mysqli->query($sql);
				if ($result === true) {
//						echo "New record created successfully";
				} else {
						echo "Error: " . $sql . "<br>" . $this->mysqli->error;
				}

		}


}