<?php

class Api_Vk
{

		private $code;
		private $token;
		private $id;

		public function __construct()
		{
				require "config.php";
				require "model/Save_user.php";
		}

		public function set_code($code)
		{
				$this->code = $code;
		}

		public function set_token($token)
		{
				$this->token = $token;
		}

		public function set_uid($id)
		{
				$this->id = $id;
		}

		public function redirect($url)
		{
				header("Location:" . $url);
				exit();
		}

		public function get_token()
		{
				if (!$this->code) {
						exit("Не верный код");
				}

				$ku = curl_init();
				$query = "client_id=" . APP_ID . "&client_secret=" . APP_SECRET . "&code=" . $this->code . "&redirect_uri=" . REDIRECT_URI;
				curl_setopt($ku, CURLOPT_URL, URL_ACCESS_TOKEN . "?" . $query);
				curl_setopt($ku, CURLOPT_RETURNTRANSFER, TRUE);

				$result = curl_exec($ku);
				curl_close($ku);

				$ob = json_decode($result);
				if ($ob->access_token) {
						$this->set_token($ob->access_token);
						$this->set_uid($ob->user_id);
						return TRUE;
				} elseif ($ob->error) {
						$_SESSION['error'] = "Ошибка";
						return FALSE;
				}
		}

		public function get_user_api()
		{
				if (!$this->token) {
						exit('Wrong code');
				}

				if (!$this->id) {
						exit('Wrong code');
				}

				$query = "uids=" . $this->id . "&fields=photo_50,city&access_token=" . $this->token . "&v=5.62";
//echo $query;

				$kur = curl_init();

				curl_setopt($kur, CURLOPT_URL, URL_GET_USER . "?" . $query);
				curl_setopt($kur, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($kur, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($kur, CURLOPT_RETURNTRANSFER, TRUE);

				$result2 = curl_exec($kur);

				curl_close($kur);

				$_SESSION['user'] = json_decode($result2);

				$user = json_decode($result2)->response[0];
//				print_r($user);
				$add_user = new Save_user();

				$add_user->add_users($user->id, $user->first_name, $user->last_name, $user->photo_50, $user->city->title);

				$this->redirect("http://testvk");

		}

}
