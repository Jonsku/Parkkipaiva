<?php
require_once("./inc/init.php");
$page = "Etusivu";
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
<div id="wrapper">
<div id="header">
  <div class="col1"><img class="logo" src="images/parkkilogo.gif" width="189" height="212" alt="Parkkipäivä"></div>
    <div class="col2"><img src="images/katukuva.gif" width="637" height="254"></div>
     <!--end of header --></div>
<div id="content">
      <div class="col1">
        <?php include($_SERVER['DOCUMENT_ROOT']."/inc/navbar.php"); ?>
        
        <p class="info">Ilmastoinfo ja Siivouspäivän tiimi järjestävät yhdessä Parkkipäivä Kuvittele – ilman autoja -nimisen tapahtuman kansainvälisenä Park(ing) Day päivänä.
Tapahtuma toteutetaan avoimena koko kansan ylläkkänä eli Flash Mobina Helsingin keskustan parkkiruuduissa.</p>
<div class="fb-like-box" data-href="https://www.facebook.com/Parkkipaiva" data-width="190" data-height="300" data-show-faces="true" data-stream="false" data-header="false"></div>
</div>
      <div class="col2">
        <h1>Parkkipäivä ilman autoja Helsingin <br>
          keskustassa pe 21.9. 16–21. <br>
        Kaikki ovat tervetulleita osallistumaan.</h1>
        <p><strong>Kuvittele!</strong> Jos autoja ei olisi, mitä kaduilla tapahtuisi? Jaa visiosi – ja toteuta se! Me autamme. Aloita ideointi täällä ja kuule mitä muilla on mielessään.</p>
        <p>Mukana Siivouspäivän tiimi &amp;Ilmastoinfo.</p>
        <p><strong>INFO</strong>: Kansainvälisenä PARKI(ing) DAY päivänä Helsingin keskusta muuttuu koko kansan flash mobin, ylläkän, tapahtumapaikaksi. Kaupungin autotilan valloitus toteutuu yksinkertaisella menetelmällä: me varaamme teille parisenkymmäntä parkkiruutua, ilmoita nettisivullamme miten ja milloin aiot ruudun vallata ja tila on sinun. Kaikki tapahtuu siis parkkiruuduissa. </p>
        <p><strong>Kuvittele! </strong>Mitä kaikkea parkkiruuduissa voisi tehdä? Laulaa, tanssia? Järjestää illallisen, vaiko pelata pöytäfutista? Pallo on nyt meillä kaikilla.</p>
        <p>Tapahtuma toteutetaan ulkoilmatapahtumana, jolle haetaan rakennusvirastolta lupa. Vallattavia parkkiruutuja tulee olemaan noin 20. Suurimman osan ohjelmasta toteuttavat kansalaiset itse, mutta myös tapahtumatiimi organisoi parkkiruutuihin toimintaa. Suunnitteilla on esimerkiksi kylpypaljukylpylä ja kauniisti katettu illallispöytä ruokineen ja vieraineen, laulua, soittoa, tanssia, leffaparkki, kirjanvaihtoparkki sekä virkamiesparkki, johon on </p>
        <p>Toivoisimme kovasti saavamme myös esiintyjiä mukaan – performanssi voi kestää mitä tahansa viiden minuutin ja viiden tunnin väliltä. Me Siivouspäivän ja Ilmastoinfon tiimi olemme käytettävissä avustajinanne, ja voimme järjestää tarvittavia fasiliteetteja esitystänne varten. </p>
        <p>Toivottavasti lähdette mukaan, ja yhdessä teemme kauniin ja hauskan yhteisöllisen tapahtuman! Pohjimmainen tarkoitus on saattaa kaupunkilaiset hetkeksi yhteen, viettämään aikaa – ja samalla antautumaan kuvitelmille kaupungista, jossa autoja olisi vähemmän. </p>
      </div>  <!--end of content --></div>
      <?php include($_SERVER['DOCUMENT_ROOT']."/inc/footer.php"); ?>
  <!--end of wrapper --></div>
</body>
</html>
