<?php 
    if ( !is_page_template('home_renovatio.php') ) {
        $class_column = "col-sm-4 col-md-4 col-lg-3";
    }else{
        $class_column = "col-sm-4 col-md-4";  
    }
    
    $class = '';
    foreach( $obj->category_id as $oci )
    {
        $class .= ' cat_'.$oci;
    }    

    //var_dump($obj);
?>
<div class="<?php echo $class_column; ?> caja cat_content <?php echo $class; ?>">
   <a href="<?php echo $obj->url; ?>">
    <div class="caja">
            <div class="ico-categoria" style="background: transparent url(<?php bloginfo('stylesheet_directory'); ?>/css/img/c_<?php echo strtolower($obj->category); ?>.png) no-repeat 20px 20px"></div>
            
            <?php comparte($obj->id,strtolower($obj->category),$obj->url,$obj->title, $obj->short_url); ?>
            
            <img src="<?php echo $obj->image; ?>" 
                 data-src="<?php echo $obj->image_thumb; ?>" 
                 class="mg-responsive lazy" 
                 style="opacity: 1;">
        
    </div>
    <h4 class="<?php echo strtolower($obj->category); ?>"><?php echo $obj->category; ?></h4>
    <h5><?php echo $obj->title; ?></h5>
    <p><?php 
    if(strlen($obj->description) > 50)
        echo substr($obj->description,0,50)."..";
    else
        echo $obj->description; ?></p>   
    </a> 
</div>
