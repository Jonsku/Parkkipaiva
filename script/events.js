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

function EventCalendar(){
    EventTarget.call(this);
}

EventCalendar.prototype = new EventTarget();
EventCalendar.prototype.constructor = EventCalendar;
EventCalendar.prototype.start_hour = 15;
EventCalendar.prototype.end_hour = 21;
EventCalendar.prototype.view = undefined;
EventCalendar.prototype.calendar = new Object;
EventCalendar.prototype.clickCb = undefined;

EventCalendar.prototype.clickCellCallback = function(func){
    this.clickCb = func;
}

EventCalendar.prototype.setTimesAndView = function(start, end, elem){
    this.start_hour = start;
    this.end_hour = end;
    this.view = elem;
    this.calendar = new Object;
    for(var i=this.start_hour; i<this.end_hour; i++){
        this.calendar[i] = new Array();
    }
}

EventCalendar.prototype.loadEvents = function(){
    var me = this;
    $.ajax({
        type: 'POST',
        url: baseUrl+'/data.php?query=getAll',
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
              for(var i in data){
               me.addEvent(data[i]);
              }
                
            }
        },
        dataType: "json"
    });
}

EventCalendar.prototype.addEvent = function(e){
    for(var i = e.start_hour;i<e.end_hour; i++){
        this.calendar[i].push(e);
        this.calendar[i].sort(sortfunction);
        this.refreshView(i);
    }
}

EventCalendar.prototype.removeEvent = function(e){
    for(var i = e.start_hour;i<e.end_hour; i++){
        var j = 0;
        while( this.calendar[i][j].location != e.location ){
            j++;
        }
        this.calendar[i].splice(j,1);
        $.log("Found at:" + j );
        //this.calendar[i].push(e);
        //this.calendar[i].sort(sortfunction);
        this.refreshView(i);
    }
}

EventCalendar.prototype.refreshView = function(time){
    //find which column needs to be updated
    var column = time-this.start_hour;
    var rows = this.view.find("tr");
    
    //add more rows if needed (-1 is to accoutn for the header)
    if(this.calendar[time].length > rows.length-1){
        for(var i=0;i<this.calendar[time].length-rows.length+1;i++){
            this.addRow();
        }
    }
    var rows = this.view.find("tr");
    var me = this;
    //update rows
    for(var i=1;i<rows.length;i++){
        if( i <= this.calendar[time].length ){
            var theEvent = this.calendar[time][i-1];
            var theCell = $($(rows[i]).find("td")[column]);
            theCell.empty().append('<span class="dot">'+theEvent.location+'</span>'+theEvent.description);
            theCell.off('click');
            theCell.click(function(){
                if(me.clickCb === undefined){
                    return;
                }
                var column = $(this).parent().children().index(this);
                var row = $(this).parent().parent().children().index(this.parentNode);
                var e = me.calendar[Number(me.start_hour+column)][row-1]; //-1 is to account for the header row
                me.clickCb(e);
             });
        }else{
            var theCell = $($(rows[i]).find("td")[column]);
            theCell.empty();
        }
    }
    
}

EventCalendar.prototype.addRow = function(){
    var row = "<tr>";
    for(var i=0;i<this.end_hour-this.start_hour;i++){
        row += "<td></td>";
    }
    row += "</tr>";
    this.view.append(row);
    
}

//takes event instances as params
function sortfunction(a, b){
    return (a.location - b.location) //causes the array to be sorted by ascending location
}
