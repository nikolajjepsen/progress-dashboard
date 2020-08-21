<?php
require_once (__DIR__ . '/../../vendor/autoload.php');
use \Progress\Content\Partial\Partials;

$pageTitle = 'Content - Partials';
$navigationPane = 'content-partials';


$partials = new Partials;

require_once (__DIR__ . '/../../app/includes/header.php');
?>
<div class="row">
	<?php 
	if ($getPartials = $partials->getPartials('active')) {
		foreach ($getPartials as $partial) {
		?>
			<div class="col-lg-4">
				<div class="card content-block">
					<div class="card-body">
						<p>
							<b><?php echo $partial->name; ?></b><br />
							<?php echo substr($partial->description, 0, 300); ?>
							<?php echo strlen($partial->description) > 300 ? ' ...' : ''; ?>
						</p>
						<p>
							<a href="single/<?php echo $partial->id; ?>/" class="btn btn-sm btn-primary">Edit</a>
						</p>
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
require_once (__DIR__ . '/../../app/includes/footer.php');
?>
