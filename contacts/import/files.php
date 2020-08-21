<?php
require_once (__DIR__ . '/../../vendor/autoload.php');
use \Progress\Contacts\Utility\ImportQueue;
$queue = new ImportQueue;

$pageTitle = 'Contacts - Upload data';
$navigationPane = 'contacts-files';

if (isset($_GET['removeFile']) && is_numeric($_GET['removeFile'])) {
	$queue->removeFile($_GET['removeFile']);
}

require_once (__DIR__ . '/../../app/includes/header.php');
?>

<style>
.preview-container {
	visibility: visible !important;
}
</style>

<link href="<?php echo $basePath; ?>assets/vendor/dropzone/dropzone.css" rel="stylesheet">

<div class="row import">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<span class="card-step-indicator text-muted upload-step-indicator">Step 1</span><br />
				<p>
					<b class="card-title upload-title-indicator">Upload new data</b>
				</p>

				<div class="row upload-zone">
					<div class="col-lg-12">
						<form class="dropzone files-container" method="post" enctype="multipart/form-data">
							<div class="dz-message text-center">
								<img src="<?php echo $basePath; ?>assets/images/cloud-upload.png">
								<br />
								<b>Drag and Drop files<br /> to upload</b>
								<p class="text-muted" style="margin-bottom: 3px;">or</p>
								<p>
									<a class="btn btn-sm btn-outline-primary" href="#">Browse</a>
								</p>
							</div>
							<div class="fallback">
								<input name="file" type="file" multiple />
							</div>
						</form>	
					</div>
				</div>
				<div class="row upload-process">
					<div class="col-lg-12">
						<!-- Uploaded files section -->
						<!--<span class="no-files-uploaded">No files uploaded yet.</span>
						<!-- Preview collection of uploaded documents -->
						<div class="preview-container dz-preview uploaded-files">
							<div id="previews">
								<div id="pm-dropzone">
									<div class="onyx-dropzone-info" data-dz-thumbnail>
										<div class="row text-center">
											<div class="col-lg-12">
												<h3>Processing file</h3>
												<div class="uploading mt-1 mb-5">
													<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6 offset-lg-3 text-center">
												<div class="upload-name pt-2">
													<span data-dz-name></span> <span data-dz-size></span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6 offset-lg-3">
												<div class="upload-actions text-center pt-2">
													<a href="#!" class="btn btn-sm btn-outline-danger" data-dz-remove>Cancel</a>
												</div>
												<div class="dz-error-message"><span data-dz-errormessage></span></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12 mt-4">
		<div class="card">
			<div class="card-body">
				<p><b>Data files</b></p>
				<table class="table">
					<thead>
						<tr>
							<td><b>ID</b></td>
							<td><b>File name</b></td>
							<td><b>Date</b></td>
							<td><b>Progress</b></td>
							<td><b>Status</b></td>
							<td><b>Actions</b></td>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($queue->getQueue() && is_array($queue->getQueue())) {
							foreach ($queue->getQueue() as $queue) {
								echo '<tr>';
									echo '<td>' . $queue->id . '</td>';
									echo '<td>' . $queue->original_name . '</td>';
									echo '<td>' . date('d-m-Y H:i:s', $queue->created) . '</td>';
									echo '<td>' . $queue->offset . '/' . $queue->total . '</td>';
									switch ($queue->status) {
										case 'processed':
											$status = 'Finished';
											break;
										case 'file_upload':
											$status = 'Uploaded';
											break;
										case 'queued':
											$status = 'Queued';
											break;
									}
									echo '<td>' . $status . '</td>';

									echo '<td>';
										if ($queue->status == 'file_upload') {
											echo '<a href="' . $basePath . 'contacts/import/' . $queue->id . '/" title="Import"><div class="fi fi-baseline"><i data-feather="upload-cloud"></i></div></a> ';
										}
										echo '<a href="?removeFile=' . $queue->id . '" title="Delete file"><div class="fi fi-baseline"><i data-feather="trash-2"></i></div></a>';
									echo '</td>';
								echo '</tr>';
							}
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo $basePath; ?>assets/vendor/papa-parse/papaparse.min.js"></script>
<script src="<?php echo $basePath; ?>assets/vendor/dropzone/dropzone.js"></script>
<script>
	$(document).ready(function() {

		var previewNode = document.querySelector("#pm-dropzone");
		previewNode.id = "";

		var previewTemplate = previewNode.parentNode.innerHTML;
		previewNode.parentNode.removeChild(previewNode);

		var pmDropzone = new Dropzone("form.dropzone", {
			url: ($("form.dropzone").attr("action")) ? $("form.dropzone").attr("action") : "/app/api/ajax/contacts/import.php?method=upload-file", // Check that our form has an action attr and if not, set one here
            maxFiles: 1,
			maxFilesize: 20,
			acceptedFiles: ".csv",
			previewTemplate: previewTemplate,
			previewsContainer: "#previews",
			clickable: true,
			dictDefaultMessage: "Drop files here to upload.", // Default: Drop files here to upload
			dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.", // Default: Your browser does not support drag'n'drop file uploads.
			dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.", // Default: File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.
			dictInvalidFileType: "You can't upload files of this type.", // Default: You can't upload files of this type.

			dictResponseError: "Server responded with {{statusCode}} code.", // Default: Server responded with {{statusCode}} code.
			dictCancelUpload: "Cancel upload.", // Default: Cancel upload
			dictUploadCanceled: "Upload canceled.", // Default: Upload canceled.
			dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?", // Default: Are you sure you want to cancel this upload?
			dictRemoveFile: "Remove file", // Default: Remove file
			dictRemoveFileConfirmation: null, // Default: null
			dictMaxFilesExceeded: "You can not upload any more files.", // Default: You can not upload any more files.
			dictFileSizeUnits: {tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b"},
		});

		pmDropzone.on("totaluploadprogress", function (progress) {

			var progr = document.querySelector(".progress .determinate");

			if (progr === undefined || progr === null) return;

			progr.style.width = progress + "%";

			console.log(progress);
		});
		pmDropzone.on('dragenter', function () {
			$("form.dropzone").addClass("hover");
		});
		pmDropzone.on('dragleave', function () {
			$("form.dropzone").removeClass("hover");			
		});
		pmDropzone.on('drop', function () {
			$("form.dropzone").removeClass("hover");	
		});
		pmDropzone.on('addedfile', function () {		
			$(".upload-step-indicator").text('Step 2');
			$(".upload-title-indicator").text('Processing data');	
			$(".upload-zone").slideUp('easeInExpo', function() {
				$(".upload-process").slideDown('easeInExpo');
			});
		});

		pmDropzone.on('removedfile', function (file) {
			$.ajax({
				type: "POST",
				url: ($("form.dropzone").attr("action")) ? $("form.dropzone").attr("action") : "../../file-upload.php",
				data: {
					target_file: file.upload_ticket,
					delete_file: 1
				}
			});
			$(".upload-process").slideUp('easeInExpo', function() {
				$(".upload-zone").slideDown('easeInExpo');
				$(".upload-step-indicator").text('Step 1');
				$(".upload-title-indicator").text('Upload data');
			});
		});

		pmDropzone.on("success", function(file, response) {
			let parsedResponse = JSON.parse(response);
			file.upload_ticket = parsedResponse.file_link;

			if (parsedResponse.status == 'success') {
				window.location.replace('<?php echo $basePath; ?>contacts/import/' + parsedResponse.queue_id);
			}
		});

		$('#import-contacts').click(function(e) {
			console.log($('form').serialize());
		});
	});
	Dropzone.autoDiscover = false;
</script>
<?php
require_once (__DIR__ . '/../../app/includes/footer.php');
?>