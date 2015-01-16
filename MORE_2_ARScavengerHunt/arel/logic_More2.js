var oTreasureHunt;

arel.sceneReady(function()
{
	//Just for Debuging purposes
	//arel.Debug.activate();
	//arel.Debug.deactivateArelLogStream();
	
	oTreasureHunt = new TreasureHunt();		
});

function TreasureHunt()
{
	//the tracking configuration needed to scan an image
	this.trackingConfiguration = "http://dev.junaio.com/publisherDownload/tutorial/tracking_tutorial.zip";
	//are we currently in GPS mode
	this.currentlyGPS = true;
	//what is the current location
	this.currentLocation = undefined;
	
	
	this.switchMode = function()
	{
		//switch to scanning something
		if(this.currentlyGPS)
		{
			var that = this;
			
			//get the current Location -> we need to know which one of the treasures we might have found
			//all the other setting are done upon receiving the location;
			arel.Scene.getLocation(function(lla){that.receiveLocation(lla);});					
			
		}
		else //switch back to GPS
		{
			//remove the tracking listener
			arel.Events.removeListener(arel.Scene);
			
			//change the tracking configuration
			arel.Scene.setTrackingConfiguration(arel.Tracking.GPS);
			this.currentlyGPS = true;
			
			//hide all objects
			var aAllObjects = arel.Scene.getObjects();
			
			for(var i in aAllObjects)
			{
				if(typeof(aAllObjects[i]) !== "function")
				{
					aAllObjects[i].setVisibility(true, true, true);
				}
			};
			
			//update the button
			$('#scan').html("Scan Treasure");	
			$('#info').html("Look for treasures. When you are close, scan it!");
		}
	};
	
	this.receiveLocation = function(lla)
	{
		var that = this;
		
		//get the location
		this.currentLocation = lla;
		
		//set the tracking
		arel.Scene.setTrackingConfiguration(this.trackingConfiguration);
		this.currentlyGPS = false;
		
		//hide all objects
		var aAllObjects = arel.Scene.getObjects();
		
		//make the location based POIs invisible
		for(var i in aAllObjects)
		{
			if(typeof(aAllObjects[i]) !== "function")
			{
				aAllObjects[i].setVisibility(false, false, false);
			}
		};
		
		//update the button
		$('#scan').html("Find Treasure");
		$('#info').html("Give it a shot. You think you have found something?");
		
		//set a Listener to the scene
		arel.Events.setListener(arel.Scene, function(type, param){that.trackingHandler(type, param);});
	};
	
	this.trackingHandler = function(type, param)
	{
		if(param[0] !== undefined)
		{
			//if the pattern is found, hide the information to hold your phone over the pattern
			//of course this is dummy finding information
			if(type && type == arel.Events.Scene.ONTRACKING && param[0].getState() == arel.Tracking.STATE_TRACKING)
			{
				//check if the user is roughly at a location where there is a treasure
				var aAllObjects = arel.Scene.getObjects();
				var objectFound = undefined;
				
				for(var i in aAllObjects)
				{
					if(typeof(aAllObjects[i]) !== "function")
					{
						var objectLocation = aAllObjects[i].getLocation();
						
						if(Math.abs(objectLocation.getLatitude() - this.currentLocation.getLatitude()) < 0.01 &&
						   Math.abs(objectLocation.getLongitude() - this.currentLocation.getLongitude()) < 0.01)
							{
								objectFound = aAllObjects[i];
								break;
							}
							
					}
				};
				
				//the user was close to a treasure
				if(objectFound !== undefined)
					$('#info').html("You found " + objectFound.getTitle() + ". <br /><br /><img src='thumb.png' />");
				else
					$('#info').html("You are at an invalid location. Are you trying to cheat?");
			
			}
		}
	};
}

function switchMode(button)
{
	button.style.backgroundColor='#fff';
	
	oTreasureHunt.switchMode();	
}