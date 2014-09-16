<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo $og_url; ?>" />
<?php if ($og_image) { ?>
<meta property="og:image" content="<?php echo $og_image; ?>" />
<?php } else { ?>
<meta property="og:image" content="<?php echo $logo; ?>" />
<?php } ?>
<meta property="og:site_name" content="<?php echo $name; ?>" />
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/rubber/stylesheet/stylesheet.css" />
<link rel="stylesheet" href="catalog/view/javascript/FontAwesome/css/font-awesome.min.css">
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/jail/jail.min.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="catalog/view/theme/rubber/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/rubber/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php if ($stores) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>
<?php echo $google_analytics; ?>
</head>
<body>
<div id="header-bg"></div>
<div id="menu-bg"></div>
<div id="container">
<div id="header">
  <?php if ($logo) { ?>
  <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
  <?php } ?>
  <?php echo $language; ?>
  <?php echo $currency; ?>
  <?php echo $cart; ?>
  <div id="search">
    <div class="button-search"><i class="fa fa-search"></i></div>
    <input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
  </div>
  <div id="welcome">
    <?php if (!$logged) { ?>
    <?php echo $text_welcome; ?>
    <?php } else { ?>
    <?php echo $text_logged; ?>
    <?php } ?>
  </div>
  <div class="links"><a href="<?php echo $wishlist; ?>" id="wishlist-total"><i class="fa fa-heart"></i><?php echo $text_wishlist; ?></a><a href="<?php echo $account; ?>"><i class="fa fa-user"></i><?php echo $text_account; ?></a><a href="<?php echo $shopping_cart; ?>"><i class="fa fa-shopping-cart"></i><?php echo $text_shopping_cart; ?></a><a href="<?php echo $checkout; ?>"><i class="fa fa-mail-forward"></i><?php echo $text_checkout; ?></a></div>
</div>
<?php if ($categories) { ?>
<div id="menu">
  <ul>
	<li><a href="<?php echo $home; ?>"><i class="fa fa-home"></i> <?php echo $text_home; ?></a></li>
    <?php foreach ($categories as $category) { ?>
    <li><?php if ($category['active']) { ?>
	<a href="<?php echo $category['href']; ?>" class="active"><i class="fa fa-bars"></i> <?php echo $category['name']; ?></a>
	<?php } else { ?>
	<a href="<?php echo $category['href']; ?>"><i class="fa fa-bars"></i> <?php echo $category['name']; ?></a>
	<?php } ?>
      <?php if ($category['children']) { ?>
      <div>
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
          <li class="menu-top"><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a>
		  <img class="menu-image" src="<?php echo $category['children'][$i]['image']; ?>">
          <?php if($category['children'][$i]['subchildren']) { ?>
          <?php foreach ($category['children'][$i]['subchildren'] as $child) { ?>
          <li><a href="<?php echo $child['href']; ?>"> <i class="fa fa-angle-right"></i> <?php echo $child['name']; ?></a></li>
          <?php } ?>
          <?php } ?>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
	  <?php } ?>
    </li>
	<li><a href="<?php echo $brands; ?>"><i class="fa fa-th"></i> <?php echo $text_brands; ?></a>
	<div class="menu-top-manufacturers">
	<ul>
	<?php foreach($manufacturer as $manufacturers){ ?>
	<div class="menu-manufacturers">
	<li class="menu-top top-manufacturers"><a href="<?php echo $manufacturers['href']; ?>"><?php echo $manufacturers['name']; ?></a></li>
	<a href="<?php echo $manufacturers['href']; ?>"><img class="menu-image image-manufacturers" src="<?php echo $manufacturers['image']; ?>"></a>
	</div>
	<? } ?>
	</ul>
	</div>
	</li>
  </ul>
</div>
<?php } ?>
<div id="notification"></div>
