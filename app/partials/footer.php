    <!-- EXTRA SPACE -->
    <div class="container">
      <div class="row my-5"></div>
    </div>
      
  
    <!-- FOOTER -->
    <footer class="py-5 white-bg" id='footer-main' style='font-size:12px;'>
      <div class="container">
        <div class="row">
          <div class="col-lg-2 col-md-2 col-sm-4">
            <div class="d-flex flex-column">
             <h6 class='text-light pb-3'>ABOUT</h6>
              <a href="#" class='text-light footer-link'>About Us</a>
              <a href="#" class='text-light footer-link font-weight-light'>Blog</a>
              <a href="#" class='text-light footer-link'>Press</a>
              <a href="#" class='text-light footer-link'>Careers</a>
              <a href="#" class='text-light footer-link'>Contact Us</a>
              <br class='vanish-lg vanish-md'>
            </div>
          </div>

    

          <div class="col-lg-2 col-md-2 col-sm-4">
            <div class="d-flex flex-column">
              <h6 class='text-light pb-3'>HELP</h6>
              <a href="#" class='text-light footer-link'>FAQs</a>
              <a href="#" class='text-light footer-link'>Payment</a>
              <a href="#" class='text-light footer-link'>Shipping</a>
              <a href="#" class='text-light footer-link'>Return & Refund</a>
              <a href="#" class='text-light footer-link'>Report Infringement</a>
              <br class='vanish-lg vanish-md'>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-4">
            <div class="d-flex flex-column">
              <h6 class='text-light pb-3'>POLICY</h6>
              <a href="#" class='text-light footer-link'>Security</a>
              <a href="#" class='text-light footer-link'>Privacy</a>
              <a href="#" class='text-light footer-link'>Return Policy</a>
              <a href="#" class='text-light footer-link'>Shoperoo Guarantee</a>
              <a href="#" class='text-light footer-link'>Terms & Conditions</a>
              <br class='vanish-lg vanish-md'>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-5">
            <br class='vanish-lg vanish-md'>
            <h6 class='text-light pb-lg-3 pb-md-3 vanish-lg vanish-md'>FOLLOW US</h6>
            <div class="d-flex flex-lg-column flex-md-column flex-sm-row text-center">
              <h6 class='text-light pb-lg-3 pb-md-3 vanish-sm'>FOLLOW US</h6>
              
              
              <a href="#" class='text-light footer-link pt-1' target="_blank">
                <span class='vanish-lg vanish-md pt-2'></span>
                <span class="fa-stack">
                  <i class="fa fa-circle fa-stack-2x icon-background"></i>
                  <i class="fab fa-facebook-f fa-stack-1x text-primary"></i>
                </span>
                <span class="vanish-md vanish-sm">Facebook</span>
              </a>

              <a href="#" class='text-light footer-link pt-1' target="_blank">
                <span class="fa-stack">
                  <i class="fa fa-circle fa-stack-2x icon-background"></i>
                  <i class="fab fa-instagram fa-stack-1x text-purple"></i>
                </span>
                <span class="vanish-md vanish-sm">Instagram</span>
              </a>

              <a href="#" class='text-light footer-link pt-lg-2 pt-1' target="_blank">
                <span class="fa-stack">
                  <i class="fa fa-circle fa-stack-2x icon-background"></i>
                  <i class="fab fa-pinterest-p fa-stack-1x text-danger"></i>
                </span>
                <span class="vanish-md vanish-sm">Pinterest</span>
              </a>

              <a href="#" class='text-light footer-link pt-lg-2 pt-1' target="_blank">
                <span class="fa-stack">
                  <i class="fa fa-circle fa-stack-2x icon-background"></i>
                  <i class="fab fa-twitter fa-stack-1x text-primary"></i>
                </span>
                <span class="vanish-md vanish-sm">Twitter</span>
              </a>
              
            </div>
          </div>  

          <div class="col-lg-4 col-md-4 col-sm-7">
            <br class='vanish-lg vanish-md'>
            <div class="d-flex flex-column px-lg-3 pl-md-3">
              <div class="flex-fill px-lg-5">
                <h6 class='text-light'>EXCLUSIVE <span class='vanish-sm'>DEALS &</span> OFFERS</h6>
              </div>

              <div class="flex-fill mt-3 px-lg-5">
                <div class='text-light'>
                  &#10003;
                  &nbsp;
                  <small>Get discounted deals <span class='vanish-sm'>from Mamaroo</span></small>
                </div>
              </div>

              <div class="flex-fill px-lg-5">
                <div class='text-light'>
                  &#10003;
                  &nbsp;
                  <small>Join our promos & contests</small>
                </div>
              </div>

              <div class="flex-fill mb-4 px-lg-5">
                <div class='text-light'>
                  &#10003;
                  &nbsp;
                  <small>Open your online shop for free</small>
                </div>
              </div>

              
              <div class="flex-fill">
                
                <a role='button' class='text-light modal-link btn btn-lg btn-border btn-block text-center' data-url='../partials/templates/registration_modal.php'>
                  Register Now
                </a>

              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <div class="container py-2">
      <div class="row">
        <div class="col-12 text-center">
          <a href="https://perezjoyce.github.io/portfolio/" target="_blank" class='purple-link'>Joyce Perez</a>  &copy; <a href="#" class='modal-link purple-link' data-url='../partials/templates/disclaimer_modal.php'>Disclaimer</a>
        </div>
      </div>
    </div>
    
    
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>

    <!-- TO ADDRESS: Added non-passive event listener to a scroll-blocking 'touchmove' event -->
    <script>
      addEventListener('touchstart', this.callPassedFuntion, { passive: false })
      
      jQuery.event.special.touchstart = {
        setup: function( _, ns, handle ){
          if ( ns.includes("noPreventDefault") ) {
            this.addEventListener("touchstart", handle, { passive: false });
          } else {
            this.addEventListener("touchstart", handle, { passive: true });
          }
        }
      };
    </script>

    <!-- SLICK -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <!-- POPPER JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    
    <!-- BOOTSTRAP JS -->
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="<?= BASE_URL ?>/node_modules/moment/moment.js"></script>

    <script src="<?= BASE_URL ?>/node_modules/timeago.js/dist/timeago.min.js"></script>

    <!-- EXTERNAL JS -->
    <script src="../assets/js/script.js"></script>

    
  
  </body>
</html>

    
