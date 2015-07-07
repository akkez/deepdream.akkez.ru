<?php
/* @var $this yii\web\View */
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
					<?php foreach ($pendingPictures as $picture)
					{ ?>
						<?php $progress = (int)(100.0 * $picture->status / 40); ?>
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
					<?php } ?>
					<p class="text-center">Images will be shown <a href="/gallery/index"><b>here</b></a> when it will be ready.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
