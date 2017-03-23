<ol>
		<? foreach ($arri as $key => $comments) {
				if ($key !== 0) break;
//				include('comment_inc.php'['items' => $comments]);
				$items = $comments;
				include('comment_inc.php');
		}
		?>
</ol>