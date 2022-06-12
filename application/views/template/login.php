<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $title; ?></title>
		<link href="<?php echo base_url('assets/css/login/style.css') ?>" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<?php if(file_exists("VERSION")) echo "Ver. 1.0.".file_get_contents("VERSION"); ?>
		<div id="wrap">
			<center><img src="assets/images/logo.png" width="349" height="96" align="middle"/></center>
			<?php if(isset($error) && !empty($error)): ?>
				<p class="alert-danger"><?php echo $error; ?></p>
			<?php endif; ?>
			<div id="form">
				<form action="login" method="POST">
					<input type="text" name="username" placeholder="Utilisateur" required>
				    <input type="password" name="password" placeholder="Mot de passe" required>
				    <button name="loginSubmit" type="submit">Connexion</button>
				</form>
			</div>
		</div>
	</body>
</html>
