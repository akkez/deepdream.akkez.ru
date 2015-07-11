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
			<a href="/picture/<?php echo $picture->id; ?>">
				<div class="picture text-center" style="display: inline-block;">
					<img src="/ready/<?php echo $picture->output; ?>" class="i-visible"/>
					<img src="/images/<?php echo $picture->source; ?>" class="i-hidden"/>
				</div>
			</a>
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