<?
require "Api_Vk.php";

$vk = new Api_Vk();

if (!$_GET['code']) {

	$query = "client_id=" . APP_ID . "&scope=offline&redirect_uri=" . REDIRECT_URI . "&response+type=code";

	$vk->redirect(URL_AUTH . "?" . $query);
}
if ($_GET['code']) {
	//echo $_GET['code'];
	$vk->set_code($_GET['code']);
	$res = $vk->get_token();

	if ($res) {
		$vk->get_user_api();
	} else {
		exit($_SESSION['error']);
	}
}
if ($_GET['error']) {
	exit($_GET['error_description']);
}

