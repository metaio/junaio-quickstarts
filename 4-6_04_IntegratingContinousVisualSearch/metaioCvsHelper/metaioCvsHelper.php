<?php

require_once 'Zend/Http/Client.php';
require_once 'Zend/Http/Response.php';

/**
 * Makes a POST-request to the Visual Search Database REST-API
 * @param string $url the name of the php-file which handles a specific REST-API request.
 * @param array $config the config for Zend_Http_Client
 * @param array $params post parameter (associative array which maps key to value)
 * @param string $localFile full local path to file to be uploaded
 * @param string $fileUploadFormName form name that will be used when uploading a file
 * @return Zend_Http_Response POST request response from visual CVS API
 */
function doPost($url, $config, $params, $localFile = NULL, $fileUploadFormName = NULL)
{
	$client = new Zend_Http_Client("https://my.metaio.com/REST/VisualSearch/".$url, $config);
	$client->setMethod(Zend_Http_Client::POST);
	$client->setParameterPost($params);
	if($localFile)
	{
		// Upload item to database
		$client->setFileUpload($localFile, $fileUploadFormName);
	}

	$response = $client->request();

	return $response;
}

/**
 * Creates a new database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database. If a database with this name does already exists, the request will fail.
 * @return Zend_Http_Response HTTP response
 */
function addDatabase($email, $password, $dbName)
{
	$postResponse = doPost
	(
		"addDatabase.php", 
		array('timeout' => 15),
		array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName
		)
	);
	return $postResponse;
}

/**
 * Deletes an existing database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @return Zend_Http_Response HTTP response
 */
function deleteDatabase($email, $password, $dbName)
{
    $postResponse = doPost
    (
        "deleteDatabase.php",
        array('timeout' => 15),
        array
        (
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName
        )
    );
    return $postResponse;
}

/**
 * Gets all databases of a user.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @return Zend_Http_Response HTTP response
 */
function getDatabases($email, $password)
{
    $postResponse = doPost
    (
        "getDatabases.php",
        array('timeout' => 15),
        array
        (
            'email' => $email,
            'password' => md5($password)
        )
    );
    return $postResponse;
}

/**
 * Binds an application identifier to a database
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $appId application identifier
 * @return Zend_Http_Response HTTP response
 */
function addApplication($email, $password, $dbName, $appId)
{
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
        )
    );
	return $postResponse;
}

/**
 * Binds a channel identifier to a database.
 * Makes two requests: first with 'com.metaio.junaio', second with 'com.metaio.junaio-ipad' as appIdentifier.
 * If first one has an error, second one is not made.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $channelID channel identifier
 * @return Zend_Http_Response HTTP response
 */
function addChannel($email, $password, $dbName, $channelID)
{
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
        )
    );

    if (isOK($postResponse) && !isError($postResponse->getBody()))
    {
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
            )
        );
    }

	return $postResponse;
}

/**
 * Unbinds an application identifier from a database
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $appId application identifier
 * @return Zend_Http_Response HTTP response
 */
function deleteApplication($email, $password, $dbName, $appId)
{
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
        )
    );
	return $postResponse;
}

/**
 * Unbinds a channel identifier from a database.
 * Makes two requests: first with 'com.metaio.junaio', second with 'com.metaio.junaio-ipad' as appIdentifier.
 * If first one has an error, second one is not made.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $channelID channel identifier
 * @return Zend_Http_Response HTTP response
 */
function deleteChannel($email, $password, $dbName, $channelID)
{
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
        )
    );

    if (isOK($postResponse) && !isError($postResponse->getBody()))
    {
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
            )
        );
    }

	return $postResponse;
}

/**
 * Adds new item to the database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $item path to the image (png, jpg), or zip file representing tracking configuration
 * @param $identifier item identifier
 * @param $metadata item metadata
 * @return Zend_Http_Response HTTP response
 */
