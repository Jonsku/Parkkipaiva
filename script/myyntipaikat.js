//execute when DOM is ready
$(function() {
    createHoursMinutesSelect();
    $("#event-form #sh option").last().remove();
    $("#event-form #eh option").first().remove();
    updateLocationsSelect();
    initializeMap();
});

function locationsLoaded(){
    $.log("Locations loaded");
}

/* ------------------------- */
/* ------------------------- */
//handle fb events
function loggedIn(){
    sessionStatus = true;
    
    resetLoginForm();
    $("#login-text").text(stringsL10N["Myyntipaikkasi tiedot ja suosikkipaikkasi"]);
    $("#event-editing").show();

  //get user's stands, if any
  $.ajax({
      type: 'POST',
      url: baseUrl+'/data.php?query=get',
      success: function(data, textStatus, jqXHR){
        $.log("Get:", data);
          if(data.event){
            $.log("My event:", data.event);
            updateEvent(data.event); //update stands array
          }
      },
      dataType: "json"
  });
  
  $('#logout-btn').show();
  $('.login-btn').hide();
}

function loggedOut(){
   // return;
    //$.log("User logged out.");
    sessionStatus = false;
    $("#event-editing").hide();
    
    
    $('#logout-btn').hide();
    $('.login-btn').show();
    $("#login-text").text(stringsL10N["Kirjaudu sisään Facebook-tunnuksillasi"]);
}

//
function showAdmin(show){
    if(uStand){
        $.log("Show admin: ", uStand);
        uStand.setDraggable(show);
        if(show){
            uStand.listenerMUP = google.maps.event.addListener(uStand, 'mouseup', function() {
                markerReleased();
            });
            /* Show data in fields */
            $('#stand-admin input[name="address"]').val(uStand.data.address);
            $('#stand-admin input[name="city"]').val(uStand.data.city);
            $('#stand-admin select[name="sh"]').val(uStand.data.start_hour);
            $('#stand-admin select[name="sm"]').val(uStand.data.start_minute);
            $('#stand-admin select[name="eh"]').val(uStand.data.end_hour);
            $('#stand-admin select[name="em"]').val(uStand.data.end_minute);
            $('#stand-admin textarea').val(uStand.data.description);
            
            $('#stand-admin .modify').show();
            $('#stand-admin .modify h2').text(stringsL10N["Muokkaa kierrätyspistettäsi"]);
            $('#stand-admin .create').hide();
            $('#stand-admin #validate').text($('#stand-admin .modify b').text());
            
        }else if(uStand.listenerMUP){
            google.maps.event.removeListener(uStand.listenerMUP);
            uStand.listenerMUP = null;
        }
    }else if(show){
        clearAdmin();
        $("#stand-admin h2").text("Create a stand");
        $('#stand-admin .modify').hide();
        $('#stand-admin .create').show();
        $('#stand-admin #validate').text($('#stand-admin .create b').text());
    }
    $("#stand-admin").toggle(show);

    if(!show){
        var wasCreateForm = $('#stand-admin .create').is(':visible');
        $("#create-stand-btn").toggle( $("#user-stands .stands li[name]").length < 1 ); //hide or show create stand button
        
        if( uStand ){
            $("#stand-info").toggle(true);
            refreshStandInfo(uStand.data);
            uStand.setPosition( new google.maps.LatLng(uStand.data.u, uStand.data.v) );
        }else{
             $("#stand-info").hide();
        }
    }
}


function updateEvent(e){
    $("#event-form textarea").val(e.description);
    $("#event-form #sh").val(e.sh);
    $("#event-form #eh").val(e.eh);
    $("#event-form #location").val(e.location);
    updateLocationsSelect();
}

/* clear stand creation form */
function clearAdmin(){
   
    $('#stand-admin input[name="address"]').val("");
    $('#stand-admin input[name="city"]').val("");
    $('#stand-admin input[name="u"]').val("");
    $('#stand-admin input[name="v"]').val("");
    $('#stand-admin select[name="sh"]').val(8);
    $('#stand-admin select[name="sm"]').val(0);
    $('#stand-admin select[name="eh"]').val(16);
    $('#stand-admin select[name="em"]').val(0);
    $('#stand-admin textarea').val("");
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


/* delete stand */
$('#info-btn .delete').click(function(){
    $('#info-btn').hide(300);
    $('#confirm-delete').show();
    return false;
});

$('#confirm-delete .yes').click(function(){
    var data= {
        i: uStand.data.id
     };
    $.log("Destroy stand: ",data.i);
    $.ajax({
        type: 'POST',
        url: baseUrl+'/data.php?query=delete',
        dataType: "json",
        
        data: data,
        success: function(data, textStatus, jqXHR){
            if(data.error){
                alert("Error:"+data.error);
            }else{
                //remove the stand from the list
                $('#user-stands .stands li[name="'+uStand.data.id+'"]').remove();
                //delete userStands[uStand.data.id];
                removeStand(uStand.data.id);
                uStand.setMap(null);
                uStand = null;
                uStand = undefined;
                $('#confirm-delete').hide();
                $('#info-btn').show();
                showAdmin(false);
            }
            return;
        }
    });
    return false;
});

$('#confirm-delete .no').click(function(){
    $('#confirm-delete').hide();
    $('#info-btn').show();
    return false;
});

//form validation with jQuery plugin
$("#event-form").validate({
    onfocusout: false, 
    onkeyup: false, 
    onclick: false, 
    rules: {
        location: { required: true },
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
                    showError( element.attr('id') != 'location' ? element.attr('id') : 'address', element.attr('id') != 'location' ? error.text() :  stringsL10N["Please select a location."]);
                    return true;
                },
    submitHandler: function(form){
        /* Validate form */
        var errors = new Array();
        var s = parseInt($('#event-form select[name="sh"]').val());
        var e = parseInt($('#event-form select[name="eh"]').val());
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
            st: urlencode($('#event-form select[name="sh"]').val()),
            et: urlencode($('#event-form select[name="eh"]').val()),
            desc: urlencode($('#event-form textarea').val())
         };
         $.log("Data", data);
        
        $.ajax({
            type: 'POST',
            url: baseUrl+'/data.php?query=add',
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
                }
            },
            dataType: "json"
        });
        return false;
    }
});

$('#stand-admin #validate').click(function(){
    hideError($('#stand-admin'));
});

$("#event-form #sh").change(function(){
    $.log("Start hour changed");
    var startHour = $(this).val();
    var endHour = $("#event-form #eh").val();
    if(startHour >= endHour){
        $("#event-form #eh").val(Number(startHour)+1).change();
    }
    updateLocationsSelect();
});

$("#event-form #eh").change(function(){
    $.log("End hour changed");
    var startHour = $("#event-form #sh").val();
    var endHour = $(this).val();
    if(endHour <= startHour){
        $("#event-form #sh").val(Number(endHour)-1).change();
    }
    updateLocationsSelect();
})

function updateLocationsSelect(){
    var data = {start: $("#event-form #sh").val(), end: $("#event-form #eh").val()};
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