/*
 * Base tests - constructor checking
 */
DeviceManager_Base = TestCase("DeviceManager_Base");

DeviceManager_Base.prototype.testBuildPatternCar = function() {
	var dev;
	var director = new Director();

	var carHideTracker = new CarHideTracker(
									2, 
									"cardevice",
									"carIcon",
									false,
									"2012-05-20 23:00:38"
								);
	director.setDeviceBuilder(carHideTracker);
	director.constructDevice();
	dev = director.getDevice();

	/* run tests */
	assertObject(carHideTracker);
	assertObject(director);
	assertObject(dev);
	assertEquals(2, dev.getID());
	assertEquals(false, dev.visible());
	assertEquals("cardevice", dev.getAlias());
	assertEquals("carIcon", dev.getDeviceIcon());
	assertBoolean(dev.getAccelerEnable());
	assertEquals(true, dev.getAccelerEnable());
	assertEquals(false, dev.getBatteryEnable());
};

DeviceManager_Base.prototype.testBuildPatternCat = function() {

/* set up devices */ 
	var dev;
	var director = new Director();

	var petTracker = new PetTracker(
								12, 
								"petdevice", 
								"petIcon", 
								true, 
								"2011-01-11 02:32:00"
							);
	director.setDeviceBuilder(petTracker);
	director.constructDevice();
	dev = director.getDevice();

/* run tests */
	assertObject(dev);
	assertEquals(12, dev.getID());
	assertEquals(true, dev.visible());
	assertBoolean(dev.getAccelerEnable());
	assertEquals("petIcon", dev.getDeviceIcon());
	assertEquals(false, dev.getAccelerEnable());
	assertEquals(true, dev.getBatteryEnable());
};

DeviceManager_Base.prototype.testBehaveFunctions = function() {
	var dev;
	var director = new Director;

	var carHideTracker = new CarHideTracker(
								2, 
								"cardevice",
								"carIcon",
								false,
								"2012-05-20 23:00:38"
							);

	director.setDeviceBuilder(carHideTracker);
	director.constructDevice();
	dev = director.getDevice();

	/* code here */
};


DeviceManager_Base.prototype.testFindDevicesByParam = function() {
	var dev;
	var director = new Director;
	var array = [];

	var carTracker = new CarHideTracker(2, "my car", "carIcon", false, "2012-05-20 23:00:38");
	director.setDeviceBuilder(carTracker);
	director.constructDevice();
	dev = director.getDevice();
	array.push(dev);

	var bikeTracker = new CarHideTracker(33, "my bike", "bikeIcon", false, "2011-15-22 23:00:00");
	director.setDeviceBuilder(bikeTracker);
	director.constructDevice();
	dev = director.getDevice();
	array.push(dev);

	var grandTracker = new PetTracker(33, "grandmom", "personIcon", false, "2009-01-13 02:17:00");
	director.setDeviceBuilder(grandTracker);
	director.constructDevice();
	dev = director.getDevice();
	array.push(dev);

	var petTracker = new PetTracker(15, "my cat", "bikeIcon", false, "2010-25-01 12:00:00");
	director.setDeviceBuilder(petTracker);
	director.constructDevice();
	dev = director.getDevice();
	array.push(dev);

	assertObject(array[0]);
	assertEquals(4, array.length);

	assertEquals(15, array[3].getID());

	var mainObj = findById( array, 15 );
	assertEquals(15, mainObj.getID());

	assertEquals('my cat', mainObj.getAlias());
};


/* 
 *	Advanced tests
 */
DeviceManager_Advanced = TestCase("DeviceManager_Advanced");

/* Create 10 devices */
DeviceManager_Advanced.prototype.setUp = function() {
	var dev;
	var director = new Director();
	var arrayDevices = [];

	for (var i = 0; i < 10; ++i)
	{
		var carTracker = new CarHideTracker(i, "car"+i, "carIcon"+i, false, "2012-05-20 23:00:38");
		director.setDeviceBuilder(carTracker);
		director.constructDevice();
		dev = director.getDevice();
		arrayDevices.push(dev);
	}

	/* Add PointLocations */
	// for (var i = 0; i < 15; ++i)
	// {
		
	// 	// arrayDevices[0].setPoint
	// }
};


DeviceManager_Advanced.prototype.testLocationPointArray = function() {
	// var 
};

DeviceManager_Advanced.prototype.testRemoveLocatiuonPointArray = function() {
	// var 
};


/* Remove all devices */
DeviceManager_Advanced.prototype.tearDown = function() {

};


