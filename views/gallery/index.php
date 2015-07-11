<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $pictures app\models\Picture[] */
/* @var $model app\models\LoginForm */

$this->title = 'Gallery';
?>
	<h1><?= Html::encode($this->title) ?></h1>
	<h4><?php

		foreach ($algorithms as $algo)
		{
			$class = $algorithmId == $algo->getPrimaryKey() ? "primary" : "info";
			?><span style="display: inline-block; margin-bottom: 10px;"><a class="label label-<?php echo $class; ?>" href="/gallery/<?= $algo->id; ?>"><?php echo Html::encode($algo->name); ?>
				(<?php echo $algo->count; ?>)</a></span> <?php
		}
		?></h4>

	<div class="text-center">
		<?php foreach ($pictures as $picture)
		{
			?>
			<div class="img-container">
				<div class="picture text-center" style="display: inline-block;">
					<a href="/picture/<?php echo $picture->id; ?>">
						<img src="/ready/<?php echo $picture->output; ?>" class="i-visible"/>
						<img src="/images/<?php echo $picture->source; ?>" class="i-hidden"/>
					</a>

					<div>
						<span class="pull-left" style="padding: 10px 0 0 10px"><a href="/picture/<?php echo $picture->id; ?>">Share</a></span>
					<span class="pull-right" style="padding-top: 5px;">
						<a class="btn btn-primary like-btn" data-picture-id="<?php echo $picture->id; ?>" data-ignore-this-hahaha="<?php echo $picture->getLikeHash(); ?>">
							<span class="like-text">Like</span> <span class="glyphicon glyphicon-heart"></span>
							<?php if ($picture->likeCount != 0)
							{
								echo '<span class="count">' . $picture->likeCount . "</span>";
							}
							else
							{
								echo '<span class="count"></span>';
							}
							?>
						</a>
					</span>
					</div>
				</div>
			</div>
		<?php
		} ?>
	</div>
<?php if (count($pictures) == 0)
{
	?><p>There arent any processed images now. Try later.</p><?php
} ?>

	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php
				echo LinkPager::widget([
					'pagination'       => $paginator,
					'registerLinkTags' => true
				]);
				?></div>
		</div>
	</div>
	<style>
		.picture {
			margin-bottom: 30px;
		}

		.i-visible, .i-hidden {
			max-width: 100%;
		}

		.i-hidden, .picture:hover .i-visible {
			display: none
		}

		.i-visible, .picture:hover .i-hidden {
			display: block
		}
	</style>
<?php
$this->registerJsFile('/js/like.js', ['depends' => ['yii\web\JqueryAsset']]);