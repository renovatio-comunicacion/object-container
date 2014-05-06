<script>
    var fechaJson = [
        <?php foreach( $events as $evt ) {
            $fecha_start = date_create($evt->event_start_date);
            $fecha_end   = date_create($evt->event_end_date);
            
        ?>
        <?php 
            $url = wp_get_attachment_image_src( get_post_thumbnail_id($evt->ID), '50');
            if($url[0]!="")
                $image = '<img class="imgthumb" src="'.$url[0].'" width="50">';
            else
                $image = '';
            
            $clasess = array('planeta','personas','sociedad');
                
            
        ?>
            [new Date(<?php echo $fecha_start->format('Y,m,d'); ?>), , '<div><?php echo $image.'<span class="title">'.$evt->post_title.'</span>'; ?><span class="text"><?php echo $evt->post_content; ?></span></div>','<?php echo $clasess[rand(0,2)] ?>'],
        <?php } ?>
    ];
</script>
<div id="mytimeline" class="row margin15"></div>
<!--
<div class="row show-grid">
    <div class="col-sm-3">
        <ul class="list-inline">
            <li><a href="#filter_01_2014" class="events_manager_filter">Ene</a></li>
            <li><a href="#filter_02_2014" class="events_manager_filter">Feb</li>
            <li class="bg-primary"><a href="#filter_03_2014" class="events_manager_filter">Mar</a></li>
            <li><a href="#filter_04_2014" class="events_manager_filter">Abr</a></li>
            <li><a href="#filter_05_2014" class="events_manager_filter">May</a></li>
            <li><a href="#filter_06_2014" class="events_manager_filter">Jun</a></li>
            <li><a href="#filter_07_2014" class="events_manager_filter">Jul</a></li>
            <li><a href="#filter_08_2014" class="events_manager_filter">Ago</a></li>
            <li><a href="#filter_09_2014" class="events_manager_filter">Sep</a></li>
            <li><a href="#filter_10_2014" class="events_manager_filter">Oct</a></li>
            <li><a href="#filter_11_2014" class="events_manager_filter">Nov</a></li>
            <li><a href="#filter_12_2014" class="events_manager_filter">Dic</a></li>
        </ul>        
    </div>
    <div class="col-sm-9">
        <div class="row">
            <div class="col-md-12">
                <div id="media_2" class="carousel slide media-carousel">
                    <div class="carousel-inner">
                        <?php $i=0; foreach( $events as $evt ) { 
                            $fecha = date_create($evt->event_start_date); ?>
                        <?php if ( $i == 0 ){ ?>
                        <div class="item active evt_<?php echo $fecha->format('m_Y'); ?>">
                        <?php } else { ?>
                        <div class="item evt_<?php echo $fecha->format('m_Y'); ?>">
                        <?php } ?>
                            <div class="col-sm-4">
                                <div class="titular">
                                    <a href="<?php echo $evt->event_attributes['external_link'];?>">
                                        <?php echo $evt->post_content?>
                                    </a>
                                </div>
                                <div class="descripcion"><?php echo $evt->post_excerpt; ?></div>
                                <div class="fecha">
                                    <div class="timeline-badge personas-background">
                                        <span class="timeline-balloon-date-day">
                                            <?php 
                                                echo $fecha->format('d')
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>                                
                        </div>                        
                        <?php $i++; } ?>
                    </div>
                    <a class="left carousel-control" href="#media" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
                    <a class="right carousel-control" href="#media" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
                </div>                          
            </div>
        </div>
    </div>
</div>
-->