<?php
// --------------------------------------------------------------------
//  You can set the page title of the header
// --------------------------------------------------------------------	
	$page_title = "character";
	
// --------------------------------------------------------------------
//  Include header
// --------------------------------------------------------------------	
	include_once('static/Header.php');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <hr>
            <h1 class="titulos">Character Information/Details</h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="card-container">
                <div class="card">
                    <div class="front">
                        <div class="cover"></div>
                        <div class="user">
                            <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/mario.png?<?=$elapsed_time?>"/>
                        </div>
                        <div class="content">
                            <div class="main"><h3 class="name">Mario</h3><p class="profession">Super Mario Bros.</p></div>
                        </div>
                    </div> <!-- end front panel -->
                    <div class="back">
                        <div class="header"><h5 class="motto">"It´s a me, Mario!"</h5></div>
                        <div class="content">
                            <div class="user"> <br> <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/mario_logo.jpeg?<?=$elapsed_time?>"/></div>
                            <div class="main">
                                <div class="stats-container">
                                    <div class="stats"><h4>A</h4><p>Tier</p></div>
                                    <div class="stats"><h4>9</h4><p>Ranking</p></div>
                                    <div class="stats"><h4>9.81</h4><p>Score</p></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end back panel -->
                </div> <!-- end card -->
            </div> <!-- end card-container --> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="card-container">
                <div class="card">
                    <div class="front">
                        <div class="cover"></div>
                        <div class="user">
                            <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/link.png?<?=$elapsed_time?>"/>
                        </div>
                        <div class="content">
                            <div class="main"><h3 class="name">Link</h3><p class="profession">The Legend of Zelda</p></div>
                        </div>
                    </div> <!-- end front panel -->
                    <div class="back">
                        <div class="header"><h5 class="motto">"Hya, Hya"</h5></div>
                        <div class="content">
                            <div class="user"> <br> <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/link_logo.jpg?<?=$elapsed_time?>"/></div>
                            <div class="main">
                                <div class="stats-container">
                                    <div class="stats"><h4>D</h4><p>Tier</p></div>
                                    <div class="stats"><h4>31</h4><p>Ranking</p></div>
                                    <div class="stats"><h4>32.11</h4><p>Score</p></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end back panel -->
                </div> <!-- end card -->
            </div> <!-- end card-container --> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="card-container">
                <div class="card">
                    <div class="front">
                        <div class="cover"></div>
                        <div class="user">
                            <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/fox.png?<?=$elapsed_time?>"/>
                        </div>
                        <div class="content">
                            <div class="main"><h3 class="name">Fox</h3><p class="profession">Star Fox</p></div>
                        </div>
                    </div> <!-- end front panel -->
                    <div class="back">
                        <div class="header"><h5 class="motto">"Oh no sir, we prefer doing things our own way."</h5></div>
                        <div class="content">
                            <div class="user"> <br> <br> <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/fox_logo.png?<?=$elapsed_time?>"/></div>
                            <div class="main">
                                <div class="stats-container">
                                    <div class="stats"><h4>A</h4><p>Tier</p></div>
                                    <div class="stats"><h4>7</h4><p>Ranking</p></div>
                                    <div class="stats"><h4>6.56</h4><p>Score</p></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end back panel -->
                </div> <!-- end card -->
            </div> <!-- end card-container --> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="card-container">
                <div class="card">
                    <div class="front">
                        <div class="cover"></div>
                        <div class="user">
                            <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/kirby.png?<?=$elapsed_time?>"/>
                        </div>
                        <div class="content">
                            <div class="main"><h3 class="name">Kirby</h3><p class="profession">Kirby´s Dream Land</p></div>
                        </div>
                    </div> <!-- end front panel -->
                    <div class="back">
                        <div class="header"><h5 class="motto">"Aye"</h5></div>
                        <div class="content">
                            <div class="user"> <br> <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/kirby_logo.jpg?<?=$elapsed_time?>"/></div>
                            <div class="main">
                                <div class="stats-container">
                                    <div class="stats"><h4>F</h4><p>Tier</p></div>
                                    <div class="stats"><h4>50</h4><p>Ranking</p></div>
                                    <div class="stats"><h4>48.86</h4><p>Score</p></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end back panel -->
                </div> <!-- end card -->
            </div> <!-- end card-container --> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="card-container">
                <div class="card">
                    <div class="front">
                        <div class="cover"></div>
                        <div class="user">
                            <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/yoshi.png?<?=$elapsed_time?>"/>
                        </div>
                        <div class="content">
                            <div class="main"><h3 class="name">Yoshi</h3><p class="profession">Yoshi Story</p></div>
                        </div>
                    </div> <!-- end front panel -->
                    <div class="back">
                        <div class="header"><h5 class="motto">“I know! We should team up! Come on! Hop on my back!”</h5></div>
                        <div class="content">
                            <div class="user"> <br> <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/yoshi_logo.jpg?<?=$elapsed_time?>"/></div>
                            <div class="main">
                                <div class="stats-container">
                                    <div class="stats"><h4>D</h4><p>Tier</p></div>
                                    <div class="stats"><h4>34</h4><p>Ranking</p></div>
                                    <div class="stats"><h4>32.96</h4><p>Score</p></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end back panel -->
                </div> <!-- end card -->
            </div> <!-- end card-container --> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="card-container">
                <div class="card">
                    <div class="front">
                        <div class="cover"></div>
                        <div class="user">
                            <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/samus.png?<?=$elapsed_time?>"/>
                        </div>
                        <div class="content">
                            <div class="main"><h3 class="name">Samus</h3><p class="profession">Metroid</p></div>
                        </div>
                    </div> <!-- end front panel -->
                    <div class="back">
                        <div class="header"><h5 class="motto">"My past it´s not a memory. It´s a force on my back"</h5></div>
                        <div class="content">
                            <div class="user"> <br> <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/samus_logo.png?<?=$elapsed_time?>"/></div>
                            <div class="main">
                                <div class="stats-container">
                                    <div class="stats"><h4>D</h4><p>Tier</p></div>
                                    <div class="stats"><h4>37</h4><p>Ranking</p></div>
                                    <div class="stats"><h4>35.13</h4><p>Score</p></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end back panel -->
                </div> <!-- end card -->
            </div> <!-- end card-container --> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="card-container">
                <div class="card">
                    <div class="front">
                        <div class="cover"></div>
                        <div class="user">
                            <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/pika.png?<?=$elapsed_time?>"/>
                        </div>
                        <div class="content">
                            <div class="main"><h3 class="name">Pikachu</h3><p class="profession">Pokémon Yellow</p></div>
                        </div>
                    </div> <!-- end front panel -->
                    <div class="back">
                        <div class="header"><h5 class="motto">"Pika, pika"</h5></div>
                        <div class="content">
                            <div class="user"> <br> <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/pika_logo.jpg?<?=$elapsed_time?>"/></div>
                            <div class="main">
                                <div class="stats-container">
                                    <div class="stats"><h4>B</h4><p>Tier</p></div>
                                    <div class="stats"><h4>15</h4><p>Ranking</p></div>
                                    <div class="stats"><h4>17.37</h4><p>Score</p></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end back panel -->
                </div> <!-- end card -->
            </div> <!-- end card-container --> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="card-container">
                <div class="card">
                    <div class="front">
                        <div class="cover"></div>
                        <div class="user">
                            <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/dk.png?<?=$elapsed_time?>"/>
                        </div>
                        <div class="content">
                            <div class="main"><h3 class="name">Donkey Kong</h3><p class="profession">Donkey Kong</p></div>
                        </div>
                    </div> <!-- end front panel -->
                    <div class="back">
                        <div class="header"><h5 class="motto">"Banana slamma!!"</h5></div>
                        <div class="content">
                            <div class="user"> <br> <img class="img-responsive" src="<?=$GLOBALS['COD']->dir?>/assets/images/dk_logo.png?<?=$elapsed_time?>"/></div>
                            <div class="main">
                                <div class="stats-container">
                                    <div class="stats"><h4>C</h4><p>Tier</p></div>
                                    <div class="stats"><h4>22</h4><p>Ranking</p></div>
                                    <div class="stats"><h4>20.82</h4><p>Score</p></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end back panel -->
                </div> <!-- end card -->
            </div> <!-- end card-container --> 
        </div>
    </div>
</div>

<?php
	include_once('static/Footer.php');
?>
