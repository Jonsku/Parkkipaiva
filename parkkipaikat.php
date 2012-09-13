<?php
if(isset($_POST["isLogged"])){
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');
  require_once("./inc/init.php");
  echo json_encode( array( "success"=> (isset($_SESSION['uid']) && $_SESSION['uid']) > 0 ? "1" : "0") );
  exit(0);
}

require_once("./inc/init.php");
if(isset($_POST['create'])){
  $_SESSION['create'] = 1;
  unset($_POST['create']);
}

$page = "Parkit";
$title = "Parkkipäivä pe 21.9. 14 – 21. Paikat ja ohjelma";
include($_SERVER['DOCUMENT_ROOT']."/inc/header.php");
?>
<link type="text/css" href="<?php echo $config['paths']['base_url']; ?>/css/map.css" rel="stylesheet" media="all" />

</head>

<body>
<div id="fb-root"></div>
<?php include($_SERVER['DOCUMENT_ROOT']."/inc/navbar.php"); ?>
        <h1>Parkkipisteiden sijainnit</h1><br>
<div id="login_row">
  <div class="span12 inset">
    <!-- Login/out -->
    <!-- <p id="login-text"><?php s(); ?>Kirjaudu sisään Facebook-tunnuksillasi lisätäksesi karttaan myyntipaikkasi tai muokataksesi merkintöjäsi.<?php e(); ?></p> -->
    <br/><br/>
    <a name="login"><button id="logout-btn" class="btn-red"><?php s('en_EN'); ?>Log out<?php e(); ?></button><button id="email-login-select" class="btn-red login-btn"><?php s('en_EN'); ?>Login with an email address<?php e(); ?></button><button id="fb-login" class="btn-red login-btn"><?php s('en_EN'); ?>Login with a facebook account<?php e(); ?></button></a><span id="contact-fb-msg"><?php s('en_EN'); ?>Contacting facebook...<?php e(); ?></span><span id="check-fb-msg"><?php s('en_EN'); ?>Checking if you're logged in...<?php e(); ?></span><span id="no-fb-msg"><?php s('en_EN'); ?>Facebook is not responding...<?php e(); ?></span>
    <?php include($_SERVER['DOCUMENT_ROOT']."/inc/login.php"); ?>
  </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT']."/inc/map.php"); ?>

<div id="event-editing">
    <form id="event-form" class="form-vertical" method="post" action="#">
      <div class="control-group">
        <label class="control-label" for="desc"><?php s('fi_FI'); ?>Lyhyt kuvaus siitä, mitä myyt (enintään 200 merkkiä)<?php e(); ?> *</label>
        <div class="controls">
          <textarea name="desc" id="desc"></textarea><br/>
          <span id="char-count">0 / 200 <?php s('fi_FI'); ?>merkkiä<?php e(); ?></span>
        </div>
      </div>
      
      <div class="control-group">
          <label class="control-label"><?php s('fi_FI'); ?>Aukioloaika<?php e(); ?> *</label>
          <div class="controls">
            Klo. <select name="sh" id="sh" class="hour" data-default="14"></select> - <select name="eh" id="eh" class="hour" data-default="19"></select>
          </div>
      </div>
      
      <div class="control-group">
          <label class="control-label"><?php s('en_EN'); ?>Pick a location for the event<?php e(); ?> *</label>
          <div class="controls location_select" id="location_select_wrap">
            <input type="hidden" value="" name="location" id="location"/>
          </div>
      </div>
      
      <div>
        <button id="validate" type="submit" class="btn-red"><?php s('en_EN'); ?>Save<?php e(); ?></button>
        <button id="remove" class="btn-red"><?php s('en_EN'); ?>Remove<?php e(); ?></button>
      </div>
        
    </form>
</div>
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
    <script type="text/javascript">
/* Localized Strings */
stringsL10N = new Array();
<?php include("l10n_map.php"); ?>
    </script>
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/form.util.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/login.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/standmap.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/events.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/fb.php"></script>    
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/myyntipaikat.js"></script>
    <script type="text/javascript">
      $.ajax({
        type: 'POST',
        dataType: "json",
        url: '',
        data :{isLogged : 1},
        error: function(jqXHR, textStatus, errorThrown){
          $.log("There was an error.");
          $.log(jqXHR);
          $.log(textStatus);
          return;
        },
        success: function(data, textStatus, jqXHR){
          if(data.success == "1"){
            loggedIn();
          }
        }
      });
    </script>
    
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-collapse.js"></script>
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-tooltip.js"></script>
</body>
</html>
