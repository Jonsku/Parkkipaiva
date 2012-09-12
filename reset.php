<?php
require_once("./inc/init.php");

$WRONG = -1;
$OUTDATED = 0;
$OK = 1;
include($_SERVER['DOCUMENT_ROOT']."/db.php");

$result = 0;
if(isset($_GET['id']) && strpos($_GET['id'],"_") > 0){
   //account validation
   list($seed,$secret) = split("_", $_GET['id'], 2);
   $now = strftime('%s');
   if( ($now - $seed) > ($config['security']['code_best_before_days'] * 24 * 60 * 60) ){
       $result = $OUTDATED;
   }else{
      try {
         $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
         $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
         $id = intval($secret) ^ ( ( intval($seed) << 2 ) ^ ( intval($seed) >> 2 ) );
         if( db_userExists($db, $id) ){
            $db = NULL;
            $result = $OK;
            doLogin($id);
         }else{
            $db = NULL;
            header("HTTP/1.0 404 Not Found");
            exit();
         }
      }catch (PDOException $e) {
         echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
         $db = NULL;
         exit();
      } 
   }
}else{
   header("HTTP/1.0 404 Not Found");
   exit();
}
require_once("./l10n.php");
?>
<!DOCTYPE html>
<html lang="fi">
  <head>
    <meta charset="UTF-8">
    <title>Parkkipäivä - <?php s('en_EN'); ?>Email login<?php e(); ?></title>
  </head>
  <body>
<?php
   if($result == $OUTDATED){
?>
   <p><?php s('en_EN'); ?>Unfortunately this link is outdated. You can either request a new one to be sent to the same email address, OR, loggin using the form on the map page.<?php e(); ?></p>
   <p><a href="<?php echo $config['paths']['base_url'] ?>/"><?php s('en_EN'); ?>Request a new email login link<?php e(); ?></a> <a href="<?php echo $config['paths']['base_url'] ?>/myyntipaikat.php"><?php s('en_EN'); ?>Login from the map page<?php e(); ?></a></p>
<?php
   }else if($result == $OK){
?>
         <p><?php s('en_EN'); ?>You are now logged in.<?php e(); ?></p>
         <p><?php s('en_EN'); ?>Please use the form below to change you password.<?php e(); ?></p>
         <form method="POST" action="<?php echo $config['paths']['base_url']; ?>/data.php?query=setpass">
            <fieldset>
               <label for="password">Password</label><input type="password" name="password" id="password" class="required" minlength="6"/>
               <label for="password_verify"><?php s('en_EN'); ?>Re-type password<?php e(); ?></label><input type="password" name="password_verify" id="password_verify" class="required"/>
               <input type="submit"><?php s('en_EN'); ?>Submit<?php e(); ?></input>
            </fieldset>
         </form>
         <p><a href="<?php echo $config['paths']['base_url'] ?>"><?php s('en_EN'); ?>Click to show the map<?php e(); ?></a></p>

<?php
   }
?>
  </body>
</html>
