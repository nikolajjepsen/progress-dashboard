<?php
require_once (__DIR__ . '/../../../vendor/autoload.php');
$pageTitle = 'Content - Loan sites overview';
$navigationPane = 'content-projects';

$leapSite = new \Progress\Api\LeapBackend\Sites;

require_once (__DIR__ . '/../../../app/includes/header.php');
?>
<div class="row loan-overview">
    <?php 
	if ($getSites = $leapSite->getAll()) {
		foreach ($getSites as $site) {
		?>
			<div class="col-lg-6">
				<div class="card content-block">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-7 text-center">
								<?php 
								$summary = $leapSite->getSummary($site->id);
								?>
								<p>
									<img src="https://images.progressmedia.dev/<?php echo $site->image_name; ?>" style="background-size: contain;max-width: 100%;" class="mb-2" /><br />
									<b><?php echo $site->name; ?></b><br />
									<?php echo $site->url; ?>
								</p>
								<a href="/content/projects/loan/single/<?php echo $site->id; ?>/" class="btn btn-sm btn-primary stretched-link"><div class="fi fi-baseline float-left" style="margin-right: 0.15rem!important;"><i data-feather="search"></i></div> Details</a>
							</div>
							<div class="col-lg-5">
								<div class="row">
									<div class="col-6">
										<span class="property text-muted">Quotes</span>
										<span class="value"><?php echo $summary->quotes; ?></span>
									</div>
									<div class="col-6">
										<span class="property text-muted">Revenue</span>
										<span class="value"><?php echo number_format($summary->revenue, 2, '.', ','); ?> kr</span>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-6">
										<span class="property text-muted">Clicks</span>
										<span class="value"><?php echo $summary->clicks; ?></span>
									</div>
									<div class="col-6">
										<span class="property text-muted">Conversions</span>
										<span class="value"><?php echo $summary->conversions; ?></span>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-6">
										<span class="property text-muted">EPC</span>
										<span class="value"><?php echo number_format($summary->epc, 2, '.', ','); ?> kr</span>
									</div>
									<div class="col-6">
										<span class="property text-muted">Conversion rate</span>
										<span class="value"><?php echo $summary->cr; ?>%</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
	}
	?>
</div>





<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once (__DIR__ . '/../../../app/includes/footer.php');
?>
