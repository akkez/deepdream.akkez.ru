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