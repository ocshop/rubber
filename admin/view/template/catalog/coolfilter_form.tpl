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
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style=""><?php
    echo $heading_title;
    ?>
    </h1>
    <div class="buttons">
      <a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
      <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
    </div>
  </div>
  <div class="content">
    <form id="form" action="<?php echo $action; ?>" method="post">
      <div>
        <table class="form">
          <tr>
            <td><?php echo $entry_name; ?></td>
            <td>
            <?php foreach ($languages as $language) { ?>
              <input type="text" name="category_option_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo (isset($name[$language['language_id']]) ? $name[$language['language_id']]['name'] : ''); ?>" />&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
            <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_type; ?></td>
            <td>
              <select name="type">
                <?php if ($types) { ?>
					<?php foreach ($type_groups as $group) { ?>
						<optgroup label="<?php echo $$group['text']; ?>"> 
							<?php foreach ($types[$group['value']] as $typeKey => $type) { ?>
								<option value="<?php echo $typeKey; ?>" <?php if($option_types == $typeKey){ ?>selected="selected"<?php } ?>><?php echo $type['value'] ?></option>
							<?php } ?>
						</optgroup>
					<?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr id="style_tr">
            <td><?php echo $entry_style; ?></td>
            <td>
              <select name="style">  
                <?php if ($styles) { ?>
                    <?php foreach ($styles as $styleKey => $style) { ?>
                        <?php if ($styleKey == $option_styles) { ?>
                            <option value="<?php echo $styleKey; ?>" selected="selected"><?php echo $style ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $styleKey; ?>"><?php echo $style ?></option>
                        <?php } ?>
                    <?php } ?>   
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_coolfilter_groups; ?></td>
            <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($coolfilter_groups as $coolfilter_group) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($coolfilter_group['coolfilter_group_id'], $option_coolfilter_group)) { ?>
                    <input type="checkbox" name="coolfilter_group_id[]" value="<?php echo $coolfilter_group['coolfilter_group_id']; ?>" checked="checked" />
                    <?php echo $coolfilter_group['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="coolfilter_group_id[]" value="<?php echo $coolfilter_group['coolfilter_group_id']; ?>" />
                    <?php echo $coolfilter_group['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
			</td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td>
              <select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
        </table>
      </div>
    </form>
  </div>
</div>
</div>
</div>
<?php echo $footer; ?>

<script>
	function checkPrice () {
		if ($("select[name='type']").val() == 'price') {
			$("#style_tr").hide();
		} else {
			$("#style_tr").show();
		}
	}
	
	checkPrice();
	
	$("select[name='type']").change(function(){
		checkPrice();
	});
</script>