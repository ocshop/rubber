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
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
	  <!-- ocshop -->
       <!-- ocshop <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a></div> -->
	  <div class="buttons"><a onclick="$('#form').attr('action', '<?php echo $enabled; ?>'); $('#form').submit();" class="button"><span><?php echo $button_enable; ?></span></a><a onclick="$('#form').attr('action', '<?php echo $disabled; ?>'); $('#form').submit();" class="button"><span><?php echo $button_disable; ?></span></a><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
	   <!-- ocshop -->
	</div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php echo $column_name; ?></td>
              <td class="right"><?php echo $column_sort_order; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($categories) { ?>
            <?php foreach ($categories as $news) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($news['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $news['news_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $news['news_id']; ?>" />
                <?php } ?></td>
              <?php if ($news['href']) { ?>
                <td class="left"><?php echo $news['indent']; ?><a href="<?php echo $news['href']; ?>"><?php echo $news['name']; ?></a></td>
              <?php } else { ?>
                <td class="left"><?php echo $news['indent']; ?><?php echo $news['name']; ?></td>
              <?php } ?>
              <td class="right"><?php echo $news['sort_order']; ?></td>
              <td class="right"><?php foreach ($news['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
	  </div>
	  <!-- ocshop -->
	  <div class="bottom">
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>  
	  <div class="buttons"><a onclick="$('#form').attr('action', '<?php echo $enabled; ?>'); $('#form').submit();" class="button"><span><?php echo $button_enable; ?></span></a><a onclick="$('#form').attr('action', '<?php echo $disabled; ?>'); $('#form').submit();" class="button"><span><?php echo $button_disable; ?></span></a><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
	  <!-- ocshop -->
    </div>
  </div>
</div>
<?php echo $footer; ?>