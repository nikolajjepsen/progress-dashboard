<?php

$pageTitle = 'Contacts - Export';
$navigationPane = 'contacts-export';

require_once (__DIR__ . '/../../app/includes/header.php');
?>
<link href="<?php echo $basePath; ?>assets/css/export.css" rel="stylesheet">
<link href="<?php echo $basePath; ?>assets/vendor/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo $basePath; ?>assets/vendor/jquery-ui/jquery-ui.structure.min.css" rel="stylesheet">
<link href="<?php echo $basePath; ?>assets/vendor/jquery-ui/jquery-ui.theme.css" rel="stylesheet">
<div class="row export">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<form class="form-queue-export">
					<p>
						<b class="card-title">Create export</b>
					</p>
					<div class="export-step-1">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="data_provider">Provider name</label>
									<select class="form-control" id="data_provider" name="data_provider">
										<option>---</option>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="data_year">Approximated collection year</label>
									<select class="form-control" id="data_year" name="data_year">
										<option>---</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="data_country">Data country</label>
							<select class="form-control" id="data_country" name="data_country">
								<option>---</option>
							</select>
						</div>

						<button type="button" class="btn btn-lg btn-primary" id="btn-step-2">Next</button>
					</div>

					<div class="export-step-2">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="file_separator">File separator</label>
									<select class="form-control" id="file_separator" name="file_separator">
										<option>---</option>
										<option value=";">;</option>
										<option value=",">,</option>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="file_chunck_size">Chunck size</label>
									<input type="text" class="form-control" name="file_chunck_size" id="file_chunck_size" placeholder="5000">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="file_size">File size</label>
									<input type="text" class="form-control" name="file_size" id="file_size" placeholder="50000">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="cc_template">Country code template</label>
							<select class="form-control" id="cc_template" name="cc_template">
								<option>---</option>
								<option value="1">None</option>
								<option value="2">xx</option>
								<option value="3">+xx</option>
								<option value="4">0xx</option>
								<option value="5">00xx</option>
							</select>
						</div>

						<button type="button" class="btn btn-lg btn-primary" id="btn-step-3">Next</button>
					</div>

					<div class="export-step-3">
						<div class="row">
							<div class="col">
								<div class="form-group mb-0">
									<label for="require_email">Email MUST be present</label>
								</div>
								<div class="form-group form-check-inline">
									<input class="form-check-input" type="radio" name="require_email" id="require_email_y" value="yes">
  									<label class="form-check-label" for="require_email_y">Yes </label>
								</div>
								<div class="form-group form-check-inline">
									<input class="form-check-input" type="radio" name="require_email" id="require_email_n" value="no" selected>
  									<label class="form-check-label" for="require_email_n">No </label>
								</div>
							</div>
							<div class="col">
								<div class="form-group mb-0">
									<label for="require_mobile">Mobile MUST be present</label>
								</div>
								<div class="form-group form-check-inline">
									<input class="form-check-input" type="radio" name="require_mobile" id="require_mobile_y" value="yes" selected>
  									<label class="form-check-label" for="require_mobile_y">Yes </label>
								</div>
								<div class="form-group form-check-inline">
									<input class="form-check-input" type="radio" name="require_mobile" id="require_mobile_n" value="no">
  									<label class="form-check-label" for="require_mobile_n">No </label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col">
									<label for="columns_inactive">Excluded columns</label>
									<ul id="available" class="connected">
										<li class="ui-state-highlight" data-field="firstname">Firstname</li>
										<li class="ui-state-highlight" data-field="lastname">Lastname</li>
										<li class="ui-state-highlight" data-field="gender">Gender</li>
										<li class="ui-state-highlight" data-field="birthdate">Birthdate</li>
										<li class="ui-state-highlight" data-field="address">Address</li>
										<li class="ui-state-highlight" data-field="zip">Zip</li>
										<li class="ui-state-highlight" data-field="city">City</li>
									</ul>
								</div>
								<div class="col">
									<label for="columns">Included columns and sorting</label>
									<ul id="used" class="connected">
										<li class="ui-state-highlight" data-field="email">Email</li>
										<li class="ui-state-highlight" data-field="mobile">Mobile</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="mobile_status">Mobile Status</label>
							<select class="form-control" id="mobile_status" name="mobile_status">
								<option> --- </option>
								<option value="1">Regardless of validation</option>
								<option value="2">Syntax- or HLR validated</option>
								<option value="3">HLR validated</option>
								<option value="4">Syntax validated</option>
								<option value="5">Invalidated</option>
							</select>
						</div>
						<button type="button" class="btn btn-lg btn-primary" id="btn-queue-export">Queue Export</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row export mt-4">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<td><b>Source / year</b></td>
							<td><b>Country</b></td>
							<td><b>Contact #</b></td>
							<td><b>Split</b></td>
							<td><b>Files</b></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Euroads / 2018</td>
							<td>Sweden</td>
							<td>200.000</td>
							<td>15.000</td>
							<td>14</td>
						</tr>
						<tr>
							<td>Euroads / 2018</td>
							<td>Sweden</td>
							<td>200.000</td>
							<td>15.000</td>
							<td>14</td>
						</tr>
						<tr>
							<td>Euroads / 2018</td>
							<td>Sweden</td>
							<td>200.000</td>
							<td>15.000</td>
							<td>14</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo $basePath; ?>assets/vendor/jquery-ui/jquery-ui.min.js"></script>

