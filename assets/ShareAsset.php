<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Created by PhpStorm.
 * Date: 11.07.2015
 * Time: 11:55
 */
class ShareAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'share/social-likes_flat.css',
	];
	public $js = [
		'share/social-likes.min.js',
	];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}