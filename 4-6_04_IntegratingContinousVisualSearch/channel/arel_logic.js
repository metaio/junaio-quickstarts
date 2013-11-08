// Maximum count of retries
var counter = 5;


arel.sceneReady(function()
		{
		
		counter = 5;
		
		// Set a listener to get information about the sceneCallback
		arel.Events.setListener(arel.Scene,sceneCallback);

		// Constructor is setting the click function to the HTML button
		// If the user is clicking the "Start CVS" button it will hide the button and start the cvs
		$(".info").bind("click",function()
			{
			// arel.Scene.requestVisualSearch("YOUR_DATABASE_ID", true); 
			arel.Scene.requestVisualSearch("QuickstartCVS", true);
			$(".info").hide();
			});
		}
		// Anzeige der Konsole
		,false
	       );

function sceneCallback(type,result)
{
	
	// If the user requested a continuous visual search this event will be triggered 
	if(type == arel.Events.Scene.ONVISUALSEARCHRESULT)
	{
		if(result.length <= 0)		
		{
			// We have not found any results
 
			counter--;

			if(counter > 0)
			{
				arel.Scene.requestVisualSearch("QuickstartCVS", true);
				// If "true", then the resulting identifier can be used as a tracking configuration
			}
			else
			{
				$(".info").show();
				counter = 5; 
			}
		}
		else
		{
			// We have received results

			$(".info").show();
			counter = 5;

			// Make sure we don't have any objects in the scene
			arel.Scene.removeObjects();

			// Look into first result(array) and convert it to a string
			var stringResult = result[0].toString();

			switch(stringResult)
			{
				case "multi_pattern_1.jpg_0":
					// Output on console
					arel.Debug.log("Fish identified"); 	

					// If the pattern is identified, trigger a movie				
					arel.Media.startVideo("resources/Junaio_trailer.mp4"); 
					break;

				case "multi_pattern_3.jpg_0":
					arel.Debug.log("Girls identified"); 

					// If the pattern is identified, trigger a website
					arel.Media.openWebsite("http://dev.junaio.com"); 
					break;

				case "multi_pattern_2.jpg_0":
					arel.Debug.log("Brandenburg Gate identified"); 
					// If the pattern is identified, add a 3D Model
					
					// Create the Metaioman object with appropriate model file and texture
					metaioMan = arel.Object.Model3D.create("metaioman", "resources/metaioman.md2", "resources/metaioman.png");
					
					// Locate the object appropriately
					metaioMan.setTranslation(new arel.Vector3D(50,0,0));		// Translate the object to appropriate position
					metaioMan.setScale(new arel.Vector3D(2, 2, 2));				// Scale the object to be well visible
					metaioMan.setCoordinateSystemID(1);							// Set the Coordinate System ID
					// Add the new Object to the Scene
					arel.Scene.addObject(metaioMan);

					// Load the pattern as tracking configuration.	
					arel.Scene.setTrackingConfiguration(result[0]);					
					break;

				default :
					// Some other image of your database has been recognized
					arel.Debug.log("Recognized " + result[0]); 
			}
		}
	}
}
