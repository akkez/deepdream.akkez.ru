<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Upload';
?>
	<h1><?= Html::encode($this->title) ?></h1>

<?php echo Html::errorSummary($model); ?>
<?php $form = ActiveForm::begin([
	'id' => 'login-form',
	'options'     => ['enctype' => "multipart/form-data"],
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