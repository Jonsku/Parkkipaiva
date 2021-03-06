<?php
date_default_timezone_set ( $config['server']['timezone'] );

$floatfix = 0.0000000000001;

 

/* STANDS */
function db_standExists($db, $id){
    $sql = "SELECT count(*) FROM stands WHERE stands.id = :id;";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

function db_ownerHasStand($db, $user_id){
    $sql = "SELECT count(*) FROM stands WHERE stands.owner = :user_id;";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

function db_getAllStands($db){
    $sql = "SELECT * FROM stands ORDER BY timestamp;";
    $stmt = $db->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $stands = array();
    while($row = $stmt->fetch()){ $stands[] = $row; }
    return $stands;
}

function db_getStand($db, $id){
    $sql = "SELECT * FROM stands WHERE stands.id = :id LIMIT 1;";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $stands = array();
    return $stmt->fetch();
}

function db_getStands($db, $user_id){
    $sql = "SELECT * FROM stands WHERE stands.owner = :owner";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':owner', $user_id);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $stands = array();
    while($row = $stmt->fetch()){
        $stands[] = $row;
    }
    return $stands;
}

function db_getAllStandsInfo($db){
    $sql = 'SELECT stands.id id, stands.address address, stands.start_hour start_hour, stands.start_minute start_minute, stands.end_hour end_hour, stands.end_minute end_minute, stands.description description, stands.u u, stands.v v, stands.slots slots, stands.modified modified, owners.fb_id fb_id, owners.email_id email, owners.phone phone, owners.name owner_name FROM stands, owners WHERE (u BETWEEN -90 AND 90) AND (v BETWEEN -180 AND 180) AND owners.id = stands.owner;';
    $stmt = $db->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $stands = array();
    while($row = $stmt->fetch()){
        $stands[] = $row;
    }
    return $stands;
}

function db_getStandsInBounds($db, $um, $uM, $vm, $vM, $timestamp = "0", $limit = 0, $offset = 0){
    $sql = 'SELECT id, address, start_hour, start_minute, end_hour, end_minute, description, u, v, slots, modified FROM stands WHERE (u BETWEEN :um AND :uM) AND (v BETWEEN :vm AND :vM)';
    if($timestamp != "0"){
        $sql .=" AND modified > :modified";  
    }
    if($limit > 0){
            $sql .= ' LIMIT :limit';
            $sql .= ' OFFSET :offset';
    }
    $sql .= ';';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':uM', $uM);
    $stmt->bindParam(':um', $um);
    $stmt->bindParam(':vM', $vM);
    $stmt->bindParam(':vm', $vm);
    if($timestamp != "0"){
         $stmt->bindParam(':modified', $timestamp);
    }
    if($limit > 0){
        $stmt->bindParam(':limit', $limit);
        $stmt->bindParam(':offset', $offset);
    }
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $stands = array();
    while($row = $stmt->fetch()){
        $stands[] = $row;
    }
    return $stands;
}

function db_getNumberOfStandsInBounds($db, $um, $uM, $vm, $vM){
    $sql = 'SELECT count(*) FROM stands WHERE u <= :uM AND u >= :um AND v <= :vM AND v >= :vm;';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':uM', $uM);
    $stmt->bindParam(':um', $um);
    $stmt->bindParam(':vM', $vM);
    $stmt->bindParam(':vm', $vm);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function db_getStandIdOfOwner($db, $user_id){
    if(!db_ownerHasStand($db, $user_id)){
        return 0;
    }
    $sql = "SELECT id FROM stands WHERE owner = ?;";
    $stmt = $db->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute(array($user_id));
    $row = $stmt->fetch();
    //$lastId = $stmt->fetchColumn();
    return $row['id'];
}


