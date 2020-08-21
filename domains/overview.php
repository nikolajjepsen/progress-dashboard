<?php

require_once (__DIR__ . '/../vendor/autoload.php');
use \Progress\Domain\Domains;
use \Progress\Application\Settings;

$domains = new Domains;
$settings = new Settings;

$pageTitle = 'Domains - Overview';
$navigationPane = 'domains-overview';

if (isset($_GET['deleteDomain']) && isset($_GET['domainId'])) {
	$domains->deleteDomain($_GET['domainId']);

	header("Location: ". $settings->get('site_path') . "domains/overview");
}

require_once (__DIR__ . '/../app/includes/header.php');
?>

<div class="row landers">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-8 float-left">
						<b class="card-title">List all domains</b>
					</div>
					<div class="col-4 float-right text-right">
						<a href="#" class="btn btn-sm btn-primary"><div class="fi fi-baseline"><i data-feather="plus-circle"></i></div> New</a>
					</div>
				</div>
				<table class="table">
					<thead>
						<tr>
							<td><b>Domain</b></td>
							<td><b>Type</b></td>
							<td><b>Status</b></td>
							<td><b>Last Check</b></td>
							<td><b></b></td>
						</tr>
					</thead>
					<tbody>
						<?php 
						if ($getDomains = $domains->getDomains()) {
							foreach ($getDomains as $domain) {
							?>
								<tr>
									<td><a href="<?php echo $basePath; ?>domains/single/<?php echo $domain->id; ?>"><?php echo $domain->domain; ?></a></td>
									<td><?php echo $domain->type; ?></td>
									<td>
										<?php
										if (empty($domain->flags)) {
											echo '<span class="badge badge-success">GOOD</span>';
										} else {
											echo '<span class="badge badge-danger">ATTENTION</span>';
										}
										?>
									</td>
									<td><?php echo date('d-m-Y H:i', $domain->last_check); ?>
									<td><a href="<?php echo $basePath; ?>domains/delete/<?php echo $domain->id; ?>"><div class="fi fi-baseline"><i data-feather="trash-2"></i></div></a></td>
								</tr>
							<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<p>
					<b class="card-title">Domain health report</b><br />
				</p>
				<p>
					Do you require a new report on all domains? Run a manual check. This can take several minutes.
				</p>
				<a href="#" id="runCheckAll" class="btn btn-primary">Run manual check</a>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once (__DIR__ . '/../app/includes/footer.php');
?>
