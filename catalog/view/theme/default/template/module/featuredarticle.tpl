<?php if (isset($articles) && count($articles)) { ?>
<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($articles as $article) { ?>
      <div style="margin:0 0 20px;width:100%;">
          <?php if ($article['thumb']) { ?>
		  <div class="image"><a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['name']; ?>" title="<?php echo $article['name']; ?>" /></a></div>
          <?php } ?>
          <div class="name" style="max-height:30px;overflow:hidden;"><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></div>
         <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($article['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
      <div class="description"><?php echo $article['description']; ?> <a href="<?php echo $article['href']?>">...&raquo;</a></div>
	  </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php } ?>
