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
    <div class="heading">
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
	 <div class="vtabs">
	                    <a href="#tab-general"><?php echo $tab_general; ?></a>
						<?php
						  for($i=1;$i<=12;$i++){
						?>
                        <a href="#tab-setting<?php echo $i;?>"><?php echo $tab_rate.' '.$i; ?></a>
					    <?php }?>
						
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	  
	  <div id="tab-general" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $tab_general; ?></td>
              <td><select name="xshipping_status">
                  <?php if ($xshipping_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
		   <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="xshipping_sort_order" value="<?php echo $xshipping_sort_order; ?>" size="1" /></td>
          </tr>
          </table>
        </div>
		<?php
				for($i=1;$i<=12;$i++){
		     ?>
	   <div id="tab-setting<?php echo $i;?>" class="vtabs-content">
          <table class="form">
		  <tr>
            <td><?php echo $entry_name; ?></td>
            <td><input type="text" name="xshipping_name<?php echo $i;?>" value="<?php echo ${'xshipping_name'.$i}; ?>" /></td>
          </tr>
		  
          <tr>
            <td><?php echo $entry_cost; ?></td>
            <td><input type="text" name="xshipping_cost<?php echo $i;?>" value="<?php echo ${'xshipping_cost'.$i}; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_tax; ?></td>
            <td><select name="xshipping_tax_class_id<?php echo $i;?>">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == ${'xshipping_tax_class_id'.$i}) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="xshipping_geo_zone_id<?php echo $i;?>">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == ${'xshipping_geo_zone_id'.$i}) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
		   <tr>
            <td><?php echo $entry_free; ?></td>
            <td><input type="text" name="xshipping_free<?php echo $i;?>" value="<?php echo ${'xshipping_free'.$i}; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="xshipping_sort_order<?php echo $i;?>" value="<?php echo ${'xshipping_sort_order'.$i}; ?>" size="1" /></td>
          </tr>
		  <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="xshipping_status<?php echo $i;?>">
                  <?php if (${'xshipping_status'.$i}) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
        </table>
        </div>
		<?php }?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.vtabs a').tabs(); 
//--></script> 
<?php echo $footer; ?> 