<?php
/**
 * Created by PhpStorm.
 * User: kapustis
 * Date: 13.03.2017
 * Time: 23:28
 */
session_start();

require_once "function/function.php";
require_once "model/Message.php";
$vi = new Message();

$data = $_POST;
if (isset($data['send_message'])) {

//		echo "<pre>";
//		print_r($data);
//		echo "</pre>";
		/*подготовка данных */
		$text = $data['message'];
		$use_id = $_SESSION['user']->response[0]->id;
		$created = date("Y-m-d H:i:s");
		$parent_id = $data ['CommentParent'];
		/*подготовка данных*/
		$vi->add_message($data['message'], $use_id, $created, $parent_id);

		header("Location:" . 'http://testvk');
}
?>
<div class="row">
		<?php
		print_arr($vi->get_message());
		$arr = $vi->get_message(); ?>
	<ul>
			<?php foreach ($arr as $item): ?>
				<li>
					<p><? echo $item['id']; ?> </p>
						<?php if (isset($item['childs'])): ?>

						<?php endif; ?>
				</li>
			<?php endforeach; ?>
	</ul>
</div>

