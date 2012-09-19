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

$page = "Liity  mukaan";
$title = "Parkkipäivä pe 21.9. 15 – 21  Info";
$noLikebox = true;
include($_SERVER['DOCUMENT_ROOT']."/inc/header.php");
?>
<link type="text/css" href="<?php echo $config['paths']['base_url']; ?>/css/map.css" rel="stylesheet" media="all" />
</head>

<body>
<div id="fb-root"></div>
<?php include($_SERVER['DOCUMENT_ROOT']."/inc/navbar.php"); ?>
        <h1>Info</h1>
        <p>
          <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><b>Parkkipäivä. Kuvittele – ilman autoja</b> on Ilmastoinfon ja Siivouspäivän tiimin kansainvälisenä parkkipaikkojen valtauspäivänä järjestämä yhteisöllinen tapahtuma Helsingin keskustassa. </p>
        <p LANG="" ALIGN="LEFT"> Parkkipäivä saattaa kaupunkilaiset hetkeksi yhteen viettämään aikaa ja antautumaan kuvitelmille kaupungista, jossa autoja olisi vähemmän ja tilaa ihmisille enemmän.</p>
        <p LANG="" ALIGN="LEFT"> Kaupungin autotilan valloitus toteutuu yksinkertaisella menetelmällä: me varaamme ruudut, sinä ilmiannat itsesi ja ideasi Facebook- tai nettisivullamme. Kerro miten ja milloin aiot ruudun vallata, varaa paikkasi tämän sivun kartalta, ja tila on sinun. Kaikki tapahtuu siis parkkiruuduissa.</p>
        <p LANG="" ALIGN="LEFT"> Parkkipäivä toteutetaan ulkoilmatapahtumana, johon on saatu luvat viranomaisilta. Tapahtuma-aika on 21.09 klo 15-21. Voit varata ruudun käyttöösi tunniksi, kahdeksi tai koko ajaksi. </p>
        <p LANG="" ALIGN="LEFT"> Me, <a href="http://ilmastoinfo.fi/">Ilmastoinfon</a> ja <a href="http://siivouspaiva.com/">Siivouspäivän</a> tiimi, olemme käytettävissänne ja autamme järjestämään ruutuparkkeerauksenne.</p>
        <p LANG="" ALIGN="LEFT"><SPAN LANG="">Tervetuloa muk</SPAN><SPAN LANG="">aan illanviettoon vaikka et itse olisikaan ruudunvaltaaja. Ohjelmaa ja hauskoja ajanviettopaikkoja on tarjolla kaikille.</SPAN></p>
        <p class="small"LANG="" ALIGN="LEFT">*HUOM! Parkin voi varata myös lyhyeksi ajaksi, vaikka viiden minuutin esitykseen. Ota silloin yhteyttä pauliina@siivouspaiva.com, tanja@siivouspaiva.com tai jaakko@siivouspaiva.com<br>
        </p>
<h2>Ilmoittautuminen</h2>
  <div id="login_row">
  <div class="span12 inset">
    <!-- Login/out -->
    <!-- <p id="login-text"><?php s(); ?>Kirjaudu sisään Facebook-tunnuksillasi lisätäksesi karttaan myyntipaikkasi tai muokataksesi merkintöjäsi.<?php e(); ?></p> -->
    <a name="login"><button id="logout-btn" class="btn-red"><?php s('en_EN'); ?>Log out<?php e(); ?></button><button id="email-login-select" class="btn-red login-btn"><?php s('en_EN'); ?>Login with an email address<?php e(); ?></button><button id="fb-login" class="btn-red login-btn"><?php s('en_EN'); ?>Login with a facebook account<?php e(); ?></button></a><span id="contact-fb-msg"><?php s('en_EN'); ?>Contacting facebook...<?php e(); ?></span><span id="check-fb-msg"><?php s('en_EN'); ?>Checking if you're logged in...<?php e(); ?></span><span id="no-fb-msg"><?php s('en_EN'); ?>Facebook is not responding...<?php e(); ?></span>
    <?php include($_SERVER['DOCUMENT_ROOT']."/inc/login.php"); ?>
  </div>
</div>

