<?php
require_once("./inc/init.php");
$page = "Etusivu";
$title = "Parkkipäivä. Kuvittele - ilman autoja Helsingin keskustassa pe 21.9. 14 – 21.";
include($_SERVER['DOCUMENT_ROOT']."/inc/header.php");
?>
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
        <h1>Kuvittele - ilman autoja <br>
        Helsingin keskustassa pe 21.9. 14 – 21. </h1>
        <p>
          <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">Kuvittele! Jos autoja ei olisi, mitä kaikkea parkkiruuduissa voisi tehdä? Laulaa, tanssia? Järjestää illallisen tai pelata pöytäfutista? Pallo on nyt meillä kaikilla. </p>
        <p LANG="" ALIGN="LEFT"> Jaa visiosi ja toteuta se! Me autamme. Aloita ideointi Facebook-sivullamme ja ilmoittaudu mukaan.<br>
          <br>
        Mukana <a href="http://ilmastoinfo.fi/">Ilmastoinfo</a> &amp; <a href="http://siivouspaiva.com/">Siivouspäivän</a> tiimi. </p>
<h2>Tapahtumakartta</h2>
        <p>Tarkemmat tiedot parkkipäivän <a href="parkkipaikat.php">ohjelmasta</a> ja <a href="info.php">ilmoittautumiset</a>.</p>
        <h2><a href="parkkipaikat.php"><img src="images/kartta.gif" alt="Parkkipaikat kartalla" width="645" height="371" border="0"></a></h2><br>
      <?php include($_SERVER['DOCUMENT_ROOT']."/inc/footer.php"); ?>
</body>
</html>
