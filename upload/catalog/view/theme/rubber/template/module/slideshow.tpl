<link rel="stylesheet" href="catalog/view/theme/rubber/stylesheet/responsiveslides.css" />
<script src="catalog/view/theme/rubber/stylesheet/responsiveslides.js"></script>
<script>
    $(function () {
      $("#slider").responsiveSlides({
        auto: true,
        pager: true,
        nav: true,
        speed: 500,
        maxwidth: 1140,
        namespace: "centered-btns"
      });
    });
  </script>
  
<div class="rslides_container">
    <ul class="rslides" id="slider">
    <?php
        $count = 0;
        $html = "";
        foreach($banners as $banner)
        {
            if($banner['link'])
                $html .= "<li><a href='".$banner['link']."'><img src='".$banner['image']."' alt='".$banner['title']."' /></a></li>";
            else
                $html .= "<li><img src='".$banner['image']."' alt='".$banner['title']."' /></li>";
            $count += 1;
        }    
        echo $html;
        if($count == 1)
            echo $html;
    ?>
    </ul>
</div>
