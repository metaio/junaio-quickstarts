var jAnalytics = undefined;

arel.sceneReady(function()
{
	//Just for Debuging purposes
	//arel.Debug.activate();
	//arel.Debug.deactivateArelLogStream();
	
	//get the metaio man model reference
	var customPoi = arel.Scene.getObject("4");
	
	//set a listener on the metaio man
	arel.Events.setListener(customPoi, function(obj, type, params){handleCustomPoiEvent(obj, type, params);});
	
	//init the analytics plugin
	jAnalytics = new arel.Plugin.Analytics (analyticsUser, arel.Plugin.Analytics.EventSampling.ALTERNATE);
	
	//log the channel opening
	//jAnalytics.logSceneReady(arel.Scene.getID());
	
});

function handleCustomPoiEvent(obj, type, param)
{
	//check if there is tracking information available
	if(type && type === arel.Events.Object.ONTOUCHSTARTED)
	{
		//track the event
		jAnalytics.logObjectInteraction(arel.Plugin.Analytics.Action.TOUCHSTARTED, obj.getID());
		
		$('#info .text').html(obj.getParameter("description"));
		$('#info .buttons').html("<div class=\"button\" onclick=\"openWebsite('" + obj.getParameter("url") + "')\">" + obj.getParameter("url") + "</div>");
		$('#info').show();
	}	
};

function openWebsite(url)
{
	jAnalytics.logWebsite(url);
	arel.Media.openWebsite(url);
};

function startVideo(url)
{
	jAnalytics.logVideo(url);
	arel.Media.startVideo(url);
}

function openImage(url)
{
	jAnalytics.logImage(url);
	arel.Media.openWebsite(url);
}

function startSound(url)
{
	jAnalytics.logSound(url);
	arel.Media.startSound(url);
}
