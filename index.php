<?php
require_once ('vendor/autoload.php');
use \Progress\Domain\Domains;
use \Progress\Application\Settings;
$domains = new Domains;
$settings = new Settings;
$calendarEntries = new \Progress\Utils\Calendar\CalendarEntries;

$pageTitle = 'Dashboard';
$navigationPane = 'home';

require_once (__DIR__ . '/app/includes/header.php');
?>
<div class="row mb-4">
	<div class="col-lg-3 my-md-0 my-3">
		<div id="jalendar" class="jalendar">
			<?php
			if ($entries = $calendarEntries->getEntries()) {
				foreach ($entries as $entry) {
					echo '<div class="added-event" data-date="' . $entry->date . '" data-time="' . $entry->time . '" data-title="' . $entry->title . '"></div>';
				}
			}
			?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-4 my-md-0 my-3">
		<div class="card tasks">
			<div class="card-header">
				<div class="row">
					<div class="col-8 pt-1">
						<b class="card-title">Notes</b>
					</div>
					<div class="col-4 text-right">
						<a href="#" class="btn btn-sm btn-primary"><div class="fi fi-baseline float-left"><i data-feather="plus-circle"></i></div> New</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="task mb-3">
					<div class="task-inner">
						<div class="float-left">
							<b class="task-title">
								Arch. lander setup + implementation
							</b>
							<p class="priority">
								Important
							</p>
						</div>
						<div class="task-actions float-right">
							<a href="#" class="edit"><div class="fi fi-baseline"><i data-feather="edit-2"></i></div></a>
							<a href="#" class="delete"><div class="fi fi-baseline"><i data-feather="trash-2"></i></div></a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="task">
					<div class="task-inner">
						<div class="float-left">
							<b class="task-title">
								Mod. data manager + implementation
							</b>
							<p class="priority">
								Important
							</p>
						</div>
						<div class="task-actions float-right">
							<a href="#"><div class="fi fi-baseline"><i data-feather="edit-2"></i></div></a>
							<a href="#"><div class="fi fi-baseline"><i data-feather="trash-2"></i></div></a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 my-md-0 my-3">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-8 pt-1">
						<b class="card-title">Domains</b>
					</div>
					<div class="col-4 text-right">
						<a href="#" class="btn btn-sm btn-primary"><div class="fi fi-baseline"><i data-feather="plus-circle"></i></div> New</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<?php 
				if ($getDomains = $domains->getDomains()) {
					$i = 0;
					foreach ($getDomains as $domain) {
						if ($i < 2) {
							$activeClass = 'active';
						} else {
							$activeClass = '';
						}
					?>
						<div class="domain <?php echo $activeClass; ?> mb-3">
							<div class="domain-inner">
								<b class="domain-title float-left">
									<?php echo $domain->domain; ?>
									<?php
									if ($domains->isFlagged($domain->id)) {
										echo '<div class="badge-important"><div class="fi fi-baseline"><i data-feather="alert-triangle"></i></div></div>';
									}
									?>
								</b>
								<div class="domain-actions float-right">
									<a href="#"><div class="fi fi-baseline"><i data-feather="edit-2"></i></div></a>
									<a href="#"><div class="fi fi-baseline"><i data-feather="trash-2"></i></div></a>
								</div>
								<div class="clearfix"></div>
		                        <span class="pr-2 text-nowrap d-block dns-info">
		                        	<?php
		                        	if ($domain->type == 'tracker') {
		                        		echo '<div class="badge badge-info mb-3 display-inline">CNAME</div> ' . $settings->get('tracker_main_domain_value');
		                        	} elseif ($domain->type == 'lander') {
		                        		echo '<div class="badge badge-info mb-3 display-inline">A</div> ' . explode(',', $settings->get('lander_hosting_ips'))[0];
		                        	}
		                        	?>
									
		                        </span>
		                    </div>
		                    <div class="row no-gutters">
		                    	<?php 
		                    	if ($domains->isFlagged($domain->id)) {
		                    		echo '<div class="col-lg-12 text-center banner badge-danger py-1">Attention</div>';
		                    	} else {
		                    		echo '<div class="col-lg-12 text-center banner badge-success py-1">No issues</div>';
		                    	}

		                    	?>
		                    	
		                    </div>
						</div>
					<?php
					$i++;
					}
				}
				?>
				<div class="text-center">
					<a href="#" class="btn btn-sm btn-outline-primary show-domains">Show all</a>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card resources">
			<div class="card-header">
				<b class="card-title">Resources</b>
			</div>
			<div class="card-body">
				<div class="resource">
					<div class="row">
						<div class="col-10"><div class="fi fi-baseline pr-2"><i data-feather="bookmark"></i></div><b>Permission Slip:</b> +45 27299072</div>
						<div class="col-2 text-right"><div class="fi fi-baseline"><i data-feather="download-cloud"></i></div></div>
					</div>
				</div>
				<div class="resource">
					<div class="row">
						<div class="col-10"><div class="fi fi-baseline pr-2"><i data-feather="list"></i></div><b>Data Export:</b> SE Static 2017</div>
						<div class="col-2 text-right"><div class="fi fi-baseline"><i data-feather="download-cloud"></i></div></div>
					</div>
				</div>
				<div class="resource">
					<div class="row">
						<div class="col-10"><div class="fi fi-baseline pr-2"><i data-feather="bookmark"></i></div><b>Permission Slip:</b> +45 27299072</div>
						<div class="col-2 text-right"><div class="fi fi-baseline"><i data-feather="download-cloud"></i></div></div>
					</div>
				</div>
				<div class="resource">
					<div class="row">
						<div class="col-10"><div class="fi fi-baseline pr-2"><i data-feather="list"></i></div><b>Data Export:</b> SE Static 2017</div>
						<div class="col-2 text-right"><div class="fi fi-baseline"><i data-feather="download-cloud"></i></div></div>
					</div>
				</div>
				<div class="resource">
					<div class="row">
						<div class="col-10"><div class="fi fi-baseline pr-2"><i data-feather="bookmark"></i></div><b>Permission Slip:</b> +45 27299072</div>
						<div class="col-2 text-right"><div class="fi fi-baseline"><i data-feather="download-cloud"></i></div></div>
					</div>
				</div>
				<div class="resource">
					<div class="row">
						<div class="col-10"><div class="fi fi-baseline pr-2"><i data-feather="list"></i></div><b>Data Export:</b> SE Static 2017</div>
						<div class="col-2 text-right"><div class="fi fi-baseline"><i data-feather="download-cloud"></i></div></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo $basePath;?>assets/vendor/jalendar/js/jalendar.min.js"></script>
<script>
	$.fn.extend({
		textToggle: function(a, b){
			return this.text(this.text() == b ? a : b);
		}
	});
	$(document).ready(function() {
		$("#jalendar").jalendar({
			color: '#fff'
		});

		$(".show-domains").click(function(e) {
			e.preventDefault();
			$(".domain.inactive").slideToggle(300);
			$(this).textToggle('Show all', 'Show less');
		});
	});
</script>
<?php
require_once (__DIR__ . '/app/includes/footer.php');
?>