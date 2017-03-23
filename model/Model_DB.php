<?php

/**
 * Created by PhpStorm.
 * User: kapustis
 * Date: 22.03.2017
 * Time: 10:43
 */
class Model_DB
{
		public function __construct()
		{
				$this->ins_db = new mysqli(HOST, USER, PASSWORD, DB_NAME);
				if ($this->ins_db->connect_error) {
//						throw new DbException("Ошибка соединения : " . $this->ins_db->connect_errno . "|" . iconv("CP1251", "UTF-8", $this->ins_db->connect_error));

				}
				$this->ins_db->query("SET NAMES 'UTF8'");

		}

		/**
		 * @param $param
		 * @param $table
		 * @param array $where
		 * @param bool $order
		 * @param string $napr
		 * @param bool $limit
		 * @param array $operand
		 * @param array $match
		 * @return array|bool
		 */
		public function select(
				$param,
				$table,
				$where = array(),
				$order = false,
				$napr = "ASC",
				$limit = false,
				$operand = array('='),
				$match = array()
		)
		{
				$sql = "SELECT";

				foreach ($param as $item) {
						$sql .= ' ' . $item . ',';
				}
				$sql = rtrim($sql, ',');
				$sql .= ' ' . 'FROM' . ' ' . $table;

				if (count($where) > 0) {
						$ii = 0;
						foreach ($where as $key => $val) {
								if ($ii == 0) {
										if ($operand[$ii] == "IN") {
												$sql .= " WHERE " . strtolower($key) . " " . $operand[$ii] . "(" . $val . ")";
										} else {
												$sql .= ' ' . ' WHERE ' . strtolower($key) . ' ' . $operand[$ii] . "'" . $this->ins_db->real_escape_string($val) . "'";
										}
								}
								if ($ii > 0) {
										if ($operand[$ii] == "IN") {
												$sql .= " AND " . strtolower($key) . " " . $operand[$ii] . "(" . $val . ")";
										} else {
												$sql .= ' ' . ' AND ' . strtolower($key) . ' ' . $operand[$ii] . "'" . $this->ins_db->real_escape_string($val) . "'";
										}
								}
								$ii++;
								if ((count($operand) - 1) < $ii) {
										$operand[$ii] = $operand[$ii - 1];
								}

						}
				}
				if (count($match) > 0) {
						foreach ($match as $k => $v) {
								if (count($where) > 0) {
										$sql .= " AND MATCH (" . $k . ") AGAINST('" . $this->ins_db->real_escape_string($v) . "')";
								} elseif (count($where) == 0) {
										$sql .= " WHERE MATCH (" . $k . ") AGAINST('" . $this->ins_db->real_escape_string($v) . "')";
								}
						}
				}

				if ($order) {
						$sql .= ' ORDER BY ' . $order . " " . $napr . ' ';
				}
//лимит
				if ($limit) {
						$sql .= " LIMIT " . $limit;
				}

				$result = $this->ins_db->query($sql);

//				if (!$result) {
//
//				}

				if ($result->num_rows == 0) {
						return false;
				}
				$row = array();
				for ($i = 0; $i < $result->num_rows; $i++) {
						$row[] = $result->fetch_assoc();
				}


				return $row;
		}

		/**
		 * @param $table
		 * @param array $data
		 * @param array $values
		 * @param bool $id
		 * @return bool|mixed
		 */
		public function insert($table, $data = array(), $values = arraY(), $id = FALSE)
		{
				//$sql = "INSERT INTO brands (brand_name,parent_id) VALUES ('TEST','0')";

				$sql = "INSERT INTO " . $table . " (";

				$sql .= implode(",", $data) . ") ";

				$sql .= "VALUES (";

				foreach ($values as $val) {
						$sql .= "'" . $val . "'" . ",";
				}

				$sql = rtrim($sql, ',') . ")";

				$result = $this->ins_db->query($sql);

				if (!$result) {
						return false;
				}

				if ($id) {
						return $this->ins_db->insert_id;
				}
				return true;
		}

		public function update($table, $data = array(), $values = array(), $where = array())
		{
				//$sql = "UPDATE brands SET brand_name='TES1',parent_id='1' WHERE brand_id = 29";
				$data_res = array_combine($data, $values);

				$sql = "UPDATE " . $table . " SET ";

				foreach ($data_res as $key => $val) {
						$sql .= $key . "='" . $val . "',";
				}

				$sql = rtrim($sql, ',');

				foreach ($where as $k => $v) {
						$sql .= " WHERE " . $k . "=" . "'" . $v . "'";
				}
				//echo $sql;
				//exit();
				$result = $this->ins_db->query($sql);

				if (!$result) {
						return false;
				}

				return true;
		}


		public function delete($table, $where = array(), $operand = array('='))
		{
				$sql = "DELETE FROM " . $table;

				if (is_array($where)) {
						if ($operand[0] == ' IN ') {
								/*
										// Начинаем формировать переменную, содержащую этот список
									// в формате "(1,2,3,8)"
									$id_zap =  "(";
									foreach($where as $val) $id_zap .= $val.",";
									$id_zap = substr($id_zap, 0, strlen($id_zap) - 1 ). ")";// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
									$sql .=  ' WHERE '.$operand[0].$id_zap;// Завершаем формирование SQL-запроса на удаление
								*/
								$i = 0;
								foreach ($where as $k => $v) {
										$sql .= ' WHERE ' . $k . $operand[$i] . "(" . $v . ")";
										$i++;
										if ((count($operand) - 1) < $i) {
												$operand[$i] = $operand[$i - 1];
										}
								}
						} else {
								//$sql = "DELETE FROM mess_db WHERE mess_id=2";
								$i = 0;
								foreach ($where as $k => $v) {
										$sql .= ' WHERE ' . $k . $operand[$i] . "'" . $v . "'";
										$i++;
										if ((count($operand) - 1) < $i) {
												$operand[$i] = $operand[$i - 1];
										}
								}
						}

				}
				#return $sql;

				$result = $this->ins_db->query($sql);
				if (!$result) {
						return false;
				}
				return true;
		}
}