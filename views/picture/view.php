<h2>Picture</h2>
<div class="row">
	<div class="col-md-6 text-center">
		<img src="/images/<?php echo $picture->source; ?>" alt=""/>
		<br/>

		<p>Source image</p>
	</div>
	<div class="col-md-6 text-center">
		<?php if ($picture->output == null)
		{
			?><p>Result image is not ready yet. You can visit this page later.</p><?php }
		else
		{ ?><img src="/ready/<?php echo $picture->output; ?>" alt=""/>
			<br/>
			<p>Processed image</p>
		<?php } ?></div>
</div>