<?php
require_once(__DIR__ . '/../../vendor/autoload.php');
$pageTitle = 'Content - Loan sites overview';
$navigationPane = 'content-projects';

$leapSite = new \Progress\Api\LeapBackend\Sites;

require_once(__DIR__ . '/../../app/includes/header.php');
?>

<style>
    .card-title {
        font-size: 34px;
        font-family: 'proxima_nova_scosfthin', 'Proxima Nova', sans-serif;
        font-weight: 300;
    }
</style>

<div class="row loan-overview">
    <div class="col-lg-2">
        <div class="card content-block">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <b class="card-title">Loan sites</b>
                        <p>Manage campaigns, sites and selections</p>
                        <a href="/lander-builder/content/projects/loan/" class="btn btn-sm btn-primary stretched-link">
                            <div class="fi fi-baseline float-left">
                                <i data-feather="search"></i>
                            </div>
                            Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once(__DIR__ . '/../../app/includes/footer.php');
?>
