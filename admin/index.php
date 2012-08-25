<?php
$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT']."/parkkipaiva/";
ini_set("session.save_path","../session/");
session_start();
$_SESSION['admin'] = 1;
$page = "Staff Only!";
include("../inc/header.php");
?>
    <link type="text/css" href="<?php echo $config['paths']['base_url']; ?>/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />
    <!-- Table sorter plugin -->
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/jquery.jscrollpane.min.js"></script>
    <!-- Google Map API init -->
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo $config['googlemap']['api_key']; ?>&sensor=true"></script>
  </head>

  <body>
    <!-- header starts -->
    <?php include("../inc/navbar.php"); ?>
    <!-- navbar ends -->
    
    <!-- content starts -->
    <div class="container">
        <?php include("../inc/map.php"); ?>
        <!-- Stands -->
        <div class="row">
            <h2>Manage stands</h2>
            <hr>
            <div class="span12 inset">
                <table id="stands-table" class="table-striped">
                    <thead>
                        <tr>
                          <th></th>
                          <th>Owner Info</th>
                          <th>Address</th>
                          <th>Opening hours</th>
                          <th>Description</th>
                          <th>Slots occupied</th>
                          <th>Created/Modified</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                <button class="btn" id="delete-stands">Delete selected</button>
            </div>
        </div>
    </div>
    <!-- Script -->
    <script type="text/javascript">
      /* Localized Strings */
      stringsL10N = new Array();
      <?php include_once("../l10n_map.php"); ?>
    </script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/standmap.js"></script>
    <script type="text/javascript">
        function updateStandsList(data){
            $.log("updateStandsList", data);
            for(var i in data){
                //format date
                var date = new Date(Number(data[i].modified)*1000);
                var ownerInfo = data[i].owner_name;
                if(data[i].phone != "")
                  ownerInfo +=  "<br/>Phone: " + data[i].phone;
                if(data[i].email != "")
                  ownerInfo +=  "<br/>Email: " + data[i].email;
                
                //id, name, address, start_hour, start_minute, end_hour, end_minute, description, tags, link, u, v, modified
                $('#stands-table tbody').append('<tr id="'+data[i].id+'"><td><input type="checkbox"/></td><td>'+ownerInfo+'</td><td>'+data[i].address+'<input name="u" type="hidden" val="'+data[i].u+'"/><input name="v" type="hidden" val="'+data[i].v+'"/></td><td>'+pad(data[i].start_hour,2)+':'+pad(data[i].start_minute,2)+' - '+pad(data[i].end_hour,2)+':'+pad(data[i].end_minute,2)+'</td><td>'+data[i].description+'</td><td>'+data[i].slots+'</td><td>'+date.toLocaleString()+'</td></tr>');
            }
        }
        
        //load Map API
        initializeMap();
        
        //load all stands
        var data= {um:"-90",uM:"90",vm:"-180",vM:"180"};
        $.log(data);
        $.ajax({
            type: 'POST',
            url: '<?php echo $config['paths']['base_url']; ?>/data.php?query=adminLoad',
            data: data,
             error: function(jqXHR, textStatus, errorThrown){
                $.log("There was an error.");
                $.log(jqXHR);
                $.log(textStatus);
                return;
            },
            success: function(data, textStatus, jqXHR){ //$.log("Ajax post result: "+textStatus); //$.log(data);
                if(data.error){
                    alert("Error:"+data.error);
                }else{
                    updateStandsList(data);
                }
            },
            dataType: "json"
        });
        
        //delete stands button
        $('#delete-stands').click(function(){
            var selected = $('#stands-table input[type="checkbox"]:checked');
            if(selected.length<1){
                alert("No stand selected for deletion!");
                return false;
            }
            var del = confirm("Are you sure you want to delete "+selected.length+" stand"+(selected.length>1 ? "s?":"?"));
            if(del === true){
              selected.each(function(){
                $.ajax({
                    type: 'POST',
                    data: {i: urlencode($(this).parents('tr').attr('id'))},
                    url: '<?php echo $config['paths']['base_url']; ?>/data.php?query=delete',
                    success: function(data, textStatus, jqXHR){ $.log("Ajax post result: "+textStatus); //$.log(data);
                        if(data.error){
                            alert("Error:"+data.error);
                        }else{
                            $('#'+data.success).remove();
                        }
                    },
                    dataType: "json"
                });
              });
            }
        });
        
        createHoursMinutesSelect();
    </script>
    <!-- Plugin -->
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-collapse.js"></script>
  </body>
</html>