function db_createUpdateStand($db, $user_id, $address, $u, $v, $sT, $eT, $description, $slots){
    $id = $createRecycle === FALSE ? db_getStandIdOfOwner($db, $user_id) : 0;
    if($id > 0){
        $sql = "UPDATE stands SET address = :address, u =  ROUND(:u,6), v =  ROUND(:v,6), start_hour = :start_hour, start_minute = :start_minute, end_hour = :end_hour, end_minute = :end_minute, description = :description, slots = :slots, modified = strftime('%s','now') WHERE id = :id AND owner = :owner;";
    }else{
        $sql = "INSERT INTO stands (id, owner, address, u, v, start_hour, start_minute, end_hour, end_minute, description, slots, timestamp, modified) VALUES (NULL, :owner, :address, round(:u,6), round(:v,6), :start_hour, :start_minute, :end_hour, :end_minute, :description, :slots,strftime('%s','now'), strftime('%s','now'));";
        $created = true;
    }
    $stmt = $db->prepare($sql);
    if($id > 0){
        $stmt->bindParam(':id', $id);
    }
    $stmt->bindParam(':owner', $user_id);
    $stmt->bindParam(':address',$address);
    $stmt->bindParam(':u',$u);
    $stmt->bindParam(':v',$v);
    $stmt->bindParam(':start_hour',$sT[0]);
    $stmt->bindParam(':start_minute',$sT[1]);
    $stmt->bindParam(':end_hour',$eT[0]);
    $stmt->bindParam(':end_minute',$eT[1]);
    $stmt->bindParam(':description',$description);
    $stmt->bindParam(':slots',$slots);
    $stmt->execute();
    if($id > 0){
        return $id;
    }else{
        return $db->lastInsertId();
    }
}

function db_deleteStand($db, $user_id, $id){
    $sql = "DELETE FROM stands WHERE id = ?";
    $whereParams = array($id);
    //only the stand owner and the admin are allowed to delete a stand
    if(!isset($_SESSION['admin']) || $_SESSION['admin'] != 1){
        $sql .= " AND owner = ?";
        $whereParams[] = $user_id;
    }
    $sql .= ";";
    $stmt = $db->prepare($sql);
    $stmt->execute($whereParams);
    return !db_standExists($db, $id);
}

// LOGIN/REGISTER
/*
 Check if a user exists and then if the password match (if email is provided).
 Returns array: "result" => 1 if the user exists and password match
                            0 if the user exists but passwords do not match
                            -1 if the user is not in the database
                            -2 if the user is in the database but has not validated his/her email
                "user_id" => if "result" >= 0
*/
function db_checkUser($db, $email, $fb_id = "", $password = ""){
    if($password === "" && $fb_id === ""){
        $password = "0";
    }
    $sql = 'SELECT id, password, status FROM owners WHERE (fb_id = "" AND email_id = ?) OR (fb_id != "" AND fb_id = ?);';
    $stmt = $db->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute(array($email, $fb_id));
    
    $return = array();
    if( !($row = $stmt->fetch()) ){
        $return["result"] = -1;
    }else if($row['status'] != 1){
        $return["result"] = -2;
    }else if($password != ""){
        if($password == $row['password']){
            $return["result"] = 1;
        }else{
            $return["result"] = 0;
        }
        $return["user_id"] = $row['id'];
    }else{
        $return["result"] = 1;
        $return["user_id"] = $row['id'];
    }
    return $return;
}


function db_createUser($db, $email, $fb_id, $password, $name, $phone){
    $timestamp = strftime('%s');
    $sql = "INSERT INTO owners (id, email_id, fb_id, password, name, phone, status, timestamp) VALUES (NULL, :email, :fb_id, :password, :name, :phone, 0, :timestamp);";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':fb_id', $fb_id);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':timestamp', $timestamp);
    $stmt->execute();
    return array("id"=>$db->lastInsertId(), "t"=>$timestamp);
}

function db_validateAccount($db, $code){
    global $config;
    //extract id
    list($seed,$secret) = split("_", $code, 2);
    $id = intval($secret) ^ ( ( intval($seed) << 2 ) ^ ( intval($seed) >> 2 ) );
    $sql = "SELECT COUNT(*) as count FROM owners WHERE id = :id AND timestamp = :timestamp;";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':timestamp', $seed);
    $stmt->execute();
    if($stmt->fetchColumn() != 1){
        //invalid validation code
        return -1;
    }
    $now = strftime('%s');
    if( ($now - $seed) > ($config['security']['code_best_before_days'] * 24 * 60 * 60) ){
        //code outdated
        return 0;
    }
    //activate account
    $sql = "UPDATE owners SET status = 1, timestamp = :timestamp WHERE id = :id;";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':timestamp', $now);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    //login
    doLogin( $id );
    return 1;
}

function db_changePassword($db, $id, $password){
    $sql = "UPDATE owners SET password = ? WHERE id = ?;";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($password, $id));
    return;
}

