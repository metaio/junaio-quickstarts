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
    // TODO Remove debug part of URL
	$url = $url . '?XDEBUG_SESSION_START=mane';
    // TODO Switch to productive
	$client = new Zend_Http_Client("https://staging.metaio.com/REST/VisualSearch/".$url, $config); //https://testserver.junaio.com/REST/VisualSearch/
    // https://my.metaio.com/REST/VisualSearch/
	$client->setMethod(Zend_Http_Client::POST);
	$client->setParameterPost($params);
	if($localFile)
	{
		// Upload images to database
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
 * @param $image path to the image file
 * @param $identifier image identifier
 * @param $metadata image metadata
 * @return Zend_Http_Response HTTP response
 */
function addItem($email, $password, $dbName, $image, $identifier, $metadata)
{
    $postResponse = doPost
	(
        "addItem.php",
        array('timeout' => 15),
        array
		(
            'email' => $email,
            'password' => md5($password),
            'dbName' => $dbName,
			'identifier' => $identifier,
			'metadata' => $metadata
        ),
        $image,
		"item"
    );
	return $postResponse;
}

/**
 * Adds new tracking data to the database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $image path to the image file
 * @return Zend_Http_Response HTTP response
 */
function addTrackingData($email, $password, $dbName, $image)
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
        $image,
		"trackable"
    );
	return $postResponse;
}

