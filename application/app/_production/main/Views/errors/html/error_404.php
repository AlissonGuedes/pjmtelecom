<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>404 Page Not Found</title>
	<?php require_once (VIEWPATH . 'templates' . DS . 'includes' . DS . 'styles.phtml'); ?>
</head>
<body>
	<div class="wrap center-align mt-10">
		<h1>404 - File Not Found</h1>

		<p>
			<?php if (! empty($message) && $message !== '(null)') : ?>
				<?= esc($message) ?>
			<?php else : ?>
				Sorry! Cannot seem to find the page you were looking for.
			<?php endif ?>
		</p>
	</div>
</body>
</html>
