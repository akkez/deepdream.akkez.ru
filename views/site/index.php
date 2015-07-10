<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'DeepDream online';
?>
<div class="site-index">

	<div class="jumbotron">
		<h1>DeepDream</h1>

		<p class="lead">Online artificial neural network image processor</p>

		<p><a class="btn btn-lg btn-success" href="/gallery/index">View gallery</a></p>
	</div>

	<div class="body-content">
		<?php if ($pendingImageCount == 0): ?>
			<p class="text-center"><b>Queue is empty.</b></p>
		<?php else: ?>
			<p class="text-center" style="font-size: 18px"><b id="queueLengthTop"><?php echo $pendingImageCount; ?></b> images in queue</p>
		<?php endif; ?>
		<br/>

		<p class="text-center">Last pictures:</p>

		<div class="row">
			<div class="col-md-3 text-center img-box">
				<?php $i = 0;
				foreach ($lastPictures as $picture) { ?>
				<div>
					<a href="/picture/<?php echo $picture->id; ?>">
						<img src="/ready/<?php echo $picture->output; ?>" alt=""/>
					</a>
				</div>
				<?php $i++;
				if ($i % 8 == 0 && $i < 32) { ?></div>
			<div class="col-md-3 text-center img-box"><?php } ?>
				<?php } ?>
			</div>
		</div>


		<div class="row">
			<div class="col-md-12">
				<?php if ($pendingImageCount == 0): ?>
					<p class="text-center"><b>Queue is empty.</b></p>
				<?php else: ?>
					<p class="text-center" style="font-size: 18px"><b id="queueLength"><?php echo $pendingImageCount; ?></b> images in queue</p>
				<?php endif; ?>
				<p class="text-center"><a class="btn btn-info" href="/upload">Upload image</a></p>
				<br/>

				<?php if (count($pendingPictures) > 0): ?>
					<p class="text-center">These images are now processing: </p>
					<div class="imgs">
						<div class="row">
							<?php foreach ($pendingPictures as $picture)
							{ ?>
								<?php $progress = (int)(100.0 * $picture->status / 40); ?>
								<div class="col-md-3 p-img" id="image-<?php echo Html::encode($picture->id); ?>">
									<div>
										<p class="text-center"><a href="/picture/<?php echo $picture->id; ?>"><img style="max-width: 100%" src="/images/<?php echo $picture->source; ?>" alt=""/></a>
										</p>

										<div class="row">
											<div class="col-xs-10 col-xs-offset-1">
												<div class="progress">
													<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $progress; ?>"
														 aria-valuemin="0"
														 aria-valuemax="100"
														 style="width: <?php echo $progress; ?>%"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<p class="text-center">Images will be shown <a href="/gallery/index"><b>here</b></a> when it will be ready.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<script type="text/template" id="image-template">
	<div class="col-md-3 p-img" id="image-__ID__" style="display: none">
		<div>
			<p class="text-center"><a href="/picture/__ID__"><img style="max-width: 100%" src="__SRC__" alt=""/></a></p>

			<div class="row">
				<div class="col-xs-10 col-xs-offset-1">
					<div class="progress">
						<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="__PROGRESS__" aria-valuemin="0"
							 aria-valuemax="100"
							 style="width: __PROGRESS__%"></div>
					</div>
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
					var answer = {};
					try {
						answer = JSON.parse(data);
					} catch (e) {
						console.log("Cannot parse json", e);
					}
					var response = answer.images;
					$("#queueLength").text(answer.queue);
					$("#queueLengthTop").text(answer.queue);
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
