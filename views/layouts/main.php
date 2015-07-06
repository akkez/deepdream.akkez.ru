<?php
use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<div class="wrap">
	<?php
	NavBar::begin([
		'brandLabel' => 'DeepDream',
		'brandUrl'   => Yii::$app->homeUrl,
		'options'    => [
			'class' => 'navbar-inverse navbar-fixed-top',
		],
	]);
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-right'],
		'items'   => [
			['label' => 'Home', 'url' => ['/site/index']],
			['label' => 'Gallery', 'url' => ['/gallery/index']],
			['label' => 'Upload', 'url' => ['/site/upload']],
			['label' => 'About', 'url' => ['/site/about']],
		],
	]);
	NavBar::end();
	?>

	<?php if (\Yii::$app->getSession()->hasFlash('success'))
	{
		echo Html::tag('div', Alert::widget([
			'options' => [
				'class' => 'alert-success',
			],
			'body'    => \Yii::$app->getSession()->getFlash('success'),
		]), ['class' => 'container']);
		\Yii::$app->getSession()->removeFlash('success');
	}
	?>
	<div class="container">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= $content ?>
	</div>
</div>

<footer class="footer">
	<div class="container">
		<p class="pull-left">&copy; <a href="https://github.com/akkez" target="_blank">akkez</a> <?= date('Y') ?></p>
	</div>
</footer>

<?php $this->endBody() ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">(function (d, w, c) {
		(w[c] = w[c] || []).push(function () {
			try {
				w.yaCounter31279788 = new Ya.Metrika({id: 31279788, webvisor: true, clickmap: true, trackLinks: true, accurateTrackBounce: true});
			} catch (e) {
			}
		});
		var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () {
			n.parentNode.insertBefore(s, n);
		};
		s.type = "text/javascript";
		s.async = true;
		s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
		if (w.opera == "[object Opera]") {
			d.addEventListener("DOMContentLoaded", f, false);
		} else {
			f();
		}
	})(document, window, "yandex_metrika_callbacks");</script>
<noscript>
	<div><img src="//mc.yandex.ru/watch/31279788" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>
<?php $this->endPage() ?>