<img src="images/kartta.gif" width="645" height="371">

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
  <tbody>
    <tr class="header">
      <td bgcolor="#e8d53e">15:00 - 16:00</td>
      <td bgcolor="#e8d53e">16:00 - 17:00</td>
      <td bgcolor="#e8d53e">17:00 - 18:00</td>
      <td bgcolor="#e8d53e">18:00 - 19:00</td>
      <td bgcolor="#e8d53e">19:00 - 20:00</td>
      <td bgcolor="#e8d53e">20:00 - 21:00</td>
    </tr>
    <tr>
      <td class=""><span class="dot">1</span><span class="event-description">PUUPARKKI Tule ihmettelemään kauniita hedelmäpuita ja merkkaamaan kartalle puuttuva puu. Kartta lahjoitetaan rakennusvirastolle.</span></td>
      <td class=""><span class="dot">1</span><span class="event-description">PUUPARKKI Tule ihmettelemään kauniita hedelmäpuita ja merkkaamaan kartalle puuttuva puu. Kartta lahjoitetaan rakennusvirastolle.</span></td>
      <td class=""><span class="dot">1</span><span class="event-description">PUUPARKKI Tule ihmettelemään kauniita hedelmäpuita ja merkkaamaan kartalle puuttuva puu. Kartta lahjoitetaan rakennusvirastolle.</span></td>
      <td class=""><span class="dot">1</span><span class="event-description">PUUPARKKI Tule ihmettelemään kauniita hedelmäpuita ja merkkaamaan kartalle puuttuva puu. Kartta lahjoitetaan rakennusvirastolle.</span></td>
      <td class=""><span class="dot">1</span><span class="event-description">PUUPARKKI Tule ihmettelemään kauniita hedelmäpuita ja merkkaamaan kartalle puuttuva puu. Kartta lahjoitetaan rakennusvirastolle.</span></td>
      <td class=""><span class="dot">1</span><span class="event-description">PUUPARKKI Tule ihmettelemään kauniita hedelmäpuita ja merkkaamaan kartalle puuttuva puu. Kartta lahjoitetaan rakennusvirastolle.</span></td>
    </tr>
    <tr>
      <td class=""><span class="dot">2</span><span class="event-description">NEULONTAA ja VAATETVAIHTOA Neulomis- ja virkkausopetusta sekä vaatekierrätystä. Kaiva vanhat vaatteet esiin ja vaihda paikalla uusiin.</span></td>
      <td class=""><span class="dot">2</span><span class="event-description">NEULONTAA ja VAATETVAIHTOA Neulomis- ja virkkausopetusta sekä vaatekierrätystä. Kaiva vanhat vaatteet esiin ja vaihda paikalla uusiin.</span></td>
      <td class=""><span class="dot">2</span><span class="event-description">NEULONTAA ja VAATETVAIHTOA Neulomis- ja virkkausopetusta sekä vaatekierrätystä. Kaiva vanhat vaatteet esiin ja vaihda paikalla uusiin.</span></td>
      <td class=""><span class="dot">2</span><span class="event-description">NEULONTAA ja VAATETVAIHTOA Neulomis- ja virkkausopetusta sekä vaatekierrätystä. Kaiva vanhat vaatteet esiin ja vaihda paikalla uusiin.</span></td>
      <td class=""><span class="dot">2</span><span class="event-description">NEULONTAA ja VAATETVAIHTOA Neulomis- ja virkkausopetusta sekä vaatekierrätystä. Kaiva vanhat vaatteet esiin ja vaihda paikalla uusiin.</span></td>
      <td class="empty"><span class="dot">2</span><span class="event-description">Vapaa</span></td>
    </tr>
    <tr>
      <td><span class="dot">3</span><span class="event-description">KUKKAKAARA Maailman kaunein auto, jota saa testata"</span></td>
      <td><span class="dot">3</span><span class="event-description">KUKKAKAARA Maailman kaunein auto, jota saa testata"</span></td>
      <td><span class="dot">3</span><span class="event-description">KUKKAKAARA Maailman kaunein auto, jota saa testata"</span></td>
      <td><span class="dot">3</span><span class="event-description">KUKKAKAARA Maailman kaunein auto, jota saa testata"</span></td>
      <td><span class="dot">3</span><span class="event-description">KUKKAKAARA Maailman kaunein auto, jota saa testata"</span></td>
      <td><span class="dot">3</span><span class="event-description">KUKKAKAARA Maailman kaunein auto, jota saa testata"</span></td>
    </tr>
    <tr>
      <td class=""><span class="dot">4</span><span class="event-description">VIRKAPARKKI Pappi ja Kasvatustieteen tohtori, Agricolan kirkon johtaja TEEMU LAAJASALO</span></td>
      <td class=""><span class="dot">4</span><span class="event-description">VIRKAPARKKI Liikennesuunnitteluviraston pyöräliikenneasiantuntija MAREK SALERMO</span></td>
      <td class=""><span class="dot">4</span><span class="event-description">VIRKAPARKKI Vastavalittu nuorisotoimenjohtaja TOMMI LAITIO</span></td>
      <td class=""><span class="dot">4</span><span class="event-description">VIRKAPARKKI Ehdota FB-sivullamme virkamiestä, jonka haluaisit tavata</span></td>
      <td class=""><span class="dot">4</span><span class="event-description">VIRKAPARKKI Ehdota FB-sivullamme virkamiestä, jonka haluaisit tavata</span></td>
      <td class=""><span class="dot">4</span><span class="event-description">VIRKAPARKKI Ehdota FB-sivullamme virkamiestä, jonka haluaisit tavata</span></td>
    </tr>
    <tr>
      <td class="empty"><span class="dot">5</span><span class="spot-label">MUSARUUTU</span><br>
        <span class="event-description">Soita, huuda tai laula. ARE:n nosturilava on sinua varten. Buukkaa oma tuntisi.</span></td>
      <td class="empty"><span class="dot">5</span><span class="spot-label">MUSARUUTU</span><br>
        <span class="event-description">Soita, huuda tai laula. ARE:n nosturilava on sinua varten. Buukkaa oma tuntisi.</span></td>
      <td class="empty"><span class="dot">5</span><span class="spot-label">MUSARUUTU</span><br>
        <span class="event-description">Soita, huuda tai laula. ARE:n nosturilava on sinua varten. Buukkaa oma tuntisi.</span></td>
      <td class="empty"><span class="dot">5</span><span class="spot-label">MUSARUUTU</span><br>
        <span class="event-description">Soita, huuda tai laula. ARE:n nosturilava on sinua varten. Buukkaa oma tuntisi.</span></td>
      <td class="empty"><span class="dot">5</span><span class="spot-label">MUSARUUTU</span><br>
        <span class="event-description">Soita, huuda tai laula. ARE:n nosturilava on sinua varten. Buukkaa oma tuntisi.</span></td>
      <td class="empty"><span class="dot">5</span><span class="spot-label">MUSARUUTU</span><br>
        <span class="event-description">Soita, huuda tai laula. ARE:n nosturilava on sinua varten. Buukkaa oma tuntisi.</span></td>
    </tr>
    <tr>
      <td class="empty"><span class="dot">6</span><span class="spot-label">UNIPARKKI</span><br>
        <span class="event-description">Lue yksin tai ääneen, nuku, tee mitä huvittaa, kaivele nenää, tai haaveile vaan. Riippumatto on sinun. Jos haluat tehdä jotain muuta, niin varaa oma aikasi.</span></td>
      <td class="empty"><span class="dot">6</span><span class="spot-label">UNIPARKKI</span><br>
        <span class="event-description">Lue yksin tai ääneen, nuku, tee mitä huvittaa, kaivele nenää, tai haaveile vaan. Riippumatto on sinun. Jos haluat tehdä jotain muuta, niin varaa oma aikasi.</span></td>
      <td class="empty"><span class="dot">6</span><span class="spot-label">UNIPARKKI</span><br>
        <span class="event-description">Lue yksin tai ääneen, nuku, tee mitä huvittaa, kaivele nenää, tai haaveile vaan. Riippumatto on sinun. Jos haluat tehdä jotain muuta, niin varaa oma aikasi.</span></td>
      <td class="empty"><span class="dot">6</span><span class="spot-label">UNIPARKKI</span><br>
        <span class="event-description">Lue yksin tai ääneen, nuku, tee mitä huvittaa, kaivele nenää, tai haaveile vaan. Riippumatto on sinun. Jos haluat tehdä jotain muuta, niin varaa oma aikasi.</span></td>
      <td class="empty"><span class="dot">6</span><span class="spot-label">UNIPARKKI</span><br>
        <span class="event-description">Lue yksin tai ääneen, nuku, tee mitä huvittaa, kaivele nenää, tai haaveile vaan. Riippumatto on sinun. Jos haluat tehdä jotain muuta, niin varaa oma aikasi.</span></td>
      <td class="empty"><span class="dot">6</span><span class="spot-label">UNIPARKKI</span><br>
        <span class="event-description">Lue yksin tai ääneen, nuku, tee mitä huvittaa, kaivele nenää, tai haaveile vaan. Riippumatto on sinun. Jos haluat tehdä jotain muuta, niin varaa oma aikasi.</span></td>
    </tr>
    <tr>
      <td class=""><span class="dot">7</span><span class="event-description">ILTASATUPARKKI
        Iltasatuja aikuisille ja ehdottomasti myös lapsille, koirille, ohipyrähtäville puluille sekä kaikille iltasatuja kaipaaville.</span></td>
      <td class=""><span class="dot">7</span><span class="event-description">ILTASATUPARKKI
        Iltasatuja aikuisille ja ehdottomasti myös lapsille, koirille, ohipyrähtäville puluille sekä kaikille iltasatuja kaipaaville.</span></td>
      <td class=""><span class="dot">7</span><span class="event-description">ILTASATUPARKKI
        Iltasatuja aikuisille ja ehdottomasti myös lapsille, koirille, ohipyrähtäville puluille sekä kaikille iltasatuja kaipaaville.</span></td>
      <td class=""><span class="dot">7</span><span class="event-description">ILTASATUPARKKI
        Iltasatuja aikuisille ja ehdottomasti myös lapsille, koirille, ohipyrähtäville puluille sekä kaikille iltasatuja kaipaaville.</span></td>
      <td class=""><span class="dot">7</span><span class="event-description">ILTASATUPARKKI
        Iltasatuja aikuisille ja ehdottomasti myös lapsille, koirille, ohipyrähtäville puluille sekä kaikille iltasatuja kaipaaville.</span></td>
      <td class=""><span class="dot">7</span><span class="event-description">ILTASATUPARKKI
        Iltasatuja aikuisille ja ehdottomasti myös lapsille, koirille, ohipyrähtäville puluille sekä kaikille iltasatuja kaipaaville.</span></td>
    </tr>
    <tr>
      <td class=""><span class="dot">8</span><span class="event-description">KOIRAPARKKI Rescue-ruutu ja Espan tassuttelu kokoavat yhteen ystäviä  Rescue-koirineen sekä kutsuvat mukaan kaikki koiraystävät</span></td>
      <td class=""><span class="dot">8</span><span class="event-description">KOIRAPARKKI Rescue-ruutu ja Espan tassuttelu kokoavat yhteen ystäviä  Rescue-koirineen sekä kutsuvat mukaan kaikki koiraystävät</span></td>
      <td class=""><span class="dot">8</span><span class="event-description">KOIRAPARKKI Rescue-ruutu ja Espan tassuttelu kokoavat yhteen ystäviä  Rescue-koirineen sekä kutsuvat mukaan kaikki koiraystävät</span></td>
      <td class=""><span class="dot">8</span><span class="event-description">Livemaalausta. TRKCrew -taiteilijakollektiivi johdattaa sinut parkkitaiteen saloihin.</span></td>
      <td class=""><span class="dot">8</span><span class="event-description">Livemaalausta. TRKCrew -taiteilijakollektiivi johdattaa sinut parkkitaiteen saloihin.</span></td>
      <td class=""><span class="dot">8</span><span class="event-description">Livemaalausta. TRKCrew -taiteilijakollektiivi johdattaa sinut parkkitaiteen saloihin.</span></td>
    </tr>
    <tr>
      <td class="empty"><span class="dot">9</span><span class="spot-label">TANSSIPARK(etti)</span><br>
        <span class="event-description">Tanssi, esiinny, opeta muille tai järkkää ruututanssit. Parketti on puunattu. Buukkaa oma aikasi.</span></td>
      <td class=""><span class="dot">9</span><span class="spot-label">TANSSIPARK(etti)</span><br>
        <span class="event-description">Pieni Suomalainen Balettiseurue järjestää balettitunnin parkkiruudussa. Tule ja tanssi kanssamme.</span></td>
      <td class=""><span class="dot">9</span><span class="spot-label">TANSSIPARK(etti)</span><br>
        <span class="event-description">Footlight</span></td>
      <td class=""><span class="dot">9</span><span class="spot-label">TANSSIPARK(etti)</span><br>
        <span class="event-description">Double Dutch on joukkuenaruhyppyä kahdella 3,5?5 metrin narulla. Huima rytmiä, riemua, liikettä ja akrobatiaa yhdistävä laji on loistava keino käyttää kehoa ja kaupunkitilaa vaikkapa parkkiruudulla!</span></td>
      <td><span class="dot">9</span><span class="spot-label">TANSSIPARK(etti)</span><br>
        <span class="event-description">Pekkarinen, improvisaatiota, tajunnanvirtaa ja livesävellystä kitaralla </span></td>
      <td class=""><span class="dot">9</span><span class="spot-label">TANSSIPARK(etti)</span><br>
        <span class="event-description">Inka ja Maya
        &amp;
        Yhtäkkiä Tässä ryhmä
        Lauluja puille kukille ja ohikulkeville ihmisille.</span></td>
    </tr>
    <tr>
      <td><span class="dot">10</span><span class="event-description"> KIRJAPARKKI ja AVOKONTTORI. Tule lukemaan, vaihtamaan kirjoja tai tuulettumaan ja fiilistelemään hetkeksi maisemakonttoriin</span></td>
      <td><span class="dot">10</span><span class="event-description"> KIRJAPARKKI ja AVOKONTTORI. Tule lukemaan, vaihtamaan kirjoja tai tuulettumaan ja fiilistelemään hetkeksi maisemakonttoriin</span></td>
      <td><span class="dot">10</span><span class="event-description"> KIRJAPARKKI ja AVOKONTTORI. Tule lukemaan, vaihtamaan kirjoja tai tuulettumaan ja fiilistelemään hetkeksi maisemakonttoriin</span></td>
      <td class=""><span class="dot">10</span><span class="event-description">KIRJAPARKKI Istahda lukemaan tai tule vaihtamaan kirjasi uuteen</span></td>
      <td class=""><span class="dot">10</span><span class="event-description">KIRJAPARKKI Istahda lukemaan tai tule vaihtamaan kirjasi uuteen</span></td>
      <td class=""><span class="dot">10</span><span class="event-description">KIRJAPARKKI Istahda lukemaan tai tule vaihtamaan kirjasi uuteen</span></td>      
    </tr>
    <tr>
      <td class=""><span class="dot">11</span><span class="event-description">HUOM: Tämä ruutu käytössä vasta klo 18 alkaen.</span></td>
      <td class=""><span class="dot">11</span><span class="event-description">HUOM: Tämä ruutu käytössä vasta klo 18 alkaen.</span></td>
      <td class=""><span class="dot">11</span><span class="event-description">HUOM: Tämä ruutu käytössä vasta klo 18 alkaen.</span></td>
      <td class=""><span class="dot">11</span><span class="event-description">ILLALLINEN ystävien kesken</span></td>
      <td class=""><span class="dot">11</span><span class="event-description">ILLALLINEN ystävien kesken</span></td>
      <td class=""><span class="dot">11</span><span class="event-description">ILLALLINEN ystävien kesken</span></td>
    </tr>
    <tr>
      <td class=""><span class="dot">12</span><span class="event-description">Pöytälätkä! Tuo kaikkien seurapelien Mats Sundin. Autojen sijaan Espalla halutaan nähdä ilmaveivejä, kannustushuutoja ja komeita tuuletuksia. Tunnelmaa luomassa myös levysoittimen rahina.</span></td>
      <td class=""><span class="dot">12</span><span class="event-description">Pöytälätkä! Tuo kaikkien seurapelien Mats Sundin. Autojen sijaan Espalla halutaan nähdä ilmaveivejä, kannustushuutoja ja komeita tuuletuksia. Tunnelmaa luomassa myös levysoittimen rahina.</span></td>
      <td class="empty"><span class="dot">12</span><span class="event-description">Vapaa</span></td>
      <td class="empty"><span class="dot">12</span><span class="event-description">Vapaa</span></td>
      <td class="empty"><span class="dot">12</span><span class="event-description">Vapaa</span></td>
      <td class="empty"><span class="dot">12</span><span class="event-description">Vapaa</span></td>
    </tr>
  </tbody>
