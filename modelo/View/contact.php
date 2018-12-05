<?php
// --------------------------------------------------------------------
//  You can set the page title of the header
// --------------------------------------------------------------------	
	$page_title = "contact";
	
// --------------------------------------------------------------------
//  Include header
// --------------------------------------------------------------------	
	include_once('static/Header.php');
?>
<div class="container contact-section">
    <hr>
    <h1 class="fondoletras text-center">Smash it up</h1>
    <hr>
  <p style="color:#d83232" class="text-center">Here you can sign up for future tournaments. Come and send us a message.</p>
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <form class="form-horizontal" method="POST" action="https://formspree.io/fjaviercarbajal08@gmail.com">
        <div class="form-group">
          <label  name="name" class="blue-text">What's your name, fighter?</label>   
          <input type="text" name="Name" class="form-control" placeholder="Fox McCloud">
        </div>
        <div class="form-group">
          <label name="email" class="blue-text">Let us know your email.</label>
          <input type="email" name="Email" class="form-control" placeholder="mario@smash.com">
        </div>
        <div class="form-group ">
          <label name="message" class="blue-text">Here you can write comments about future torunaments.</label>
          <textarea class="form-control" name="Message" placeholder="Final destination, no itmes, Fox only"></textarea> 
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-flat">Smash it down!</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MAPS -->
<div class="container contact-section">
    <hr>
    <h1 class="fondoletras text-center">Next Tournament Location</h1>
    <hr>
    <h2 class="fondoletras text-center">Visit Us</h2>
    <p style="color:#d83232" class="text-center">December 07, 2018 4:00 P.M.</p>
    <div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="embed-responsive embed-responsive-16by9">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3393.0854578956214!2d-106.45413388539826!3d31.740868281297274!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86e7595ad4dee483%3A0x205ab965506d453!2sPlaza+de+las+Am%C3%A9ricas!5e0!3m2!1ses!2smx!4v1544046656784" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
    </div>
    </div>
</div>

<?php
	include_once('static/Footer.php');
?>
