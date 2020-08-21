<?php

$pageTitle = 'Contacts - Search';
$navigationPane = 'contacts-search';

if (isset($_POST) && isset($_POST['search'])) {
	$pageTitle = $pageTitle . ': ' . $_POST['search'];
}

require_once (__DIR__ . '/../app/includes/header.php');
?>
<div class="row search">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-12">
				<form action="" method="POST">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="addon-search"><i class="material-icons">search</i></span>
						</div>
						<input type="text" class="form-control" name="search" placeholder="Search by phone number or e-mail" aria-label="search" aria-describedby="addon-search">
					</div>
				</form>
			</div>
		</div>
		<?php if (isset($_POST) && isset($_POST['search'])) { ?>
			<div class="row search-result-heading mt-4">
				<div class="col-lg-1">Internal ID</div>
				<div class="col-lg-2">E-mail</div>
				<div class="col-lg-2">Number</div>
				<div class="col-lg-2">Data Provider</div>
				<div class="col-lg-2">Subscription start</div>
				<div class="col-lg-2">Subscription IP</div>
				<div class="col-lg-1"></div>
			</div>

			<div class="row">
				<div class="col-lg-12 search-result-bar mb-2">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-lg-1">#1</div>
								<div class="col-lg-2">nj@codefighter.dk</div>
								<div class="col-lg-2">+45 27299072</div>
								<div class="col-lg-2">Static Magnet 2017</div>
								<div class="col-lg-2">25-10-2018</div>
								<div class="col-lg-2">25.255.214.51</div>
								<div class="col-lg-1 text-center"><a href="#" data-toggle="tooltip" title="Queue Permission Slip" data-placement="bottom"><div class="fi fi-baseline"><i data-feather="download"></i></div></a></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12 search-result-bar">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-lg-1">#2</div>
								<div class="col-lg-2">nj@codefighter.dk</div>
								<div class="col-lg-2">+45 27299072</div>
								<div class="col-lg-2">Euroads 2016</div>
								<div class="col-lg-2">25-10-2018</div>
								<div class="col-lg-2">25.255.214.51</div>
								<div class="col-lg-1 text-center"><a href="#" data-toggle="tooltip" title="Queue Permission Slip" data-placement="bottom"><div class="fi fi-baseline"><i data-feather="download"></i></div></a></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php
require_once (__DIR__ . '/../app/includes/footer.php');
?>