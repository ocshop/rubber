<?php if($show_title) { ?>
	<div class="box">
	<?php if($title) { ?>
		<div class="box-heading"><?php echo $title; ?></div>
	<?php } else { ?>
	<div style="border-bottom:1px solid #CFDFEA;"></div>
	<?php } ?>
	<div class="box-content">
		<div class="box-html">
			<?php echo $html; ?>
		</div>
	</div>
	</div>
<?php } else { ?>
	<div class="box-html">
		<?php echo $html; ?>
	</div>
<?php } ?>
