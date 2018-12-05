var swiper = new Swiper('.swiper-container', {
        autoplay: {
            delay: 2500,
        },
        loop: true,
      pagination: {
        el: '.swiper-pagination',
        dynamicBullets: true,
      },
      navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
      }
    });

    $(document).keydown(function(e) {
    if (e.keyCode === 37) {
       // Previous
       $(".swiper-button-prev",).click();
       return false;
    }
    if (e.keyCode === 39) {
       // Next
       $(".swiper-button-next").click();
       return false;
    }
});