function db_userExists($db, $uid){
    $sql = "SELECT COUNT(*) as count FROM owners WHERE id = ?;";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($uid));
    return ($stmt->fetchColumn() == 1);
}

/* #################### 
    HIGH LEVEL FUNCTIONS
   #################### */

function replacePost($jsonString){
    $tmp = json_decode($jsonString, true);
    foreach($tmp as $key => $b){
        $_POST[$key] = $b;
    }
}

function checkRequired($params){
    $missing = array();
    $empty = array();
    foreach($params as $param => $required){
        if(!isset($_POST[$param])){
            $missing[] = $param;
        }else if($required && trim($_POST[$param]) == ""){
            $empty[] = $param;
        }
    }
    if(sizeof($missing) + sizeof($empty) == 0){
        return 1;
    }else{
        $errMsg = "Incorrect query:\n";
        if( sizeof($missing) > 0 ){
            $errMsg .= "Missing: ".implode(", ",$missing)."\n";
        }
        if( sizeof($empty) > 0 ){
            $errMsg .= "Empty: ".implode(", ",$empty)."\n";
        }
        //$errMsg .= "POST: ".implode(", ",array_keys($_POST))."\n";
        return $errMsg;
    }
}


/* ############### */

/* STANDS */
//get all stands with cities
function getAllStands(){
     try {
        //connect to db
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        echo json_encode(db_getAllStands($db));
        $db = NULL;
        return;
     }catch(PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
        $db = NULL;
        return;
     }
}

//get a stand by id (i:)
function get(){
    if(isset($_GET["debug"])){
        echo "debug\n";
        replacePost('{"i":"679632245"}');
    }
    
    /*
    if(function_exists('customLog'))
        customLog("./logs/getLog.txt", json_encode($_POST));
    */
    
    $user_id = trim($_SESSION['uid']);
    
    try {
        //connect to db
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $stands = db_getStands($db, $user_id);
            echo json_encode(array("stands" => $stands));
            $db = NULL;
            return;
    }catch (PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
        $db = NULL;
        return;
    }
}

function delete(){
    if(isset($_GET["debug"])){
        replacePost('{"i":"679632245"}');
    }
    
    if(function_exists('customLog'))
        customLog("./logs/standsLog.txt", "delete|".json_encode($_POST));

    /* Validate */
    $validate = checkRequired(array("i" => true));
    if($validate != 1){
        echo json_encode(array("error"=>$validate));
        return;
    }
    $user_id = trim($_SESSION['uid']);
    $id = trim($_POST["i"]);
    
    try {
        //connect to db
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        if(db_deleteStand($db, $user_id, $id)){
            echo json_encode(array("success" => $id));
        }else{
            echo json_encode(array("error" => "Unable to delete stand."));
        }
        $db = NULL;
        return;
    }catch (PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
        $db = NULL;
        return;
    }
}

