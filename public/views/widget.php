<?php 
    if ( !is_page_template('home_renovatio.php') ) {
        $class_column = "col-lg-12";
        $full = TRUE;
    }else{
        $class_column = "col-lg-9";  
        $full = FALSE;
    }
?>

<?php if(count($widgetContent) > 0){  ?>
<div class="col-sm-12 col-md-12 <?php echo $class_column; ?>">
    <div style="padding-left: 15px; margin-bottom: 15px;" class="row">
        <h3 style="float:left; padding-right: 20px; padding-left:15px; margin-top: 5px; margin-bottom: 0px;"><?php (!$full) ? _e("Nos preocupa lo mismo que a ti") : ''; ?></h3>
        <ul class="nav nav-tabs selector">
            <?php 
            $headerSelector .= '<li class="active" ><a href="#" class="selector_item" rel="0">' . _( 'Todos' ) . '</a></li>';
            foreach( $this->categories as $key => $value )
            {
                $headerSelector .= '<li><a href="#" data-toggle="tab" class="selector_item '.strtolower($value).'" rel="'.$key.'" data-toggle="tab">' . $value . '</a></li>';
            }  
            echo $headerSelector;
            ?>
        </ul>
    </div>
    <div class="row destacados">
        <?php foreach( $widgetContent as $wc ){ 
            echo $wc;
        } ?>
    </div>
</div>
<?php }  ?>