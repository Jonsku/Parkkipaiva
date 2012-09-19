<?php
require_once("./inc/init.php");
$page = "Parkit";
$title = "Parkkipäivä pe 21.9. 15 – 21. Paikat ja ohjelma";
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
        
<img src="images/kartta.gif" width="645" height="371">

<h2>Päivän ohjelma tunneittain.</h2>
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
