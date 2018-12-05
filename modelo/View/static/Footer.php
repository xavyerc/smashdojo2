<div class="clearfix"></div>
<footer>
<div class="container-fluid">
    <div class="col-xs-12">
        <hr>
            <div class="text-center center-block"><br/>
                <a href="https://www.facebook.com/bootsnipp"><i id="social-fb" class="fa fa-facebook-square fa-3x social" color="#d31c1c"></i></a>
	            <a href="https://twitter.com/bootsnipp"><i id="social-tw" class="fa fa-twitter-square fa-3x social"></i></a>
	            <a href="https://plus.google.com/+Bootsnipp-page"><i id="social-gp" class="fa fa-google-plus-square fa-3x social"></i></a>
	            <a href="mailto:bootsnipp@gmail.com"><i id="social-em" class="fa fa-envelope-square fa-3x social"></i></a>
            </div>
        <hr>
    </div>
</div><br/>
</footer>
<script src="<?=$GLOBALS['COD']->dir?>/assets/js/jquery-3.2.1.min.js?<?=$elapsed_time?>"></script>
<script src="<?=$GLOBALS['COD']->dir?>/assets/css/bootstrap/bootstrap.min.js?<?=$elapsed_time?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/js/swiper.js"></script>
<script src="<?=$GLOBALS['COD']->dir?>/assets/js/swiper.js?<?=$elapsed_time?>"></script>
<script src="<?=$GLOBALS['COD']->dir?>/assets/js/jquery.lazyload.min.js?<?=$elapsed_time?>"></script>
<script src="<?=$GLOBALS['COD']->dir?>/assets/js/greensock.js?<?=$elapsed_time?>"></script>
<script src="<?=$GLOBALS['COD']->dir?>/assets/js/kl-animate.min.js?<?=$elapsed_time?>"></script>
<script src="<?=$GLOBALS['COD']->dir?>/assets/js/<?=$page_title?>.js?<?=$elapsed_time?>"></script>
<script>
    $(document).ready(function(){
       $("img.lazy").lazyload({
            threshold : 200,
            effect : "fadeIn"
        });
       $("#botonmenu, #cerrar").click(function (){
            $(".menu-hidden-show").toggleClass("esconder-menu-hiden-show");
        });
       $.klAnimate();
    });
</script>
</body>
</html>