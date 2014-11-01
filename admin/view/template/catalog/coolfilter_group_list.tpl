<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <style type="text/css">
  .value,.cat{background:none repeat scroll 0 0 #EDEDED; border-left:4px solid #DBDBDB;display:inline-block;margin:3px;min-width:190px; padding:2px 3px;  }
  .cat{background: #FCF7C1;border-color:#F8EDA5;}
  </style>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'agd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
			  <td class="left">
                <?php echo $column_categories; ?>
              </td>
              <td class="right"><?php if ($sort == 'ag.sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($coolfilter_groups) { ?>
            <?php foreach ($coolfilter_groups as $coolfilter_group) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($coolfilter_group['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $coolfilter_group['coolfilter_group_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $coolfilter_group['coolfilter_group_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $coolfilter_group['name']; ?></td>
			  <td class="left">
			  <?php foreach ($coolfilter_group['categories'] as $category) { ?>
				   <span class="cat"><?php echo $category['name']; ?></span>
			  <?php } ?>
			  </td>
              <td class="right"><?php echo $coolfilter_group['sort_order']; ?></td>
              <td class="right"><?php foreach ($coolfilter_group['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>