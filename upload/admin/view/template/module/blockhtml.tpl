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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_layout; ?></td>
            <td class="left"><?php echo $entry_position; ?></td>
            <td class="left"><?php echo $entry_status; ?></td>
            <td class="right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $module_row = 1; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left"><select name="blockhtml_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="left"><select name="blockhtml_module[<?php echo $module_row; ?>][position]">
                <?php if ($module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>  
                <?php if ($module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>     
                <?php if ($module['position'] == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="blockhtml_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="text" name="blockhtml_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
		  <tr>
		  	<td colspan="6">
			<div id="language-<?php echo $module_row; ?>" class="htabs">
			<?php foreach ($languages as $language) { ?>
				            <a href="#tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>
				<?php foreach ($languages as $language) { ?>
				<div id="tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>">
				<table class="form">
					<tr>
						<td><?php echo $entry_title; ?></td>
						<td>
						<input size="100" name="blockhtml_module[<?php echo $module_row; ?>][title][<?php echo $language['language_id']; ?>]" id="title-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>" value ="<?php echo isset($module['title'][$language['language_id']]) ? $module['title'][$language['language_id']] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td><?php echo $entry_header; ?></td>
						<td>
						
							<label for="yeslabel"><?php echo $entry_yes; ?></label>
							<input <?php echo ($module['header'][$language['language_id']] == 1) ? 'checked="checked"' : ''; ?> value="1" name="blockhtml_module[<?php echo $module_row; ?>][header][<?php echo $language['language_id']; ?>]" type="radio" />
							<label for="nolabel"><?php echo $entry_no; ?></label>
							<input <?php echo ($module['header'][$language['language_id']] == 0) ? 'checked="checked"' : ''; ?> value="0" name="blockhtml_module[<?php echo $module_row; ?>][header][<?php echo $language['language_id']; ?>]" type="radio" />
						</td>
					</tr>
					<tr>
						<td><?php echo $entry_html; ?></td>
						<td>

					           <textarea name="blockhtml_module[<?php echo $module_row; ?>][html][<?php echo $language['language_id']; ?>]" id="html-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><?php echo isset($module['html'][$language['language_id']]) ? $module['html'][$language['language_id']] : ''; ?></textarea> 
					         
						</td>
					</tr>
				</table>
				</div>
			<?php } ?>
			</td>
		  </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="4"></td>
            <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('html-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>', {
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
<?php $module_row++; ?>
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="blockhtml_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="blockhtml_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="blockhtml_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="blockhtml_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	
	html += ' <tr> ';
	html +=	' 	<td colspan="6">';
	html +=	'	<div id="language-' + module_row + '" class="htabs"> ';
	<?php foreach ($languages as $language) { ?>
	html +=	'       <a href="#tab-language-' + module_row + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>' ;
	<?php } ?>
	html +=	' </div>';
				<?php foreach ($languages as $language) { ?>
	html +=	'		<div id="tab-language-' + module_row + '-<?php echo $language['language_id']; ?>">';
	html +=	'		<table class="form">';
	html +=	'			<tr>';
	html +=	'				<td><?php echo $entry_title; ?></td> ';
	html +=	'				<td> ';
	html +=	'				<input size="100" name="blockhtml_module[' + module_row + '][title][<?php echo $language['language_id']; ?>]" id="title-' + module_row + '-<?php echo $language['language_id']; ?>"">';
	html +=	'				</td>';
	html +=	'			</tr>';
	html +=	'			<tr>';
	html +=	'				<td><?php echo $entry_header; ?></td>';
	html +=	'				<td>';
						
	html +=	'					<label for="yeslabel"><?php echo $entry_yes; ?></label>';
	html +=	'					<input checked="checked" value="1" name="blockhtml_module[' + module_row + '][header][<?php echo $language['language_id']; ?>]" type="radio" />';
	html +=	'					<label for="nolabel"><?php echo $entry_no; ?></label>';
	html +=	'					<input value="0" name="blockhtml_module[' + module_row + '][header][<?php echo $language['language_id']; ?>]" type="radio" /> ';
	html +=	'				</td>';
	html += '				</tr>';
	html +=	'			<tr>';
	html +=	'				<td><?php echo $entry_html; ?></td>';
	html +=	'				<td>';

	html +=	'			           <textarea name="blockhtml_module[' + module_row + '][html][<?php echo $language['language_id']; ?>]" id="html-' + module_row + '-<?php echo $language['language_id']; ?>"></textarea> ';
			         
	html +=	'				</td>';
	html +=	'			</tr>';
	html +=	'		</table>';
	html +=	'		</div>';
			<?php } ?>
	html +=	'	</td>';
	html +=	'  </tr>';

	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	<?php foreach ($languages as $language) { ?>
	CKEDITOR.replace('html-' + module_row + '-<?php echo $language['language_id']; ?>', {
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	});
	<?php } ?>
	
	
	$('#language-' + module_row + ' a').tabs();
	
	module_row++;
}
//--></script>
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
$('#language-<?php echo $module_row; ?> a').tabs();
<?php $module_row++; ?>
<?php } ?> 
//--></script> 

<?php echo $footer; ?>