function addItem($email, $password, $dbName, $item, $identifier, $metadata)
{
	$params = array (
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName
					);
					
	// Add identifier and metadata as parameters only if they are not empty.
	if (strlen(trim($identifier)) > 0)
	{
		$params['identifier'] = $identifier;
	}
	if (strlen(trim($metadata)) > 0)
	{
		$params['metadata'] = $metadata;
	}
	
    $postResponse = doPost
	(
        "addItem.php",
        array('timeout' => 15),
        $params,
        $item,
		"item"
    );
	return $postResponse;
}

/**
 * Adds new tracking data to the database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $trackable path to the image (png, jpg), or zip file representing tracking configuration
 * @return Zend_Http_Response HTTP response
 */
function addTrackingData($email, $password, $dbName, $trackable)
{
    $postResponse = doPost
	(
        "addTrackingData.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName
        ),
        $trackable,
		"trackable"
    );
	return $postResponse;
}

/**
 * Removes existing item from the database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $itemName name of the image (png, jpg) or of the file generated out of tracking configuration (ending with zip_<number>)
 * @return Zend_Http_Response HTTP response
 */
function removeItem($email, $password, $dbName, $itemName)
{
    $postResponse = doPost
	(
        "removeItem.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
			'itemName' => $itemName
        )
    );
	return $postResponse;
}

/**
 * Deletes existing trackables from the database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $tdNames list of names of the image files or of the files generated out of tracking configuration (ending with zip_<number>)
 * @return Zend_Http_Response HTTP response
 */
function deleteTrackingDatas($email, $password, $dbName, $tdNames)
{
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
        )
    );
	return $postResponse;
}

/**
 * Gets the names of the items contained in the database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @return Zend_Http_Response HTTP response
 */
function getItems($email, $password, $dbName)
{
	$postResponse = doPost
	(
		"getItems.php", 
		array('timeout' => 15),
		array
		(
            'email' => $email,
            'password' => md5($password), //md5($email.$password),
            'dbName' => $dbName
		)
	);
	return $postResponse;
}

/**
 * Gets the names of the tracking datas contained in the database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @return Zend_Http_Response HTTP response
 */
function getTrackingDatas($email, $password, $dbName)
{
	$postResponse = doPost
	(
		"getTrackingDatas.php", 
		array('timeout' => 15),
		array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName
		)
	);
	return $postResponse;
}

/**
 * Gets database statistics/information.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @return Zend_Http_Response HTTP response
 */
function getStats($email, $password, $dbName)
{
	$postResponse = doPost
	(
		"getStats.php", 
		array('timeout' => 15),
		array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName
		)
	);
	return $postResponse;
}

//-------------- HELPER FUNCTIONS --------------

function getAction()
{
    echo PHP_EOL."Please choose one of the commands: ".PHP_EOL."[addDatabase | deleteDatabase | getDatabases | addApplication | deleteApplication] ".PHP_EOL."[addItem | addTrackingData | removeItem | deleteTrackingData | getItems | getTrackingDatas | getStats ]".PHP_EOL;
    $action = trim(fgets(STDIN));
    return $action;
}

function getEMail()
{
    echo PHP_EOL."Please enter your e-mail!".PHP_EOL;
    $email = trim(fgets(STDIN));
    return $email;
}

function getPassword()
{
    echo PHP_EOL."Please enter your ORIGINAL password! (metaioCvsHelper will encode it for you)".PHP_EOL;
    $password = trim(fgets(STDIN));
    return $password;
}

function getDbName()
{
    echo PHP_EOL."Please enter the name of your database!".PHP_EOL;
    $dbName = trim(fgets(STDIN));
    return $dbName;
}

function getAppId()
{
    echo PHP_EOL."Please enter your Application or Channel ID!".PHP_EOL;
    $appId = trim(fgets(STDIN));
    return $appId;
}

function getItemPath()
{
    echo PHP_EOL."Please enter the path of your image or tracking configuration!".PHP_EOL;
    $filename = trim(fgets(STDIN));
    return $filename;
}

