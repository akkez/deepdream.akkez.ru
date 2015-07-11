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

<?php if (!empty($key))
{ ?>
	<div class="alert alert-success">
		<b><?php echo $key->count - $key->used; ?></b> images left.
	</div>
<?php }
else
{ ?>
	<div class="alert alert-info">
		Your picture will be ready after <b>~<?php echo Helper::formatHourAndMin($readyTime); ?></b>.
	</div>
<?php } ?>

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
		<?= $form->field($model, 'algoId')->dropDownList($algorithms) ?>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title"><a data-toggle="collapse" data-target="#collapseExamples" href="#collapseExamples" onclick="return false;">Show examples</a></h3>
			</div>
			<div id="collapseExamples" class="panel-collapse collapse">
				<div class="panel-body">
					<ul>
						<li>Click on image to choose it. (<a href="/yarik/default.jpg" target="_blank">source image</a>)</li>
					</ul>
					<div class="row">
						<?php /* @var \app\models\Algorithm[] $algos */
						foreach ($algos as $algorithm)
						{
							$first = $algorithm->getPrimaryKey() == $algos[0]->getPrimaryKey();
							?>
						<div class="col-md-3 pic-algo pic-algo-<?= $algorithm->id; ?>">
							<a onclick="chooseYarik(<?= $algorithm->id; ?>); return false;" target="_blank">
								<img src="/yarik/<?= str_replace('/', '-', $algorithm->name) ?>.jpg"
									 style="max-width: 85%; margin: 10px 0 0 20px; cursor: pointer; <?php if ($first): ?>border: 2px solid red;<?php endif; ?>"/>
							</a><br/>

							<p class="text-center"><?= Html::encode($algorithm->name); ?></p></div><?php
						} ?>

					</div>
				</div>
			</div>
		</div>
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
				return Html::a(Html::img('/images/' . $row->source, ['style' => 'max-width: 300px; max-height: 300px']), '/picture/' . $row->id);
			}
		],
		'algorithm:text:Output layer',
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

<?php $script = <<<JS
	var chooseYarik = function (a) {
		$("#uploadform-algoid").val(a);
		$(".pic-algo img").css('border', 'none');
		$(".pic-algo-" + a + " img").css('border', '2px solid red');
	};
	$("#uploadform-algoid").change(function () {
		$(".pic-algo img").css('border', 'none');
		$(".pic-algo-" + this.value + " img").css('border', '2px solid red');
    });
JS;
$this->registerJs($script, $this::POS_END);