/**
 * Removes existing item/image from the database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $itemName name of the image file
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
 * Deletes existing tracking datas / images from the database.
 * @param $email login email address at the license portal
 * @param $password password at the license portal
 * @param $dbName name of the new database
 * @param $tdNames list of names of the image files
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
 * Gets the names of the items/images contained in the database.
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
 * Gets the names of the tracking datas / images contained in the database.
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
    echo "\nPlease choose one of the commands: \n[addDatabase | deleteDatabase | getDatabases | addApplication | deleteApplication] \n[addItem | addTrackingData | removeItem | deleteTrackingData | getItems | getTrackingDatas | getStats ]\n";
    $action = trim(fgets(STDIN));
    return $action;
}

function getEMail()
{
    echo "\nPlease enter your e-mail!\n";
    $email = trim(fgets(STDIN));
    return $email;
}

function getPassword()
{
    echo "\nPlease enter your password!\n";
    $password = trim(fgets(STDIN));
    return $password;
}

function getDbName()
{
    echo "\nPlease enter the name of your database!\n";
    $dbName = trim(fgets(STDIN));
    return $dbName;
}

function getAppId()
{
    echo "\nPlease enter your Application or Channel ID!\n";
    $appId = trim(fgets(STDIN));
    return $appId;
}

function getImagePath()
{
    echo "\nPlease enter the path of your image!\n";
    $filename = trim(fgets(STDIN));
    return $filename;
}

function getImageFileName()
{
    echo "\nPlease enter the name of your image, including extension!\n";
    $filename = trim(fgets(STDIN));
    return $filename;
}

function validateImageExtension($filePath)
{
    // Check extension
    $extension = substr($filePath, strrpos($filePath, ".") + 1);
    if(strcasecmp($extension, "jpg") != 0 && strcasecmp($extension, "png") != 0)
    {
        $msg = "\nFile ".$filePath." skipped: it is not a jpg or png file - $extension\n";
        return $msg;
    }
}

function validateImagePath($filePath)
{
    if(!is_file($filePath))
    {
        $msg = "\nERROR: ".$filePath." is not a file (or you don't have permission to use it)\n ---- ADDING IMAGE FAILED ---- \n";
        return $msg;
    }

    $msg = validateImageExtension($filePath);

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
            $msg = "\nERROR: $localParentFolderPath.$fileName is not a file (or you don't have permission to use it)\n ---- ADDING IMAGE FAILED ---- \n";
            echo "\n$msg\n";
            return false;
        }
    }

    $msg = validateImageExtension($localParentFolderPath.$fileName);
    if(isset($msg)) // TODO check if works with implicit NULL as false
    {
        echo $msg;
        return false;
    }

    return true;
}

function getOS()
{
    $os = strtoupper(substr(php_uname('s'), 0, 3));
    return $os;
}

function getMoveCommand($parent, $oldFileName, $newFileName)
{
    $os = getOS();
    if (strcmp($os, "WIN") === 0)
    {
        $moveCommand = "rename \"$parent\\$oldFileName\" \"$newFileName\"";
    }
    else // Linux/Unix, Mac, ...
    {
        $moveCommand = "mv '$parent/$oldFileName' '$parent/$newFileName'";
    }
    // redirecting stderr to stdout
    $moveCommand .= " 2>&1";

    return $moveCommand;
}

function replaceWhitespaces(&$fileName, $parent = NULL)
{
    $found = strpos($fileName, " ");
    $position = $found;
    if ($found !== FALSE && $position > 0)
    {
        echo "\nReplacing white spaces\n";

        $newFileName = str_replace(" ", "_", $fileName);

        if ($parent !== NULL)
        {
            $cmd = getMoveCommand($parent, $fileName, $newFileName);

            exec($cmd, $out, $failure);

            if($failure)
            {
                $msg = "\nERROR - not possible to replace white spaces in image $fileName: $out[0]\n";
                echo $msg;
                return $msg;
            }
        }

        $fileName = $newFileName;
    }
}

function getIdentifier()
{
    echo "\nOptional: Please enter the identifier for your image!\n";
    $identifier = trim(fgets(STDIN));
    return $identifier;
}

function getMetadata()
{
    echo "\nOptional: Please enter the metadata for your image!\n";
    $metadata = trim(fgets(STDIN));
    return $metadata;
}

function getImageFolderPath()
{
    echo "\nPlease enter the folder-path of your images!\n";
    //images - important: folder name must end with "/", for example "images/" and not "images"
    $localFolderName = trim(fgets(STDIN));
    return $localFolderName;
}

function validateImageFolderPath($localFolderName)
{
    if(!is_dir($localFolderName))
    {
        $msg = "\nERROR: ".$localFolderName." is not a folder\n ---- ADDING IMAGE(S) FAILED ---- \n";
        return $msg;
    }
}

function updateImageFolderPath($localFolderName)
{
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

function printImageArrayResult($response, $failMsg, $successMsg)
{
    $myXml = new SimpleXMLElement($response->getBody());

    foreach($myXml as $tag)
    {
        if(strcmp($tag->getName(),"Error") === 0)
        {
            $msg = "\n".$tag."\n\n$failMsg\n";
        }
        else
        {
            $itemName = "";
            foreach($tag->children() as $item)
            {
                $itemName .= $item['Name'] . "\n" . "  ";
            }
            $msg = "\n... got image: \n  $itemName\n\n $successMsg \n\n";
        }

        echo $msg;
    }
}

//-------------- EXECUTION START --------------

$action = getAction();

switch ($action)
{
    case "addDatabase":

        echo "Creating CVS Database...\n";

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();

        $response = addDatabase($email, $password, $dbName);

        if(isOK($response))
        {
            printResult($response,"CREATING CVS DB FAILED"," Database $dbName has been created.\n ---- CREATING CVS DB SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "deleteDatabase":

            echo "Deleting CVS Database...\n";

            $email = getEMail();
            $password = getPassword();
            $dbName = getDbName();

            $response = deleteDatabase($email, $password, $dbName);

            if(isOK($response))
            {
                printResult($response,"DELETING CVS DB FAILED"," Database $dbName has been deleted.\n ---- DELETING CVS DB SUCCESSFULLY COMPLETED ----");
            }
            else
            {
                echo $response->getMessage();
            }

            break;

    case "getDatabases":

            echo "Getting CVS Databases...\n";

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

        echo "Connecting Application to CVS Database...\n";

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
            printResult($response,"CONNECTING APPLICATION TO CVS DB FAILED"," Application $appId has been connected to the database $dbName.\n ---- CONNECTING APPLICATION TO CVS DB SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "deleteApplication":

        $msg = "Deleting Application ...\n";
        echo "\n$msg";

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
            printResult($response,"DELETING APPLICATION FROM CVS FAILED"," Application $appId has been deleted from the database $dbName.\n ---- DELETING APPLICATION FROM CVS SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "addItem":

        echo "Adding image...\n";

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $filePath = getImagePath();
        $identifier = getIdentifier();
        $metadata = getMetadata();

        $msg = validateImagePath($filePath);
        if(isset($msg)) // TODO check if works with implicit NULL as false
        {
            echo $msg;
            break;
        }

        // TODO Whitespaces should be replaced in file name only! (and not in local folder name!)
        $fileName = basename($filePath);
        $parentFolder = dirname($filePath);
        $oldFileName = $fileName;
        $msg = replaceWhitespaces($fileName, $parentFolder); // TODO double-check if passing of argument is OK (as it is by reference!)
        if(isset($msg)) // TODO check if works with implicit NULL as false
        {
            break;
        }
        else
        {
            $filePath = substr_replace($filePath, $fileName, strlen($filePath)-strlen($oldFileName));
        }

        echo "\nUploading ".$filePath."...\n";
        $response = addItem($email, $password, $dbName, $filePath, $identifier, $metadata);

        if(isOK($response))
        {
            printResult($response, "ADDING IMAGE $filePath FAILED", " \n ---- ADDING IMAGE $filePath SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "addTrackingData":

        echo "Adding images...\n";

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $localFolderName = getImageFolderPath();

    $ext = pathinfo($localFolderName, PATHINFO_EXTENSION);

    if( strtolower($ext) == "zip" )
    {
        $response = addTrackingData($email, $password, $dbName, $localFolderName);

        echo "\n\n... uploading ".$localFolderName."...\n\n";

        if(isOK($response))
        {
            printResult($response, " ---- ADDING IMAGE FAILED ---- ", " ---- ADDING IMAGE SUCCESSFULLY COMPLETED ----");
        }
        else
        {
            echo $response->getMessage();
        }
    }
        else {

        $msg = validateImageFolderPath($localFolderName);
        if(isset($msg))
        {
            echo $msg;
            break;
        }

        $localFolder = opendir($localFolderName);

        $localFolderName = updateImageFolderPath($localFolderName);

        $imageIndex = 0;
        while($filename = readdir($localFolder))
        {
            if (!isValidPath($localFolderName,$filename))
            {
                continue;
            }

            $msg = replaceWhitespaces($filename, $localFolderName); // TODO double-check if passing of argument is OK (as it is by reference!)
            if(isset($msg)) // TODO check if works with implicit NULL as false
            {
                continue;
            }

            $image = $localFolderName.$filename;
            $response = addTrackingData($email, $password, $dbName, $image);

            echo "\n\n... uploading ".$filename."...\n\n";

            if(isOK($response))
            {
                printResult($response, " ---- ADDING IMAGE FAILED ---- ", " ---- ADDING IMAGE SUCCESSFULLY COMPLETED ----");
                if (!isError($response->getBody()))
                {
                    $imageIndex++;
                }
                continue;
            }
            else
            {
                echo $response->getMessage();
            }
        }

        echo "\n\n... $imageIndex image(s) have been added.\n\n";

        }

        break;

    case "removeItem":

        echo "Removing image...\n";

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $filename = getImageFileName();

        $msg = validateImageExtension($filename);
        if(isset($msg)) // TODO check if works with implicit NULL as false
        {
            echo $msg;
            break;
        }

        replaceWhitespaces($filename); // TODO double-check if passing of argument is OK (as it is by reference!)

        $response = removeItem($email, $password, $dbName, $filename);

        if(isOK($response))
        {
            printResult($response, " ---- DELETING IMAGE FAILED ---- ", "\n    Image $filename has been deleted.\n\n ---- DELETING IMAGE SUCCESSFULLY COMPLETED ---- ");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "deleteTrackingData":

        echo "Deleting image...\n";

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();
        $filename = getImageFileName();

    //    $msg = validateImageExtension($filename);
    //    if(isset($msg)) // TODO check if works with implicit NULL as false
    //    {
    //        echo $msg;
    //        break;
    //    }

        replaceWhitespaces($filename); // TODO double-check if passing of argument is OK (as it is by reference!)

        $tdNames = array($filename); //."_0.td");

        $response = deleteTrackingDatas($email, $password, $dbName, $tdNames);

        if(isOK($response))
        {
            printResult($response, " ---- DELETING IMAGE $filename FAILED ---- ", "\n    Image $filename has been deleted.\n ---- DELETING IMAGE SUCCESSFULLY COMPLETED ---- ");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "getItems":

        echo "Getting Items...\n";

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

        echo "Getting Tracking Data...\n";

        $email = getEMail();
        $password = getPassword();
        $dbName = getDbName();

        $response = getTrackingDatas($email, $password, $dbName);

        if(isOK($response))
        {
            printImageArrayResult($response, " ---- GETTING TRACKING DATA FAILED ---- ", " ---- GETTING TRACKING DATA SUCCESSFULLY COMPLETED ---- ");
        }
        else
        {
            echo $response->getMessage();
        }

        break;

    case "getStats":

        echo "Getting Stats...\n";

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
