<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'DeepDream';
?>
<div class="site-index">

	<div class="jumbotron">
		<h1>DeepDream</h1>

		<p class="lead">Online artificial neural network image processor</p>

		<p><a class="btn btn-lg btn-success" href="/gallery/index">View gallery</a></p>
	</div>

	<div class="body-content">
		<div class="row">
			<div class="col-md-12">
				<?php if ($pendingImageCount == 0): ?>
					<p class="text-center"><b>Queue is empty.</b></p>
				<?php else: ?>
					<p class="text-center" style="font-size: 18px"><b><?php echo $pendingImageCount; ?></b> images in queue</p>
				<?php endif; ?>
				<p class="text-center"><a class="btn btn-info" href="/upload">Upload image</a></p>
				<br/>


				<?php if (count($pendingPictures) > 0): ?>
					<p class="text-center">These images are now processing: </p>
					<div class="imgs">
						<?php foreach ($pendingPictures as $picture)
						{ ?>
							<?php $progress = (int)(100.0 * $picture->status / 40); ?>
							<div class="p-img" id="image-<?php echo Html::encode($picture->id); ?>">
								<p class="text-center"><img src="/images/<?php echo $picture->source; ?>" alt=""/></p>

								<div class="row">
									<div class="col-xs-6 col-xs-offset-3">
										<div class="progress">
											<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0"
												 aria-valuemax="100"
												 style="width: <?php echo $progress; ?>%"></div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
					<p class="text-center">Images will be shown <a href="/gallery/index"><b>here</b></a> when it will be ready.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<script type="text/template" id="image-template">
	<div class="p-img" id="image-__ID__" style="display: none">
		<p class="text-center"><img src="__SRC__" alt=""/></p>

		<div class="row">
			<div class="col-xs-6 col-xs-offset-3">
				<div class="progress">
					<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="__PROGRESS__" aria-valuemin="0"
						 aria-valuemax="100"
						 style="width: __PROGRESS__%"></div>
				</div>
			</div>
		</div>
	</div>
</script>
<?php
$script = <<<JS
	$(function () {
		var updateImages = function () {
			$.ajax({
				url:      '/status',
				dataType: 'text',
				method:   'GET',
				success:  function (data) {
					var response = [];
					try {
						response = JSON.parse(data);
					} catch (e) {
						console.log("Cannot parse json", e);
					}
					var childs = $('.imgs').children(".p-img");
					for (var i = 0; i < childs.length; i++) {
						var id = $(childs[i]).attr('id').replace("image-", "");
						var alive = false;
						for (var j = 0; j < response.length; j++) {
							if (response[j].id == parseInt(id)) {
								alive = true;
								break;
							}
						}
						if (!alive) {
							console.log("Img #" + id + " is ready");
							$(childs[i]).fadeOut(400, function () {
								$(this).remove();
							});
						}
					}
					for (var i = 0; i < response.length; i++) {
						var imgDiv = $("#image-" + response[i].id);
						if (imgDiv.length == 0) {
							console.log("New img #" + response[i].id);
							var template = $("#image-template").html();
							template = template.replace(/__ID__/g, response[i].id);
							template = template.replace(/__SRC__/g, response[i].source);
							template = template.replace(/__PROGRESS__/g, response[i].progress);
							$(".imgs").append(template);
							$("#image-" + response[i].id).fadeIn();
						} else {
							imgDiv.find(".progress-bar").width(response[i].progress + "%");
							//console.log("Img #" + response[i].id + ": progress " + response[i].progress);
						}
					}
				}
			});
		};
		setInterval(updateImages, 10000);
	});
JS;
$this->registerJs($script);

?>
