<?php
require_once (__DIR__ . '/../../vendor/autoload.php');
use \Progress\Contacts\Utility\Import;
use \Progress\Contacts\Utility\ImportQueue;
$import = new Import;
$queue = new ImportQueue;

if (! $file = $queue->getById($_GET['fileId'])) {
	header("Location: /lander-builder/");
}

$pageTitle = 'Contacts - Import<br><small>From file: ' . $file->original_name . '</small>';
$navigationPane = 'contacts-files';

require_once (__DIR__ . '/../../app/includes/header.php');
?>

<link href="<?php echo $basePath; ?>assets/vendor/dropzone/dropzone.css" rel="stylesheet">

<div class="row import flex-row-reverse flex-md-row d-flex">
	<div class="col-lg-8 order-2 order-sm-0">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body">
						<form class="data-provider">
							<p>
								<b class="card-title">Data provider</b>
							</p>
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group">
										<label for="data_provider">Provider name</label>
										<select class="form-control" id="data_provider" name="data_provider">
											<option value="0">---</option>
										</select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label for="data_year">Approximated collection year</label>
										<select class="form-control" id="data_year" name="data_year">
											<option value="0">---</option>
											<?php
											for ($i = date('Y'); $i >= 2014; $i--) {
												//var_dump($i, $currentYear-$i);
												echo '<option>' . $i . '</option>';
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label for="data_country">Data country</label>
										<select class="form-control" id="data_country" name="data_country">
											<option value="0">---</option>
										</select>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-12 mt-4">
				<div class="card">
					<div class="card-body">
						<span class="card-step-indicator text-muted upload-step-indicator">Step 3</span><br />
						<p>
							<b class="card-title upload-title-indicator">Data Mapping</b>
						</p>
						<div class="row upload-map-fields">
							<div class="col-lg-3 mb-3"><b>Column name</b></div>
							<div class="col-lg-3 mb-3"><b>System field</b></div>
							<div class="col-lg-6 mb-3"><b>Example values</b></div>
							<form class="data-mapping" style="width:100%;"></form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4  order-1 order-sm-1 mb-4 mb-sm-0">
		<div class="card">
			<div class="card-body">
				<form class="data-manipulation">
					<p>
						<b class="card-title">Import manipulation</b>
					</p>
					<div class="form-group">
						<label for="email_manipulation">Email manipulation</label>
						<select class="form-control" id="email_manipulation" name="email_manipulation">
							<option value="lc">Lowercase</option>
							<option value="uc">Uppercase</option>
							<option value="0">Don't manipulate</option>
						</select>
					</div>

					<div class="form-group">
						<label for="number_manipulation">Number manipulation</label>
						<select class="form-control" id="number_manipulation" name="number_manipulation">
							<option value="1">Automatic country code control</option>
							<option value="0">No country code control (advanced)</option>
						</select>
					</div>

					<div class="form-group">
						<label for="name_manipulation">Name manipulation</label>
						<select class="form-control" id="name_manipulation" name="name_manipulation">
							<option value="ucf">Uppercase first</option>
							<option value="lc">Lowercase</option>
							<option value="uc">Uppercase</option>
							<option value="0">Don't manipulate</option>
						</select>
					</div>
				</form>
			</div>
		</div>
		<div class="card mt-4 submit-card">
			<div class="card-body text-center">
				<button type="button" class="btn btn-lg btn-primary" id="import-contacts">Import contacts</button>
			</div>
		</div>
	</div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo $basePath; ?>assets/vendor/papa-parse/papaparse.min.js"></script>
<script src="<?php echo $basePath; ?>assets/vendor/dropzone/dropzone.js"></script>
<script>
	$(document).ready(function() {

		// Populate countries
		$.getJSON('../../../app/api/ajax/utils/country.php?method=list-countries', function(data) {
			$.each(data, function(index, element) {
				$('<option/>', {value: element.id}).text(element.nicename).appendTo('#data_country');
			});
		});

		// Populate data providers
		$.getJSON('../../../app/api/ajax/contacts/provider.php?method=list-providers', function(data) {
			$.each(data, function(index, element) {
				$('<option/>', {value: element.id}).text(element.name).appendTo('#data_provider');
			});
		});

		$(".upload-step-indicator").text('Step 3');
		$(".upload-title-indicator").text('Map data');

		var data = {headers: {}, rows: {}};
		Papa.parse('../../../app/files/import/<?php echo $file->file; ?>', {
			download: true,
			preview: 3,
			header: true,
			trimHeaders: true,
			skipEmptyLines: true,
			complete: function(response) {
				data.headers = response.meta.fields;
				data.rows = response.data
				let delimiter = response.meta.delimiter;

				$(".uploaded-files").hide();
				$(".upload-map-fields").slideDown('easeInExpo').css('display', 'flex');
				$(".submit-card").slideDown('easeInExpo');

				var selectComponents = [
					{value: 'ignore', text: 'Ignore'},
					{value: 'email', text: 'E-mail'},
					{value: 'firstname', text: 'Firstname'},
					{value: 'lastname', text: 'Lastname'},
					{value: 'mobile', text: 'Mobile number'}
				]
				$.each(data.headers, function (headerKey, headerValue) {
					var temp = '<div class="col-lg-12">';
						temp += '<div class="row mb-3">';
							temp += '<div class="col-lg-3">';
								temp += '<div class="csvHeader">' + headerValue + '</div>';
							temp += '</div>';

							temp += '<div class="col-lg-3">';
								temp += '<div class="systemField">';
									temp += '<select name="header[' + headerKey + ']" class="form-control">';
										for (i = 0; i < selectComponents.length; i++) {
											if (headerValue == selectComponents[i]['value']){
												temp += '<option value="' + selectComponents[i]['value'] + '" selected>' + selectComponents[i]['text'] + '</option>';
											} else {
												temp += '<option value="' + selectComponents[i]['value'] + '">' + selectComponents[i]['text'] + '</option>';
											}
											
										}
									temp += '</select>';
								temp += '</div>';
							temp += '</div>';
							temp += '<div class="col-lg-6">';
								temp += '<div class="csvValues">';
									$.each(data.rows, function (rowsKey, rows) {
										$.each(rows, function (rowKey, row) {
											if (rowKey == headerValue) {
												temp += '<span class="rowValue">' + row + '</span>';
											}
										});
									});
								temp += '</div>';
							temp += '</div>';
						temp += '</div>';
					temp += '</div>';
					$(".upload-map-fields form.data-mapping").append(temp).
					temp = '';
				});

				$(".upload-map-fields form.data-mapping").append('<input type="hidden" name="delimiter" value="' + delimiter + '" />');
			}
		});
		$('#import-contacts').click(function(e) {
			$.post('../../../app/api/ajax/contacts/import.php?method=queue-file&file_id=<?php echo $_GET['fileId']; ?>', $('form').serialize(), function(data) {
				console.log(data);
			})
			console.log($('form').serialize());
		});
	});
</script>
<?php
require_once (__DIR__ . '/../../app/includes/footer.php');
?>