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

	foreach ($algorithms as $algo) {
		$class = $algorithmId == $algo->getPrimaryKey() ? "primary" : "info";
		?><span style="display: inline-block; margin-bottom: 10px;"><a class="label label-<?php echo $class; ?>" href="/gallery/<?= $algo->id; ?>"><?php echo Html::encode($algo->name); ?> (<?php echo $algo->count; ?>)</a></span> <?php
	}
	?></h4>

<?php foreach ($pictures as $picture)
{
	$color = '';
	if ($picture->ip == Yii::$app->getRequest()->getUserIP())
	{
		$color = 'background-color: #aaf';
	}
	?>
	<div class="text-center"><?php
	echo Html::a(Html::img('/ready/' . $picture->output, ['style' => 'max-width: 100%; padding: 15px; cursor: pointer; ' . $color]), '/picture/' . $picture->id);
	?></div><?php
} ?>
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