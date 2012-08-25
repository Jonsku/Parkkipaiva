<!-- footer starts -->

<div class="container footer">
  <div class="row">
    <div class="span12"> <img class="line" src="<?php echo $config['paths']['base_url']; ?>/img/long_line.png" alt="footer starts"> </div>
  </div>
  <div class="row">
    <div class="span12">
      <div class="row">
        <div class="span4">
          <div class="row">
            <ul class="span4 footer-nav">
              <?php
                                foreach($navigation as $label=>$fileName){
                                ?>
              <li<?php if($page == $label) echo ' class="active"'; ?>><a href="<?php echo $config['paths']['base_url']."/".$_SESSION['locale']."/".$fileName; ?>">
                <?php s('fi_FI'); echo $label; e(); ?>
                </a></li>
              <?php
                                }
                                ?>
              <li><a href="http://www.facebook.com/siivouspaiva">Facebook</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- footer ends --> 
<script type="text/javascript">
         $('h3').click(function(){
            var showId = $(this).attr('name');
            if( $(this).find('i.minus-icon').length > 0 ){
                 $(this).find('i').removeClass("minus-icon").addClass("plus-icon");
            }else{
                 $(this).find('i').removeClass("plus-icon").addClass("minus-icon");
            }
            $('#'+showId).toggle();
        });
        </script>