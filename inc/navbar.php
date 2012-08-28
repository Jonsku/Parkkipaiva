    <!-- header starts -->
    <div id="header">
      <div class="container logo">
          <div class="row">
              <div class="span12 center">
                  <h1>Parking päivä</h1>
              </div>
          </div>
      </div>
    
    
      <!-- navbar starts -->
      <div class="container">
        <div class="navbar row">
          <div class="span12">
            <div class="custom-navbar-inner">
              <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </a>
              <div class="nav-collapse">
                <!-- Language selector -->

                <ul class="nav pull-right" data-no-collapse="true">
                  <li class="dropdown">
                    <a href="<?php echo $config['paths']['base_url']."/".($_SESSION['locale'] == "fi_FI" ? "en_EN" : "fi_FI")."/".$navigation[$page]; ?>"><?php echo $_SESSION['locale'] == "fi_FI" ? $localeToLanguage['en_EN'] : $localeToLanguage['fi_FI']; ?></a>
                  </li>
                </ul>
                <!--
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $localeToLanguage[$_SESSION['locale']]; ?><b class="caret"></b></a>
                   
                    <ul class="dropdown-menu">
                    <?php
                      foreach($localeToLanguage as $code=>$langName){
                    ?>
                      <li<?php if($_SESSION['locale'] == $code) echo ' class="active"'; ?>><a href="<?php echo $config['paths']['base_url']."/".$code."/".$navigation[$page]; ?>"><?php echo $langName; ?></a></li>
                    <?php
                      }
                    ?>
                    </ul>
                  -->
              </div><!--/.nav-collapse -->
            </div>
          </div>
        </div>
      </div>
      <!-- navbar ends -->
    </div>
    <!-- header ends -->
