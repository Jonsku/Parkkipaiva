<?php
require_once("./inc/init.php");
require_once($_SERVER['DOCUMENT_ROOT']."/db.php");
try {
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        //print_r($_GET);
        //echo "\n".isInvitationId($db, $_GET['id'])?"invit":"not invite"."\n";
        //check wether it's a respons to an invit, or an account creation validation
        if(isset($_GET['id']) && strpos($_GET['id'],"_") > 0){
         //account validation
         $result = db_validateAccount($db, $_GET['id']);
         if($result < 0){
            header("HTTP/1.0 404 Not Found");
            exit();
         }
require_once("./l10n.php");
?>
<!DOCTYPE html>
<html lang="fi">
  <head>
    <meta charset="UTF-8">
    <title>Parkkipäivä - <?php s('en_EN'); ?>Account activation<?php e(); ?></title>
  </head>
  <body>
<?php
   if($result == 1){
?>
   <p><?php s('en_EN'); ?>Your account is now activated and you can start adding your stand to the map and also keep a list of your favorite stands.<?php e(); ?></p>
   <p><a href="<?php echo $config['paths']['base_url'] ?>"><?php s('en_EN'); ?>Click to show the map<?php e(); ?></a></p>
<?php
   }else{
?>
   <p><?php s('en_EN'); ?>Unfortunately your activation code is outdated. You can either request a new one to be sent to the same email address, OR, create a new one using the form on the map page.<?php e(); ?></p>
   <p><a href="<?php echo $config['paths']['base_url'] ?>/"><?php s('en_EN'); ?>Request a new validation code<?php e(); ?></a> <a href="<?php echo $config['paths']['base_url'] ?>"><?php s('en_EN'); ?>Create a new account<?php e(); ?></a></p>
<?php
   }
?>
  </body>
</html>
<?php
         exit();
        }
        
        //Invitations
        if(!isset($_GET['id']) || !db_isInvitationId($db, $_GET['id'])){
            header("HTTP/1.0 404 Not Found");
            exit();
        }
        $mail =  db_getEmailFromInvitationId($db, $_GET['id']);
}catch (PDOException $e) {
    echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
    $db = NULL;
   exit();
}  

include "./inc/header.php";
$page = "Register";
$navigation[$page] = "register/".$_GET['id'];
$_SESSION['invitation'] = 1; 
?>
  </head>

  <body>
    <!-- Init FB SDK -->
    <div id="fb-root"></div>
    
   <!-- header starts -->
  </head>
  <body>
    <!-- Init FB SDK -->
    <div id="fb-root"></div>
    <?php include($_SERVER['DOCUMENT_ROOT']."/inc/navbar.php"); ?>
    <div id="content">
      <div class="container">
        <div class="row" id="login_row">
          <div class="span12 inset">
            <!-- Login/out -->
            <p><?php s('en_EN'); ?>Once you have created an account using your email address you will be able to add recycling centers to the map.<?php e(); ?></p>
            <br/><br/>
            <button id="email-login-select" class="btn-red login-btn" style="display:none"><?php s('en_EN'); ?>Login with an email address<?php e(); ?></button>
            <?php include($_SERVER['DOCUMENT_ROOT']."/inc/login.php"); ?>
          </div>
        </div>
      </div>
    </div>
      <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/jquery.form.js"></script>
      <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/form.util.js"></script>
      <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/login.js"></script>
      <script>
        $("#email").hide();
        $("#email").val("<?php echo $mail; ?>");
        $('#email-login-form input[name="type"]').val("invite");
        $("#email").parent().append("<?php echo $mail; ?>");
        $("#email-login-select").click();
        
        function loggedIn(){
            $.ajax({
                type: 'POST',
                url: '<?php echo $config['paths']['base_url']; ?>/data.php?query=confirm',
                data: {id: "<?php echo $_GET['id'] ?>"},
                success: function(data, textStatus, jqXHR){ //$.log("Ajax post result: "+textStatus); //$.log(data);
                    if(data.error){
                        alert("Error:"+data.error);
                    }else{
                       window.location = "<?php echo $config['paths']['base_url']; ?>/myyntipaikat.php";
                    }
                },
                dataType: "json"
            });
        }
    </script>
    <!-- Plugin -->
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-collapse.js"></script>
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-tooltip.js"></script>
  </body>
</html>
