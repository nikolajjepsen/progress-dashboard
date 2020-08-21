<?php
require_once (__DIR__ . '/../../vendor/autoload.php');
use \Progress\Content\Partial\Partials;

$partials = new Partials;

$partialInfo = $partials->getPartial($_GET['partialId']);
$partialContent = $partials->getPartialContent($_GET['partialId']);

$pageTitle = 'Update partial: <br /><small>' . $partialInfo->name . '</small>';


$navigationPane = 'content-blocks';

require_once (__DIR__ . '/../../app/includes/header.php');
?>
<link href="https://cdn.jsdelivr.net/npm/codemirror@5.42.0/lib/codemirror.css" rel="stylesheet">
<link href="<?php echo $basePath; ?>assets/css/codemirror.theme.css" rel="stylesheet">
<style>
	.card.markup, .card.js, .card.css {
		cursor: pointer;
	}
</style>
<form name="block_information" method="POST">
	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label for="block_name">Block name</label>
				<input type="text" id="block_name" name="block_name" class="form-control" value="<?php echo $partialInfo->name; ?>" />
			</div>
			<div class="form-group">
				<label for="block_description">Block description</label>
				<textarea id="block_description" name="block_description" class="form-control"><?php echo $partialInfo->description; ?></textarea>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 my-2">
			<b>Edit block fragments.</b><br />
			Updating will completely overwrite existing fragment.
		</div>
		<div class="col-lg-4">
			<div class="card markup">
				<div class="card-body text-center">
					<b>Markup</b>
				</div>
			</div>	
		</div>
		<div class="col-lg-4">
			<div class="card js">
				<div class="card-body text-center">
					<b>Javascript</b>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="card css">
				<div class="card-body text-center">
					<b>Stylesheet</b>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-3 mb-4 content-editor">
		<div class="col-lg-12 block-html">
			<b>HTML</b>
			<textarea id="html" name="markup_content">
				<?php echo $partialContent['markup']; ?>
			</textarea>
		</div>
		<div class="col-lg-12 block-js">
			<b>JS</b>
			<textarea id="js" name="javascript_content">
				<?php echo $partialContent['javascript']; ?>
			</textarea>
		</div>
		<div class="col-lg-12 block-css">
			<b>CSS</b>
			<textarea id="css" name="stylesheet_content">
				<?php echo $partialContent['stylesheet']; ?>
			</textarea>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-lg-12">
			<input type="button" name="updatePartial" id="updateBlock" class="btn btn-primary" value="Update partial" />
		</div>
	</div>
</form>




<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.42.0/lib/codemirror.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.42.0/mode/javascript/javascript.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.42.0/mode/css/css.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.42.0/mode/xml/xml.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.42.0/mode/htmlmixed/htmlmixed.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.42.0/addon/edit/matchbrackets.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.42.0/addon/fold/xml-fold.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.42.0/addon/edit/matchtags.js"></script>


<script>
	$(document).ready(function() {
		var jsCodeMirror = CodeMirror.fromTextArea(document.getElementById('js'), {
			mode: 'javascript',
			lineNumbers: true,
			styleActiveLine: true,
   			indentWithTabs: 1,
   			indentUnit: 4,
   			matchBrackets: true,
   			matchTags: 1
		});
		for (var i=0;i<jsCodeMirror.lineCount();i++) { jsCodeMirror.indentLine(i); }
		jsCodeMirror.setOption('theme', 'pm');
		jsCodeMirror.setSize('100%', '600px');

		var cssCodeMirror = CodeMirror.fromTextArea(document.getElementById('css'), {
			mode: 'css',
			lineNumbers: true,
			styleActiveLine: true,
   			indentWithTabs: 1,
   			indentUnit: 4,
   			matchBrackets: true,
   			matchTags: 1
		});
		for (var i=0;i<cssCodeMirror.lineCount();i++) { cssCodeMirror.indentLine(i); }
		cssCodeMirror.setOption('theme', 'pm');
		cssCodeMirror.setSize('100%', '600px');

		var htmlCodeMirror = CodeMirror.fromTextArea(document.getElementById('html'), {
			mode: 'htmlmixed',
			lineNumbers: true,
			styleActiveLine: true,
   			indentWithTabs: 1,
   			indentUnit: 4,
   			matchBrackets: true,
   			matchTags: 1
		});
		for (var i=0;i<htmlCodeMirror.lineCount();i++) { htmlCodeMirror.indentLine(i); }
		htmlCodeMirror.setOption('theme', 'pm');
		htmlCodeMirror.setSize('100%', '600px');

		$(".card.markup").click(function() {
			$('.block-js, .block-css').hide();
			if($('.block-html').is(':visible')) {
				$('.block-html').hide();
			} else {
				$(".block-html").show();
				$('html, body').animate({
                    scrollTop: $(".content-editor").offset().top
                }, 0);
			}
		});
		$(".card.js").click(function() {
			$('.block-html, .block-css').hide();
			if($('.block-js').is(':visible')) {
				$('.block-js').hide();
			} else {
				$(".block-js").show();
				$('html, body').animate({
                    scrollTop: $(".content-editor").offset().top
                }, 0);
			}
		});
		$(".card.css").click(function() {
			$('.block-html, .block-js').hide();
			if($('.block-css').is(':visible')) {
				$('.block-css').hide();
			} else {
				$(".block-css").show();
				$('html, body').animate({
                    scrollTop: $(".content-editor").offset().top
                }, 0);
			}
		});

		$('.block-html, .block-js, .block-css').hide();

		$("#updateBlock").click(function(e) {
			e.preventDefault();
			console.log($('form').serialize());
		});

	});
</script>
<?php
require_once (__DIR__ . '/../../app/includes/footer.php');
?>
