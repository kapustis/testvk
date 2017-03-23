<?php foreach ($items as $item): ?>
	<li>
		<p><?php echo $item['id']; ?></p>
		<p><?php echo $item['text']; ?></p>

			<?php
			if (isset($arri[$item->id])) {
					echo "<ul>";
					include "comment_inc.php";
					echo "</ul>";
			}
			?>
	</li>
<?php endforeach; ?>