/**
Arel-Social-Plugin
*/



arel.Plugin.Social = function ()
{
	this.resourcePath = undefined;
	this.cssButtonContainer = undefined;
	this.cssTwitterButtonStyle = undefined;
	this.cssFbShareButtonStyle = undefined;
	this.cssGpShareButtonStyle = undefined;
	this.cssYouTubeButtonStyle = undefined;
	this.isAndroidDevice = undefined;
	
	
	//constructor
	this.init = function()
	{	
		//build up the right absolut path to the resources
		if( window.location.href.lastIndexOf("/") == window.location.href.length - 1 )
		{
			this.resourcePath = window.location.href + "js/arel_plugin_social/resources/";
		}
		else
		{
			this.resourcePath = window.location.href.substring( 0, window.location.href.lastIndexOf("/") + 1 ) + "js/arel_plugin_social/resources/";
		}
		
		
		this.cssButtonContainer = 		"position: absolute;" +
										"bottom: 5px;" +
										"right: 5px;" +
										"display: block;";
		
		
		this.cssTwitterButtonStyle = 	"float: right;" +
										"margin-left: 5px; " +
										"display: block;" +
										"outline: none;" +
										"background: #eee url('" + this.resourcePath + "favicon.ico') 3px center no-repeat;" +
										"padding: 4px 5px 4px 20px;" +
										"font: 16px/100% 'Lucida Grande','Lucida Sans Unicode', sans-serif;" +
										"font-color: #fff;" +
										"border-radius: 3px;";
		
		
		this.cssFbShareButtonStyle = 	"float: right;" +
										"display:inline-block;" +
										"font-size:16px;" +
										"font-family: Arial, Helvetica, sans-serif;" +
										"line-height:1em;" +
										"text-decoration:none;" +
										"color:#3B5998;" +
										"margin-left: 5px; " +
										"padding:3px 4px 3px 20px;" +
										"border:1px solid #CAD4E7;" +
										"-webkit-border-radius:3px;" +
										"border-radius:3px;" +
										"background-color:#eceef5;" +
										"background-image:url('" + this.resourcePath + "facebook_share_icon.png');" +
										"background-position:2px 3px;" +
										"background-repeat:no-repeat;";
		
		
		this.cssGpShareButtonStyle = 	"float: right;" +
										"display:inline-block;" +
										"font-size:16px;" +
										"font-family: Arial, Helvetica, sans-serif;" +
										"line-height:1em;" +
										"text-decoration:none;" +
										"color:#DF573B;" +
										"margin-left: 5px; " +
										"padding:3px 4px 3px 20px;" +
										"border:1px solid #CAD4E7;" +
										"-webkit-border-radius:3px;" +
										"border-radius:3px;" +
										"background-color:#eceef5;" +
										"background-image:url('" + this.resourcePath + "button-share-google.png');" +
										"background-position:0px 6px;" +
										"background-repeat:no-repeat;";
		
		
		this.cssYouTubeButtonStyle = 	"float: right;" +
										"display:inline-block;" +
										"width: 50px;" +
										"height: 16px;" +
										"margin-left: 5px; " +
										"padding:3px 4px 3px 3px;" +
										"border:1px solid #CAD4E7;" +
										"-webkit-border-radius:3px;" +
										"border-radius:3px;" +
										"background-color:#eceef5;" +
										"background-image:url('" + this.resourcePath + "youtube_watch.png');" +
										"background-position:3px 1px;" +
										"background-repeat:no-repeat;";
		
		
		var buttonContainer = document.createElement("div");
		buttonContainer.setAttribute("id", "buttonContainer");
		buttonContainer.setAttribute("style", this.cssButtonContainer);
		
		document.body.appendChild(buttonContainer);
		
		
		//determines if the mobile-device is an Android or iOS device
		if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i)))
		{
			this.isAndroidDevice = false;
		}
		else
		{
			this.isAndroidDevice = true;
		}
		
		
	};
	
	
	/*
	 * Social-Functionalities
	 */
	
	//Twitter
	this.sendTweetMessage = function (customTweetMessage)
	{
		var tweetMessage;
		
		if(customTweetMessage)
		{
			tweetMessage = customTweetMessage;
		}
		else
		{
			tweetMessage = "Check out this Channel http://dev.junaio.com/channels/info/channel/" + arel.Scene.getID() + " on junaio!";
		}
				
		var url = encodeURI( "http://twitter.com/intent/tweet?text=" + tweetMessage );
//		var url = "http://www.junaio.com";
		
		//opens a webview with the according twitter-page
		arel.Media.openWebsite(url, false);
	};
	
	
	//Facebook
	this.shareFbURL = function (customURL)
	{
		var url;
		
		if(customURL)
		{
			url = encodeURI( "http://www.facebook.com/sharer.php?u=" + customURL);
		}
		else
		{
			url = encodeURI( "http://www.facebook.com/sharer.php?u=http://dev.junaio.com/channels/info/channel/" + arel.Scene.getID() );
		}
		
		//opens a webview with the according facebook-page
		arel.Media.openWebsite(url, false);
	};
	
	
	//GooglePlus
	this.shareGpURL = function (customURL)
	{
		var url;

		if(customURL)
		{
			url = encodeURI( "https://plus.google.com/share?url=" + customURL);
		}
		else
		{
			url = encodeURI( "https://plus.google.com/share?url=http://dev.junaio.com/channels/info/channel/" + arel.Scene.getID() );
		}
		
		//opens a webview with the according google-plus-page
		arel.Media.openWebsite(url, false);
	};
	
	
	//YouTube
	this.watchYtURL = function (customVideoID)
	{
		var url;

		if(customVideoID)
		{
			if(this.isAndroidDevice)
			{
				url = encodeURI( "vnd.youtube:" + customVideoID );
			}
			else
			{
				url = encodeURI( "http://m.youtube.com/watch?v=" + customVideoID);
			}
		}
		else
		{
			if(this.isAndroidDevice)
			{
				url = encodeURI( "vnd.youtube:vEM_iW9WRUQ" );
			}
			else
			{
				url = encodeURI( "http://m.youtube.com/watch?v=vEM_iW9WRUQ" );
			}
		}
		
		//opens a webview with the according google-plus-page
		arel.Media.openWebsite(url, false);
	};
	
	
	
	
	/*
	 * Custom-Social-Objects
	 */
	
	
	this.addTwitterFunctionality = function (customObject, tweetMessage)
	{
		var social = this;
		
		try 
		{
			if(customObject.nodeType && customObject.nodeType == 1)
			{
				// adds the Twitter-Functionaltiy to the object
				customObject.onclick = function(){ social.sendTweetMessage( tweetMessage ); };
			}
			else
			{
				// adds a eventlistener to the object
				arel.Events.setListener( customObject, function(obj, type, message){social.handleTwitterModelEvents(obj, type, tweetMessage);});
			}
		}
		catch(e)
		{
			arel.Debug.error("Exception: " + e);
		}
		
	};
	
	
	this.addFacebookFunctionality = function (customObject, customUrl)
	{
		var social = this;
		
		try 
		{
			if(customObject.nodeType && customObject.nodeType == 1)
			{
				// adds the Facebook-Functionaltiy to the object
				customObject.onclick = function(){ social.shareFbURL( customUrl ); };
			}
			else
			{
				// adds a eventlistener to the object
				arel.Events.setListener( customObject, function(obj, type, url){social.handleFacebookModelEvents(obj, type, customUrl);});
			}
		}
		catch(e)
		{
			arel.Debug.error("Exception: " + e);
		}
		
	};
	
	
	this.addGooglePlusFunctionality = function (customObject, customUrl)
	{
		var social = this;
		
		try 
		{
			if(customObject.nodeType && customObject.nodeType == 1)
			{
				// adds the GooglePlus-Functionaltiy to the object
				customObject.onclick = function(){ social.shareGpURL( customUrl ); };
			}
			else
			{
				// adds a eventlistener to the object
				arel.Events.setListener( customObject, function(obj, type, url){social.handleGooglePlusModelEvents(obj, type, customUrl);});
			}
		}
		catch(e)
		{
			arel.Debug.error("Exception: " + e);
		}
		
	};
	
	
	this.addYoutubeFunctionality = function (customObject, customVideoID)
	{
		var social = this;
		
		try 
		{
			if(customObject.nodeType && customObject.nodeType == 1)
			{
				// adds the Youtube-Functionaltiy to the object
				customObject.onclick = function(){ social.watchYtURL( customVideoID ); };
			}
			else
			{
				// adds a eventlistener to the object
				arel.Events.setListener( customObject, function(obj, type, videoId){social.handleYoutubeModelEvents(obj, type, customVideoID);});
			}
		}
		catch(e)
		{
			arel.Debug.error("Exception: " + e);
		}
		
	};
	
	
	/*
	 * Social-HTML-Overlay-Buttons
	 */
	
	
	//Twitter-Button
	this.addTweetButton = function (customTweetMessage)
	{
		//creats the according html-elements of the twitter-button
		var tweetButton = document.createElement("div");
		tweetButton.setAttribute("id", "tweetButton");
		tweetButton.setAttribute("style", this.cssTwitterButtonStyle);
		tweetButton.innerText = "Tweet";
		tweetButton.textContent = "Tweet";
		
		// adds the Twitter-Functionaltiy to the button
		this.addTwitterFunctionality(tweetButton, customTweetMessage);	
		
		//adds the button to the HTML-DOM
		document.getElementById("buttonContainer").appendChild(tweetButton);
	};
	
	
	//FB-Share-Button
	this.addFacebookShareButton = function (customURL)
	{			
		//creats the according html-elements of the fb-share-button
		var fbShareButton = document.createElement("a");
		fbShareButton.setAttribute("id", "fbShareButton");
		fbShareButton.setAttribute("style", this.cssFbShareButtonStyle);
		fbShareButton.innerText = "Share";
		fbShareButton.textContent = "Share";
		
		// adds the Facebook-Functionaltiy to the button
		this.addFacebookFunctionality(fbShareButton, customURL);
		
		//adds the button to the HTML-DOM
		document.getElementById("buttonContainer").appendChild(fbShareButton);
		
	};
	
	
	//GP-Share-Button
	this.addGooglePlusShareButton = function (customURL)
	{	
		//creats the according html-elements of the fb-share-button
		var gpShareButton = document.createElement("a");
		gpShareButton.setAttribute("id", "gpShareButton");
		gpShareButton.setAttribute("style", this.cssGpShareButtonStyle);
		gpShareButton.innerText = "Share";
		gpShareButton.textContent = "Share";

		// adds the GooglePlus-Functionaltiy to the button
		this.addGooglePlusFunctionality(gpShareButton, customURL);
		
		//adds the button to the HTML-DOM
		document.getElementById("buttonContainer").appendChild(gpShareButton);
		
	};
	
	
	//YouTube-Button
	this.addYouTubeButton = function (customVideoID)
	{			
		//creats the according html-elements of the fb-share-button
		var ytWatchButton = document.createElement("a");
		ytWatchButton.setAttribute("id", "ytWatchButton");
		ytWatchButton.setAttribute("style", this.cssYouTubeButtonStyle);
		
		// adds the Youtube-Functionaltiy to the button
		this.addYoutubeFunctionality(ytWatchButton, customVideoID);
		
		//adds the button to the HTML-DOM
		document.getElementById("buttonContainer").appendChild(ytWatchButton);
	};
	
	
	
	
	/*
	 * Social-Glue-Buttons
	 */
	
	this.setGlueButtonParameters = function(arelObjectModel3D, coordinateSystemID, translation, rotation, scale)
	{
		arelObjectModel3D.setCoordinateSystemID(coordinateSystemID);
		
		// some default transformations to place the button in a user-friendly view
		var defaulftTranslation = new arel.Vector3D(0, 0, 10);
		var defaultRotationVector = new arel.Vector3D(90, 0, 180);
		var defaultScale = new arel.Vector3D(0.1, 0.1, 0.1);
		
		
		//Default or custom translation
		if(translation)
		{
			var customTranslation = new arel.Vector3D.add( defaulftTranslation, translation );
			arelObjectModel3D.setTranslation(customTranslation);
		}
		else
		{
			arelObjectModel3D.setTranslation(defaulftTranslation);
		}
		
		
		//Default or custom rotation
		if(rotation)
		{
			var customRotationVector = new arel.Vector3D.add( defaultRotationVector, rotation );
			var customRotation = new arel.Rotation();
			customRotation.setFromEulerAngleDegrees( customRotationVector );
			arelObjectModel3D.setRotation(customRotation);
		}
		else
		{
			var defaultRotation = new arel.Rotation();
			defaultRotation.setFromEulerAngleDegrees( defaultRotationVector );
			arelObjectModel3D.setRotation(defaultRotation);
		}
		
		
		//Default or custom scaling
		if(scale)
		{
			var customScale = new arel.Vector3D( 	
					defaultScale.getX() * scale.getX(), 
					defaultScale.getY() * scale.getY(), 
					defaultScale.getZ() * scale.getZ() 
					);
			arelObjectModel3D.setScale(customScale);
		}
		else
		{
			arelObjectModel3D.setScale(defaultScale);
		}
	
		
		// add object to the scene
		arel.Scene.addObject(arelObjectModel3D);
	};
	
	
	
	/*
	 * Twitter-Button Glue
	 */
	
	this.handleTwitterModelEvents = function (obj, type, tweetMessage)
	{
		//check if the object has been clicked
		if(type && type === arel.Events.Object.ONTOUCHSTARTED)
		{
			this.sendTweetMessage(tweetMessage);
		}
	};
	
	
	this.createGlueTwitterModel3D = function (objectID, coordinateSystemID, twitterModel3dOptionalParams)
	{
		
		//creates the 3D-model of the button
		var twitterModel3D = new arel.Object.Model3D.create(
									objectID,
									this.resourcePath + "socialGlueBtn3D.md2",
									this.resourcePath + "twitterBtn3D.png"
								   );
		
		
		if(twitterModel3dOptionalParams)
		{
			// applies custom parameters to the button
			this.setGlueButtonParameters(twitterModel3D, coordinateSystemID, twitterModel3dOptionalParams.translation3D, twitterModel3dOptionalParams.rotation3D, twitterModel3dOptionalParams.scale3D);
		}
		else
		{
			// applies default parameters to the button
			this.setGlueButtonParameters(twitterModel3D, coordinateSystemID);
		}
		
		
		// adds the Twitter-Functionaltiy to the button
		this.addTwitterFunctionality(twitterModel3D, twitterModel3dOptionalParams.customSocialParam);		
		
	};
	
	
	
	/*
	 * Facebook-Share-Button Glue
	 */
	
	this.handleFacebookModelEvents = function (obj, type, url)
	{
		//check if the object has been clicked
		if(type && type === arel.Events.Object.ONTOUCHSTARTED)
		{
			this.shareFbURL(url);
		}
	};
	
	
	this.createGlueFacebookModel3D = function (objectID, coordinateSystemID, facebookModel3dOptionalParams)
	{
		
		//creates the 3D-model of the button
		var facebookModel3D = new arel.Object.Model3D.create(
				objectID,
				this.resourcePath + "socialGlueBtn3D.md2",
				this.resourcePath + "facebookBtn3D.png"
			   );

		
		if(facebookModel3dOptionalParams)
		{
			// applies custom parameters to the button
			this.setGlueButtonParameters(facebookModel3D, coordinateSystemID, facebookModel3dOptionalParams.translation3D, facebookModel3dOptionalParams.rotation3D, facebookModel3dOptionalParams.scale3D);
		}
		else
		{
			// applies default parameters to the button
			this.setGlueButtonParameters(facebookModel3D, coordinateSystemID);
		}
		
		
		// adds the Twitter-Functionaltiy to the button
		this.addFacebookFunctionality(facebookModel3D, facebookModel3dOptionalParams.customSocialParam);
			
	};
	
	
	
	/*
	 * Google-Plus-Share-Button Glue
	 */
	
	this.handleGooglePlusModelEvents = function (obj, type, url)
	{
		//check if the object has been clicked
		if(type && type === arel.Events.Object.ONTOUCHSTARTED)
		{
			this.shareGpURL(url);
		}
	};
	
	
	this.createGlueGooglePlusModel3D = function (objectID, coordinateSystemID, googlePlusModel3dOptionalParams)
	{
		
		//creates the 3D-model of the button
		var googlePlusModel3D = new arel.Object.Model3D.create(
				objectID,
				this.resourcePath + "socialGlueBtn3D.md2",
				this.resourcePath + "googlePlusBtn3D.png"
			   );

		
		if(googlePlusModel3dOptionalParams)
		{
			// applies custom parameters to the button
			this.setGlueButtonParameters(googlePlusModel3D, coordinateSystemID, googlePlusModel3dOptionalParams.translation3D, googlePlusModel3dOptionalParams.rotation3D, googlePlusModel3dOptionalParams.scale3D);
		}
		else
		{
			// applies default parameters to the button
			this.setGlueButtonParameters(googlePlusModel3D, coordinateSystemID);
		}
		
		
		// adds the Twitter-Functionaltiy to the button
		this.addGooglePlusFunctionality(googlePlusModel3D, googlePlusModel3dOptionalParams.customSocialParam);
			
	};
	
	
	
	/*
	 * YouTube-Watch-Button Glue
	 */
	
	this.handleYoutubeModelEvents = function (obj, type, videoID)
	{
		//check if the object has been clicked
		if(type && type === arel.Events.Object.ONTOUCHSTARTED)
		{
			this.watchYtURL(videoID);
		}
	};
	
	
	this.createGlueYouTubeModel3D = function (objectID, coordinateSystemID, youTubeModel3dOptionalParams)
	{
		
		//creates the 3D-model of the button
		var youTubeModel3D = new arel.Object.Model3D.create(
				objectID,
				this.resourcePath + "socialGlueBtn3D.md2",
				this.resourcePath + "youTubeBtn3D.png"
			   );

		
		if(youTubeModel3dOptionalParams)
		{
			// applies custom parameters to the button
			this.setGlueButtonParameters(youTubeModel3D, coordinateSystemID, youTubeModel3dOptionalParams.translation3D, youTubeModel3dOptionalParams.rotation3D, youTubeModel3dOptionalParams.scale3D);
		}
		else
		{
			// applies default parameters to the button
			this.setGlueButtonParameters(youTubeModel3D, coordinateSystemID);
		}
		
		
		// adds the Twitter-Functionaltiy to the button
		this.addYoutubeFunctionality(youTubeModel3D, youTubeModel3dOptionalParams.customSocialParam);
			
	};
	
	
	//invokes the constructor and initializes the social plugin
	this.init();
};





