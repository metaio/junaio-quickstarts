<?php

require_once 'Zend/Http/Client.php';
require_once 'Zend/Http/Response.php';

/**
 * Makes a POST-request to the Visual Search Database REST-API
 * @param $url the name of the php-file which handles a specific REST-API request.
 * @param $config the config for Zend_Http_Client
 * @param $params post parameter (associative array which maps key to value)
 * @param $message the Visual Search Database result as XML DOM or the error
 * message as string if the request failed.
 * @param $files files which are uploaded to the server (only png, jpeg and xml are allowed)
 */
function doPost($url, $config, $params, &$message, $localFile = NULL)
{
	$client = new Zend_Http_Client("https://mobiledeveloperportal.ar-live.de/REST/VisualSearch/".$url, $config);
	$client->setMethod(Zend_Http_Client::POST);
	$client->setParameterPost($params);
	if($localFile)
	{
		// Upload images to database
		$client->setFileUpload($localFile, "trackable");
	}

	$response = $client->request();

	if(strcmp($response->getStatus(),"403") === 0)
	{
		echo "\nNo license present for your user!\n\n";
		echo "Please verify if:\n";
		echo "      - you have purchased a license.\n";                          
		echo "      - your license has already expired.\n";
		echo "      - you have used up your entire image quota.\n";
		exit;
	}
	return $response;
}

/**
 * Creates a new database.
 * @param string $vstreeID name of the new database. If a database with this 
 * name does already exists, the request will fail.
 * @return an error message if the request failed otherwise an empty string 
 */
function addDatabase($email, $password, $dbName)
{
	$errorMsg = "";
	$postResponse = doPost
	(
		"addDatabase.php", 
		array('timeout' => 15),
		array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName
		),
		$errorMsg
	);
	$return = array($postResponse, $errorMsg);   
	return $return;
}

// Deletes an existing database
function deleteDatabase($email, $password, $dbName)
{
	$errorMsg = "";
	$postResponse = doPost
	(
		"deleteDatabase.php", 
		array('timeout' => 15),
		array
		(
            'email' => $email,
            'password' => md5($password), 
            'dbName' => $dbName
		),
		$errorMsg
	);
	$return = array($postResponse, $errorMsg);   
	return $return;
}

// Adds an Application to the database
function addApplication($email, $password, $dbName, $appId)
{
    $errorMsg = "";
    $postResponse = doPost
	(
        "addApplication.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
            'appIdentifier' => $appId
        ),
        $errorMsg
    );
    $return = array($postResponse, $errorMsg);   
	return $return;
}

// Adds a Channel to the database
function addChannel($email, $password, $dbName, $channelID)
{
    $errorMsg = "";
    $postResponse = doPost
	(
        "addApplication.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
            'appIdentifier' => 'com.metaio.junaio',
			'channelId' => $channelID
        ),
        $errorMsg
    );
	
	$postResponse = doPost
	(
        "addApplication.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
            'appIdentifier' => 'com.metaio.junaio-ipad',
			'channelId' => $channelID
        ),
        $errorMsg
    );
    $return = array($postResponse, $errorMsg);   
	return $return;
}

// Deletes an existing Application from the database
function deleteApplication($email, $password, $dbName, $appId)
{
    $errorMsg = "";
    $postResponse = doPost
	(
        "deleteApplication.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
            'appIdentifier' => $appId
        ),
        $errorMsg
    );
	$return = array($postResponse, $errorMsg);   
	return $return;
}

// Deletes a Channel from the database
function deleteChannel($email, $password, $dbName, $channelID)
{
    $errorMsg = "";
    $postResponse = doPost
	(
        "deleteApplication.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
            'appIdentifier' => 'com.metaio.junaio',
			'channelId' => $channelID
        ),
        $errorMsg
    );

	$postResponse = doPost
	(
        "deleteApplication.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
            'appIdentifier' => 'com.metaio.junaio-ipad',
			'channelId' => $channelID
        ),
        $errorMsg
    );
    $return = array($postResponse, $errorMsg);   
	return $return;
}

/**
 * Adds new TrackingDatas to the Database.
 * @param string $vstreeID name of the existing database. If a database with 
 * this name does not exist, the request will fail.
 * @param array $files a set of feature containers you want to upload. 
 * Feature containers are TrackingDatas or images (png or jpg).
 * @return an error message if the request failed otherwise an empty string 
 */
