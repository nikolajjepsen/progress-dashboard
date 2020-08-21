<?php

require_once (__DIR__ . '/../vendor/autoload.php');
use \Progress\Domain\Domains;
use \Progress\Application\Settings;

$domains = new Domains;
$settings = new Settings;

$pageTitle = 'Domains - Settings';
$navigationPane = 'domains-settings';

if ($settings->get('tracker_main_domain_value'))
	$primaryDomain = $settings->get('tracker_main_domain_value');
else
	$primaryDomain = '';

require_once (__DIR__ . '/../app/includes/header.php');
?>

<div class="row landers">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-body">
				<p>
					<b class="card-title">Tracking: Primary domain</b><br />
					<span class="text-muted">Declare primary tracker domain, and generate postback URLs.</span>
				</p>


				<form action="" method="POST" id="primaryDomainForm">
					<div class="form-group">
						<label for="primaryDomain">Primary domain</label>
						<input type="text" class="form-control" value="<?php echo $primaryDomain; ?>" id="primaryDomain" name="primaryDomain" />
					</div>
					<input type="submit" name="updatePrimaryDomain" value="Update primary domain" class="btn btn-primary" />

					<div class="form-group mt-4">
						<label for="generatedPostback">Generate postback URL</label>
						<input type="text" class="form-control" placeholder="Select network type to generate postback URL" id="generatedPostback" />
						<div class="btn-toolbar" role="toolbar">
							<div class="btn-group btn-group mt-2 d-flex pr-3" role="group" aria-label="networkGroup">
								<button type="button" data-network="cake" class="btn btn-secondary network-selector">Cake</button>
								<button type="button" data-network="hasoffer" class="btn btn-secondary network-selector">Hasoffer</button>
								<button type="button" data-network="custom" class="btn btn-secondary network-selector">Custom</button>
							</div>
							<div class="input-group pt-3">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="sslSwitch" checked>
									<label class="custom-control-label" for="sslSwitch">SSL</label>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
	$(document).ready(function() {
		var cakePrepend = '/postback?cid=#s2#&payout=#price#&txid=#tid#';
		var hasofferPrepend = '/postback?cid={aff_sub2}&payout={payout}&txid=';
		var customPrepend = '/postback?cid=#cid#&payout=#payout#&txid=#orderId#';

		$(document).on('mousedown', '.network-selector', function(){
			let primaryDomain = $("#primaryDomain").val();
			let postbackUrlInput = $("#generatedPostback");
			if ($("#sslSwitch").is(':checked')) {
				var protocol = 'https://';
			} else {
				var protocol = 'http://';
			}
			switch($(this).data('network')) {
				case 'cake':
					postbackUrlInput.val(protocol + primaryDomain + cakePrepend);
					break;
				case 'hasoffer':
					postbackUrlInput.val(protocol + primaryDomain + hasofferPrepend);
					break;
				case 'custom':
					postbackUrlInput.val(protocol + primaryDomain + customPrepend);
					break;
			}
		});

		$("#primaryDomainForm").submit(function(e) {
			e.preventDefault();

			$.post('../app/api/ajax/domains/update_primary_domain.php', $(this).serialize(), function(data) {
				if (data) {
					let jsonResponse = JSON.parse(data);
					if (jsonResponse.status == 'success') {
						$("#primaryDomain").val(jsonResponse.new_value).css('border-color', 'green');
					}
				} else {
					$("#primaryDomain").css('border-color', 'red');
				}
			});
		})

	});
</script>

<?php
require_once (__DIR__ . '/../app/includes/footer.php');
?>
