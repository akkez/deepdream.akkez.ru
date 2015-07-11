<?php use app\assets\ShareAsset;

$this->title = 'Single picture';
/* @var $this yii\web\View */
ShareAsset::register($this);
?>
<h2>Picture</h2>
<div class="row">
	<div class="col-md-6 text-center">
		<img style="max-width: 100%" src="/images/<?php echo $picture->source; ?>" alt=""/>
		<br/>

		<p>Source image</p>
	</div>
	<div class="col-md-6 text-center">
		<?php if ($picture->output == null)
		{
			?><p>Result image is not ready yet. You can visit this page later.</p><?php }
		else
		{ ?><img style="max-width: 100%" src="/ready/<?php echo $picture->output; ?>" alt=""/>
			<br/>
			<p>Processed image</p>
		<?php } ?></div>
</div>
<div class="text-center">
	<div class="social-likes">
		<div class="facebook" title="Share link on Facebook">Facebook</div>
		<div class="twitter" title="Share link on Twitter">Twitter</div>
		<div class="vkontakte" title="Поделиться ссылкой во Вконтакте">VK</div>
	</div>
</div>
<?php
if ($picture->state != 'ready')
{
	if ($picture->state == 'new')
	{
		$status = 'In queue';
	}
	else if ($picture->state == 'pending')
	{
		$progress = (int)(100.0 * $picture->status / 40);
		$status   = 'Processing (' . $progress . '%)';
	}
	else
	{
		$status = 'Plz wait';
	}
	?><h3>Status: <?php echo \yii\helpers\Html::encode($status); ?></h3><?php
}
?><h4>Output layer: <?php echo \yii\helpers\Html::encode($picture->algorithm); ?></h4>
