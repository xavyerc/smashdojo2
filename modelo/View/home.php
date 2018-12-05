<?php
// --------------------------------------------------------------------
//  You can set the page title of the header
// --------------------------------------------------------------------	
	$page_title = "home";
	
// --------------------------------------------------------------------
//  Include header
// --------------------------------------------------------------------	
	include_once('static/Header.php');
?>

<hr>
<h1 class = "titulos">New Trailers</h1>
<hr>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
        <iframe class="embed-responsive-item" src="http://www.youtube.com/embed/3tfjcsMvN8M" width="100%" height="400" frameborder="10" allowfullscreen></iframe>
		</div>
        <div class="swiper-slide">
        <iframe class="embed-responsive-item" src="http://www.youtube.com/embed/WShCN-AYHqA" width="100%" height="400" frameborder="10" allowfullscreen></iframe>
		</div>
        <div class="swiper-slide">
        <iframe class="embed-responsive-item" src="http://www.youtube.com/embed/BnpmjTMI12c" width="100%" height="400" frameborder="10" allowfullscreen></iframe>
		</div>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>
<hr>
<h1 class = "titulos">Initial Characters</h1>
<hr>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="row">
                <div class="col-xs-12 col-sm-6"><img src="<?=$GLOBALS['COD']->dir?>/assets/images/mario.png?<?=$elapsed_time?>" alt="Mario" class="img-responsive center-block lazy"></div>
                <div class="col-xs-12 col-sm-6">
                    <p class="name">Mario</p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="row">
                <div class="col-xs-12 col-sm-6"><img src="<?=$GLOBALS['COD']->dir?>/assets/images/link.png?<?=$elapsed_time?>" alt="Link" class="img-responsive center-block lazy"></div>
                <div class="col-xs-12 col-sm-6">
                    <p class="name">Link</p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="row">
                <div class="col-xs-12 col-sm-6"><img src="<?=$GLOBALS['COD']->dir?>/assets/images/fox.png?<?=$elapsed_time?>" alt="Fox" class="img-responsive center-block lazy"></div>
                <div class="col-xs-12 col-sm-6">
                    <p class="name">Fox</p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="row">
                <div class="col-xs-12 col-sm-6"><img src="<?=$GLOBALS['COD']->dir?>/assets/images/kirby.png?<?=$elapsed_time?>" alt="Kirby" class="img-responsive center-block lazy"></div>
                <div class="col-xs-12 col-sm-6">
                    <p class="name">Kirby</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="row">
                <div class="col-xs-12 col-sm-6"><img src="<?=$GLOBALS['COD']->dir?>/assets/images/yoshi.png?<?=$elapsed_time?>" alt="Yoshi" class="img-responsive center-block lazy"></div>
                <div class="col-xs-12 col-sm-6">
                    <p class="name">Yoshi</p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="row">
                <div class="col-xs-12 col-sm-6"><img src="<?=$GLOBALS['COD']->dir?>/assets/images/samus.png?<?=$elapsed_time?>" alt="Samus" class="img-responsive center-block lazy"></div>
                <div class="col-xs-12 col-sm-6">
                    <p class="name">Samus</p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="row">
                <div class="col-xs-12 col-sm-6"><img src="<?=$GLOBALS['COD']->dir?>/assets/images/pika.png?<?=$elapsed_time?>" alt="Pikachu" class="img-responsive center-block lazy"></div>
                <div class="col-xs-12 col-sm-6">
                    <p class="name">Pikachu</p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="row">
                <div class="col-xs-12 col-sm-6"><img src="<?=$GLOBALS['COD']->dir?>/assets/images/dk.png?<?=$elapsed_time?>" alt="Dk" class="img-responsive center-block lazy"></div>
                <div class="col-xs-12 col-sm-6">
                    <p class="name">Donkey Kong</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
	include_once('static/Footer.php');
?>
