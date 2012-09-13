<?php
require_once("./inc/init.php");
$page = "Liity  mukaan";
$title = "Parkkipäivä pe 21.9. 14 – 21  Info";
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
        <h1>Info</h1>
        <p>
          <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">Parkkipäivä. Kuvittele – ilman autoja on Ilmastoinfon ja Siivouspäivän tiimin kansainvälisenä parkkipaikkojen valtauspäivänä, Park(ing) Day, järjestämä yhteisöllinen tapahtuma Helsingin keskustassa. </p>
        <p LANG="" ALIGN="LEFT"> Se saattaa kaupunkilaiset hetkeksi yhteen, viettämään aikaa ja antautumaan kuvitelmille kaupungista, jossa autoja olisi vähemmän ja tilaa ihmisille enemmän.</p>
        <p LANG="" ALIGN="LEFT"> Kaupungin autotilan valloitus toteutuu yksinkertaisella menetelmällä: me varaamme ruudut, sinä ilmiannat itsesi ja ideasi Facebook sivullamme. Kerro miten ja milloin aiot ruudun vallata, varaa paikkasi tämän sivun kartalta, ja tila on sinun. Kaikki tapahtuu siis parkkiruuduissa.</p>
        <p LANG="" ALIGN="LEFT"> Parkkipäivä toteutetaan ulkoilmatapahtumana. Tapahtuma-aika on 21.09 klo 15-21. Voit varata ruudun käyttöösi tunniksi, kahdeksi, tai koko ajaksi. </p>
        <p LANG="" ALIGN="LEFT"> Me, <a href="http://ilmastoinfo.fi/">Ilmastoinfon</a> ja <a href="http://siivouspaiva.com/">Siivouspäivän</a> tiimi, olemme käytettävissänne ja autamme järjestämään ruutuparkkeerauksenne.</p>
        <p LANG="" ALIGN="LEFT"><SPAN LANG="">Tervetuloa muk</SPAN><SPAN LANG="">aan illanviettoon vaikka et itse olisikaan ruudunvaltaaja. Ohjelmaa ja hauskoja ajanviettopaikkoja on tarjolla kaikille.</SPAN><br>
        </p>
<?php include($_SERVER['DOCUMENT_ROOT']."/inc/footer.php"); ?>
</body>
</html>