</table>
<table id="info-calendar" width="634" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><span class="dot">Ilmastoinfo</span><span class="event-description">Kuvittele – ilman autoja
        Mihin kaikkeen parkkipaikkaa voi käyttää? Testaa ideasi pienoiskorttelin avulla!</span></td>
      <td><span class="dot">Ilmastoinfo</span><span class="event-description">Kuvittele – ilman autoja
        Mihin kaikkeen parkkipaikkaa voi käyttää? Testaa ideasi pienoiskorttelin avulla!</span></td>
      <td><span class="dot">Ilmastoinfo</span><span class="event-description">Kuvittele – ilman autoja
        Mihin kaikkeen parkkipaikkaa voi käyttää? Testaa ideasi pienoiskorttelin avulla!</span></td>
      <td><span class="dot">Ilmastoinfo</span><span class="event-description">Kuvittele – ilman autoja
        Mihin kaikkeen parkkipaikkaa voi käyttää? Testaa ideasi pienoiskorttelin avulla!</span></td>
      <td><span class="dot">Ilmastoinfo</span><span class="event-description">Kuvittele – ilman autoja
        Mihin kaikkeen parkkipaikkaa voi käyttää? Testaa ideasi pienoiskorttelin avulla!</span></td>
      <td><span class="dot">Ilmastoinfo</span><span class="event-description">Kuvittele – ilman autoja
        Mihin kaikkeen parkkipaikkaa voi käyttää? Testaa ideasi pienoiskorttelin avulla!</span></td>
    </tr>
  </tbody>
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
