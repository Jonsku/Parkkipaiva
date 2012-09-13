<div id="wrapper">
  <div id="header">
    <div class="col1"><img class="logo" src="<?php echo $config['paths']['base_url'] ?>/images/parkkilogo.gif" width="192" height="211" alt="Parkkipäivä"></div>
      <div class="col2"><img src="<?php echo $config['paths']['base_url'] ?>/images/katukuva.gif" width="637" height="254"></div>
       <!--end of header -->
  </div>
  <div id="content">
    <div class="col1">
    <!-- navbar starts -->
      <ul>
     <?php
      foreach($navigation as $label=>$fileName){
      ?>
        <li><a<?php if($page == $label) echo ' class="active"'; ?> href="<?php echo $config['paths']['base_url']."/".$_SESSION['locale']."/".$fileName; ?>"><?php s('fi_FI'); echo $label; e(); ?></a></li>
      <?php
      }
      ?>
      </ul>
      <!-- navbar ends -->
            
      <p class="info">Parkkipäivä. Kuvittele – ilman autoja on Ilmastoinfon ja Siivouspäivän tiimin järjestämä yhteisöllinen tapahtuma Helsingin keskustassa. </p>
      <p class="info">Kaupunkilaiset kokoontuvat hetkeksi yhteen, viettämään aikaa ja antautumaan kuvitelmille kaupungista, jossa autoja olisi vähemmän ja tilaa ihmisille enemmän. </p>
      <p class="info">Parkkipäivää vietetään 21. syyskuuta, joka on kansainvälinen parkkipaikkojenvaltauspäivä, Park(ing) Day.</p>
      <div class="fb-like-box" data-href="https://www.facebook.com/Parkkipaiva" data-width="190" data-height="300" data-show-faces="true" data-stream="false" data-header="false"></div>
    </div>
    <div class="col2">
    <!-- Section content starts -->