function getItemFileName($entity = "item")
{
    echo PHP_EOL."Please enter the name of your $entity (if image, including extension; if $entity created based on zip file, including zip_<number> part)!".PHP_EOL;
    $filename = trim(fgets(STDIN));
    return $filename;
}

function validateItemExtension($filePath, $removal = false)
{
    // Check extension
    $extension = substr($filePath, strrpos($filePath, ".") + 1);
    if(strcasecmp($extension, "jpg") !== 0 && strcasecmp($extension, "png") !== 0 && strcasecmp($extension, "zip") !== 0 && (!$removal || preg_match("/zip_[\d]{1,5}$/", $extension) !== 0))
    {
        $msg = PHP_EOL."File ".$filePath." skipped: it is not a jpg, png nor ";
        if ($removal)
        {
            $msg .= "in format zip_<number>";
        }
        else
        {
            $msg .= "zip file";
        }
        $msg .= " - $extension".PHP_EOL;
        return $msg;
    }
}

function validateItemPath($filePath)
{
    $msg = NULL;
    if(!is_file($filePath))
    {
        $msg = PHP_EOL."ERROR: ".$filePath." is not a file (or you don't have permission to use it)".PHP_EOL." ---- ADDING ITEM FAILED ---- ".PHP_EOL;
        return $msg;
    }

    $msg = validateItemExtension($filePath);

    return $msg;
}

function isValidPath($localParentFolderPath, $fileName)
{
    if(!is_file($localParentFolderPath.$fileName))
    {
        if(strcmp($fileName,".") === 0)
        {
            return false;
        }
        elseif(strcmp($fileName,"..") === 0)
        {
            return false;
        }
        else
        {
            $msg = PHP_EOL."ERROR: $localParentFolderPath.$fileName is not a file (or you don't have permission to use it)".PHP_EOL." ---- ADDING TRACKABLE FAILED ---- ".PHP_EOL;
            echo PHP_EOL."$msg".PHP_EOL;
            return false;
        }
    }

    $msg = validateItemExtension($localParentFolderPath.$fileName);
    if(isset($msg))
    {
        echo $msg;
        return false;
    }

    return true;
}

function replaceWhitespaces(&$fileName, $parent = NULL, $entity = "item")
{
    $found = strpos($fileName, " ");
    $position = $found;
    if ($found !== FALSE && $position > 0)
    {
        echo PHP_EOL."Replacing white spaces".PHP_EOL;

        $newFileName = str_replace(" ", "_", $fileName);

        if ($parent !== NULL)
        {
            if (file_exists($parent.DIRECTORY_SEPARATOR.$newFileName))
            {
                $msg = PHP_EOL."ERROR - not possible to replace white spaces in $entity $fileName: $newFileName already exists!".PHP_EOL;
                echo $msg;
                return $msg;
            }

            if (!rename($parent.DIRECTORY_SEPARATOR.$fileName, $parent.DIRECTORY_SEPARATOR.$newFileName))
            {
                $msg = PHP_EOL."ERROR - not possible to replace white spaces in $entity $fileName: reason unknown. Check if you have permission to create file (with new name).".PHP_EOL;
                echo $msg;
                return $msg;
            }
        }

        $fileName = $newFileName;
    }
}

function getIdentifier()
{
    echo PHP_EOL."Optional: Please enter the identifier for your item!".PHP_EOL;
    $identifier = trim(fgets(STDIN));
    return $identifier;
}

function getMetadata()
{
    echo PHP_EOL."Optional: Please enter the metadata for your item!".PHP_EOL;
    $metadata = trim(fgets(STDIN));
    return $metadata;
}

function getTrackablesFolderPath()
{
    echo PHP_EOL."Please enter the folder-path of your trackables!".PHP_EOL;
    //trackables - important: folder name must end with "/", for example "trackables/" and not "trackables"
    $localFolderName = trim(fgets(STDIN));
    return $localFolderName;
}

