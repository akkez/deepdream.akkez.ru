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
<p><a href="/gallery/queue">View queue</a></p>

<?php foreach ($pictures as $picture)
{
	$color = '';
	if ($picture->ip == Yii::$app->getRequest()->getUserIP())
	{
		$color = 'background-color: #aaf';
	}
	echo Html::a(Html::img('/ready/' . $picture->output, ['style' => 'padding: 15px; cursor: pointer; ' . $color, 'title' => 'Click to view source']), '/images/' . $picture->source);
} ?>
<?php if (count($pictures) == 0)
{
	?><p>There arent any processed images now. Try later.</p><?php
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