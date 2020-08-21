<?php

require_once (__DIR__ . '/../vendor/autoload.php');
use \Progress\Domain\Domains;
use \Progress\Domain\Dns\Dns;
use \Progress\Application\Settings;
use Spatie\SslCertificate\SslCertificate;

$domains = new Domains;
$settings = new Settings;
$hostingIps = explode(',', $settings->get('hosting_lander_ips'));

$domainInfo = $domains->getDomain($_GET['domainId']);
$sslCert = SslCertificate::createForHostName($domainInfo->domain);
$dns = new Dns($domainInfo->domain);

$pageTitle = 'Domain: ' . $domainInfo->domain;
$navigationPane = 'domains-overview';

require_once (__DIR__ . '/../app/includes/header.php');
?>

<div class="row">
	<div class="col-lg-8">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body status-bar">
						<div class="row my-3">
							<div class="col-4">
								<div class="item d-flex align-items-center">
									<span class="title float-left">SSL</span>
									<div class="value float-left">
										<?php echo $sslCert->isValid() ? 'Valid' : 'Invalid'; ?>
									</div>
								</div>
							</div>
							<div class="col-4">
								<div class="item d-flex align-items-center">
									<span class="title float-left">DNS</span>
									<div class="value float-left">
										<?php
										if ($domainInfo->type == 'lander' && array_intersect($dns->getRecords('A'), $hostingIps)) {
											echo 'Valid';
										} elseif ($domainInfo->type == 'tracker' && in_array($settings->get('tracker_main_domain_value'), $dns->getRecords('CNAME'))) {
											echo 'Valid';
										} elseif ($domainInfo->type == 'misc') {
											echo 'N/A';
										} else {
											echo 'Invalid';
										}
										?>
									</div>
								</div>
							</div>
							<div class="col-4">
								<div class="item">
									<span class="title float-left">Accessibility</span>
									<div class="value float-left">Accessable</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col-lg-6">
				<div class="card">
					<div class="card-body">
						<?php
						 /*
						$url = 'https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyB8La9M-QH_xQSF_fJxXFL-OLh7o_Ha8h8';
						$request = [
							'client' => [
								'clientId' => 'progress-media-aps',
								'clientVersion' => '1.0.0'
							],
							'threatInfo' => [
								'threatTypes' => [
									'THREAT_TYPE_UNSPECIFIED', 'MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'
								],
								'platformTypes' => [
									'ANY_PLATFORM'
								],
								'threatEntryTypes' => [
									'URL'
								],
								'threatEntries' => [
									'url' => 'https://thenextbig.club',
									'url' => 'http://testsafebrowsing.appspot.com/apiv4/ANY_PLATFORM/MALWARE/URL/'
								]

							]
						];

						$curl = curl_init($url);
						curl_setopt($curl, CURLOPT_HEADER, false);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_HTTPHEADER,
						        array("Content-type: application/json"));
						curl_setopt($curl, CURLOPT_POST, true);
						curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
						curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

						$json_response = curl_exec($curl);

						$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

						/*if ( $status != 200 ) {
						    die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
						}

						curl_close($curl);

						$response = json_decode($json_response, true);

						print_r($response); */
						?>



						<b class="card-title">SSL report</b>
						<div class="row mt-3">
							<div class="col-4">Expiration date</div>
							<div class="col-8"><?php echo $sslCert->expirationDate(); ?></div>
						</div>
						<div class="row mt-3">
							<div class="col-4">Issuer</div>
							<div class="col-8"><?php echo $sslCert->getIssuer(); ?></div>
						</div>
						<div class="row mt-3">
							<div class="col-4">SAN</div>
							<div class="col-8"><?php echo implode($sslCert->getAdditionalDomains(), '<br>');	?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card">
					<div class="card-body">
						<p>
							<b class="card-title">DNS report</b>
						</p>
						<div class="row mt-3">
							<div class="col-4">Domain type</div>
							<div class="col-8"><?php echo $domainInfo->type; ?></div>
						</div>
						<div class="row mt-3">
							<div class="col-4">A-records</div>
							<div class="col-8">
								<?php 
								if (!empty($dns->getRecords('A'))) {
									echo implode($dns->getRecords('A'), '<br>');
								} else {
									echo 'No record(s) founds.';
								}
								?>								
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-4">CNAME-records</div>
							<div class="col-8">
								<?php 
								if (!empty($dns->getRecords('CNAME'))) {
									echo implode($dns->getRecords('CNAME'), '<br>');
								} else {
									echo 'No record(s) founds.';
								}
								?>								
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-4">NS-records</div>
							<div class="col-8">
								<?php 
								if (!empty($dns->getRecords('NS'))) {
									echo implode($dns->getRecords('NS'), '<br>');
								} else {
									echo 'No record(s) founds.';
								}
								?>								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<p>
					<b class="card-title">Domain actions</b><br />
				</p>
				<p>
					Run domain health report manually for this specific domain. This may take some time ...
				</p>

				<div class="row">
					<div class="col-6 text-left">
						<a href="#" id="deleteDomain" class="btn btn-primary"><div class="fi fi-baseline"><i data-feather="refresh-ccw"></i></div> Run check</a>
					</div>
					<div class="col-6 text-right">
						<a href="#" id="deleteDomain" class="btn btn-danger"><div class="fi fi-baseline"><i data-feather="trash-2"></i></div> Delete domain</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once (__DIR__ . '/../app/includes/footer.php');
?>