function add(){
    if(isset($_GET["debug"])){
        /*
        replacePost('{
            "address": "Tarkk\'ampujankatu+18%2C+00150+Helsinki%2C+Finland",
            "city": "Helsinki",
            "desc": "BlablablablbaBlablablablba%0ABlablablablba%0A%0ABlablablablba%0A%0ABlablablablba%0A%0ABlablablablbaBlablablablbaBlablablablbaBlablablablba%0ABlablablablbaBlablablablba%0A%0A%0A%0A%0ABlablablablbaBlablablablbaBlablablablba%0A%0A%0ABl",
            "et": "15%3A00",
            "i": "679632245",
            "lnk": "",
            "name": "My+Stand",
            "st": "12%3A00",
            "t": "0+2+3+6+",
            "u": "60.16078470000001",
            "v": "24.942949699999986"
        }');*/
        replacePost('{"i":"518892420","name":"Joensuu - Ilosaari","address":"Siltakatu 1, 80100 Joensuu, Finland","city":"","u":"62.5990455742272","v":"29.77065405078747","st":"10:00","et":"14:00","desc":"Kaikille avoin ja ilmainen kirpputori Joensuun Ilosaaressa. S\u00e4\u00e4varaus.","t":"0 1 3 4 6 7 8 ","lnk":"http:\/\/www.facebook.com\/JoensuunSeudunKevatsiivous"}');
        //replacePost('{"i":"679632245","name":"A Stand","address":"Tarkk\'ampujankatu 18, 00150 Helsinki, Finland","city":"Helsinki","u":"60.16195618672868","v":"24.945653289471466","st":"12:00","et":"17:00","desc":"sdsdsd","t":"0 2 4 6 8 ","lnk":""}');
    }    

    if(function_exists('customLog'))
        customLog("./logs/standsLog.txt", "add|".json_encode($_POST));
    /* Validate */
    $validate = checkRequired(array("address" => true, "city" => true, "u" => true, "v" => true, "desc" => true, "st" => true, "et" => true, "slots" => true));
    if($validate != 1){
        echo json_encode(array("error"=>$validate));
        return;
    }
    
    $user_id = trim($_SESSION['uid']);
    $address = trim($_POST["address"]);
    $city = trim($_POST["city"]);
    $city = strcasecmp($city,"helsingfors") == 0 ? "Helsinki" : $city;
    $u = trim($_POST["u"]);
    $v = trim($_POST["v"]);
    $description = trim($_POST["desc"]);
    $slots = trim($_POST["slots"]);
    $sT = explode(":",$_POST["st"]);
    $eT = explode(":",$_POST["et"]);
    if(function_exists('customLog'))
        customLog("./logs/debugLog.txt", $_POST["st"].", ".$_POST["et"]." => ".$sT[0].", ".$sT[1]." / ".$eT[0].", ".$eT[1]."\n");
    //connect to db
    try {
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $id = db_createUpdateStand($db, $user_id, $address, $u, $v, $sT, $eT, $description, $slots);
        
        $return = array("id" => $id,
        "address" => $address,
        "u" => $u,
        "v" => $v,
        "city" => $city,
        "start_hour" => $sT[0],
        "start_minute" => $sT[1],
        "end_hour" => $eT[0],
        "end_minute" => $eT[1],
        "description" => $description,
        "slots" => $slots
        );
        if(function_exists('customLog'))         customLog("./logs/debugLog.txt", json_encode(array($return))."\n");
        echo json_encode(array($return));
        $db = NULL;
        return;
    } catch (PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
        $db = NULL;
        return;
    }
}

function adminLoad(){
//connect to db
    try {
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $stands = db_getAllStandsInfo($db);
        echo json_encode($stands);
        $db = NULL;   
        return;
    }catch (PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
        $db = NULL;
        return;
    }
}

function load(){
    if(isset($_GET["debug"])){
        //replacePost('{"um":"60.12712243909954","uM":"60.21251207707566","vm":"24.852720111523467","vM":"25.024381488476592"}');
        replacePost('{"um":"-90","uM":"90","vm":"-180","vM":"180","c":"true"}'); //{"um":"-90","uM":"90","vm":"-180","vM":"180","c":"true"}

        //replacePost('{"um":"60.12712243909954","uM":"60.21251207707566","vm":"24.737706988964874","vM":"25.139394611035186","t":"1.7976931348623157e+308"}');
        

    }
    /*
    if(function_exists('customLog'))
        customLog("./logs/loadLog.txt", json_encode($_POST));
*/
    /* Validate */
    $validate = checkRequired(array("um" => true, "uM" => true, "vm" => true, "vM" => true));
    if($validate != 1){
        echo json_encode(array("error"=>$validate));
        return;
    }
    
    $uM = trim($_POST["uM"]);
    $um = trim($_POST["um"]);
    $vM = trim($_POST["vM"]);
    $vm = trim($_POST["vm"]);
    $limit = isset($_POST["l"]) ? trim($_POST["l"]) : 0;
    $offset = isset($_POST["o"]) ? trim($_POST["o"]) : 0; //id negative, only return the number of stands in the boundaries
    $timestamp = isset($_POST["t"]) ? trim($_POST["t"]) : "0";

    //connect to db
    try {
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');//'.dirname(__FILE__).'

        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        
        if($offset < 0){
            //calculate how many stands are in the boundaries
            $standCount = db_getNumberOfStandsInBounds($ds, $um, $uM, $vm, $vM);
            echo json_encode(array("standCount" => $standCount));
        }else{
            $stands = db_getStandsInBounds($db, $um, $uM, $vm, $vM, $timestamp, $limit, $offset);
            echo json_encode($stands);
        }
        $db = NULL;   
        return;
    }catch (PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
        $db = NULL;
        return;
    }catch(Exception $ee){
        echo $ee->getMessage();
        return;
    }
}


