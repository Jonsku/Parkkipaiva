  <!-- navbar starts -->
  <ul>
 <?php
  foreach($navigation as $label=>$fileName){
  ?>
    <li<?php if($page == $label) echo ' class="active"'; ?>><a href="<?php echo $config['paths']['base_url']."/".$_SESSION['locale']."/".$fileName; ?>"><?php s('fi_FI'); echo $label; e(); ?></a></li>
  <?php
  }
  ?>
  </ul>
  <!-- navbar ends -->
