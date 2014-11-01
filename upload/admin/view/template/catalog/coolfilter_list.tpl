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
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style=""><?php echo $heading_title; ?></h1>
    <div class="buttons">
      <a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a>
      <a onclick="$('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a>
    </div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" align="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left" width="20%"><?php echo $column_name; ?></td>
			<td class="left" width="20%"><?php echo $column_type; ?></td>
			<td class="left" width="20%"><?php echo $column_style; ?></td>
            <td class="left" width="50%"><?php echo $column_coolfilter_groups; ?></td>
            <td class="right"><?php echo $column_sort_order; ?></td>
            <td class="right"><?php echo $column_status; ?></td>
            <td class="right" width="100"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($options) { ?>
          <?php foreach ($options as $option) { ?>
          <tr>
            <td align="center"><input type="checkbox" name="selected[]" value="<?php echo $option['option_id']; ?>" /></td>
            <td class="left">
              <span class="name"><?php echo $option['name']; ?></span>
            </td>
			<td class="left">
              <span class="name"><?php echo $option['type']; ?></span>
            </td>
			<td class="left">
              <span class="name"><?php echo $option['style']; ?></span>
            </td>
            <td>
              <?php foreach ($option['coolfilter_group'] as $value) { ?>
                <span class="value"><?php echo $value['name']; ?></span>
              <?php } ?>
            </td>
            <td class="right"><?php echo $option['sort_order']; ?></td>
            <td class="right"><?php echo $option['status']; ?></td>
            <td>
              <?php foreach ($option['action'] as $action) { ?>
                <a href="<?php echo $action['href']; ?>" class="button"><span><?php echo $action['text']; ?></span></a>
              <?php } ?>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
  </div>
</div>
</div>
<?php echo $footer; ?>