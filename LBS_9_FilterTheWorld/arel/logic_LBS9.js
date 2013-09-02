var oFilter;

arel.sceneReady(function()
{
	//Just for Debuging purposes
	//arel.Debug.activate();
	//arel.Debug.deactivateArelLogStream();
	
	oFilter = new ArelFilter();
});

function ArelFilter()
{
	//array to keep all objects -> used for local filtering
	this.allObjects = undefined;
	
	this.distance = 0;
	
	this.init = function()
	{
		var that = this;
		
		try
		{
			//get all objects (we will need them for the local search)
			this.allObjects = arel.Scene.getObjects();	
			
			//init GUI stuff
			$('#searchlocal').keypress(function(e) { return that.keyControl(e); });		
			$('#filter').change(function() { that.filterServer($('#filter').val()); return true; });
			
			$('.filterbuttonArea').click(function() {
				$('.filterOptionsInner').slideToggle(900);				
		    });			
		}
		catch(e)
		{
			arel.Debug.error(e);
		}
	};
	//local search
	this.searchLocally = function(term)
	{
		try
		{
			//remove all objects that are not found
			for(var i in this.allObjects)
			{
				if(this.allObjects[i] instanceof arel.Object)
				{
					//check if the search term is in the title -> remove the poi if not
					if(this.allObjects[i].getTitle().toLowerCase().indexOf(term.toLowerCase()) === -1)
						arel.Scene.removeObject(this.allObjects[i]);
					else if(!arel.Scene.objectExists(this.allObjects[i]))
						arel.Scene.addObject(this.allObjects[i]);					
				}
			}
		}
		catch(e)
		{
			arel.Debug.error(e);
		}
		
	};
	
	this.handleLocation = function(lla)
	{
		try
		{
			arel.Debug.log(lla);
			var that = this;
			
			if(this.startLLA === undefined)
				this.startLLA = lla;
			else
			{
				//get the distance form the last know point to this one
				this.distance += Math.round(arel.Util.getDistanceBetweenLocationsInMeter(this.startLLA, lla));
				
				//store the new point
				this.startLLA = lla;
				
				$('.info').html("distance since start:<br />" + this.distance + "m");
			}			
		}catch(e)
		{
			arel.Debug.error(e);
		}
	};
	
	this.filterServer = function(val)
	{
		//arel.Debug.log(val);
		
		//hide the selector again
		$('#filter').blur();
		
		//make a request to the server
		arel.Scene.triggerServerCall(true, {"filter_value" : val, "filter_filtered": "true"}, false);		
	};
	
	this.keyControl = function(oEvent) {
		
		var keycode;
		
		if (window.event) {
			keycode = window.event.keyCode;
		} else if (oEvent) {
			keycode = oEvent.which;
		} else {
			return true;
		}
		
		if (keycode == 13) 
		{
			//make sure the iPhone keyboard disappears on pressing go
			$('#searchlocal').blur();						
			
			//do the search
			this.searchLocally($('#searchlocal').val());			
				
			return false;
		} else {
			return true;
		}
	};
	
	this.init();
}