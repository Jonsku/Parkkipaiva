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
Stand.prototype.label = "";
Stand.prototype.description = "";
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
            data: {id : this.id, x : this.x, y : this.y, type: this.type, start: this.start_hour, end: this.end_hour, label: this.label, description: this.description},
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
        data: {id : this.id, x : this.x, y : this.y, type: this.type, start: this.start_hour, end: this.end_hour, label: this.label, description: this.description},
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
                }else if(data.hasOwnProperty('not_empty')){
                    alert("This spot can not be deleted because they are "+data['not_empty']+" event(s) taking place there.\n Please remove the events before deleting the spot.");
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
                $.log("Fetched location:", data);
                for(var i in data.success){
                    data.success[i].x = data.success[i].x.replace(/[^\d.]/g, '');
                    data.success[i].y = data.success[i].y.replace(/[^\d.]/g, '');
                    var newStand = new Stand();
                    newStand.id = data.success[i].id;
                    newStand.x = data.success[i].x + "%";
                    newStand.y = data.success[i].y + "%";
                    newStand.type = data.success[i].type;
                    newStand.start_hour = data.success[i].start_hour;
                    newStand.end_hour = data.success[i].end_hour;
                    newStand.label = data.success[i].label;
                    newStand.description = data.success[i].description;
                    newStand.appendToMap();
                    StandsList[newStand.id] = newStand;
                }
                locationsLoaded();
            }
        },
        dataType: "json"
    });
}