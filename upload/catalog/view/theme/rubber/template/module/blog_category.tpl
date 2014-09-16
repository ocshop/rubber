<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">
      <ul>
        <?php foreach ($categories as $news) { ?>
        <li>
          <?php if ($news['news_id'] == $news_id) { ?>
          <a href="<?php echo $news['href']; ?>" class="active"><?php echo $news['name']; ?></a>
          <?php } else { ?>
          <a href="<?php echo $news['href']; ?>"><?php echo $news['name']; ?></a>
          <?php } ?>
          <?php if ($news['children']) { ?>
          <ul>
            <?php foreach ($news['children'] as $child) { ?>
            <li>
              <?php if ($child['news_id'] == $child_id) { ?>
              <a href="<?php echo $child['href']; ?>" class="active"> - <?php echo $child['name']; ?></a>
              <?php } else { ?>
              <a href="<?php echo $child['href']; ?>"> - <?php echo $child['name']; ?></a>
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
</div>
