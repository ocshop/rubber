<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <ul class="box-category">
      <?php foreach ($categories as $category) { ?>
      <li>
        <?php if ($category['category_id'] == $category_id) { ?>
        <a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
        <?php } else { ?>
        <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
        <?php } ?>
        <?php if (($category['children']) && ($category['category_id'] == $category_id)) { ?>
        <ul>
          <?php foreach ($category['children'] as $child) { ?>
          <li>
            <?php if ($child['category_id'] == $child_id) { ?>
            <a href="<?php echo $child['href']; ?>" class="active"><?php echo $child['name']; ?></a>
            <?php } else { ?>
            <a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
            <?php } ?>
			
			        <?php if (($child['children2']) &&  ($category['category_id'] == $category_id)) { ?>
						<ul>
						  <?php foreach ($child['children2'] as $child2) { ?>
						  <li>
							<?php if ($child2['category_id'] == $child_id2) { ?>
							<a href="<?php echo $child2['href']; ?>" class="active"> - <?php echo $child2['name']; ?></a>
							<?php } else { ?>
							<a href="<?php echo $child2['href']; ?>"> - <?php echo $child2['name']; ?></a>
							<?php } ?>
		
								<?php if (($child2['children3']) &&  ($category['category_id'] == $category_id)) { ?>
								
														
									<ul>
									  <?php foreach ($child2['children3'] as $child3) { ?>
									  <li>
										<?php if ($child3['category_id'] == $child_id3) { ?>
										<a href="<?php echo $child3['href']; ?>" class="active"> - <?php echo $child3['name']; ?></a>
										<?php } else { ?>
										<a href="<?php echo $child3['href']; ?>"> - <?php echo $child3['name']; ?></a>
										<?php } ?>
									  </li>
									  <?php } ?>
									</ul>
								 <?php } ?>
							
							
						  </li>
						  <?php } ?>
						</ul>
					 <?php } ?>
			
			
          </li>
          <?php } ?>
        </ul>
        <?php } ?>
      </li>
      <?php } ?>
    </ul>
  </div>
</div>