function validateTrackablesFolderPath($localFolderName)
{
    if(!is_dir($localFolderName))
    {
        $msg = PHP_EOL."ERROR: ".$localFolderName." is not a folder".PHP_EOL." ---- ADDING TRACKABLE(S) FAILED ---- ".PHP_EOL;
        return $msg;
    }
}

function updateTrackablesFolderPath($localFolderName)
{
    $lastCharName = strlen($localFolderName)-1;
    $result = substr($localFolderName, $lastCharName);

    if ($result == "/")
    {
        echo PHP_EOL."The path of the local folder is: ".PHP_EOL;
        echo PHP_EOL." $localFolderName ".PHP_EOL;
    }
    else
    {
        $result = $localFolderName."/";
        $localFolderName = $localFolderName."/";
        echo PHP_EOL."The path of the local folder is: ".PHP_EOL;
        echo PHP_EOL." $result ".PHP_EOL;
    }

    return $localFolderName;
}

function isOK($response)
{
    $error = strcmp($response->getStatus(),"200") === 0;
    return $error;
}

function isError($body)
{
    $myXml = new SimpleXMLElement($body);

    foreach($myXml as $tag)
    {
        if(strcmp($tag->getName(),"Error") === 0)
        {
            return true;
        }
    }

    return false;
}

function printResult($response, $failMsg, $successMsg, $printWholeBody = false)
{
    $myXml = new SimpleXMLElement($response->getBody());

    if ($printWholeBody)
    {
        $msg = PHP_EOL;
        $msg .= $response->getBody();
        $msg .= PHP_EOL;
        $msg .= $successMsg;
        $msg .= PHP_EOL;
    }
    else
    {
        foreach($myXml as $tag)
        {
            $msg = PHP_EOL.PHP_EOL;
            if(strcmp($tag->getName(),"Error") === 0)
            {
                $msg .= $failMsg;
            }
            else
            {
                $msg .= $successMsg;
            }
            $msg .= PHP_EOL.PHP_EOL;
        }
    }
    echo $msg;
}

function printDatabasesResult($response, $failMsg, $successMsg, $dumpXml = false)
{
    $myXml = new SimpleXMLElement($response->getBody());

    foreach($myXml as $tag)
    {
        $msg = PHP_EOL.PHP_EOL;
        if(strcmp($tag->getName(),"Error") === 0)
        {
            $msg .= $failMsg;
        }
        else
        {
            foreach ($tag->children() as $database)
            {
                $databaseName = (string)($database->attributes());
                $msg .= "Database: $databaseName".PHP_EOL;
                $applications = $database->children()->children();
                if (sizeof($applications)>0)
                {
                    foreach($applications as $application)
                    {
                        $attributes = $application->attributes();
                        $applicationName = (string)$attributes['Name'];
                        $channelId = (int)$attributes['ChannelId'];
                        $msg .= "   Application: $applicationName";
                        if ($channelId !== -1)
                        {
                            $msg .= ",\tChannel ID: $channelId";
                        }
                        $msg .= PHP_EOL;
                    }
                }
            }
        }
        $msg .= PHP_EOL.PHP_EOL;

        echo $msg;
    }

    if ($dumpXml)
    {
        echo $response->getBody();
    }
}

function printImageArrayResult($response, $failMsg, $successMsg, $entity = "item")
{
    $myXml = new SimpleXMLElement($response->getBody());

    foreach($myXml as $tag)
    {
        if(strcmp($tag->getName(),"Error") === 0)
        {
            $msg = PHP_EOL.$tag.PHP_EOL.PHP_EOL."$failMsg".PHP_EOL;
        }
        else
        {
            $itemName = "";
            foreach($tag->children() as $item)
            {
                $itemName .= $item['Name'] . PHP_EOL."" . "  ";
            }
            $msg = PHP_EOL."... got $entity: ".PHP_EOL."  $itemName".PHP_EOL.PHP_EOL." $successMsg ".PHP_EOL;
        }

        echo $msg;
    }
}

