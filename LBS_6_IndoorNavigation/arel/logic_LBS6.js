// if the scene is ready start your javascript
arel.sceneReady(function() {
	//Just for Debuging purposes
	//arel.Debug.activate();
	//arel.Debug.deactivateArelLogStream();
	
	//add listener to the scene 
	arel.Events.setListener(arel.Scene, function(type, trackingInformation) {handleSceneEvents(type, trackingInformation);});
	
	//show the command to scan something
	$('.llaInfo').show();
});

function handleSceneEvents(type, trackingInformation)
{
	if(type && type == arel.Events.Scene.ONTRACKING && trackingInformation[0].getType() == arel.Tracking.LLA_MARKER && trackingInformation[0].getCoordinateSystemID() == 2)
	{
		//override the GPS of the device with the location encoded in the marker
		var lla = trackingInformation[0].getContent();
		arel.Scene.setLocation(lla);
		
		//remove the scan information
		$('.llaInfo').hide();
		
		//set the POIs visible
		arel.Scene.getObject("arrow1").setVisibility(true, true, true);
		arel.Scene.getObject("arrow2").setVisibility(true, true, true);
		arel.Scene.getObject("conf1").setVisibility(true, true, true);	
	}
}