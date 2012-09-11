<?php
require_once("../inc/init.php");
$_SESSION['admin'] = 1;
$page = "Staff Only!";
include("../inc/header.php");
?>
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
    <link type="text/css" href="<?php echo $config['paths']['base_url']; ?>/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />
    <link type="text/css" href="<?php echo $config['paths']['base_url']; ?>/css/map.css" rel="stylesheet" media="all" />
    <!-- Table sorter plugin -->
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/jquery.jscrollpane.min.js"></script>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
  </head>

  <body>
    <!-- header starts -->
    <?php include("../inc/navbar.php"); ?>
    <!-- navbar ends -->
    
    <!-- content starts -->
    <div class="container">
        <?php include("../inc/map.php"); ?>
        <div id="map_action">
          <button class="add">Add slot</button>
          <select id="slot_type"></select>
          <button class="remove">Remove last slot</button>
          Available from <select name="sh" id="sh" class="hour" data-default="14"></select> to <select name="eh" id="eh" class="hour" data-default="19"></select>
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
      var currentSlot = undefined;
     initializeMap();
     createHoursMinutesSelect();
      $("#sh option").last().remove();
      $("#eh option").first().remove();
      
      $("#sh").change(function(){
          $.log("Start hour changed");
          var startHour = $(this).val();
          var endHour = $("#eh").val();
          if(startHour >= endHour){
              $("#eh").val(Number(startHour)+1).change();
          }
          hoursChanged();
      });
      
      $("#eh").change(function(){
          $.log("End hour changed");
          var startHour = $("#sh").val();
          var endHour = $(this).val();
          if(endHour <= startHour){
              $("#sh").val(Number(endHour)-1).change();
          }
          hoursChanged();
      })
      
     for(var i in StandTypes){
      $("#slot_type").append('<option value="'+i+'">'+StandTypes[i]+'</option>'); 
     }
     
     $("#slot_type").change(function(){
      if(currentSlot != undefined){
        currentSlot.changeType($(this).val());
      }
     });
     
     function hoursChanged(){
      if(currentSlot === undefined){
        return;
      }
      currentSlot.changeHours( $("#sh").val(), $("#eh").val() );
     }
     
     function locationsLoaded(){
      $.log("Locations loaded");
      for(var i in StandsList){
        StandsList[i].toggleEdit();
        StandsList[i].addListener("clicked", function(stand){
          currentSlot = stand;
          $("#slot_type").val(currentSlot.type);
          $("#sh").val(stand.start_hour);
          $("#eh").val(stand.end_hour);
          currentSlot.select();
          $.log("Stand "+stand.id+" clicked");
        });
        
        StandsList[i].addListener("destroyed",function(stand){
          if(stand === currentSlot){
            currentSlot = undefined;
          }
        });
      }
     }
     
      $("#map_action .add").click(function(){
          var newStand = new Stand();
          newStand.id = $("#map_canvas .locationIcon").length + 1;
          $("#slot_type").val(0);
          $("#sh").val(14);
          $("#eh").val(19);
          newStand.type = $("#slot_type").val();
          newStand.start_hour = $("#sh").val();
          newStand.end_hour = $("#eh").val();
          newStand.appendToMap();
          newStand.toggleEdit();
          newStand.addListener("clicked", function(stand){
            currentSlot = stand;
            $("#slot_type").val(currentSlot.type);
            $("#sh").val(stand.start_hour);
            $("#eh").val(stand.end_hour);
            currentSlot.select();
            $.log("Stand "+stand.id+" clicked");
          });
          newStand.addListener("destroyed",function(stand){
            if(stand === currentSlot){
              currentSlot = undefined;
            }
          });
          newStand.updateLocation();
          StandsList[newStand.id] = newStand;
          currentSlot = newStand;
          currentSlot.select();
      });
      
      $("#map_action .remove").click(function(){
        StandsList[$("#map_canvas .locationIcon").length].destroy();
      });
    </script>
    <!-- Plugin -->
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-collapse.js"></script>
  </body>
</html>