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
				$arr_mess = array();
				$sql = "SELECT * FROM messages";

				$result = $this->mysqli->query($sql);

				for($i = 0; $i < $result->num_rows;$i++) {
						$row = $result->fetch_array();

						//Формируем массив, где ключами являются адишники на родительские категории
						if(empty($arr_mess[$row['parent_id']])) {
								$arr_mess[$row['parent_id']] = array();
						}
						$arr_mess[$row['parent_id']][] = $row;
				}

//				while ($row = ($result->fetch_assoc())) {
//						$arr_mess[$row['id']] = $row;
//				}
//				$tree = array();
//				foreach ($arr_mess as $id => &$node) {
//						if (!$node['parent_id']) {
//								$tree[$id] = &$node;
//						} else {
//								$arr_mess[$node['parent_id']]['childs'][$id] = &$node;
//						}
//				}

				return $arr_mess;
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