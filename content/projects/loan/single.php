<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');
$leapSite = new \Progress\Api\LeapBackend\Sites;
$leapConfig = new \Progress\Api\LeapFrontend\Config;

$siteId = $_GET['siteId'] ?? die;
if (!$siteDetails = $leapSite->getById($siteId)) {
    echo 'unable to fetch site';
    die;
}
$pageTitle = 'Loan site: ' . $siteDetails->url;
$navigationPane = 'content-projects';

$configItems = $leapConfig->list($siteId);

require_once(__DIR__ . '/../../../app/includes/header.php');
?>
<div class="row loan-single">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header p-3">
                <div class="row">
					<div class="col-8 pt-1 float-left">
						<b class="card-title">Configuration tags</b>
					</div>
					<div class="col-4 float-right text-right">
						<a href="#" class="btn btn-sm btn-primary"><div class="fi fi-baseline"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg></div> New</a>
					</div>
				</div>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-3"><b class="pl-1">Name</b></div>
                    <div class="col-lg-7"><b class="pl-1">Value</b></div>
                    <div class="col-lg-2"></div>
                </div>
                <?php
                foreach ($configItems as $item) {
                    ?>
                    <div class="row config-entry" data-id="<?php echo $item->id; ?>">
                        <div class="col-lg-3">
                            <input type="text" name="name" class="form-control mb-2" value="<?php echo $item->name; ?>" />
                        </div>
                        <div class="col-lg-7">
                            <input type="text" name="value" class="form-control mb-3" value="<?php echo $item->value; ?>" />
                        </div>
                        <div class="col-lg-2">
                            <button type="submit" name="doUpdate" class="btn btn-sm btn-outline-primary">Update</button>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="/assets/js/loan-single.js"></script>
<?php
require_once (__DIR__ . '/../../../app/includes/footer.php');
?>
