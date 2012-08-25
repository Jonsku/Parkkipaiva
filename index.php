<?php
//This line must be the first of the script
$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT']."/parkkipaiva/";
//The 2 lines below are required to be able to keep track og logged in users
ini_set("session.save_path",$_SERVER['DOCUMENT_ROOT']."/session/");
session_start();

//This is the name of the page, check header.php to see how it can be used to generate the navigation links
$page = "Etusivu";
//This must be included in every page of the site
include($_SERVER['DOCUMENT_ROOT']."/inc/header.php");
?>
<!-- Here you can declare addtional style sheets and javascripts -->
  </head>

  <body>
    <!-- This DIV is required by the facebook API but it is invisible -->
    <div id="fb-root"></div>
    <!-- Needed for facebook API -->
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/fi_FI/all.js#xfbml=1&appId=<?php echo $config['facebook']['app_id']; ?>";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <!-- This create the navigation, see also ./inc/header.php -->
    <?php include($_SERVER['DOCUMENT_ROOT']."/inc/navbar.php"); ?>
    
    <div id="content">
      <!-- content starts -->
      <div class="container">
        <div class="row">
          <div class="span12">
            <div class="row">
              <div class="span8">
                <div id="intro">
                  <div class="row">
                      <p><?php s('en_EN'); ?>A sample page. Look at the PHP script index.php to se how the strings are localized by encapsulating them between 's('some locale');' 'and e();'.
		      The locale can be 'fi_FI' or 'en_EN' for instance. The default is 'fi_FI', so s('fi_FI'); is the same as s();. Check out l10n.php for more info on the subject.
		      <?php e(); ?></p>
		      <p>Where to put different files:</p>
		      <ul>
			<li>Images : root folder/img/ <img src="<?php echo $config['paths']['base_url']; ?>/img/add_stand.png" alt="Lisää oma myyntipaikkassi"/></li>
			<li>Stylesheets : root folder/css/</li>
			<li>Javascripts : root folder/script/</li>
			<li>Pages: in the root folder</li>
		      </ul>
		      <p>The root folder should be referenced using the PHP code <b>echo $config['paths']['base_url'];</b>. Look a index.php to see of it is used for the image for instance.</p>                   
                  </div>
                </div>
              </div>
              
              <div class="span4">
		<!-- change the data-href attribute to point to the right facebook page -->
                  <div class="like-box my-well" style="text-align: center; padding:0px; margin-bottom: 5px;">
                      <div class="fb-like-box" data-href="http://www.facebook.com/siivouspaiva" data-width="280" data-show-faces="true" data-stream="true" data-header="false"></div><!-- 345 -->
                  </div>
                  <!-- Contact form -->
                  <form id="message-form" class="form-inline my-well" action="/mail/" method="post">
                      <h1>Ota yhteyttä</h1>
                      <fieldset>
                          <div class="control-group subject">
                              <label for="subject"><?php s(); ?>aihe<?php e(); ?>:</label>
                              <div class="controls">
                                  <input type="text" name="subject" id="subject"/>
                              </div>
                          </div>
                          <div class="control-group email">
                              <label for="email"><?php s(); ?>sähköpostiosoitteesi<?php e(); ?>:</label>
                              <div class="controls">
                                  <input type="text" name="email" id="email"/>
                              </div>
                          </div>
                          <div class="control-group message">
                              <label for="message"><?php s(); ?>viesti<?php e(); ?>:</label>
                              <div class="controls">
                                  <textarea name="message" id="message"></textarea>
                              </div>
                          </div>
                          <button type="submit" class="btn-red"><?php s(); ?>Lähetä<?php e(); ?></button>
                      </fieldset>
                  </form>
                  
                  <!-- Side illustration -->
                  <div class="illustration center">
                      <img src="<?php echo $config['paths']['base_url']; ?>/img/side_illustration.png" alt="side illustration">
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Show navigation links and partners -->
      <?php include($_SERVER['DOCUMENT_ROOT']."/parkkipaiva/inc/footer.php"); ?>
        
    </div>
    <script type="text/javascript">
    //Handle the contact form
    function messageSent(){
        $('#message-form input[name="email"]').val("");
        $('#message-form input[name="subject"]').val("");
        $('#message-form textarea').val("");
        alert("Kiitos! Olemme sinuun yhteydessä pian.")
    }
    
    $('#message-form button').click(function(){
        $('#message-form .error').removeClass("error");
        /* Validate */
        if($('#message-form input[name="email"]').val().replace(" ","").length == 0){
            alert("You must enter an email address.");
            $('.control-group.email').toggleClass("error");
            return false;
        }
        if($('#message-form textarea').val().replace(" ","").length == 0){
            alert("You must enter a message.");
            $('.control-group.textarea').toggleClass("error");
            return false;
        }
        var data= {
            email: urlencode($('#message-form input[name="email"]').val()),
            subject: urlencode($('#message-form input[name="subject"]').val()),
            message: urlencode($('#message-form textarea').val())
        };
        $.log(data);
        $.ajax({
            type: 'POST',
            url: '<?php echo $config['paths']['base_url']; ?>/data.php?query=mail',
            data: data,
            success: function(data, textStatus, jqXHR){ //$.log("Ajax post result: "+textStatus); //$.log(data);
                if(data.error){
                    alert("Error:"+data.error);
                }else{
                    messageSent();
                }
            },
            dataType: "json"
        });
        return false;
    });
    </script>
    <!-- Plugin -->
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-collapse.js"></script>
  </body>
</html>