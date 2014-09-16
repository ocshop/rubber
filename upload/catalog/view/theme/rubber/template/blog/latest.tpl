<?php echo $header; ?><?php echo $content_top; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $i=> $breadcrumb) { ?>
    <?php if($i+1<count($breadcrumbs)) { ?><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><?php } ?>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($articles) { ?>
  
   <!-- ocshop -->
  <div class="product-filter">
  <div class="display"><?php echo $text_display; ?> <i class="fa fa-list fa-lg"></i> <a onclick="displaybutton('grid');"><i class="fa fa-th fa-lg"></i></a></div>
  <div class="limit">
  <ul>
  <li><?php echo $text_limit; ?></li>
  <?php foreach ($limits as $limites) { ?>
  <li>
  <?php if ($limites['value'] == $limit) { ?>
  <span class="active"><?php echo $limites['text']; ?></a></span>
  <?php } else { ?>
  <a href="<?php echo $limites['href']; ?>"><?php echo $limites['text']; ?></a>
  <?php } ?>
  </li>
  <?php if ($article_total < (int)$limites['value']) break;  ?>
  <?php } ?>
  </ul>
  </div>
  <div class="sort">
  <ul>
  <li>
  <?php echo $text_sort_by; ?>
  </li>
  <li>
  <span   <?php if (($sorts[1]['value'] == $sort . '-' . $order) or ($sorts[2]['value'] == $sort . '-' . $order)) { ?><?php  echo 'class="active"'; ?><?php } ?>><a href="<?php if ($sorts[1]['value'] == $sort . '-' . $order) echo $sorts[2]['href']; else echo $sorts[1]['href']; ?>"><?php echo $text_sort_name; ?></a></span>
  </li>
  <li>
  <span   <?php if (($sorts[3]['value'] == $sort . '-' . $order) or ($sorts[4]['value'] == $sort . '-' . $order)) { ?><?php  echo 'class="active"'; ?><?php } ?>><a href="<?php if ($sorts[3]['value'] == $sort . '-' . $order) echo $sorts[4]['href']; else echo $sorts[3]['href']; ?>"><?php echo $text_sort_date; ?></a></span>
  </li>
  <li>
  <span   <?php if (($sorts[5]['value'] == $sort . '-' . $order) or ($sorts[6]['value'] == $sort . '-' . $order)) { ?><?php  echo 'class="active"'; ?><?php } ?>><a href="<?php if ($sorts[5]['value'] == $sort . '-' . $order) echo $sorts[6]['href']; else echo $sorts[5]['href']; ?>"><?php echo $text_sort_rated; ?></a></span>
  </li>
  <!-- ocshop popularity-->
  <li>
  <span   <?php if (($sorts[7]['value'] == $sort . '-' . $order) or ($sorts[8]['value'] == $sort . '-' . $order)) { ?><?php  echo 'class="active"'; ?><?php } ?>><a href="<?php if ($sorts[7]['value'] == $sort . '-' . $order) echo $sorts[8]['href']; else echo $sorts[7]['href']; ?>"><?php echo $text_sort_viewed; ?></a></span>
  </li>
  <!-- ocshop popularity-->
  </ul>
  </div>
  </div>
  <!-- ocshop -->
  <div class="product-list">
    <?php foreach ($articles as $article) { ?>
    <div>
      <?php if ($article['thumb']) { ?>
      <div class="image"><a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" title="<?php echo $article['name']; ?>" alt="<?php echo $article['name']; ?>" /></a></div>
      <?php } ?>
      <div class="name"><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></div>
      <div class="description"><?php echo $article['description']; ?></div>
	  <div class="more"><a class="button button-more" href="<?php echo $article['href']; ?>"><?php echo $button_more; ?></a></div>
	  <div class="added-viewed"><i class="fa fa-clock-o"></i> <?php echo $article["date_added"];?>&nbsp;&nbsp;&nbsp;<i class="fa fa-eye"></i> <?php echo $text_views; ?> <?php echo $article["viewed"];?></div>
       <div class="rating brating">
				  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($article['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
	  </div>
    </div>
    <?php } ?>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
function displaybutton (view) {
	display(view);
	$(function(){
                $('img.imagejail').jail({
					effect: 'fadeIn',
					offset: 300,
					speed : 400
				});
            });
};

function display(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.product-list > div').each(function(index, element) {	
			
			html = '<div class="left">';
			
			var image = $(element).find('.image').html();
			
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
					
			html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
			html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
			html += '  <div class="more">' + $(element).find('.more').html() + '</div>';
			html += '  <div class="added-viewed">' + $(element).find('.added-viewed').html() + '</div>';
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
				
			html += '</div>';

						
			$(element).html(html);
		});		
		
		$('.display').html('<?php echo $text_display; ?> <i class="fa fa-list fa-lg"></i> <a onclick="displaybutton(\'grid\');"><i class="fa fa-th fa-lg"></i></a>');
		
		$.cookie('display', 'list'); 
	} else {
		$('.product-list').attr('class', 'product-grid');
		
		$('.product-grid > div').each(function(index, element) {
			html = '';
			
			var image = $(element).find('.image').html();
			
			if (image != null) {
				html += '<div class="image">' + image + '</div>';
			}
			
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';
			html += '  <div class="more">' + $(element).find('.more').html() + '</div>';
			html += '  <div class="added-viewed">' + $(element).find('.added-viewed').html() + '</div>';
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
			
			$(element).html(html);
		});	
					
		$('.display').html('<?php echo $text_display; ?> <a onclick="displaybutton(\'list\');"><i class="fa fa-list fa-lg"></i></a> <i class="fa fa-th fa-lg"></i>');
		
		$.cookie('display', 'grid');
	}
}

view = $.cookie('display');

if (view) {
	display(view);
} else {
	display('list');
}
//--></script> 
<?php echo $footer; ?>