function addTrackingData($email, $password, $dbName, $image)
{
	$errorMsg = "";
    $postResponse = doPost
	(
        "addTrackingData.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
			'image' => $image
        ),
        $errorMsg,
        $image
    );
	$return = array($postResponse, $errorMsg);   
	return $return;
}

// Deletes existing TrackingDatas/Images from the database
function deleteTrackingDatas($email, $password, $dbName, $tdNames)
{
	$errorMsg = "";
    $postResponse = doPost
	(
        "deleteTrackingDatas.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
			'tdNames' => $tdNames
        ),
        $errorMsg
    );
    $return = array($postResponse, $errorMsg);   
	return $return;
}

// Gets the names of the TrackingDatas/Images contained in the database
function getTrackingDatas($email, $password, $dbName)
{
	$errorMsg = "";
	$postResponse = doPost
	(
		"getTrackingDatas.php", 
		array('timeout' => 15),
		array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName
		),
		$errorMsg
	);
	$return = array($postResponse, $errorMsg);   
	return $return;
}

// Gets database statistics/information
function getStats($email, $password, $dbName)
{
	$errorMsg = "";
	$postResponse = doPost
	(
		"getStats.php", 
		array('timeout' => 15),
		array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName
		),
		$errorMsg
	);
	$return = array($postResponse, $errorMsg);   
	return $return;
}


//-------------- EXECUTION START ------------------------

$msg = " ---- STARTING CREATE CVS DB ---- ";
echo "\n$msg\n";

// Output with possible commands to choose from
 echo "\nPlease choose one of the commands: \n[addDatabase | deleteDatabase | addApplication | deleteApplication] \n[addTrackingData | deleteTrackingDatas | getTrackingDatas | getStats ]\n";
 
$action = trim(fgets(STDIN));

