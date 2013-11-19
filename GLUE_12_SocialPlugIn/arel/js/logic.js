arel.sceneReady(function()
{	
	
	//set a listener to tracking to get information about when the image is tracked
	arel.Events.setListener(arel.Scene, function(type, param){trackingHandler(type, param);});
	
	//if the user holds the device over the pattern already, when the scene starts
	arel.Scene.getTrackingValues(function(trackingValues){receiveTrackingStatus(trackingValues);});
		
	
	//initialises the Arel-Social-Plugin
	var social = new arel.Plugin.Social();
	
	
	var tweetMessage = "This is a custom Tweet Message!";
	var urlToShare = "http://www.junaio.com";
	var videoId = "vEM_iW9WRUQ";
	
	
	/*
	 * Social-HTML-Overlay-Buttons
	 */
	
	
	// adds a twitter-button to the HTML-Overlay of the channel with a custom tweet-message
	social.addTweetButton(tweetMessage);

	// adds a facebook-share-button to the HTML-Overlay of the channel with a custom urlToShare
	social.addFacebookShareButton(urlToShare);
	
	// adds a google-plus-share-button to the HTML-Overlay of the channel with no custom urlToShare, in this case the url of the current channel is used and will be shared
	social.addGooglePlusShareButton(urlToShare);
	
	// adds a youtube-button to the HTML-Overlay of the channel with no custom urlToWatch, in this case a default url will be used
	social.addYouTubeButton();
	
	
	
	/*
	 * Social-Glue-Buttons
	 */
	
	/**
	To creat e social Model3d button you have to specify an object-id and the coordinatesystemid of your tracking-pattern
	In addition you can specifiy a third parameter with the following structur:
	
	
	var model3dOptionalParams =
	{
		customSocialParam : "text", (in case of twitter this should be a tweetmessage, in case of facebook, google+ or youtube this should be a URL)
		translation3D : new arel.Vector3D(0.0, 0.0, 0.0),
		rotation3D : new arel.Vector3D(0.0, 0.0, 0.0),
		scale3D : new arel.Vector3D(1.0, 1.0, 1.0)
	}; 
		
	
	Whenever you miss out one of these parameters, a default value will be used instead.	
	Further some 3D-Transformations are allready applied to place the button flat on top of you pattern.
	Use the tranformation-parameters to customize your 3D-Buttons.
	
	The following three example-buttons illustrate some possible configurations
	*/
	
	
	//optional parameters for the 3D Twitter-Button (3D-Transformations, custom tweetmessage)
	var twitterModel3dOptionalParams =
	{
		customSocialParam : tweetMessage,
		translation3D : new arel.Vector3D(110.0, 110.0, 0.0),
		rotation3D : new arel.Vector3D(0.0, 45.0, 0.0),
	};
	
	
	// adds a 3D twitter-button to the Scene of a Glue-channel
	social.createGlueTwitterModel3D("twitterModel3D", 1, twitterModel3dOptionalParams);
	
	
	//optional parameters for the 3D Facebook-Button (3D-Transformations, custom URL to share)
	var facebookModel3dOptionalParams =
	{
		customSocialParam : urlToShare,
		translation3D : new arel.Vector3D(-110.0, 110.0, 0.0),
	};
	
	
	// adds a 3D facebook-share-button to the Scene of a Glue-channel
	social.createGlueFacebookModel3D("facebookModel3D", 1, facebookModel3dOptionalParams);
	
	
	//optional parameters for the 3D GooglePlus-Button (3D-Transformations, custom URL to share)
	var googlePlusModel3dOptionalParams =
	{
		translation3D : new arel.Vector3D(-110.0, -110.0, 0.0),
		rotation3D : new arel.Vector3D(0.0, -45.0, 0.0),
		scale3D : new arel.Vector3D(0.5, 0.5, 0.5)
	};
	
	
	// adds a 3D google-plus-button to the Scene of a Glue-channel
	social.createGlueGooglePlusModel3D("googlePlusModel3D", 1, googlePlusModel3dOptionalParams);
	
	
	//optional parameters for the 3D Youtube-Button (3D-Transformations, custom URL to share)
	var youTubeModel3dOptionalParams =
	{
		customSocialParam: videoId,
		translation3D : new arel.Vector3D(110.0, -110.0, 0.0),
		rotation3D : new arel.Vector3D(0.0, 0.0, 0.0),
		scale3D : new arel.Vector3D(1.0, 1.0, 1.0)
	};
	
	
	// adds a 3D youtube-button to the Scene of a Glue-channel
	social.createGlueYouTubeModel3D("youTubeModel3D", 1, youTubeModel3dOptionalParams);
		
	
});


function trackingHandler(type, param)
{
	//check if there is tracking information available
	if(param[0] !== undefined)
	{
		//if the pattern is found, hide the information to hold your phone over the pattern
		if(type && type == arel.Events.Scene.ONTRACKING && param[0].getState() == arel.Tracking.STATE_TRACKING)
		{
			$('#info').fadeOut("fast");
		}
		//if the pattern is lost tracking, show the information to hold your phone over the pattern
		else if(type && type == arel.Events.Scene.ONTRACKING && param[0].getState() == arel.Tracking.STATE_NOTTRACKING)
		{
			$('#info').fadeIn("fast");
		}
	}
};


function receiveTrackingStatus(trackingValues)
{
	if(trackingValues[0] === undefined)
		$('#info').fadeIn("fast");
	
};
