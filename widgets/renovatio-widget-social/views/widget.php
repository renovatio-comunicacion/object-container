<div class="container-fluid show-grid">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h4 style="color:#000;margin-top: 30px; margin-bottom: 20px;">
                <span style="padding: 7px 10px; background-color: #F2F2F2; font-size: 20px;">RConversa</span>
            </h4>
            <img class="img-responsive" src="<?php echo $rconversa->get_enclosure()->get_thumbnail(); ?>" width="265" height="165"/>
            <h5><?php echo $rconversa->get_title(); ?></h5>
            <span><?php echo $rconversa->get_date(); ?></span>
            <p>
                <?php $state = $rconversa->data['child']['http://www.rconversa.com/']['state'][0]['data']; ?>
                Estado: <?php echo $state; ?>
            </p>
            <ul class="rws-comments">
                <?php foreach ($comments as $c) { ?>
                    <li class="rws-comment">
                        <img class="rws-comment-image" src="<?php echo $c->data['child']['http://base.google.com/ns/1.0']['image_link'][0]['data']; ?>"/>
                        <span class="rws-comment-date"><?php echo Carbon\Carbon::createFromTimeStamp((int) $c->get_date('U'))->diffForHumans(); ?></span>
                        <span class="rws-comment-author"><?php echo $c->get_author()->email; ?></span>
                        <span class="rws-comment-content"><?php echo $c->get_content(); ?></span>
                    </li>
                <?php } ?>
            </ul>
            <!--<img class="img-responsive" src="http://jupiter.renovatio-comunicacion.com/test/wptest/wp-content/themes/renovatio/css/img_temp/rconversa.png">-->
        </div>
        <div class="col-sm-6 col-md-3" data-twttr-id="twttr-sandbox-0">
            <h4 style="color:#000;margin-top: 30px; margin-bottom: 20px;"><span style="padding:7px 10px; background-color: #F2F2F2; font-size: 20px;">Twitter</span></h4>
            <a class="twitter-timeline"  href="https://twitter.com/RCySost"  data-widget-id="461135375491022848">Tweets by @RCySost</a>
            <script>!function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = p + "://platform.twitter.com/widgets.js";
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, "script", "twitter-wjs");</script>
        </div>
        <div class="col-sm-6 col-md-3">
            <h4 style="color:#000;margin-top: 30px; margin-bottom: 20px;"><span style="padding:7px 10px; background-color: #F2F2F2; font-size: 20px;">Newsletter</span></h4>
            <form role="form" id="newsletterForm">
                <div class="form-group">
                    <label for="NewsletterEmail">E-mail</label>
                    <input type="text" 
                           class="form-control" 
                           id="NewsletterEmail" 
                           name="NewsletterEmail"
                           placeholder="nombre@example.com">
                </div>
                <div class="form-group">
                    <label for="NewsletterClave">Clave</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <img src="<?php echo $requestURL; ?>"/>
                        </span>
                        <input type="text" 
                           class="form-control" 
                           id="NewsletterClave" 
                           name="NewsletterClave" 
                           placeholder="">

                    </div>                    
                </div>
                <div class="checkbox">
                    <label>
                        <input id="NewsletterAccept" name="NewsletterAccept" type="checkbox"> He leído y acepto el <a href="">aviso legal</a>
                    </label>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <!--<img class="img-responsive" src="http://jupiter.renovatio-comunicacion.com/test/wptest/wp-content/themes/renovatio/css/img_temp/suscripcion.png">-->
        </div>    
    </div>
</div>
