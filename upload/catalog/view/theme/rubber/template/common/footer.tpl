</div>
<div id="container-footer">
<div id="footer">
  <?php if ($informations) { ?>
  <div class="column">
    <h3><?php echo $text_information; ?></h3>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  <div class="column">
    <h3><?php echo $text_service; ?></h3>
    <ul>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
	  <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
	  <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_extra; ?></h3>
    <ul>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
      <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
      <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
    </ul>
  </div>
  <div class="column-last">
    <h3><?php echo $text_account; ?></h3>
    <ul>
	  <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
  <hr>
<div id="powered"><?php echo $powered; ?><br /><?php echo $text_ocshop; ?></div>
<div id="social">
	   <a target="_blank" href="http://vk.com/"><i class="fa fa-vk"></i></a>
	   <a target="_blank" href="https://www.facebook.com/"><i class="fa fa-facebook"></i></a>
	   <a target="_blank" href="https://plus.google.com/"><i class="fa fa-google-plus"></i></a>
	   <a target="_blank" href="http://www.youtube.com/"><i class="fa fa-youtube"></i></a>
	   <a target="_blank" href="https://twitter.com/"><i class="fa fa-twitter"></i></a>
</div>
</div>
</div>
</body></html>