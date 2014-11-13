arel.sceneReady(function()
{
	//Just for Debuging purposes
	//arel.Debug.activate();
	//arel.Debug.deactivateArelLogStream();
	
	//get the metaio man model reference
	var customPoi = arel.Scene.getObject("4");
	//get the sound poi reference
    var soundPoi = arel.Scene.getObject("1");
    //get the call poi reference
    var callPOI = arel.Scene.getObject("5");

	//set a listener on the metaio man
	arel.Events.setListener(customPoi, function(obj, type, params){handleCustomPoiEvent(obj, type, params);});
	//set a listener on the sound poi
	arel.Events.setListener(soundPoi, function(obj, type, params){handlePoiSoundEvent(obj, type, params);});
    //set a listener on the call poi
    arel.Events.setListener(callPOI, function(obj, type, params){handlePoiCallvent(obj, type, params);});
});

function handleCustomPoiEvent(obj, type, param)
{
	//check if there is tracking information available
	if(type && type === arel.Events.Object.ONTOUCHSTARTED)
	{
		$('#info .text').html(obj.getParameter("description"));
		$('#info .buttons').html("<div class=\"button\" onclick=\"arel.Media.openWebsite('" + obj.getParameter("url") + "')\">" + obj.getParameter("url") + "</div>");
		$('#info').show();
	}
};

function handlePoiSoundEvent(obj, type, param)
{
    //check if there is tracking information available
    if(type && type === arel.Events.Object.ONTOUCHENDED)
    {
        arel.Media.startSound("http://dev.junaio.com/publisherDownload/tutorial/test.mp3");
    }
};

function handlePoiCallvent(obj, type, param)
{
    //check if there is tracking information available
    if(type && type === arel.Events.Object.ONTOUCHSTARTED)
    {
        arel.Media.openWebsite("tel:15400546165",true);
    }
};
