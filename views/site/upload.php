<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \app\helpers\Helper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

/* @var int $readyTime */
/* @var int $avgPictureTime */
/* @var \yii\data\ActiveDataProvider $myPictureDP */
/* @var int $lastPendingId */
/* @var int $pendingPicsCount */

$this->title = 'Upload';


?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="alert alert-info">
	Your picture will be ready after <b>~<?php echo Helper::formatHourAndMin($readyTime); ?></b>.
</div>

<?php echo Html::errorSummary($model); ?>
<?php $form = ActiveForm::begin([
	'id'      => 'login-form',
	'options' => ['enctype' => "multipart/form-data"],
	/*'fieldConfig' => [
		'template'     => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
		'labelOptions' => ['class' => 'col-lg-1 control-label'],
	],*/
]); ?>

<div class="form-group">
	<?= $form->field($model, 'email')->textInput() ?>
	<span class="help-block">Image will be sent on this email after processing.</span>
</div>
<div class="form-group">
	<?= $form->field($model, 'image')->fileInput() ?>
</div>

<div class="form-group">
	<?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<h2>My images</h2>
<?php

echo GridView::widget([
	'dataProvider' => $myPictureDP,
	'columns'      => [
		[
			'header' => 'Image',
			'format' => 'raw',
			'value'  => function ($row)
			{
				return Html::img('/images/' . $row->source, ['style' => 'max-width: 300px; max-height: 300px']);
			}
		],
		[
			'header' => 'Position in queue',
			'format' => 'text',
			'value'  => function ($row) use ($lastPendingId, $pendingPicsCount)
			{
				$pos = intval($row->id - $lastPendingId);

				if ($pos == 1)
				{
					return '1st / ' . $pendingPicsCount;
				}
				if ($pos == 2)
				{
					return '2nd / ' . $pendingPicsCount;
				}

				return $pos . 'th / ' . $pendingPicsCount;
			}
		],
		[
			'header' => 'Time before complete',
			'format' => 'text',
			'value'  => function ($row) use ($lastPendingId, $avgPictureTime)
			{
				$pos = intval($row->id - $lastPendingId);

				return '~' . Helper::formatHourAndMin(($pos + 1) * $avgPictureTime);
			}
		],
	],
]) ?><!-- apt <?php echo $avgPictureTime; ?> rt <?php echo $readyTime; ?> -->