/* 
* Class Device
* Don't use it directly - only via Director
* Pattern Builder
*/

function Device(id,alias,icon,last_loc_time,last_loc_coor) {
/****   variavles ini   ****/
	var dublicate = this;
	var id_ = id;
	var alias_ = alias;
	
	var accelerenable_;
	var batteryenable_;
	
	var icon_ = icon;
	var show_ = false;
	
	var last_location_time_ = last_loc_time;
	var last_location_coor_ = last_loc_coor;
	var last_location_temp_;
	var last_location_speed_;
	var last_location_stat_;
	
	var markers_ =  new Array(); 	/*      array with device markers, each member of array contains object with parametres of device at this time mark     */
	var marker;	
	var history_value_;
	
	var time_intreval_ = null;
	var activity_ = false;
				
/****   set functions   ****/
	this.setID        = function(val) {     id_ = val;              };
	this.setAlias = function(val) { alias_ = val;   };

	this.setAccelerEnable = function(val) { accelerenable_ = val; };
	this.setBatteryEnable = function(val) { batteryenable_ = val; };

	this.setIcon  = function(val) { icon_ = val;    };
	this.setShow  = function(val) { show_ = val;    };

	this.setLastLocationTime  = function(val) { last_location_time_ = val;  };
	this.setLastLocationCoor  = function(val) { last_location_coor_ = val;  };	
	
	this.pushMarker = function(obj) { markers_.push(obj);  };
	this.cleanMarkersArray = function() { markers_ = [];  };
	this.setHistoryValue = function(val) { history_value_ = val;       };

/****   get functions   ****/
	this.getID        = function() {        return id_;    };
	this.getAlias = function() {    return alias_; };       
	
	this.getDeviceIcon  = function() {    return icon_;  };
	
	this.getLastLocationTime  = function() {    return last_location_time_;  };
    this.getLastLocationCoor = function() {	return last_location_coor_;};      
                
	this.getPointLength = function() { return markers_.length;       };
	this.getMarkers_Array = function() { return markers_;       };
	this.getHistoryValue = function() { return history_value_;       };

	this.getAccelerEnable = function() { return accelerenable_; };
	this.getBatteryEnable = function() { return batteryenable_; };

/**** for builder pattern ****/
	this.clear = function() {
		dublicate.setAccelerEnable(undefined);
		dublicate.setBatteryEnable(undefined);
        };

	this.visible = function() { return show_; };
	this.activity = function() { return activity_; };
}

/**
 * Is this layer visible?
 *
 * Returns visibility setting
 *
 * @return {Boolean} Visible
 */


/**
 * Device Builder pattern
 */
function DeviceBuilder(id,alias,icon,show,time) {
        var device = new Device(id,alias,icon,show,time);

        this.getDevice = function() {
                return device;
        }

        this.createNewDevice = function() {
                device.clear();
        };

        this.buildAcceler = function(val) { };
        this.buildBattery = function(val) { };
}


//ConcreteBuilders
function CarHideTracker(id,alias,icon,show,time) {
        DeviceBuilder.call(this,id,alias,icon,show,time);

        var device = this.getDevice(); //protected imitation

        this.buildAcceler = function() { device.setAccelerEnable(true); };
        this.buildBattery = function() { device.setBatteryEnable(false); };
}

function PetTracker(id,alias,icon,show,time) {
        DeviceBuilder.call(this,id,alias,icon,show,time);

        var device = this.getDevice();

        this.buildAcceler = function() { device.setAccelerEnable(false); };
        this.buildBattery = function() { device.setBatteryEnable(true); };
}


//Director
function Director() {
        var deviceBuilder;

        this.setDeviceBuilder = function(builder) {
                deviceBuilder = builder;
        };

        this.getDevice = function() {
                return deviceBuilder.getDevice();
        };

        this.constructDevice = function() {
                deviceBuilder.createNewDevice();
                deviceBuilder.buildAcceler();
                deviceBuilder.buildBattery();
        };
}


/* Find devices in array by id */
function findById(source, id) {
    return source.filter(function( obj ) {
        // return +obj.id_ === +id_;
        return (obj.getID() == id);
    })[ 0 ];
}