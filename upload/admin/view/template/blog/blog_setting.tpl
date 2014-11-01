<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/setting.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-image"><?php echo $tab_image; ?></a><a href="#tab-option"><?php echo $tab_option; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
         <div id="tab-image">
          <table class="form">
            
            <tr>
              <td><span class="required">*</span> <?php echo $entry_blog_image_category; ?></td>
              <td><input type="text" name="config_blog_image_category_width" value="<?php echo $config_blog_image_category_width; ?>" size="3" />
                x
                <input type="text" name="config_blog_image_category_height" value="<?php echo $config_blog_image_category_height; ?>" size="3" />
                <?php if ($error_blog_image_category) { ?>
                <span class="error"><?php echo $error_blog_image_category; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_blog_image_article; ?></td>
              <td><input type="text" name="config_blog_image_article_width" value="<?php echo $config_blog_image_article_width; ?>" size="3" />
                x
                <input type="text" name="config_blog_image_article_height" value="<?php echo $config_blog_image_article_height; ?>" size="3" />
                <?php if ($error_blog_image_article) { ?>
                <span class="error"><?php echo $error_blog_image_article; ?></span>
                <?php } ?></td>
            </tr>
			<tr>
              <td><span class="required">*</span> <?php echo $entry_blog_image_gallery; ?></td>
              <td><input type="text" name="config_blog_image_gallery_width" value="<?php echo $config_blog_image_gallery_width; ?>" size="3" />
                x
                <input type="text" name="config_blog_image_gallery_height" value="<?php echo $config_blog_image_gallery_height; ?>" size="3" />
                <?php if ($error_blog_image_gallery) { ?>
                <span class="error"><?php echo $error_blog_image_gallery; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_blog_image_additional; ?></td>
              <td><input type="text" name="config_blog_image_additional_width" value="<?php echo $config_blog_image_additional_width; ?>" size="3" />
                x
                <input type="text" name="config_blog_image_additional_height" value="<?php echo $config_blog_image_additional_height; ?>" size="3" />
                <?php if ($error_blog_image_additional) { ?>
                <span class="error"><?php echo $error_blog_image_additional; ?></span>
                <?php } ?></td>
            </tr>
			<tr>
              <td><span class="required">*</span> <?php echo $entry_blog_image_popup; ?></td>
              <td><input type="text" name="config_blog_image_popup_width" value="<?php echo $config_blog_image_popup_width; ?>" size="3" />
                x
                <input type="text" name="config_blog_image_popup_height" value="<?php echo $config_blog_image_popup_height; ?>" size="3" />
                <?php if ($error_blog_image_popup) { ?>
                <span class="error"><?php echo $error_blog_image_popup; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_blog_image_related; ?></td>
              <td><input type="text" name="config_blog_image_related_width" value="<?php echo $config_blog_image_related_width; ?>" size="3" />
                x
                <input type="text" name="config_blog_image_related_height" value="<?php echo $config_blog_image_related_height; ?>" size="3" />
                <?php if ($error_blog_image_related) { ?>
                <span class="error"><?php echo $error_blog_image_related; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </div>
        <div id="tab-option">
          <h2><?php echo $text_items; ?></h2>
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_blog_catalog_limit; ?></td>
              <td><input type="text" name="config_blog_catalog_limit" value="<?php echo $config_blog_catalog_limit; ?>" size="3" />
                <?php if ($error_blog_catalog_limit) { ?>
                <span class="error"><?php echo $error_blog_catalog_limit; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_blog_admin_limit; ?></td>
              <td><input type="text" name="config_blog_admin_limit" value="<?php echo $config_blog_admin_limit; ?>" size="3" />
                <?php if ($error_blog_admin_limit) { ?>
                <span class="error"><?php echo $error_blog_admin_limit; ?></span>
                <?php } ?></td>
            </tr>
          </table>
          <h2><?php echo $text_article; ?></h2>
          <table class="form">
		    <tr>
              <td><?php echo $entry_blog_header_menu; ?></td>
              <td><?php if ($config_blog_header_menu) { ?>
                <input type="radio" name="config_blog_header_menu" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_blog_header_menu" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_blog_header_menu" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_blog_header_menu" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_blog_article_count; ?></td>
              <td><?php if ($config_blog_article_count) { ?>
                <input type="radio" name="config_blog_article_count" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_blog_article_count" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_blog_article_count" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_blog_article_count" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_blog_review; ?></td>
              <td><?php if ($config_blog_review_status) { ?>
                <input type="radio" name="config_blog_review_status" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_blog_review_status" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_blog_review_status" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_blog_review_status" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_blog_download; ?></td>
              <td><?php if ($config_blog_download) { ?>
                <input type="radio" name="config_blog_download" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_blog_download" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_blog_download" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_blog_download" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
<?php echo $footer; ?>