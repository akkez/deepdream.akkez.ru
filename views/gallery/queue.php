<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $pictures app\models\Picture[] */
/* @var $model app\models\LoginForm */

$this->title = 'Queue';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php foreach ($pictures as $picture)
{
	$color = '';
	if ($picture->ip == Yii::$app->getRequest()->getUserIP())
	{
		$color = 'background-color: #aaf';
	}
	echo Html::img('/images/' . $picture->source, ['style' => 'padding: 15px; ' . $color]);
} ?>
<?php if (count($pictures) == 0)
{
	?><p>There arent any queued images now. Try later.</p><?php
} ?>

<div class="row">
	<div class="col-md-12">
		<?php
		echo LinkPager::widget([
			'pagination'       => $paginator,
			'registerLinkTags' => true
		]);
		?>
	</div>
</div>