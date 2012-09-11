/* Add Custom Event Functionalities */
function EventTarget(){
    this._listeners = {};
}

EventTarget.prototype = {

    constructor: EventTarget,

    addListener: function(type, listener){
        if (typeof this._listeners[type] == "undefined"){
            this._listeners[type] = [];
        }

        this._listeners[type].push(listener);
    },

    fire: function(event){
        if (typeof event == "string"){
            event = { type: event };
        }
        if (!event.target){
            event.target = this;
        }

        if (!event.type){  //falsy
            throw new Error("Event object missing 'type' property.");
        }

        if (this._listeners[event.type] instanceof Array){
            var listeners = this._listeners[event.type];
            //$.log("event",event.type, this);
            for (var i=0, len=listeners.length; i < len; i++){
                listeners[i].call(this, this, event);
            }
        }
    },

    removeListener: function(type, listener){
        if (this._listeners[type] instanceof Array){
            var listeners = this._listeners[type];
            for (var i=0, len=listeners.length; i < len; i++){
                if (listeners[i] === listener){
                    listeners.splice(i, 1);
                    break;
                }
            }
        }
    }
};

/* Stand Class */
var StandTypes = new Array("single","double","special");

function Stand(){
    EventTarget.call(this);
}



Stand.prototype = new EventTarget();
Stand.prototype.constructor = Stand;
Stand.prototype.x = "50%";
Stand.prototype.y = "50%";
Stand.prototype.id = 0;
Stand.prototype.type = 0;
Stand.prototype.start_hour = 14;
Stand.prototype.end_hour = 19;
Stand.prototype.edit = false;
Stand.prototype.elem = undefined;

Stand.prototype.appendToMap = function(){
    $.log(this.type, StandTypes[this.type]);
    this.elem = $('<div class="locationIcon '+StandTypes[this.type]+'" id="location_'+this.id+'"><img src="'+baseUrl+'/images/location_'+StandTypes[this.type]+'.png"><i>'+this.id+'</i></div>');
    $("#map_canvas").append(this.elem);
    this.elem.css('top', this.y).css('left', this.x).css('cursor','pointer');
    var me = this;
    this.elem.click(function(){
        me.fire("clicked");
    });
}

Stand.prototype.select = function(){
    $(".locationIcon.selected").removeClass("selected");
    this.elem.addClass("selected");
}

Stand.prototype.changeType = function(newType){
    
    if(this.elem != undefined){
        this.elem.removeClass(StandTypes[this.type]);
        this.type = newType;
        this.elem.addClass(StandTypes[this.type]);
        this.elem.find("img").attr("src",baseUrl+'/images/location_'+StandTypes[this.type]+'.png');
    }else{
        this.type = newType;
    }
    this.updateData();
}

Stand.prototype.changeHours = function(sH, eH){
    this.start_hour = sH;
    this.end_hour = eH;
    this.updateData();
}

Stand.prototype.toggleEdit = function(){
    if(this.edit){
        //remove draggable
        this.elem.draggable( 'disable' );
        this.elem.css('cursor','pointer');
    }else{
        var me = this;
        this.elem.draggable({
            containment: "#map_canvas",
            stop: function() {
              me.updateLocation();
            }
          });
          this.elem.css('cursor','move');
    }
    this.edit = !this.edit;
}

Stand.prototype.updateLocation = function(){
        //convert to percent
        var mapWidth = 100.0/ Number($("#map_canvas").width());
        var mapHeight = 100.0/  Number($("#map_canvas").height());
        var l = this.elem.css('left');
        var t = this.elem.css('top');
        this.x = Number(l.substring(0,l.length-2)) * mapWidth;
        this.y = Number(t.substring(0,t.length-2)) * mapHeight;
        $.ajax({
            type: 'POST',
            url: baseUrl+'/data.php?query=updatelocation',
            data: {id : this.id, x : this.x, y : this.y, type: this.type, start: this.start_hour, end: this.end_hour},
            success: function(data, textStatus, jqXHR){
                if(data.error){
                    alert("Error:"+data.error);
                }else{
                    $.log("Position updated");
                }
            },
            dataType: "json"
        });
}

