<?php
session_start();
require_once (__DIR__ . '/vendor/autoload.php');
use \Progress\Application\Settings;
use \Progress\Application\Auth;

$settings = new Settings;
$auth = new Auth;
$basePath = $settings->get('site_path');

if (isset($_POST) && isset($_POST['user']) && isset($_POST['password'])) {
	if ($userId = $auth->login($_POST['user'], $_POST['password'])) {
		$_SESSION['userId'] = $userId;
		header("Location: $basePath");
	} else {
		$message = 'Unable to match user on record.';
	}
}

?>
<!DOCTYPE html>
<html lang="en" class="login">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title></title>

		<link href="<?php echo $basePath; ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo $basePath; ?>assets/vendor/jalendar/css/jalendar.min.css" rel="stylesheet">

		<!-- fonts -->
		<link href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" rel="stylesheet" >
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="<?php echo $basePath; ?>assets/css/fonts/fonts.css" rel="stylesheet">
		<link href="<?php echo $basePath; ?>assets/css/app.css" rel="stylesheet">
		<script src="https://unpkg.com/feather-icons"></script>
	</head>

	<body class="login">
		<div class="container bg-login-gradient">
			<div class="row">
				<div class="col-lg-4 offset-lg-4">
				</div>
				<div class="col-lg-4 offset-lg-4 login-form">
					<div class="logo text-center py-2">
						<img src="assets/images/logo-dark-blue.svg">
					</div>
					<div class="inner">
						<form action="" method="POST">
							<span class="error-message"><?php echo isset($message) ? $message : ''; ?></span>
							<div class="form-group">
								<label for="user">Email</label>
								<input type="text" name="user" class="form-control" required>
							</div>
							<div class="form-group">
								<label for="user">Password</label>
								<input type="password" name="password" class="form-control" required>
							</div>
							<div class="form-group">
								<input type="submit" class="btn btn-sm btn-primary" value="Login">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>