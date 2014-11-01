<div class="box">
   <?php if ($header) {?>
  <div class="box-heading"><?php echo $header; ?></div>
  <?php } ?>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($reviews as $review) { ?>
      <div style="margin:0 0 10px;width:100%;overflow:auto;">
		<?php if ($review['article_id']) {?>
        <?php if ($review['articl_thumb']) { ?>
        <div class="image" style="float:left;margin-right:20px;"><a href="<?php echo $review['articl_href']; ?>"><img src="<?php echo $review['articl_thumb']; ?>" alt="<?php echo $review['articl_name']; ?>" title="<?php echo $review['articl_name']; ?>"/></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $review['articl_href']; ?>"><?php echo $review['articl_name']; ?></a></div>
        <div class="description"><?php echo $review['description']?> <a href="<?php echo $review['href']?>">...&raquo;</a></div>
		<div class="added-viewed" style="text-align:right;margin:10px 0 5px;"><i class="fa fa-clock-o"></i> <?php echo $review["date_added"];?>&nbsp;&nbsp;&nbsp;<i class="fa fa-eye"></i> <?php echo $text_views; ?> <?php echo $review["viewed"];?></div>
		<div class="rating" style="text-align:right;overflow:hidden;">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($review['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
		<div class="author" style="text-align:right;margin-top:5px;"><strong><?php echo $review['author']?></strong></div>
		<?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
</div>