Stand.prototype.updateData = function(){
    $.ajax({
        type: 'POST',
        url: baseUrl+'/data.php?query=updatelocation',
        data: {id : this.id, x : this.x, y : this.y, type: this.type, start: this.start_hour, end: this.end_hour},
        success: function(data, textStatus, jqXHR){
            if(data.error){
                alert("Error:"+data.error);
            }else{
                $.log("Data updated");
            }
        },
        dataType: "json"
    });
}

Stand.prototype.destroy = function(){
        $.ajax({
            type: 'POST',
            url: baseUrl+'/data.php?query=deletelocation',
            data: {id : this.id},
            success: function(data, textStatus, jqXHR){
                if(data.error){
                    alert("Error:"+data.error);
                }else{
                    $.log("Slot deleted");
                    StandsList[data.success].fire("destroyed");
                    StandsList[data.success].elem.remove();
                    StandsList[data.success] = undefined;
                }
            },
            dataType: "json"
        });
}

var StandsList = new Object;



function initializeMap(){
    $.ajax({
        type: 'POST',
        url: baseUrl+'/data.php?query=loadlocations',
        success: function(data, textStatus, jqXHR){
            if(data.error){
                alert("Error:"+data.error);
            }else{
                $.log("Fetched location:", data.success.length);
                for(var i in data.success){
                    var newStand = new Stand();
                    newStand.id = data.success[i].id;
                    newStand.x = data.success[i].x + "%";
                    newStand.y = data.success[i].y + "%";
                    newStand.type = data.success[i].type;
                    newStand.start_hour = data.success[i].start_hour;
                    newStand.end_hour = data.success[i].end_hour;
                    newStand.appendToMap();
                    StandsList[newStand.id] = newStand;
                }
                locationsLoaded();
            }
        },
        dataType: "json"
    });
}
/*
        var elem = addLocation(true);
        var newLoc = {
          dom : elem,
          id : elem.attr('id').split("_")[1]
        }
        return newLoc;
      }
      
      function appendLocation(location){
        var elem = addLocation(true, location.x, location.y, location.id);
        var newLoc = {
          dom : elem,
          id : elem.attr('id').split("_")[1]
        }
        return newLoc;
      }
      
      function addLocation(edit, l, t, n){
        var num = n ? n : $("#map_canvas .locationIcon").length + 1;
        var button = $('<div class="locationIcon" id="location_'+num+'"><img src="<?php echo $config['paths']['base_url']; ?>/img/location_icon.png"><i>'+num+'</i></div>');
        $("#map_canvas").append(button);
        if(!l){
          l = '50%';
        }
        if(!t){
          t = '50%';
        }
        button.css('top', t).css('left', l).css('cursor','pointer');
        if(edit){
          button.draggable({
            containment: "#map_canvas",
            stop: function() {
              updateLocationPosition(num);
            }
          });
          button.css('cursor','move');
        }
        return button;
      }
      
      function updateLocationPosition(num){
        //convert to percent
        var mapWidth = 100.0/ Number($("#map_canvas").width());
        var mapHeight = 100.0/  Number($("#map_canvas").height());
        var l = $("#location_"+num).css('left');
        var t = $("#location_"+num).css('top');
        var percX = Number(l.substring(0,l.length-2)) * mapWidth;
        var percY = Number(t.substring(0,t.length-2)) * mapHeight;
        console.log(percX, percY);
        $("#location_"+num).css('left',percX+'%');
        $("#location_"+num).css('top',percY+'%');
        return true;
      }


Stand.prototype.dontFilter = false;

Stand.prototype.markAsUserStand = function(){
    if(this.dontFilter)
     return false;

    changeMarkerIcon(this.marker, "stand_edit");
    this.dontFilter = true;
    return true;
};

Stand.prototype.unmarkAsUserStand = function(){
    if(!this.dontFilter)
        return false;
    changeMarkerIcon(this.marker, "stand");
    this.dontFilter = false;
    return true;
};

// Filter/unfilter stand based on criteria
Stand.prototype.filter = function(on){
    //if(this.dontFilter)
    //$.log("Filter: ",on && !this.dontFilter);
    this.marker.setVisible(!on || this.dontFilter);
    if(on && this.marker.iWin == openedWindow){
            openedWindow.close();
            openedWindow = undefined;
    }
    this.fire(on && !this.dontFilter ? "filter" : "unfilter");
};

function createStand(data, editMode){
        var p = new google.maps.LatLng(data.u, data.v);
        
        var newStand = new Stand();
        newStand.data = data;
        var markerName = "stand";
        markerName += editMode ? "_edit" : "";
        //$.log("MarkerName", markerName);
        newStand.marker = setMarkers(map, p, markerName);
        google.maps.event.addListener(newStand.marker, 'dblclick', function(){
            focusOnStand(this);
        });
        
        newStand.addListener("bookmark",function(stand){
                if(typeof bookmark == 'function') { 
                        bookmark(stand); 
                }
        });
        newStand.addListener("unmark",function(stand){
                if(typeof unmark == 'function') { 
                        unmark(stand); 
                }
        });
        
        newStand.addListener("filter",function(stand){
            stand.marker.setVisible(false); 
        });
        newStand.addListener("unfilter",function(stand){
            stand.marker.setVisible(true);
        });
        
        var timeString = "Avoinna "+data.start_hour+":"+data.start_minute+" - "+data.end_hour+":"+data.end_minute;
        var standInfo = '<div class="iWin" data-stand="'+data.id+'"><p>'+data.address+'</p><p>'+timeString+'</p>';
        standInfo += '<pre>'+data.description+'</pre>';
        standInfo += '</div>';
        
        //create google map info window for the stand
        newStand.marker.iWin = new google.maps.InfoWindow({
            content: standInfo,
            maxWidth: 400
        });
        //Requirement:only one window opened at a time
        //content_changed
        newStand.iWinListener = google.maps.event.addListener(newStand.marker, 'click', function(){ //open info window on click on marker
                //$.log("Open win", this.iWin, on ? "This is a bookmarked stand" : "Not a bookmarked stand");
                if(openedWindow){
                    openedWindow.close();
                }
                openedWindow = this.iWin;
                openedWindow.open(map, this);
        });
        
        newStand.iWinListener = google.maps.event.addListener(newStand.marker.iWin, 'domready', function(){
            var myStand = loadedStands[$(this.getContent()).attr('data-stand')];
            //set bookmark button text and behaviour
            $('button.bookmark').text(myStand.isBookmarked ? stringsL10N["Poista suosikeistasi"] : stringsL10N["Lisää suosikkeihin"]).off('click').click(function(){
                    var myStand = loadedStands[$(this).parent().attr("data-stand")];
                    myStand.bookmark(!myStand.isBookmarked);
                    return false;
                });
        });
        
        google.maps.event.addListener(newStand.marker.iWin, 'closeclick', function(){ //unregister the window has being opened
            openedWindow = undefined;
        });
        
        newStand.marker.setTitle(data.address+"\n"+timeString+"\n"+data.description);
        newStand.data.start_time = hourMinutesToMinutes(Number(data.start_hour),Number(data.start_minute));
        newStand.data.end_time = hourMinutesToMinutes(Number(data.end_hour),Number(data.end_minute));
        return newStand;
}



var cities = new Object; //list of cities
var loadedStands = new Object; //list of loaded stands
var openedWindow = undefined;


var markerImgPath = baseUrl+"/img/";
var mapSprites = new Object;
var map = undefined;
var geocoder = undefined;
var marker = undefined;
var lastBounds = undefined;
var lastZoom = undefined;

//at what zoom level the stands are shown
var zoomBoundary = 9;

$("#stands_list").tablesorter({
        headers: {
                    // disable sort on tags
                    4: { sorter: false },
                    //the bookmark column
                    5: { sorter: false }
        }
}); 

function initializeMap() {
        var myOptions = {
          center: new google.maps.LatLng(60.169845, 24.93855080000003), //helsinki by default
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);
        
        //GEOCODER
        geocoder = new google.maps.Geocoder();
        
        //MAP SPRITES MAPPING
        mapSprites["recycle"] = {
            size: new google.maps.Size(20, 32),
            origin: new google.maps.Point(0,32),
            anchor: new google.maps.Point(10, 32)
        };
        
        mapSprites["recycle_edit"] = {
            size: new google.maps.Size(20, 32),
            origin: new google.maps.Point(20,32),
            anchor: new google.maps.Point(10, 32)
        };
        
        mapSprites["stand"] = {
            size: new google.maps.Size(20, 32),
            origin: new google.maps.Point(0,0),
            anchor: new google.maps.Point(10, 32)
        };
        
        mapSprites["stand_edit"] = {
            size: new google.maps.Size(20, 32),
            origin: new google.maps.Point(20,0),
            anchor: new google.maps.Point(10, 32)
        };
        
        mapSprites["shadow"] = {
            size: new google.maps.Size(34, 21),
            origin: new google.maps.Point(40,0),
            anchor: new google.maps.Point(4, 19)
        };
      
        
        google.maps.event.addListener(map, 'bounds_changed', function() {
                ////$.log("Bounds have changed");
                var bounds = map.getBounds();
                refreshStands(bounds.getSouthWest().lat(), bounds.getNorthEast().lat(), bounds.getSouthWest().lng(), bounds.getNorthEast().lng());
                lastBounds = bounds;
                lastZoom = map.getZoom();
                return;
        });
        
         google.maps.event.addListener(map, 'dragend', function() {
            //$.log("drag ended");
            loadStandsFromDb();
         });
        
        google.maps.event.addListener(map, 'center_changed', function() {
                
        });
}

//hide/show stands/cities depending on the zoom level
function toggleStandsMarkers(show){
    for(var i in loadedStands){
        loadedStands[i].marker.setVisible( show);
        isFiltered(loadedStands[i]);
    }
}
//zoom_changed

function loadStandsFromDb(){
     var bounds = map.getBounds();
    if(map.getZoom()>zoomBoundary){
                        //extract only the bounds that needs refreshing
                        //var unionBounds = lastBounds.union(bounds);
                        if(lastBounds.intersects(bounds) && !(lastBounds.contains(bounds.getNorthEast()) && lastBounds.contains(bounds.getSouthWest()))){ //chech if intersect and is not completely contained
                                //the intersecting rectangle
                                var north = Math.min(lastBounds.getNorthEast().lat(), bounds.getNorthEast().lat());
                                var south = Math.max(lastBounds.getSouthWest().lat(), bounds.getSouthWest().lat());
                                var east  = Math.min(lastBounds.getNorthEast().lng(), bounds.getNorthEast().lng());
                                var west  = Math.max(lastBounds.getSouthWest().lng(), bounds.getSouthWest().lng());
                                
                                if(north == lastBounds.getNorthEast().lat()){ //there is a rectangle North
                                        refreshStands(north,  bounds.getNorthEast().lat(), Math.min(bounds.getSouthWest().lng(), lastBounds.getSouthWest().lng()), Math.max(bounds.getNorthEast().lng(), lastBounds.getNorthEast().lng()));
                                }else{ //there is rectangle South
                                        refreshStands(bounds.getSouthWest().lat(), south, Math.min(bounds.getSouthWest().lng(), lastBounds.getSouthWest().lng()), Math.max(bounds.getNorthEast().lng(), lastBounds.getNorthEast().lng()));
                                }
                                
                                if(east == lastBounds.getNorthEast().lng()){ //there is a rectangle east                       
                                        refreshStands(lastBounds.getSouthWest().lat(), lastBounds.getNorthEast().lat(), east, bounds.getNorthEast().lng());
                                }else{ //there is rectangle West
                                        refreshStands(lastBounds.getSouthWest().lat(), lastBounds.getNorthEast().lat(), bounds.getSouthWest().lng(), west);
                                }
                        }else{
                                refreshStands(bounds.getSouthWest().lat(), bounds.getNorthEast().lat(), bounds.getSouthWest().lng(), bounds.getNorthEast().lng(), getCitiesLastUpdate());
                        }
                        lastBounds = bounds;
                }
}

function refreshStands(um, uM, vm, vM, t){
    //var bounds = map.getBounds();
    var data= {
            um: urlencode(um),
            uM: urlencode(uM),
            vm: urlencode(vm),
            vM: urlencode(vM)
    };
    if(t != undefined){
            ////$.log("Refresh");
            data.t = urlencode(t);
    }
    $.ajax({
        type: 'POST',
        url: baseUrl+'/data.php?query=load',
        data: data,
        success: function(data, textStatus, jqXHR){
            if(data.error){
                alert("Error:"+data.error);
            }else{
                $.log("Fetched stands:", data.length);
                updateStands(data);
            }
        },
        dataType: "json"
    });
}

function setMarkers(map, location, marker_name) {
  
    var image = new google.maps.MarkerImage(markerImgPath+'map_sprites.png',
       mapSprites[marker_name].size,
       mapSprites[marker_name].origin,
       mapSprites[marker_name].anchor);
    
    var shadow = new google.maps.MarkerImage(markerImgPath+'map_sprites.png',
       mapSprites['shadow'].size,
       mapSprites['shadow'].origin,
       mapSprites['shadow'].anchor);
  
    var shape = {
        coord: [0, 0, 20, 0, 20, 24, 0 , 24],
        type: 'poly'
    };
    var m = new google.maps.Marker({
        position: location,
        map: map,
        shadow: shadow,
        icon: image,
        shape: shape,
        title: "the title",
        zIndex: 1
    });
  return m;
}

function changeMarkerIcon(marker, marker_name){
    var image = new google.maps.MarkerImage(markerImgPath+'map_sprites.png',
       mapSprites[marker_name].size,
       mapSprites[marker_name].origin,
       mapSprites[marker_name].anchor);
    
    var shadow = new google.maps.MarkerImage(markerImgPath+'map_sprites.png',
       mapSprites['shadow'].size,
       mapSprites['shadow'].origin,
       mapSprites['shadow'].anchor);
    
    marker.setIcon(image);
    marker.setShadow(shadow);
              
}

function unmarkAllUserStands(){
    for(var i in loadedStands){
        if(loadedStands[i].unmarkAsUserStand()){
            isFiltered(loadedStands[i]);
        }
    }
}

function geocode(address, callback){
    geocoder.geocode( {'address': address }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            callback(results);
        }
    });
}

function reverseGeocode(lat, lng, callback){
        var latlng = new google.maps.LatLng(lat, lng);
        geocoder.geocode({'latLng': latlng}, function(results, status) {
            $.log("geocoder.geocode", results, status);
            if (status == google.maps.GeocoderStatus.OK && results[0]) {
                callback(results);
            }
        });
}

function panAndZoomTo(position){
    map.panTo(position);
    map.setZoom(16);
}

function goTo(bounds){
    map.fitBounds(bounds);
    var newBounds = map.getBounds();
    refreshStands(newBounds.getSouthWest().lat(), newBounds.getNorthEast().lat(), newBounds.getSouthWest().lng(), newBounds.getNorthEast().lng());
}

function getStreetAddress(geolocations){
        ////$.debug("Get street address:");
        for(var j in geolocations){
            
                var arr = jQuery.grep(geolocations[j].types, function(n, i){
                    return (n == "street_address" || n == "route" || n == "intersection");
                });
                
                if(arr.length > 0 && (geolocations[j].geometry.location_type === google.maps.GeocoderLocationType.ROOFTOP || geolocations[j].geometry.location_type === google.maps.GeocoderLocationType.RANGE_INTERPOLATED) ){
                    return geolocations[j];   
                }
        }
        return geolocations[0];
}

function getCityFromAddress(address){
    for(var j in address.address_components){
        var arr = jQuery.grep(address.address_components[j].types, function(n, i){
            return (n == "administrative_area_level_3");
        });
        if(arr.length > 0){
            return address.address_components[j].short_name;
        }
    }
    return -1;
}


function getPostCode(stand){
        var rxPattern = /[0-9]{5}/;
        var postCode = stand.address.match(rxPattern);
        return postCode != null ? postCode : "-";
}


function removeStand(id){
    delete loadedStands[id];
}




function updateStands(data){
    //$.log("updateStands");
    var city = undefined;
    var newlyLoaded = new Array();
    for(var i in data){
        //padding
        data[i].start_hour = pad(data[i].start_hour, 2);
        data[i].start_minute = pad(data[i].start_minute, 2);
        data[i].end_hour = pad(data[i].end_hour, 2);
        data[i].end_minute = pad(data[i].end_minute, 2);
        if(data[i].tags === ""){
            data[i].tags = "n"; //n = others
        }
       
        var p = new google.maps.LatLng(data[i].u, data[i].v);
        
        if(loadedStands[data[i].id] === undefined || loadedStands[data[i].id] === null){ //the stand is not in the list yet
            loadedStands[data[i].id] = createStand(data[i]);
        }else{
            loadedStands[data[i].id].marker.setPosition(p);
        }
        

        var timeString = "Avoinna "+data[i].start_hour+":"+data[i].start_minute+" - "+data[i].end_hour+":"+data[i].end_minute;
        var standInfo = '<div class="iWin" data-stand="'+data[i].id+'"><p>'+data[i].address+'</p><p>'+timeString+'</p>';

        standInfo += '<pre>'+data[i].description+'</pre>';
        standInfo += '<button class="btn-red bookmark">'+stringsL10N["Lisää suosikkeihin"]+'</button>';
        standInfo += '</div>';
        
        //update google map info window for the stand
        loadedStands[data[i].id].marker.iWin.setContent(standInfo);
        loadedStands[data[i].id].marker.setTitle(data[i].address+"\n"+timeString+"\n"+data[i].description);
        
        loadedStands[data[i].id].data = data[i];
        loadedStands[data[i].id].data.start_time = hourMinutesToMinutes(Number(data[i].start_hour),Number(data[i].start_minute));
        loadedStands[data[i].id].data.end_time = hourMinutesToMinutes(Number(data[i].end_hour),Number(data[i].end_minute));
        
        //Add to the list view
        addToListView(loadedStands[data[i].id]);
        //hide/show based on filters
        isFiltered(loadedStands[data[i].id]);
        
        
        newlyLoaded.push(loadedStands[data[i].id]);
    }
    return newlyLoaded;
}



 
function focusOnStand(marker){
    ////$.log("focusOn");
    panAndZoomTo(marker.getPosition());
    google.maps.event.trigger(marker, "click");
}

function moveMapToAddress(geoLocation){
    if(!(geoLocation instanceof Array)){
        marker.setPosition(geoLocation.geometry.location);
        marker.setTitle(geoLocation.formatted_address);   
    }else{
        geoLocation = geoLocation[0];
    }
    $('#geocode-form input[name="address"]').val(geoLocation.formatted_address)
    goTo(geoLocation.geometry.viewport);
}



$('#geocode-form input[type="submit"]').click(function(){
    geocode($('#geocode-form input[name="geocode-address"]').val(), moveMapToAddress);
    return false;
});





//filter by tags
function applyFilter(){
    var filter = $('body').data('filter.tags');

    var start_time = Number($('body').data('filter.start_time'));
    var end_time = Number($('body').data('filter.end_time'));
    
    //hide/show the markers based on the tags
    for(var i in loadedStands){
        var stand = loadedStands[i];
        if(stand.dontShow){ //need to remain hidden, skip
            continue;
        }
        //test if range overlap
        var inTimeRange = ( stand.data.start_time < end_time && stand.data.end_time >= end_time ) || ( stand.data.end_time > start_time && stand.data.end_time <= end_time );
        stand.filter( !inTimeRange );
    }
}

//return true if the marker should be filtered
function isFiltered(stand){
    var start_time = Number($('body').data('filter.start_time'));
    var end_time = Number($('body').data('filter.end_time'));
    
    //$.log("( "+stand.data.start_time +" <= "+ end_time+" && "+stand.data.end_time+" >= "+end_time +") || ( "+stand.data.end_time+" >= "+start_time+" && "+stand.data.end_time+" <= "+end_time+" )");
    //test if range overlap
    var inTimeRange = ( stand.data.start_time <= end_time && stand.data.end_time >= end_time ) || ( stand.data.end_time >= start_time && stand.data.end_time <= end_time );
    stand.filter( !( !(stand.dontShow) && inTimeRange ) );
    //return !(stand.dontShow) && ( inTimeRange && ( filter != "" && patt.test(stand.data.tags) ) );
}


$('#times input[type="radio"]').click(function(){
   if( $(this).attr('value') === "all" ){
    $('body').data('filter.start_time', 0);
    $('body').data('filter.end_time',1440);
    //disable select
    $('#times select.time-filter').attr('disabled', 'true');
   }else if($(this).attr('value') == "now"){
        var dateNow = new Date();
        var h = dateNow.getHours();
        var m = dateNow.getMinutes();
        $('body').data('filter.start_time', hourMinutesToMinutes(h, m) );
        $('body').data('filter.end_time', hourMinutesToMinutes(h+1%23, m) );
   }else{
    $('#times select.time-filter').removeAttr('disabled');
    $('body').data('filter.start_time', hourMinutesToMinutes(Number($('#times select[name="sh"]').val()), Number($('#times select[name="sm"]').val())) );
    $('body').data('filter.end_time', hourMinutesToMinutes(Number($('#times select[name="eh"]').val()), Number($('#times select[name="em"]').val())) );
   }
   applyFilter();
});

$('#times select.time-filter').change(function(){
    var sh = Number( $('#times select[name="sh"]').val() );
    var sm = Number( $('#times select[name="sm"]').val() );
    var eh = Number( $('#times select[name="eh"]').val() );
    var em = Number( $('#times select[name="em"]').val() );
    if( !( eh > sh || (eh == sh && em >= sm) ) ){
        //revert to last value
        $(this).val($(this).data('current'));
    }else if($('#times input[type="radio"]:checked').val() == "other"){
        //do the change
        $(this).data('current', $(this).val());
        $('body').data('filter.start_time', hourMinutesToMinutes(sh, sm) );
        $('body').data('filter.end_time', hourMinutesToMinutes(eh, em) );
        applyFilter();
    }
   return false;
});

$('.scrollable').jScrollPane({autoReinitialise: true});

//register default values
$('#times select.time-filter').each(function(){
    $(this).data('current', $(this).val());
});

//disabled by default
$('#times select.time-filter').attr('disabled', '');

//disable now filter if not siivouspaiva
var dateNow = new Date();
var eventDate = new Date(2012, 08, 08); //!January = 0

if(dateNow.getFullYear() === eventDate.getFullYear() && dateNow.getMonth() === eventDate.getMonth() && dateNow.getDate() === eventDate.getDate()){
    $('#times label.now').show();
}



//manage the list view
function addToListView(stand){
    //$.log("addToListView", stand);
    var listTable = $("#stands_list");
    if( listTable.find("#listStand_"+stand.data.id).length > 0 ){
            //already in the list
            return;
    }
    $("#stands_list").find("tbody").append('<tr id="listStand_'+stand.data.id+'"><td><a href="#" class="showOnMap">'+stand.data.address+'</a></td><td>'+stand.data.start_hour+":"+stand.data.start_minute+'</td><td>'+stand.data.end_hour+":"+stand.data.end_minute+'</td><td>'+getPostCode(stand.data)+'</td><td>'+stand.data.description+'</td></tr>');
        
    //Hide/show based on filters 
    stand.addListener("filter", function(stand){
            $("#listStand_"+stand.data.id).hide();
    });
    stand.addListener("unfilter", function(stand){
            $("#listStand_"+stand.data.id).show();
    });
        
    //Show the stand on the map view
     $("#listStand_"+stand.data.id+" .showOnMap").click(function(){
        //1) Swicth to map
        $(".views.nav-tabs a:first").click();
        //2) Focus on the stand and open info window
        focusOnStand(stand.marker);
        return false;
     });
    
    $("#stands_list").trigger("update");
}

//views tabs
$(".views.nav-tabs a").click(function(){
    //$.log("Switch");
   if( !$(this).parent().hasClass('active') ){
        $("div.map").toggle();
        $("div.list").toggle();
        $(".views.nav-tabs li").removeClass('active');
        $(this).parent().addClass('active');
   }
   //close the info window if we switched to the list
   if($("div.map").is(':visible') && openedWindow){
        openedWindow.close();
   };
   return false;     
});

//initial state
$('body').data('filter.start_time', 0);
$('body').data('filter.end_time',1440);
applyFilter();
 */
