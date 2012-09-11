<?php
require_once("./inc/init.php");
if(isset($_POST['create'])){
  $_SESSION['create'] = 1;
  unset($_POST['create']);
}

if(isset($_POST["isLogged"])){
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');
  echo json_encode( array( "success"=> (isset($_SESSION['uid']) && $_SESSION['uid']) > 0 ? "1" : "0") );
  exit(0);
}

$page = "Parkkipaikat";
include($_SERVER['DOCUMENT_ROOT']."/inc/header.php");
?>
<link type="text/css" href="<?php echo $config['paths']['base_url']; ?>/css/map.css" rel="stylesheet" media="all" />

</head>

<body>
<div id="fb-root"></div>
<!--
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fi_FI/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
-->
<div id="wrapper">
<div id="header">
  <div class="col1"><img  class="logo" src="images/parkkilogo.gif" width="189" height="212" alt="Parkkipäivä"></div>
    <div class="col2"><img src="images/katukuva.gif" width="637" height="254"></div>
     <!--end of header --></div>
<div id="content">
      <div class="col1">
        <?php include($_SERVER['DOCUMENT_ROOT']."/inc/navbar.php"); ?>
        <!--
        <ul>
          <li><a href="index.html">Etusivu</a></li>
          <li><a class="active" href="parkkipaikat.php">Parkkipaikat</a></li>
          <li><a href="galleria.php">Galleria</a></li>
        </ul>
        -->
        
        
        <p class="info">Ilmastoinfo ja Siivouspäivän tiimi järjestävät yhdessä Parkkipäivä Kuvittele – ilman autoja -nimisen tapahtuman kansainvälisenä Park(ing) Day päivänä.
Tapahtuma toteutetaan avoimena koko kansan ylläkkänä eli Flash Mobina Helsingin keskustan parkkiruuduissa.</p>
<div class="fb-like-box" data-href="https://www.facebook.com/Parkkipaiva" data-width="190" data-height="300" data-show-faces="true" data-stream="false" data-header="false"></div>
</div>
      <div class="col2">
        <h1>Tässä parkkipäivän kartta. <br>
        Ilmoita  lomakkeella omat ideasi</h1>
        
<!--<p><img src="images/kartta.jpg" width="634" height="392" alt="Kartta"></p>-->
<div id="login_row">
  <div class="span12 inset">
    <!-- Login/out -->
    <p id="login-text"><?php s(); ?>Kirjaudu sisään Facebook-tunnuksillasi lisätäksesi karttaan myyntipaikkasi tai muokataksesi merkintöjäsi.<?php e(); ?></p>
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
          <div class="controls location_select">
            <input type="hidden" value="" name="location" id="location"/>
          </div>
      </div>
      
      <div>
        <button id="validate" type="submit" class="btn-red"><?php s('en_EN'); ?>Save<?php e(); ?></button>
      </div>
        
    </form>
</div>


<table width="634" border="0" cellspacing="0" cellpadding="0">
  <tr class="header">
    <td bgcolor="#e8d53e">14:00 - 15:00</td>
    <td bgcolor="#e8d53e">15:00 - 16:00</td>
    <td bgcolor="#e8d53e">16:00 - 17:00</td>
    <td bgcolor="#e8d53e">17:00 - 18:00</td>
    <td bgcolor="#e8d53e">18:00 - 19:00</td>
  </tr>
  <tr>
    <td><span class="dot">11</span> Tässä ensimmäinen paikka. Pullaa ja kahvia.</td>
    <td><span class="dot">1</span> Tässä ensimmäinen paikka. Pullaa ja kahvia.</td>
    <td><span class="dot">1</span> Tässä ensimmäinen paikka. Pullaa ja kahvia.</td>
    <td><span class="dot">1</span> Tässä ensimmäinen paikka. Pullaa ja kahvia.</td>
    <td><span class="dot">1</span> Tässä ensimmäinen paikka. Pullaa ja kahvia.</td>
  </tr>
  <tr>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
    <td bgcolor="#EFEFEF">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
      </div>  <!--end of content --></div>
      <?php include($_SERVER['DOCUMENT_ROOT']."/inc/footer.php"); ?>
       <!--end of wrapper -->
</div>

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
      
      $.ajax({
        type: 'POST',
        url: baseUrl+'/data.php?query=getAll',
        error: function(jqXHR, textStatus, errorThrown){
            $.log("There was an error.");
            $.log(jqXHR);
            $.log(textStatus);
            return;
        },
        success: function(data, textStatus, jqXHR){
            if(data.error !== undefined){
                alert("Error:"+data.error);
            }else{
                $.log(data);
            }
        },
        dataType: "json"
    });
    </script>
    
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-collapse.js"></script>
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-tooltip.js"></script>
</body>
</html>
