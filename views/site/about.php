<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'About';
?>
<div class="site-about">
	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		This is deepdream image online processor. You can upload any image and within a little time it will be processed and sent you via email. <br/>
		You can view <a href="/">image queue</a> or <a href="/gallery/index">already processed images</a>. <br/>
		<br/><br/>
		You also can always contact me via Telegram: <a href="https://telegram.me/akkez"><b>@akkez</b></a>
	</p>
	<br/><br/>

	<p>More info about deepdream:
	<ul>
		<li><a target="_blank" href="http://googleresearch.blogspot.ru/2015/07/deepdream-code-example-for-visualizing.html">http://googleresearch.blogspot.ru/2015/07/deepdream-code-example-for-visualizing.html</a>
		</li>
		<li><a target="_blank" href="https://github.com/google/deepdream">https://github.com/google/deepdream</a></li>
	</ul>
	</p>
</div>
