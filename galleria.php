<?php
require_once("./inc/init.php");
$page = "Galleria";
$title = "Parkkipäivä pe 21.9. 14 – 21 – Galleria";
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
        <h1>Galleria</h1>
<p>Tänne päivitetään myöhemmin kuvia tapahtumasta.</p>
<p><img src="images/kuvia.jpg" alt="Kuvia" width="400" height="377" align="right"></p>
      <?php include($_SERVER['DOCUMENT_ROOT']."/inc/footer.php"); ?>
</body>
</html>