switch ($action)
{
case "addDatabase":

// CREATE DATABASE

	$msg = "Creating CVS Database...\n";
	echo "\n$msg";
	
	// User input through: $... = trim(fgets(STDIN));
	echo "\nPlease enter your e-mail!\n";
	$email = trim(fgets(STDIN));
	echo "\nPlease enter your password!\n";
	$password = trim(fgets(STDIN));
	echo "\nPlease enter the name of your database!\n";
	$dbName = trim(fgets(STDIN));

	$addDbResponse = addDatabase($email, $password, $dbName);
	$response = $addDbResponse[0];
	// echo "\n".$response->getStatus();
	// echo "\n".$response->getMessage();
	// echo "\n".$response->getBody()."\n";
	// var_dump($response);
	
	if(strcmp($response->getStatus(),"200") !== 0)
	{
		echo "\nCreating CVS database failed! ".$response->getStatus()." - ".$response->getMessage()."\n";
		exit;
	}

	if(strlen($addDbResponse[1]) === 0)
	{
	   $myXml = new SimpleXMLElement($response->getBody());
	   $myXml->getName();
	   
	   foreach($myXml as $tag)
	   {
			if(strcmp($tag->getName(),"Error") === 0)
			{
				echo "\n".$tag."\n";
				$msg = " ---- CREATING CVS DB FAILED ---- ";
				echo "\n$msg\n";
			}
			else
			{
				$msg = "... Database $dbName has been created.\n";
				echo "\n$msg\n";
				$msg = " ---- CREATING CVS DB SUCCESSFULLY COMPLETED ---- ";
				echo "\n$msg\n";
			}
		exit;
	   }
	}
break;

case "deleteDatabase":

// DELETING DATABASE

	$msg = "Deleting CVS Database...\n";
	echo "\n$msg";

	echo "\nPlease enter your e-mail!\n";
	$email = trim(fgets(STDIN));
	echo "\nPlease enter your password!\n";
	$password = trim(fgets(STDIN));
	echo "\nPlease enter the name of your database!\n";
	$dbName = trim(fgets(STDIN));

	$addDbResponse = deleteDatabase($email, $password, $dbName);
	$response = $addDbResponse[0];
	// echo "\n".$response->getStatus();
	// echo "\n".$response->getMessage();
	// echo "\n".$response->getBody()."\n";
	// var_dump($response);

	if(strlen($addDbResponse[1]) === 0)
	{
	   $myXml = new SimpleXMLElement($response->getBody());
	   $myXml->getName();
	   
	   foreach($myXml as $tag)
	   {
			if(strcmp($tag->getName(),"Error") === 0)
			{
				echo "\n".$tag."\n";
				$msg = " ---- DELETING CVS DB FAILED ---- ";
				echo "\n$msg\n";
			}	
			else
			{
				$msg = "... Database $dbName has been deleted.\n";
				echo "\n$msg\n";
				$msg = " ---- DELETING CVS DB SUCCESSFULLY COMPLETED ---- ";
				echo "\n$msg\n";
			}
		exit;
	   }
	}
break;

case "addApplication":

// ADD APPLICATION

// Connect app to db

	$msg = "Connecting Application to CVS Database...\n";
	echo "\n$msg";

	echo "\nPlease enter your e-mail!\n";
	$email = trim(fgets(STDIN));
	echo "\nPlease enter your password!\n";
	$password = trim(fgets(STDIN));
	echo "\nPlease enter the name of your database!\n";
	$dbName = trim(fgets(STDIN));
	echo "\nPlease enter your Application or Channel ID!\n";
	$appId = trim(fgets(STDIN));

	if (is_numeric($appId)) 
	{
		$addDbResponse = addChannel($email, $password, $dbName, $appId);
		$response = $addDbResponse[0];
	} 
	else 
	{
		$addDbResponse = addApplication($email, $password, $dbName, $appId);
		$response = $addDbResponse[0];
		echo "\n".$response->getStatus();
		// echo "\n".$response->getMessage();
		// echo "\n".$response->getBody()."\n";
		// var_dump($response);
	}	

	if(strcmp($response->getStatus(),"200") !== 0)
	{
		echo "Adding the application to the CVS database failed! ".$response->getStatus()." - ".$response->getMessage()."\n";
		exit;
	}
	
	if(strlen($addDbResponse[1]) === 0)
	{
	   $myXml = new SimpleXMLElement($response->getBody());
	   $myXml->getName();
	   
	   foreach($myXml as $tag)
	   {		
			if(strcmp($tag->getName(),"Error") === 0)
			{
				echo "\n".$tag."\n";
				$msg = " ---- CONNECTING APLLICATION TO CVS DB FAILED ---- ";
				echo "\n$msg\n";
			}	
			else
			{
				$msg = "... Application $appId has been connected to the database $dbName.\n";
				echo "\n$msg\n";
				$msg = " ---- CONNECTING APLLICATION TO CVS DB SUCCESSFULLY COMPLETED ---- ";
				echo "\n$msg\n";
			}
		exit;
	   }
	}
break;

case "deleteApplication":

// DELETE APPLICATION

	$msg = "Deleting Application ...\n";
	echo "\n$msg";

	echo "\nPlease enter your e-mail!\n";
	$email = trim(fgets(STDIN));
	echo "\nPlease enter your password!\n";
	$password = trim(fgets(STDIN));
	echo "\nPlease enter the name of your database!\n";
	$dbName = trim(fgets(STDIN));
	echo "\nPlease enter your Application or Channel ID!\n";
	$appId = trim(fgets(STDIN));

	if (is_numeric($appId)) 
	{
		$addDbResponse = deleteChannel($email, $password, $dbName, $appId);
		$response = $addDbResponse[0];
	} 
	else 
	{
		$addDbResponse = deleteApplication($email, $password, $dbName, $appId);
		$response = $addDbResponse[0];
		// echo "\n".$response->getStatus();
		// echo "\n".$response->getMessage();
		// echo "\n".$response->getBody()."\n";
		// var_dump($response);
	}	
	
	if(strlen($addDbResponse[1]) === 0)
	{
	   $myXml = new SimpleXMLElement($response->getBody());
	   $myXml->getName();
	   
	   foreach($myXml as $tag)
	   {
			if(strcmp($tag->getName(),"Error") === 0)
			{
				echo "\n".$tag."\n";
				$msg = " ---- DELETING APLLICATION FROM CVS FAILED ---- ";
				echo "\n$msg\n";
			}	
			else
			{
				$msg = "... Application $appId has been deleted from the database $dbName.\n";
				echo "\n$msg\n";
				$msg = " ---- DELETING APLLICATION FROM CVS SUCCESSFULLY COMPLETED ---- ";
				echo "\n$msg\n";
			}
		exit;
	   }
	}
break;

case "addTrackingData":

// ADD TRACKING DATA/IMAGES

	// Add images that are in $localFolder to the CVS database
	$msg = "Adding images...\n";
	echo "\n$msg";

	echo "\nPlease enter your e-mail!\n";
	$email = trim(fgets(STDIN));
	echo "\nPlease enter your password!\n";
	$password = trim(fgets(STDIN));
	echo "\nPlease enter the name of your database!\n";
	$dbName = trim(fgets(STDIN));
	echo "\nPlease enter the folder-path of your images!\n";
	//images - important: folder name must end with "/", for example "images/" and not "images"
	$localFolderName = trim(fgets(STDIN));

	if(!is_dir($localFolderName))
	{
		$msg = "ERROR: ".$localFolderName." is not a folder";
		echo "\n$msg\n";
		$msg = " ---- ADDING IMAGE(S) FAILED ---- ";
		echo "\n$msg\n";
		exit;
	}
	$localFolder = opendir($localFolderName);

	$lastCharName = strlen($localFolderName)-1; 
	$result = substr($localFolderName, $lastCharName); 
	
	if ($result == "/") 
	{
		echo "\nThe path of the local folder is: \n";
		echo "\n $localFolderName \n"; 
	}
	else 
	{ 
		$result = $localFolderName."/"; 
		$localFolderName = $localFolderName."/"; 
		echo "\nThe path of the local folder is: \n";
		echo "\n $result \n"; 
	}
	$imageIndex = 0;
	while($filename = readdir($localFolder))
	{
		if(!is_file($localFolderName.$filename))
		{
			if(strcmp($filename,".") === 0)
			{
				continue;
			}
			elseif(strcmp($filename,"..") === 0) 
			{
				continue;
			}
			else
			{
				echo "\n $localFolderName$filename is not a file!";
				$msg = " File ".$filename." skipped: it is not a jpg or png file";
				echo "\n$msg\n";
				continue;
			}
		}
		
		// Check extension
		$extension = substr($filename, strrpos($filename, ".") + 1);
		if(strcasecmp($extension, "jpg") != 0 && strcasecmp($extension, "png") != 0)
		{
			$msg = "\nFile ".$filename." skipped: it is not a jpg or png file"."-".$extension;
			echo "\n$msg\n";
			continue;
		}

		// Replace white spaces
		if(strpos($filename, " ") > 0)
		{
			$msg = "        Replacing white spaces ";
			echo "\n$msg\n";
			$newFilename = str_replace(" ", "_", $filename);
			$cmd = "mv '".$localFolderName.$filename."' '".$localFolderName.$newFilename."' 2>&1";
			exec($cmd, $out, $failure);
			if($failure)
			{
				$msg = "        ERROR: impossible to replace white spaces in image ".$filename;
				echo "\n$msg\n";
				continue;
			}
			$filename = $newFilename;
		}
		$image = $localFolderName.$filename;
		$addImageResponse = addTrackingData($email, $password, $dbName, $image);
		$msg = "\n... uploading ".$filename."...\n";
		echo "\n$msg\n";
		$response = $addImageResponse[0];

		if(strcmp($response->getStatus(),"200") === 0)
		{
			echo "Image-Upload was successful!\n";
			$imageIndex++;	
			continue;	
		}		
		else
		{
			echo "\n".$tag."\n";
			$msg = " ---- ADDING IMAGE(S) FAILED ---- ";
			echo "\n$msg\n";
		}
	}
	$msg = "\n... $imageIndex image(s) have been added.\n";
	echo "\n$msg\n";
break;

case "deleteTrackingDatas":

// DELETE TRACKING DATA/IMAGES

	$msg = "Deleting images...\n";
	echo "\n$msg";

	echo "\nPlease enter your e-mail!\n";
	$email = trim(fgets(STDIN));
	echo "\nPlease enter your password!\n";
	$password = trim(fgets(STDIN));
	echo "\nPlease enter the name of your database!\n";
	$dbName = trim(fgets(STDIN));
	echo "\nPlease enter the name and the extension of the image you want to delete!\n";
	$filename = trim(fgets(STDIN));

	// Check extension
	$extension = substr($filename, strrpos($filename, ".") + 1);
	if(strcasecmp($extension, "jpg") != 0 && strcasecmp($extension, "png") != 0)
	{
		$msg = "    File ".$filename." skipped: it is not a jpg or png file";
		echo "\n$msg\n";
		continue;
	}

	if(strpos($filename, " ") > 0)
	{
		echo "Images are not aloud to contain white spaces!";
	}
	$tdNames = array($filename."_0.td");

	$addImageResponse = deleteTrackingDatas($email, $password, $dbName, $tdNames);
	$response = $addImageResponse[0];
	// echo "\n".$response->getStatus();
	// echo "\n".$response->getMessage();
	// echo "\n".$response->getBody()."\n";
	// var_dump($response);

	if(strcmp($response->getStatus(),"200") === 0)
	{
		$msg = "... deleting ".$filename;
		echo "\n$msg\n";
	}		
	else
	{
		echo "\n".$tag."\n";		
		$msg = " ---- DELETING IMAGE FAILED ---- ";
		echo "\n$msg\n";
	}
	$msg = "\n    Image $filename has been deleted.\n";
	echo "\n$msg\n";
	$msg = " ---- DELETING IMAGE SUCCESSFULLY COMPLETED ---- ";
	echo "\n$msg\n";
break;

case "getTrackingDatas":

// GET TRACKING DATAS

	$msg = "Getting Tracking Data...\n";
	echo "\n$msg";

	echo "\nPlease enter your e-mail!\n";
	$email = trim(fgets(STDIN));
	echo "\nPlease enter your password!\n";
	$password = trim(fgets(STDIN));
	echo "\nPlease enter the name of your database!\n";
	$dbName = trim(fgets(STDIN));
	
	$addDbResponse = getTrackingDatas($email, $password, $dbName);
	$response = $addDbResponse[0];
	// echo "\n".$response->getStatus();
	// echo "\n".$response->getMessage();
	// echo "\n".$response->getBody()."\n";
	// var_dump($response);

	if(strlen($addDbResponse[1]) === 0)
	{
	   $myXml = new SimpleXMLElement($response->getBody());
	   $myXml->getName();
	   
	   foreach($myXml as $tag)
	   {
			if(strcmp($tag->getName(),"Error") === 0)
			{
				echo "\n".$tag."\n";
				$msg = " ---- GETTING TRACKING DATA FAILED ---- ";
				echo "\n$msg\n";
			}	
			else
			{
				$trackingDataName = "";
				foreach($tag->children() as $trackingData)
				{
					$trackingDataName .= $trackingData['Name'] . "\n" . "  ";
				}
				$msg = "... got Tracking Data: \n  $trackingDataName"; 
				echo "\n$msg\n";
				$msg = " ---- GETTING TRACKING DATA SUCCESSFULLY COMPLETED ---- ";
				echo "\n$msg\n";
			}
	   }
	}
break;

case "getStats":

// GET STATS

	$msg = "Getting Stats...\n";
	echo "\n$msg";

	echo "\nPlease enter your e-mail!\n";
	$email = trim(fgets(STDIN));
	echo "\nPlease enter your password!\n";
	$password = trim(fgets(STDIN));
	echo "\nPlease enter the name of your database!\n";
	$dbName = trim(fgets(STDIN));

	$addDbResponse = getStats($email, $password, $dbName);
	$response = $addDbResponse[0];
	// echo "\n".$response->getStatus();
	// echo "\n".$response->getMessage();
	// echo "\n".$response->getBody()."\n";
	// var_dump($response);

	if(strlen($addDbResponse[1]) === 0)
	{
	   $myXml = new SimpleXMLElement($response->getBody());
	  

		// find out if the request was sucessful:
	   foreach($myXml as $tag)
	   {
			if(strcmp($tag->getName(),"Error") === 0)
			{	
				echo "\n".$tag."\n";
				$msg = " ---- GETTING STATS FAILED ---- ";
				echo "\n$msg\n";
				break;
			}
		}
		echo "\n... got Stats: \n ";
		echo "\n";
		var_dump($myXml);
		$msg = " ---- GETTING STATS SUCCESSFULLY COMPLETED ---- ";
		echo "\n$msg\n";
	}
	break;
}

?>
