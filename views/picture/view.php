<?php $this->title = 'Single picture'; ?>
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
		<span class='st_facebook_vcount' displayText='Facebook'></span>
		<span class='st_twitter_vcount' displayText='Tweet'></span>
		<span class='st_vkontakte_vcount' displayText='Vkontakte'></span>
		<span class='st_email_vcount' displayText='Email'></span>
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

	<script type="text/javascript">var switchTo5x = true;</script>
	<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "60d902d6-b24b-4134-8230-d21b9f7115e4", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<?php
/* @var $this yii\web\View */