/* LOGIN/REGISTER */
function login(){
    $validate = checkRequired(array("a" => true, "type" => true));
    if($validate != 1){
        echo json_encode(array("error"=>$validate));
        return;
    }
    
    $action = trim($_POST["a"]);
    
    if(function_exists('customLog'))
        customLog("./logs/loginLog.txt", $action."|".json_encode($_POST));
    switch($action){
        case 'login':
            validateLogin();
            break;
        case 'email_login':
            emailLogin();
            break;
        case 'register':
            registerUser();
            break;
        default:
            echo json_encode(array("error"=>"unknown query"));
            break;
    }
    return;
}

function validateLogin(){
    $requiredFields = trim($_POST["type"]) == "email" || (isset($_SESSION['invitation']) && $_SESSION['invitation'] === 1) ? array("email"=>true, "password" => false) : array("fb_id"=>true);
    $validate = checkRequired($requiredFields);
    if($validate != 1){
        echo json_encode(array("error"=>$validate));
        return;
    }
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? customEncrypt( trim($_POST["password"]) ) : "";
    $fb_id = isset($_POST["fb_id"]) ? trim($_POST["fb_id"]) : "";
    try{
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $verification = db_checkUser($db, $email, $fb_id, $password);
        //1 if the user exists and password match
        //0 if the user exists but passwords do not match
        //-1 if the user is not in the database
        //-2 if the user is in the database but has not validated his/her email
        if($verification["result"] > 0){
            doLogin( $verification["user_id"] );
            echo json_encode( array("success"=>1) );
        }else if($verification["result"] == 0){
            echo json_encode(array("success"=>0,"message"=>"wrong password"));
        }else if($verification["result"] == -1){
            echo json_encode(array("success"=>0,"message"=>"no user"));
        }else if($verification["result"] == -2){
            echo json_encode(array("success"=>0,"message"=>"need validation"));
        }
        $db = NULL;
    }catch (PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
        $db = NULL;
    }
    return;
}

function emailLogin(){
    global $config;
    //$requiredFields = trim($_POST["type"]) == "email" ? array("email"=>true, "password" => false) : array("fb_id"=>true);
    $validate = checkRequired( array("email"=>true) );
    if($validate != 1){
        echo json_encode(array("error"=>$validate));
        return;
    }
    $email = trim($_POST["email"]);
    try{
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $verification = db_checkUser($db, $email, "", "");
        //1 if the user exists and password match
        //0 if the user exists but passwords do not match
        //-1 if the user is not in the database
        //-2 if the user is in the database but has not validated his/her email
        if($verification["result"] >= 0){
            $t = strftime('%s');
            $id = $t."_".( intval($verification["user_id"]) ^ ( ( intval($t) << 2 ) ^ ( intval($t) >> 2 ) ) );
            $link = $config['paths']['base_url']."/reset/".$id;
            
            $message = "Asettaaksesi uuden salasanan klikkaa seuraavaa linkkiä\r\n". //To reset your password, please follow the link.
                        $link."\r\n".
                        "Ystävällisin terveisin,\r\n";
            $headers = 'From: '.$config['email']."\r\n" .
                'Reply-To: '.$config['email']."\r\n" .
                'X-Mailer: PHP/' . phpversion()."\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/plain; charset=UTF-8'."\r\n";
            $headers .= 'Content-Transfer-Encoding: 8bit'."\r\n";
            $headers .= "\r\n";
            mail($email, "=?utf-8?b?".base64_encode("Aseta uusi salasana sivulle siivouspaiva.com")."?=", $message, $headers); //Reset your password on siivouspaiva.com
            
            echo json_encode(array("success"=>"1", "link"=>$link));
        }else if($verification["result"] == -1){
            echo json_encode(array("success"=>0,"message"=>"no user"));
        }else if($verification["result"] == -2){
            echo json_encode(array("success"=>0,"message"=>"need validation"));
        }
        $db = NULL;
    }catch (PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
        $db = NULL;
    }
    return;
}

