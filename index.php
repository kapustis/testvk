<?php
session_start();
require_once "function/function.php";
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" href="libs/foundation/css/foundation.css">
	<link rel="stylesheet" href="libs/foundation/css/app.css">
	<link rel="stylesheet" href="libs/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<title>Тестовый сайт</title>
</head>
<body>
<header class="main_head">
	<div <?php if (isset($_SESSION['user'])) echo 'hidden'; ?>>
		<a class="hollow button" href="auth.php">VK.com</a>
		<a class="hollow button alert" href="#">Google+</a>
		<a class="hollow button" href="#">Facebook</a>
	</div>
</header>

<section class="user_s">
		<?php
		if ($_SESSION['user']) :
				$user = $_SESSION['user']->response[0];
				?>
			<div class="row">
				<div class="medium-6 columns">
					<div class="person">
						<img src="<?php echo $user->photo_50; ?>">&numsp;
						<p> <?php echo $user->first_name; ?></p>
						<p><?php echo $user->last_name; ?></p>
						<a href="?do=logout"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		<?php endif; ?>
</section>

<section class="send_message">
	<div class="row">
			<?php if (empty($_SESSION['user']))
					echo "<p>Для добавления и комментирования сообщений выполните вход* <i class=\"fa fa-level-up\" aria-hidden=\"true\"></i></p>"; ?>
		<form action="message_page.php" method="post">
			<div class="row">
				<div class="input-group">
					<textarea name="message" rows="3"></textarea>
					<div class="input-group-button">
						<button type="submit" class="button" name="send_message" <?php if (empty($_SESSION['user'])) echo 'disabled'; ?>>Отправить</button>
					</div>
				</div>
				<input type="hidden" name="CommentParent" id="CommentParent" value="0">
		</form>
	</div>
</section>

<section id="get_mess">
		<?php require_once "message_page.php"; ?>

</section>

<footer class="row">
	<div class="large-12 columns">
		<hr/>
		<div class="row">
			<div class="large-6 columns">
				<p>&copy; <?php echo date("Y"); ?></p>
			</div>
		</div>
	</div>
</footer>

</body>

</html>