<script>
	$(document).ready(function() {

		var providerName, providerId, yearName, yearId, countryName, countryId, fileSeparator, fileSize, chunckSize, ccTemplate;
		$( "#available, #used" ).sortable({
	      connectWith: ".connected"
	    })
	    .on('mouseup', '.ui-state-highlight', function() {
	    	$(this).appendTo($("#available, #used").not($(this).closest("ul")));
	    });

	    // Populate data providers
		$.getJSON('../../app/api/ajax/contacts/provider.php?method=list-providers', function(data) {
			$.each(data, function(index, element) {
				$('<option/>', {value: element.id}).text(element.name).appendTo('#data_provider');
			});
		});
		$("#data_year, #data_country").attr('disabled', 'disabled');


		$("#data_provider").change(function() {
			providerName = $("#data_provider option:selected").text();
			providerId = $(this).val();
			$("#data_year option:not(:first)").remove();
			yearOptions = [
				{
					'id': 2019,
					'name': '2019'
				},
				{
					'id': 2018,
					'name': '2018'
				},
				{
					'id': 2017,
					'name': '2017'
				},
				{
					'id': 2016,
					'name': '2016'
				},
				{
					'id': 2015,
					'name': '2015'
				},
				{
					'id': 2014,
					'name': '2014'
				},
				{
					'id': 2013,
					'name': '2013'
				}
			];


			$.each(yearOptions, function(index, element) {
				$('<option/>', {value: element.id}).text(element.name).appendTo('#data_year');
			});

			$("#data_year").attr('disabled', false);
			$("#data_country option:not(:first)").remove();
			$("#data_country").attr('disabled', true);

		});
		$("#data_year").change(function() {
			yearName = $("#data_year option:selected").text();
			yearId = $(this).val();
			$("#data_country option:not(:first)").remove();
			$("#data_country").attr('disabled', false);

	 		// Populate data countries
			$.getJSON('../../app/api/ajax/utils/country.php?method=list-countries', function(data) {
				$.each(data, function(index, element) {
					$('<option/>', {value: element.id}).text(element.nicename).appendTo('#data_country');
				});
			});
		});
		$("#data_country").change(function() {
			countryName = $("#data_country option:selected").text();
			countryId = $(this).val();
		});
		$("#file_separator").change(function() {
			fileSeparator = $("#file_separator option:selected").text();
		});
		$("#file_size").keyup(function() {
			fileSize = $("#file_size").val();
		});
		$("#chunck_size").keyup(function() {
			chunckSize = $("#chunck_size").val();
		});
		$("#cc_template").change(function() {
			ccTemplate = $("#cc_template option:selected").text();
			if (ccTemplate == 'None') {
				ccTemplate = '';
			}
		});



		$("#btn-step-2").click(function() {

			$('<div class="selected-data selected-step-1 text-center my-3 p-3">' +
				'<span class="selected-source">' + providerName + ' / ' + yearName + '</span>' +
				'<br>' +
				'<span class="selected-country">' + countryName + '</span></div>'
			).insertAfter('.export-step-1');

			$(".export-step-1").hide();
			$(".export-step-2").show();
		});
		$("#btn-step-3").click(function() {

			$('<div class="selected-data selected-step-2 text-center my-3 p-3">' +
				'<span class="selected-source">File sized divided by ' + chunckSize + ' and separated by \'' + fileSeparator + '\'</span>' +
				'<br>' +
				'<span class="selected-country">Using mobile template: ' + ccTemplate + '27299072</span></div>'
			).insertAfter('.export-step-2');

			$(".export-step-2").hide();
			$(".export-step-3").show();
		});


		$(document).on("click", ".selected-step-1", function() {
			$(".export-step-2").hide();
			$(".export-step-3").hide();
			$(".export-step-1").show();
			$(".selected-step-1").remove();
		});
		$(document).on("click", ".selected-step-2", function() {
			$(".export-step-1").hide();
			$(".export-step-3").hide();
			$(".export-step-2").show();
			$(".selected-step-2").remove();
		});

		$("#btn-queue-export").click(function() {
			var field = {};
			$('#used li').each(function(i) {
				field['field[' + i + ']'] = $(this).data('field');
			});
			$.post('../../../app/api/ajax/contacts/export.php?method=queue-export', $('.form-queue-export').serialize() + '&' + $.param(field), function(data) {
				console.log(data);
			});
		});
	});
</script>
<?php
require_once (__DIR__ . '/../../app/includes/footer.php');
?>