//-------------- EXECUTION START --------------

$action = getAction();

switch ($action)
{
    case "addDatabase":

        echo "Creating CVS Database...".PHP_EOL;

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();

        $response = addDatabase($email, $password, $dbName);

        if(isOK($response))
        {
            printResult($response,"CREATING CVS DB FAILED"," Database $dbName has been created.".PHP_EOL." ---- CREATING CVS DB SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "deleteDatabase":

            echo "Deleting CVS Database...".PHP_EOL;

            $email = getEMail();
            $password = getPassword();
            $dbName = getDbName();

            $response = deleteDatabase($email, $password, $dbName);

            if(isOK($response))
            {
                printResult($response,"DELETING CVS DB FAILED"," Database $dbName has been deleted.".PHP_EOL." ---- DELETING CVS DB SUCCESSFULLY COMPLETED ----");
            }
            else
            {
                echo $response->getMessage();
            }

            break;

    case "getDatabases":

            echo "Getting CVS Databases...".PHP_EOL;

            $email = getEMail();
            $password = getPassword();

            $response = getDatabases($email, $password);

            if(isOK($response))
            {
                printDatabasesResult($response,"GETTING CVS DBs FAILED"," ---- GETTING CVS DBs SUCCESSFULLY COMPLETED ----");
            }
            else
            {
                echo $response->getMessage();
            }

            break;

    case "addApplication":

        echo "Connecting Application to CVS Database...".PHP_EOL;

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $appId = getAppId();

        if (is_numeric($appId))
        {
            $response = addChannel($email, $password, $dbName, $appId);
        }
        else
        {
            $response = addApplication($email, $password, $dbName, $appId);
        }

        if(isOK($response))
        {
            printResult($response,"CONNECTING APPLICATION TO CVS DB FAILED"," Application $appId has been connected to the database $dbName.".PHP_EOL." ---- CONNECTING APPLICATION TO CVS DB SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "deleteApplication":

        $msg = "Deleting Application ...".PHP_EOL;
        echo PHP_EOL."$msg";

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $appId = getAppId();

        if (is_numeric($appId))
        {
            $response = deleteChannel($email, $password, $dbName, $appId);
        }
        else
        {
            $response = deleteApplication($email, $password, $dbName, $appId);
        }

        if(isOK($response))
        {
            printResult($response,"DELETING APPLICATION FROM CVS FAILED"," Application $appId has been deleted from the database $dbName.".PHP_EOL." ---- DELETING APPLICATION FROM CVS SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "addItem":

        echo "Adding item...".PHP_EOL;

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $filePath = getItemPath();
        $identifier = getIdentifier();
        $metadata = getMetadata();

        $msg = validateItemPath($filePath);
        if(isset($msg))
        {
            echo $msg;
            break;
        }

        $fileName = basename($filePath);
        $parentFolder = dirname($filePath);
        $oldFileName = $fileName;
        $msg = replaceWhitespaces($fileName, $parentFolder);
        if(isset($msg))
        {
            break;
        }
        else
        {
            $filePath = substr_replace($filePath, $fileName, strlen($filePath)-strlen($oldFileName));
        }

        echo PHP_EOL."Uploading ".$filePath."...".PHP_EOL;
        $response = addItem($email, $password, $dbName, $filePath, $identifier, $metadata);

        if(isOK($response))
        {
            printResult($response, "ADDING ITEM $filePath FAILED", " ".PHP_EOL." ---- ADDING ITEM $filePath SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "addTrackingData":

        echo "Adding trackables...".PHP_EOL;

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $localFolderName = getTrackablesFolderPath();

    $ext = pathinfo($localFolderName, PATHINFO_EXTENSION);

    if( strtolower($ext) == "zip" )
    {
        $response = addTrackingData($email, $password, $dbName, $localFolderName);

        echo PHP_EOL."... uploading ".$localFolderName."...".PHP_EOL;

        if(isOK($response))
        {
            printResult($response, " ---- ADDING TRACKABLES FAILED ---- ", " ---- ADDING TRACKABLES SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }
    }
        else {

        $msg = validateTrackablesFolderPath($localFolderName);
        if(isset($msg))
        {
            echo $msg;
            break;
        }

        $localFolder = opendir($localFolderName);

        $localFolderName = updateTrackablesFolderPath($localFolderName);

        $trackableIndex = 0;
        while($filename = readdir($localFolder))
        {
            if (!isValidPath($localFolderName,$filename))
            {
                continue;
            }

            $msg = replaceWhitespaces($filename, $localFolderName, "trackable");
            if(isset($msg))
            {
                continue;
            }

            $trackable = $localFolderName.$filename;
            $response = addTrackingData($email, $password, $dbName, $trackable);

            echo PHP_EOL."... uploading ".$filename."...".PHP_EOL;

            if(isOK($response))
            {
                printResult($response, " ---- ADDING TRACKABLE FAILED ---- ", " ---- ADDING TRACKABLE SUCCESSFULLY COMPLETED ----");
                if (!isError($response->getBody()))
                {
                    $trackableIndex++;
                }
                continue;
            }
            else
            {
                echo $response->getMessage();
            }
        }

        echo PHP_EOL."... $trackableIndex trackable(s) have been added.".PHP_EOL;

        }

        break;

    case "removeItem":

        echo "Removing item...".PHP_EOL;

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $filename = getItemFileName();

        $msg = validateItemExtension($filename, true);
        if(isset($msg))
        {
            echo $msg;
            break;
        }

        replaceWhitespaces($filename);

        $response = removeItem($email, $password, $dbName, $filename);

        if(isOK($response))
        {
            printResult($response, " ---- DELETING ITEM FAILED ---- ", PHP_EOL."    Item $filename has been deleted.".PHP_EOL.PHP_EOL." ---- DELETING ITEM SUCCESSFULLY COMPLETED ---- ");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "deleteTrackingData":

        echo "Deleting trackable...".PHP_EOL;

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $filename = getItemFileName("trackable");

        $msg = validateItemExtension($filename, true);
        if(isset($msg))
        {
            echo $msg;
            break;
        }

        replaceWhitespaces($filename);

        $tdNames = array($filename);

        $response = deleteTrackingDatas($email, $password, $dbName, $tdNames);

        if(isOK($response))
        {
            printResult($response, " ---- DELETING TRACKABLE $filename FAILED ---- ", PHP_EOL."    Trackable $filename has been deleted.".PHP_EOL." ---- DELETING TRACKABLE SUCCESSFULLY COMPLETED ---- ");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "getItems":

        echo "Getting Items...".PHP_EOL;

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();

        $response = getItems($email, $password, $dbName);

        if(isOK($response))
        {
            printImageArrayResult($response, " ---- GETTING ITEM FAILED ---- ", " ---- GETTING ITEM SUCCESSFULLY COMPLETED ---- ");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "getTrackingDatas":

        echo "Getting trackables...".PHP_EOL;

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();

        $response = getTrackingDatas($email, $password, $dbName);

        if(isOK($response))
        {
            printImageArrayResult($response, " ---- GETTING TRACKABLES FAILED ---- ", " ---- GETTING TRACKABLES SUCCESSFULLY COMPLETED ---- ", "trackable");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "getStats":

        echo "Getting Stats...".PHP_EOL;

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();

        $response = getStats($email, $password, $dbName);

        if(isOK($response))
        {
            printResult($response, " ---- GETTING STATS FAILED ---- ", " ---- GETTING STATS SUCCESSFULLY COMPLETED ---- ", true);
        }
        else
        {
            echo $response->getMessage();
        }

        break;
}

?>
