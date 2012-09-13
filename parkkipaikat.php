<?php
require_once("./inc/init.php");
$page = "Parkit";
$title = "Parkkipäivä pe 21.9. 14 – 21. Paikat ja ohjelma";
include($_SERVER['DOCUMENT_ROOT']."/inc/header.php");
?>
<link type="text/css" href="<?php echo $config['paths']['base_url']; ?>/css/map.css" rel="stylesheet" media="all" />

</head>

<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fi_FI/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php include($_SERVER['DOCUMENT_ROOT']."/inc/navbar.php"); ?>
        <h1>Parkkipisteiden sijainnit</h1><br>
        
<?php include($_SERVER['DOCUMENT_ROOT']."/inc/map.php"); ?>

<h2>Tapahtumapäivän ohjelma tunneittain.</h2>
<table id="events-calendar" width="634" border="0" cellspacing="0" cellpadding="0">
  <tr class="header">
    <?php for($i = $config['start_time'];$i < $config['end_time'];$i++){ ?>
    <td bgcolor="#e8d53e"><?php echo $i; ?>:00 - <?php echo $i+1; ?>:00</td>
    <?php } ?>
  </tr>
</table>
<p>&nbsp;</p>
      <?php include($_SERVER['DOCUMENT_ROOT']."/inc/footer.php"); ?>
       <!--end of wrapper -->

<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/standmap.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/events.js"></script>    
    <script type="text/javascript">
      var calendar = new EventCalendar();
      initializeMap();
      
      function locationsLoaded(){
          //init the events timetable
          calendar.setTimesAndView(<?php echo $config['start_time']; ?>, <?php echo $config['end_time']; ?>,  StandsList, $("#events-calendar"));
          calendar.loadEvents();
      }
    </script>
</body>
</html>
