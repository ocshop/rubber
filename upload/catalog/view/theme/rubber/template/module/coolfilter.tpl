<?php if ($coolfilters) { ?>
<noindex>
<div class="box">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
	<?php foreach ($coolfilters as $coolfilter) { ?>
		<?php if (isset($coolfilter['coolfilters'])) { ?>
            <?php if ($coolfilter['style_id'] == 'list') { ?>
                <div class="coolfilter-item coolfilter-item-list">
                    <b><?php echo $coolfilter['name']; ?></b>
                    <ul>
                    <?php foreach ($coolfilter['coolfilters'] as $coolfilter_value) { ?>
                        <?php if ($coolfilter_value['count'] || !$count_enabled) { ?>
							<li><a href="<?php echo $coolfilter_value['href']; ?>" <?php if($coolfilter_value['active']) { ?>class="coolfilter_active"<?php } ?> data-key="<?php echo $coolfilter_value['key']; ?>" data-value="<?php echo $coolfilter_value['value']; ?>"><?php echo $coolfilter_value['name']; ?></a> <?php echo $coolfilter_value['view_count']; ?></li>
						<?php } else { ?>
							<li><?php echo $coolfilter_value['name']; ?> <?php echo $coolfilter_value['view_count']; ?></li>
						<?php } ?>
                    <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if ($coolfilter['style_id'] == 'checkbox') { ?>
                <div class="coolfilter-item coolfilter-item-checkbox">
                    <b><?php echo $coolfilter['name']; ?></b>
                    <ul>
                    <?php foreach ($coolfilter['coolfilters'] as $coolfilter_value) { ?>
						<?php if ($coolfilter_value['count'] || !$count_enabled) { ?>
							<li><input type="checkbox" <?php if($coolfilter_value['active']) { ?>checked="checked"<?php } ?>><a href="<?php echo $coolfilter_value['href']; ?>" <?php if($coolfilter_value['active']) { ?>class="coolfilter_active"<?php } ?> data-key="<?php echo $coolfilter_value['key']; ?>" data-value="<?php echo $coolfilter_value['value']; ?>"><?php echo $coolfilter_value['name']; ?></a> <?php echo $coolfilter_value['view_count']; ?></li>
						<?php } else { ?>
							<li><input type="checkbox" disabled="disabled"><?php echo $coolfilter_value['name']; ?> <?php echo $coolfilter_value['view_count']; ?></li>
						<?php } ?>
                    <?php } ?>
                    </ul>
                </div>
            <?php } ?>
			<?php if ($coolfilter['style_id'] == 'select') { ?>
                <div class="coolfilter-item coolfilter-item-select">
                    <div class="coolfilter-item-select-head"><?php echo $coolfilter['name']; ?><div class="coolfilter-item-select-button"></div></div>
                    <div class="coolfilter-item-select-list">
						<ul>
						<?php foreach ($coolfilter['coolfilters'] as $coolfilter_value) { ?>
							<?php if ($coolfilter_value['count'] || !$count_enabled) { ?>
								<li><input type="checkbox" <?php if($coolfilter_value['active']) { ?>checked="checked"<?php } ?>><a href="<?php echo $coolfilter_value['href']; ?>" <?php if($coolfilter_value['active']) { ?>class="coolfilter_active"<?php } ?> data-key="<?php echo $coolfilter_value['key']; ?>" data-value="<?php echo $coolfilter_value['value']; ?>"><?php echo $coolfilter_value['name']; ?></a> <?php echo $coolfilter_value['view_count']; ?></li>
							<?php } else { ?>
								<li><input type="checkbox" disabled="disabled"><?php echo $coolfilter_value['name']; ?> <?php echo $coolfilter_value['view_count']; ?></li>
							<?php } ?>
						<?php } ?>
						</ul>
					</div>
                </div>
            <?php } ?>
			
			<?php if ($coolfilter['style_id'] == 'image') { ?>
                <div class="coolfilter-item coolfilter-item-image">
                    <div class="coolfilter-item-image-head"><?php echo $coolfilter['name']; ?></div>
					<?php foreach ($coolfilter['coolfilters'] as $coolfilter_value) { ?>
						<?php if ($coolfilter_value['count'] || !$count_enabled) { ?>
							<a href="<?php echo $coolfilter_value['href']; ?>" <?php if($coolfilter_value['active']) { ?>class="coolfilter_active"<?php } ?> data-key="<?php echo $coolfilter_value['key']; ?>" data-value="<?php echo $coolfilter_value['value']; ?>"><img src="<?php echo $coolfilter_value['image']; ?>" alt="<?php echo $coolfilter_value['name']; ?><?php echo $coolfilter_value['view_count']; ?>" title="<?php echo $coolfilter_value['name']; ?><?php echo  $coolfilter_value['view_count']; ?>"></a>
						<?php } else { ?>
							<img src="<?php echo $coolfilter_value['image']; ?>" alt="<?php echo $coolfilter_value['name']; ?><?php echo  $coolfilter_value['view_count']; ?>" title="<?php echo $coolfilter_value['name']; ?><?php echo $coolfilter_value['view_count']; ?>">
						<?php } ?>
					<?php } ?>
                </div>
            <?php } ?>
			<?php if ($coolfilter['style_id'] == 'slider') { ?>
                <div class="coolfilter-item coolfilter-item-slider">
                    <b><?php echo $coolfilter['name']; ?></b>
					<div class="coolfilter-item-slider-body">
					<input type="text" id="price" style="border:0; color:#229AC8; background:#fff; font-weight:bold;" class="coolfilter_active" data-key="p" data-value="<?php echo $coolfilter['coolfilters'][0]['value'] . ',' . $coolfilter['coolfilters'][1]['value']; ?>" disabled="disabled" />
					<div id="slider-range" class="slider-range"></div>
					</div>
					<script>
					$(function() {
						if (/\Wp:[\d\.]+,[\d\.]+/.test(location.href)) {
							var myRe = /\Wp:([\d\.]+),([\d\.]+)/;
							var pricecoolfilterValue = myRe.exec(location.href);
							startValue = pricecoolfilterValue[1];
							endValue = pricecoolfilterValue[2];
							$("#price").attr('data-value', startValue + ',' + endValue);
						} else {
							startValue = <?php echo $coolfilter['coolfilters'][0]['value']; ?>;
							endValue = <?php echo $coolfilter['coolfilters'][1]['value']; ?>;
						}
						$( "#slider-range" ).slider({
							range: true,
							min: <?php echo $coolfilter['coolfilters'][0]['value']; ?>,
							max: <?php echo $coolfilter['coolfilters'][1]['value']; ?>,
							values: [ startValue, endValue ],
							slide: function( event, ui ) {
								$( "#price" ).val( "<?php echo $currency_symbol_left; ?>" + ui.values[ 0 ].toFixed(<?php echo $count_symbols; ?>) + "<?php echo $currency_symbol_right; ?> - <?php echo $currency_symbol_left; ?>" + ui.values[ 1 ].toFixed(<?php echo $count_symbols; ?>) + "<?php echo $currency_symbol_right; ?>" );
							},
							change: function( event, ui ) {
								/*var href = '<?php echo htmlspecialchars_decode($coolfilter['coolfilters'][0]['href']); ?>';
								var exp = /p:[\d\.,]+/g;
								href = href.replace(exp, "p:" + ui.values[ 0 ] + "," + ui.values[ 1 ]);
								location = href;*/
								$( "#price" ).attr("data-value", ui.values[ 0 ] + "," + ui.values[ 1 ]);
							}
						});
						$( "#price" ).val( "<?php echo $currency_symbol_left; ?>" + $( "#slider-range" ).slider( "values", 0 ).toFixed(<?php echo $count_symbols; ?>) + 
							"<?php echo $currency_symbol_right; ?> - <?php echo $currency_symbol_left; ?>" + $( "#slider-range" ).slider( "values", 1 ).toFixed(<?php echo $count_symbols; ?>) + "<?php echo $currency_symbol_right; ?>" );
					});
					</script>
                </div>
            <?php } ?>
		<?php } ?>
	<?php } ?>
	<a id="coolfilter_apply_button" class="button"><span><?php echo $text_apply; ?></span></a>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
</noindex>
<?php } ?>
<script>
	$("#coolfilter_apply_button").click(function(){
		var coolfilter = '';
		var arr = {};
		$(".coolfilter_active").each(function(i){
			var key = $(this).attr("data-key");
			var value = $(this).attr("data-value");
			if (arr[key] === undefined) {
				arr[key] = '';
				arr[key] += value;
			} else {
				arr[key] += ',' + value;
			}
			
		});
		
		$.each(arr, function(index,val){
			coolfilter += index + ':' + val + ';';
		});
		coolfilter = coolfilter.substr(0, coolfilter.length - 1);
		setUrl(coolfilter);
	});
	
	function setUrl(coolfilter) {
		var href = location.href;
		
		var exp = /&page=(.*?)(&|$)/g;
		href = href.replace(exp, "");
		var exp = /&coolfilter=(.*?)(&|$)/g;
		href = href.replace(exp, "");
		href = href.replace(exp, "$2") + '&coolfilter=' + coolfilter;
				
		location = href;
	}
	
	function addButtonReset() {
		var href = location.href;
		if (/(\?|&)coolfilter=(.*?)/.test(href)) {
			$("#coolfilter_apply_button").after('<br><br>[ <a onclick="resetcoolfilter();"><?php echo $text_reset_coolfilter; ?></a> ]');
		}
	}
	
	addButtonReset();
	
	function resetcoolfilter() {
		var href = location.href;
		var exp = /(\?|\&)coolfilter=(.*)?(&|$)/g;
		href = href.replace(exp, "");
		location = href;
	}
	
	$(".coolfilter-item-select-head").click(function(){
		$(".coolfilter-item-select-list").not($(this).next(".coolfilter-item-select-list")).hide();
		$(this).next(".coolfilter-item-select-list").toggle(); 
		return false;
	});
	
	$(document).click(function(e){ 
		var $target = $(e.target);
		if (!$target.is("a") && !$target.is("input:checkbox")) { 
			$(".coolfilter-item-select-list").hide(); 
		} 
	});
	
	$(".coolfilter-item a").click(function(e){ 
		e.preventDefault();
		$(this).toggleClass("coolfilter_active");
		var checkbox = $(this).siblings("input:checkbox");
		if (checkbox.is(':checked')) {
			checkbox.attr('checked', false);
		} else {
			checkbox.attr('checked', true);
		}
	});
	
	
	 $(".coolfilter-item-checkbox input:checkbox, .coolfilter-item-select input:checkbox").click(function(){
		$(this).siblings("a").toggleClass("coolfilter_active");
		$(this).parents(".coolfilter-item-select-list").show();
    });
	
	
	
	
</script>