function registerUser(){
    global $config;
    $requiredFields = trim($_POST["type"]) == "email" || (isset($_SESSION['invitation']) && $_SESSION['invitation'] === 1) ? array("email"=>true, "password" => true, "password_verify" => true) : array("fb_id"=>true);
    $requiredFields = array_merge($requiredFields, array("user_name"=>true, "phone" => true) );
    $validate = checkRequired( $requiredFields );
    if($validate != 1){
        echo json_encode(array("error"=>$validate));
        return;
    }

    $name = isset($_POST["user_name"]) ? trim($_POST["user_name"]) : "";
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $password_verify = trim($_POST["password_verify"]);
    $fb_id = isset($_POST["fb_id"]) ? trim($_POST["fb_id"]) : "";
    try{
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $verification = db_checkUser($db, $email, $fb_id, "");
        //1 if the user exists and password match
        //0 if the user exists but passwords do not match
        //-1 if the user is not in the database
        //-2 if the user is in the database but has not validated his/her email
        if($verification["result"] == -2){
            echo json_encode(array("success"=>"0", "message"=>"need validation"));
        }else if($verification["result"] != -1){
            echo json_encode(array("success"=>"0", "message"=>"user exists"));
        }else{
            if($password != $password_verify){
                echo json_encode(array("success"=>"0", "message"=>"passwords mismatch"));
            }else{
                //crypt pass
                if($password != ""){
                    $password = customEncrypt($password);
                }
                //create user
                $user_info = db_createUser($db, $email, $fb_id, $password, $name, $phone);

                $id = $user_info["t"]."_".( intval($user_info["id"]) ^ ( ( intval($user_info["t"]) << 2 ) ^ ( intval($user_info["t"]) >> 2 ) ) );
                if(trim($_POST["type"]) != "email"){
                    //validate the account automatically
                    unset($_SESSION['invitation']);
                    echo json_encode( array("success"=>db_validateAccount($db, $id) ) );
                }else{
                    //send verif mail
                    $link = $config['paths']['base_url']."/register/".$id;
                    $message = "Vahvistaaksesi tilin, klikkaa seuraavaa linkkiä.On\r\n". //To validate your account, please follow the link.
                                $link."\r\n".
                                "Ystävällisin terveisin,\r\n";
                    $headers = 'From: '.$config['email']."\r\n" .
                        'Reply-To: '.$config['email']."\r\n" .
                        'X-Mailer: PHP/' . phpversion()."\r\n";
                    $headers .= 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/plain; charset=UTF-8'."\r\n";
                    $headers .= 'Content-Transfer-Encoding: 8bit'."\r\n";
                    $headers .= "\r\n";
                    mail($email, "=?utf-8?b?".base64_encode("Vahvista tili sivulle siivouspaiva.com")."?=", $message, $headers); //Validate your account on siivouspaiva.com
                    
                    echo json_encode(array("success"=>"1", "link"=>$link));
                }
            }
        }
    }catch (PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
    }
    $db = NULL;
    return;
}

function setPass(){
    $validate = checkRequired( array("password"=>true, "password_verify"=>true) );
    if($validate != 1){
        echo json_encode(array("error"=>$validate));
        return;
    }
    $id = trim($_SESSION['uid']);
    $password = trim($_POST["password"]);
    $password_verify = trim($_POST["password_verify"]);
    if($password != $password_verify){
        echo json_encode(array("success"=>"0", "message"=>"passwords mismatch"));
        return;
    }
    try{
        $db = new PDO('sqlite:'.dirname(__FILE__).'/db/data.db');
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        //crypt pass
        $password = customEncrypt($password);
        db_changePassword($db, $id, $password);
        echo json_encode(array("success"=>"1"));
        $db = NULL;
    }catch (PDOException $e) {
        echo json_encode(array("error"=>"'Db error: ".$e->getMessage()."'"));
        $db = NULL;
    }
    return;
}

function doLogin( $userId ){
    if(isset($_SESSION['invitation'])){
        unset($_SESSION['invitation']);
    }
    $_SESSION['uid'] = $userId;
}

function logout(){
    $_SESSION['uid'] = -1;
    unset($_SESSION['uid']);
    echo json_encode(array("success"=>"1"));
    return;
}

function customEncrypt($mySecret){
    return sha1(md5($mySecret).$mySecret);
}

function isLogged(){
    if(!isset($_SESSION['uid']) || $_SESSION['uid']<0){
        return false;
    }
    return true;
}

function isAdmin(){
    if(!isset($_SESSION['admin']) || $_SESSION['admin'] != 1){
        return false;
    }
    return true;
}


?>
