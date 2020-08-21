<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
require_once (__DIR__ . '/../../vendor/autoload.php');
use \Progress\Application\Settings;
use \Progress\Application\Auth;
$settings = new Settings;
$basePath = $settings->get('site_path');

$auth = new Auth;

if (!isset($_SESSION['userId']) || !is_numeric($_SESSION['userId'])) {
	header("Location: " . $basePath . 'login');
}

$auth->setUserId($_SESSION['userId']);
if (!$userInfo = $auth->getUser()) {
	header("Location: " . $basePath . 'login');
}
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title><?php echo $settings->get('label_company_name'); ?></title>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link href="<?php echo $basePath; ?>assets/vendor/jalendar/css/jalendar.min.css" rel="stylesheet">

		<!-- fonts -->
		<link href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" rel="stylesheet" >
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="<?php echo $basePath; ?>assets/css/fonts/fonts.css" rel="stylesheet">
		<link href="<?php echo $basePath; ?>assets/css/app.css" rel="stylesheet">
		<script src="https://unpkg.com/feather-icons"></script>
	</head>

	<body>
		<div class="container-fluid">
			<div class="row">
				<nav class="col-md-2 d-none d-md-block sidebar">
					<div class="sidebar-sticky">
						<div class="row no-gutters">
							<div class="col-lg-8 offset-lg-2 text-center pb-2 profile-container">
								<a href="<?php echo $basePath; ?>">
									<img src="<?php echo $basePath; ?>assets/images/logo-dark-blue.svg" class="img-fluid mb-1">
								</a>
								<!--<div class="profile <?php echo isset($navigationPane) && $navigationPane == 'home' ? ' active' : ''; ?> rounded-circle">
									<a href="<?php echo $basePath; ?>">
										<img src="<?php echo $basePath; ?>assets/images/nikolaj.png" class="rounded-circle img-fluid" />
									</a>
								</div> -->
							</div>
						</div>
						<div class="row pt-2">
							<div class="col-lg-12 text-center">
								<h4 class="user"><?php echo $userInfo->name; ?></h4>
							</div>
						</div>
						<div class="row pt-3 no-gutters">
							
							<div class="col-12 content-nav">
								<ul class="nav flex-column">
									<li>
										<h6 class="mt-4 mb-1 text-muted nav-heading">
											<span class="icon"><div class="fi fi-baseline"><i data-feather="codepen"></i></div></span>
											<span class="title">Content</span>
										</h6>
									</li>
									<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'content-partials' ? ' active' : ''; ?>">
										<a class="nav-link" href="<?php echo $basePath; ?>content/partials">
											<span class="title">Partials</span>
										</a>
									</li>
									<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'content-projects' ? ' active' : ''; ?>">
										<a class="nav-link" href="<?php echo $basePath; ?>content/projects">
											<span class="title">Projects</span>
										</a>
									</li>

									<li>
										<h6 class="mt-4 mb-1 text-muted nav-heading">
											<span class="icon"><div class="fi fi-baseline"><i data-feather="users"></i></div></span>
											<span class="title">Contacts</span>
										</h6>
									</li>
									<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'contacts-search' ? ' active' : ''; ?>">
										<a class="nav-link" href="<?php echo $basePath; ?>contacts/search">
											<span class="title">Search</span>
										</a>
									</li>
									<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'contacts-files' ? ' active' : ''; ?>">
										<a class="nav-link" href="<?php echo $basePath; ?>contacts/files">
											<span class="title">Import</span>
										</a>
									</li>
									<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'contacts-export' ? ' active' : ''; ?>">

										<a class="nav-link" href="<?php echo $basePath; ?>contacts/export">
											<span class="title">Export</span>
										</a>
									</li>
									<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'contacts-settings' ? ' active' : ''; ?>">

										<a class="nav-link" href="<?php echo $basePath; ?>contacts/settings">
											<span class="title">Settings</span>
										</a>
									</li>
									<li>
										<h6 class="mt-4 mb-1 text-muted nav-heading">
											<span class="icon"><div class="fi fi-baseline"><i data-feather="feather"></i></div></span>
											<span class="title">Domains</span>
										</h6>
									</li>
									<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'domains-overview' ? ' active' : ''; ?>">
										<a class="nav-link" href="<?php echo $basePath; ?>domains/overview">
											<span class="title">Overview</span>
										</a>
									</li>
									<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'domains-settings' ? ' active' : ''; ?>">
										<a class="nav-link" href="<?php echo $basePath; ?>domains/settings">
											<span class="title">Settings</span>
										</a>
									</li>
									<li>
										<h6 class="mt-4 mb-1 text-muted nav-heading">
											<span class="icon"><div class="fi fi-baseline"><i data-feather="log-out"></i></div></span>
											<span class="title">Session</span>
										</h6>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="<?php echo $basePath; ?>logout">
											<span class="title">Logout</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</nav>
				<div class="col-lg-12 d-block d-sm-none bg-white">
					<nav class="navbar navbar-expand-lg navbar-light bg-white">
						<a class="navbar-brand" href="<?php echo $basePath; ?>"><img src="<?php echo $basePath; ?>assets/images/logo-dark-blue.svg" style="width: 200px;" class="img-fluid mb-3 pt-3"></a>
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>

						<div class="collapse navbar-collapse" id="navbarSupportedContent">
							<div class="row mt-4">
								<div class="col">
									<ul class="navbar-nav mr-auto">
										<li>
											<h6 class="text-muted nav-heading">
												<span class="icon"><div class="fi fi-baseline"><i data-feather="codepen"></i></div></span>
												<span class="title">Content</span>
											</h6>
										</li>
										<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'content-landers' ? ' active' : ''; ?>">
											<a class="nav-link" href="<?php echo $basePath; ?>content/landers">
												<span class="title">Landers</span>
											</a>
										</li>
										<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'content-blocks' ? ' active' : ''; ?>">
											<a class="nav-link" href="<?php echo $basePath; ?>content/blocks">
												<span class="title">Blocks</span>
											</a>
										</li>
										<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'content-templates' ? ' active' : ''; ?>">
											<a class="nav-link" href="<?php echo $basePath; ?>content/templates">
												<span class="title">Templates</span>
											</a>
										</li>
									</ul>
								</div>
								<div class="col">
									<ul class="navbar-nav mr-auto">
										<li>
											<h6 class="text-muted nav-heading">
												<span class="icon"><div class="fi fi-baseline"><i data-feather="users"></i></div></span>
												<span class="title">Contacts</span>
											</h6>
										</li>
										<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'contacts-search' ? ' active' : ''; ?>">
											<a class="nav-link" href="<?php echo $basePath; ?>contacts/search">
												<span class="title">Search</span>
											</a>
										</li>
										<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'contacts-import' ? ' active' : ''; ?>">
											<a class="nav-link" href="<?php echo $basePath; ?>contacts/import">
												<span class="title">Import</span>
											</a>
										</li>
										<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'contacts-export' ? ' active' : ''; ?>">
											<a class="nav-link" href="<?php echo $basePath; ?>contacts/export">
												<span class="title">Export</span>
											</a>
										</li>
										<li class="nav-item<?php echo isset($navigationPane) && $navigationPane == 'contacts-settings' ? ' active' : ''; ?>">
											<a class="nav-link" href="<?php echo $basePath; ?>contacts/settings">
												<span class="title">Settings</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<ul class="navbar-nav mr-auto">
										<li>
											<h6 class="mt-4 mb-1 text-muted nav-heading">
												<span class="icon"><div class="fi fi-baseline"><i data-feather="log-out"></i></div></span>
												<span class="title">Session</span>
											</h6>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="<?php echo $basePath; ?>logout">
												<span class="title">Logout</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</nav>
				</div>
				<main class="main ml-sm-auto col-md-10 offset-md-2 px-md-5" role="main">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
						<h4 class="page-title"><?php echo isset($pageTitle) ? $pageTitle : ''; ?>
							<?php echo isset($pageSubTitle) ? '<br /><span class="page-sub-title">' . $pageSubTitle . '</span>' : ''; ?>
						</h4>
					</div>