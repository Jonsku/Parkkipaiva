<?php
require_once("../inc/init.php");
$_SESSION['admin'] = 1;
$page = "Staff Only!";
$title = "Parkkipäivä. Administration";
include("../inc/header.php");
?>
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
    <link type="text/css" href="<?php echo $config['paths']['base_url']; ?>/css/map.css" rel="stylesheet" media="all" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
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
<?php include($_SERVER['DOCUMENT_ROOT']."/inc/map.php"); ?>
        <div id="map_action">
          <button class="add">Add slot</button>
          <select id="slot_type"></select>
          <button class="remove">Remove last slot</button>
          Available from <select name="sh" id="sh" class="hour" data-default="14"></select> to <select name="eh" id="eh" class="hour" data-default="19"></select>
          <br>
          Label: <input type="text" name="label" id="label" value=""/><button id="changeLabel">Set label</button>
          <br>
          Description: <textarea name="spot-description" id="spot-description"></textarea><button id="changeDescription">Set description</button>
        </div>
        
        <br><hr><br>
        <p>Here you can create and edit events:</p>
        <button id="create-event">Create a new event</button>
        <div id="event-editing">
          <form id="event-form" class="form-vertical" method="post" action="#">
            <input type="hidden" name="eventId" id="eventId" value="0"/> 
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
                  Klo. <select name="starth" id="starth" class="hour" data-default="14"></select> - <select name="endh" id="endh" class="hour" data-default="19"></select>
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
        <br><hr><br>
        <p>Click on an event to edit it in the form:</p>
      <table id="events-calendar" width="634" border="0" cellspacing="0" cellpadding="0" >
        <tr class="header">
          <?php for($i = $config['start_time'];$i < $config['end_time'];$i++){ ?>
          <td bgcolor="#e8d53e"><?php echo $i; ?>:00 - <?php echo $i+1; ?>:00</td>
          <?php } ?>
        </tr>
      </table>
      <br><br>
 <?php include($_SERVER['DOCUMENT_ROOT']."/inc/footer.php"); ?>
       <!--end of wrapper -->
    
    <!-- Script -->
    <script type="text/javascript">
      /* Localized Strings */
      stringsL10N = new Array();
      <?php include_once("../l10n_map.php"); ?>
    </script>
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/form.util.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/standmap.js"></script>
    <script type="text/javascript" src="<?php echo $config['paths']['base_url']; ?>/script/events.js"></script>
    <script type="text/javascript">
    var userEvent = undefined;
    //init the events timetable
    var calendar = new EventCalendar();
    $("#remove").hide();
    
    //load the event in the form when clicking on the timetable
    calendar.clickCellCallback(function(ev){
      $.log("Callback called", ev);
      $('#event-form input[name="location"]').val(ev.location);
      $('#event-form select[name="starth"]').val(ev.start_hour);
      $('#event-form select[name="endh"]').val(ev.end_hour);
      $('#event-form textarea').val(ev.description);
      $('#event-form input[name="eventId"]').val(ev.id);
      updateLocationsSelect();
      $('#event-form textarea').keyup();
      userEvent = ev;
      $("#remove").show();
    });
    
     var currentSlot = undefined;
     initializeMap();
     createHoursMinutesSelect();
      $("#sh option, #starth option").last().remove();
      $("#eh option, #endh option").first().remove();
      updateLocationsSelect();
      $("#event-editing").show();
      
      
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
      updateLocationsSelect();
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
          $("#label").val(stand.label);
          $("#spot-description").val(stand.description);
          currentSlot.select();
          $.log("Stand "+stand.id+" clicked");
        });
        
        StandsList[i].addListener("destroyed",function(stand){
          if(stand === currentSlot){
            currentSlot = undefined;
          }
        });
      }
      //
      calendar.setTimesAndView($("#sh option").first().val(), $("#eh option").last().val(),  StandsList, $("#events-calendar"));
      calendar.loadEvents();
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
          $("#label").val("");
          $("#spot-description").val("");
          
          newStand.appendToMap();
          newStand.toggleEdit();
          newStand.addListener("clicked", function(stand){
            currentSlot = stand;
            $("#slot_type").val(currentSlot.type);
            $("#sh").val(stand.start_hour);
            $("#eh").val(stand.end_hour);
            $("#label").val(stand.label);
            $("#spot-description").val(stand.description);
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
      
      $("#changeLabel").click(function(){
        if(currentSlot === undefined){
          alert("Select a slot first.");
          return false;
        }
        currentSlot.label = $("#label").val();
        currentSlot.updateData();
        return false;
      });
      
      $("#changeDescription").click(function(){
        if(currentSlot === undefined){
          alert("Select a slot first.");
          return false;
        }
        currentSlot.description = $("#spot-description").val();
        currentSlot.updateData();
        return false;
      });
      
      $("#map_action .remove").click(function(){
        StandsList[$("#map_canvas .locationIcon").length].destroy();
      });
      
      //ADD EVENTS
      $("#create-event").click(function(){
          $('#event-form input[name="location"]').val();
          $('#event-form textarea').val("");
          $('#event-form input[name="eventId"]').val(0);
          updateLocationsSelect();
          $('#event-form textarea').keyup();
          userEvent = undefined;
          $("#remove").hide();
      });
      
      $("#event-form #starth").change(function(){
        $.log("Start hour changed");
        var startHour = $(this).val();
        var endHour = $("#event-form #endh").val();
        if(startHour >= endHour){
            $("#event-form #endh").val(Number(startHour)+1).change();
        }
        updateLocationsSelect();
    });
    
    $("#event-form #endh").change(function(){
        $.log("End hour changed");
        var startHour = $("#event-form #starth").val();
        var endHour = $(this).val();
        if(endHour <= startHour){
            $("#event-form #starth").val(Number(endHour)-1).change();
        }
        updateLocationsSelect();
    })
    
    function updateLocationsSelect(){
        var data = {start: $("#event-form #starth").val(), end: $("#event-form #endh").val(), fakeid: $("#event-form #eventId").val()};
        var selectLoc = $("#event-form #location").val();
        $.ajax({
            type: 'POST',
            url: baseUrl+'/data.php?query=freelocations',
            data: data,
            error: function(jqXHR, textStatus, errorThrown){
                $.log("There was an error.");
                $.log(jqXHR);
                $.log(textStatus);
                return;
            },
            success: function(data, textStatus, jqXHR){
                if(data.error !== undefined){
                    alert("Error:"+data.error);
                }else{
                    $.log(data.success);
                    $("#event-form span.location").remove();
                    for(var i in data.success){
                        var loc = $('<span class="location dot">'+data.success[i]+'</span>');
                        $(".location_select").append(loc);
                        if(data.success[i] == selectLoc){
                            loc.toggleClass('selected');   
                        }
                        loc.click(function(){
                            var wasSelected = $(this).hasClass('selected');
                            $("#event-form span.location").removeClass('selected');
                            if( !wasSelected ){
                                $(this).addClass('selected');
                                $("#event-form #location").val($(this).text());
                            }else{
                                $("#event-form #location").val("");
                            }
                        });
                    }
                    if($(".location_select .selected").length == 0){
                        $("#event-form #location").val("");
                    }
                    
                }
            },
            dataType: "json"
        });
    }
    
    /* Update description char count when typing */
    $('#event-form textarea').keyup(function(){
        if($(this).val().length > 200){
            var txt = $(this).val();
            $(this).val(txt.substring(0,200));
            return false;
        }else{
            $('#char-count').text($(this).val().length + " / 200 "+stringsL10N["merkkiä"]);
            return true;
        }
    });
    
    $('#event-editing #validate').click(function(){
     hideError($('#event-editing'));
    });
    
    /* delete stand */
    $('#remove').click(function(){
        if(confirm(stringsL10N["Are you sure you want to remove this event?"]) != true){
            return false;
        }
        $.ajax({
            type: 'POST',
            url: baseUrl+'/data.php?query=deleteAdminEvent',
            data: { eventId : urlencode($('#event-form input[name="eventId"]').val()) },
            dataType: "json",
            error: function(jqXHR, textStatus, errorThrown){
                $.log("There was an error.");
                $.log(jqXHR);
                $.log(textStatus);
                return;
            },
            success: function(data, textStatus, jqXHR){
                if(data.error){
                    alert("Error:"+data.error);
                }else{
                    $.log("Delete result:", data);
                    calendar.removeEvent(userEvent);    
                    userEvent = undefined;
                    $("#create-event").click();
                }
                return;
            }
        });
        return false;
    });
    
    //form validation with jQuery plugin
    $("#event-form").validate({
        onfocusout: false, 
        onkeyup: false, 
        onclick: false,
        ignore: "",
        rules: {
            location: { number:true, required: true },
            desc: { required: true, maxlength: 200 }
        },
        messages: {
            location: {
              required: stringsL10N["Please select a location."]
            },
            desc: {
                required: stringsL10N["Tämä kenttä on pakollinen."],
                maxlength: stringsL10N["Kuvaus voi olla korkeintaan 200 merkkiä pitkä."]
            }
        },
        errorPlacement: function(error,element) {
                        //add a tooltip message over the invalid field
                        showError( element.attr('id') != 'location' ? element.attr('id') : 'location_select_wrap', error.text());
                        return true;
                    },
        submitHandler: function(form){
            /* Validate form */
            var errors = new Array();
            var s = parseInt($('#event-form select[name="starth"]').val());
            var e = parseInt($('#event-form select[name="endh"]').val());
            if(s >= e){
                errors.push({field:"sh", msg:stringsL10N["Sulkemisajan täytyy olla myöhemmin kuin avaamisajan."]});
            }
           // $('#stand-admin input[name="tags"]').val("");
            
            if(errors.length > 0){
                ////$.log("Form invalid.");
                ////$.log(errors);
                showErrors(errors);
                return false;
            }
            
            //$.log("Form valid.");
             var data = {
                location: urlencode($('#event-form input[name="location"]').val()),
                st: urlencode($('#event-form select[name="starth"]').val()),
                et: urlencode($('#event-form select[name="endh"]').val()),
                desc: urlencode($('#event-form textarea').val()),
                eventId : urlencode($('#event-form input[name="eventId"]').val())
             };
             $.log("Data", data);
            
            $.ajax({
                type: 'POST',
                url: baseUrl+'/data.php?query=adminEvent',
                data: data,
                error: function(jqXHR, textStatus, errorThrown){
                    $.log("There was an error.");
                    $.log(jqXHR);
                    $.log(textStatus);
                    return;
                },
                success: function(data, textStatus, jqXHR){
                    if(data.error !== undefined){
                        alert("Error:"+data.error);
                    }else{
                        $.log(data);
                        if(userEvent != undefined){
                            calendar.removeEvent(userEvent);    
                        }
                        userEvent = data;
                        calendar.addEvent(data);
                    }
                },
                dataType: "json"
            });
            return false;
        }
    });
    </script>
    <!-- Plugin -->
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-collapse.js"></script>
    <script src="<?php echo $config['paths']['base_url']; ?>/bootstrap/js/bootstrap-tooltip.js"></script>
  </body>
</html>