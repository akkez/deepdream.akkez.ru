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
	<h4><?php

		foreach ($algorithms as $algo)
		{
			$class = $algorithmId == $algo->getPrimaryKey() ? "primary" : "info";
			?><span style="display: inline-block; margin-bottom: 10px;"><a class="label label-<?php echo $class; ?>" href="/gallery/<?= $algo->id; ?>"><?php echo Html::encode($algo->name); ?>
				(<?php echo $algo->count; ?>)</a></span> <?php
		}
		?></h4>

	<div class="text-center">
		<?php foreach ($pictures as $picture)
		{
			?>
			<div class="img-container">
				<div class="picture text-center" style="display: inline-block;">
					<a href="/picture/<?php echo $picture->id; ?>">
						<img src="/ready/<?php echo $picture->output; ?>" class="i-visible"/>
						<img src="/images/<?php echo $picture->source; ?>" class="i-hidden"/>
					</a>

					<div>
						<span class="pull-left" style="padding: 10px 0 0 10px"><a href="/picture/<?php echo $picture->id; ?>">Share</a></span>
					<span class="pull-right" style="padding-top: 5px;">
						<a class="btn btn-primary like-btn" data-picture-id="<?php echo $picture->id; ?>" data-ignore-this-hahaha="<?php echo $picture->getLikeHash(); ?>">
							<span class="like-text">Like</span> <span class="glyphicon glyphicon-heart"></span>
							<?php if ($picture->likeCount != 0)
							{
								echo '<span class="count">' . $picture->likeCount . "</span>";
							}
							else
							{
								echo '<span class="count"></span>';
							}
							?>
						</a>
					</span>
					</div>
				</div>
			</div>
		<?php
		} ?>
	</div>
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
	<style>
		.picture {
			margin-bottom: 30px;
		}

		.i-visible, .i-hidden {
			max-width: 100%;
		}

		.i-hidden, .picture:hover .i-visible {
			display: none
		}

		.i-visible, .picture:hover .i-hidden {
			display: block
		}
	</style>
<?php
$script = <<<JS
	var st = function () {
		var storage = false;
		try {
			storage = 'localStorage' in window && window['localStorage'] !== null;
		} catch (e) {
			return false;
		}
		if (!storage) {
			return false;
		}
		return window['localStorage'];
	};

	$(".like-btn").click(function () {
		if (!$(this).hasClass('like-btn')) {
			return;
		}
		$(this).removeClass('like-btn').removeClass('btn-primary').addClass('btn-default');
		$(this).find('.like-text').remove();

		var btn = $(this);
		var id = $(this).data('picture-id');
		var h = $(this).data('ignore-this-hahaha'); //i lied
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
		$.ajax({
			method: "POST",
			dataType: 'text',
			url: "/picture/like",
			data: {
				'p': id,
				'h': h,
				'_csrf': csrfToken
			},
			headers: {
				'X-Like': 'True'
			},
			success: function (data) {
				if (data.length > 1 && data[0] == '+') {
					var count = parseInt(data);
					btn.find(".count").text(count);
				}
				var s = st();
				if (s) {
					s.setItem(id, "1");
				}
			}
		});
	});

	$(function() {
		var s = st();
		if (!s) {
			return;
		}
		$(".like-btn").each(function () {
			var r = s.getItem($(this).data("picture-id"));
			if (r != null) {
				$(this).removeClass('like-btn').removeClass('btn-primary').addClass('btn-default');
				$(this).find('.like-text').remove();
			}
		});
	});
JS;
$this->registerJs($script);