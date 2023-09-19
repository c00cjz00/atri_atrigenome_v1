<?php

error_reporting(0); // Set E_ALL for debuging

// // Optional exec path settings (Default is called with command name only)
// define('ELFINDER_TAR_PATH',      '/PATH/TO/tar');
// define('ELFINDER_GZIP_PATH',     '/PATH/TO/gzip');
// define('ELFINDER_BZIP2_PATH',    '/PATH/TO/bzip2');
// define('ELFINDER_XZ_PATH',       '/PATH/TO/xz');
// define('ELFINDER_ZIP_PATH',      '/PATH/TO/zip');
// define('ELFINDER_UNZIP_PATH',    '/PATH/TO/unzip');
// define('ELFINDER_RAR_PATH',      '/PATH/TO/rar');
// define('ELFINDER_UNRAR_PATH',    '/PATH/TO/unrar');
// define('ELFINDER_7Z_PATH',       '/PATH/TO/7za');
// define('ELFINDER_CONVERT_PATH',  '/PATH/TO/convert');
// define('ELFINDER_IDENTIFY_PATH', '/PATH/TO/identify');
// define('ELFINDER_EXIFTRAN_PATH', '/PATH/TO/exiftran');
// define('ELFINDER_JPEGTRAN_PATH', '/PATH/TO/jpegtran');
// define('ELFINDER_FFMPEG_PATH',   '/PATH/TO/ffmpeg');

// define('ELFINDER_CONNECTOR_URL', 'URL to this connector script');  // see elFinder::getConnectorUrl()

// define('ELFINDER_DEBUG_ERRORLEVEL', -1); // Error reporting level of debug mode

// // To Enable(true) handling of PostScript files by ImageMagick
// // It is disabled by default as a countermeasure 
// // of Ghostscript multiple -dSAFER sandbox bypass vulnerabilities
// // see https://www.kb.cert.org/vuls/id/332928
// define('ELFINDER_IMAGEMAGICK_PS', true);
// ===============================================

// // load composer autoload before load elFinder autoload If you need composer
// // You need to run the composer command in the php directory.
is_readable('./vendor/autoload.php') && require './vendor/autoload.php';

// // elFinder autoload
require './autoload.php';
// ===============================================
/***** basic auth section *****/
session_start();  // 新增部分
// // Enable FTP connector netmount
//elFinder::$netDrivers['ftp'] = 'FTP';
// ===============================================

// // Required for Dropbox network mount
// // Installation by composer
// // `composer require kunalvarma05/dropbox-php-sdk` on php directory
// // Enable network mount
// elFinder::$netDrivers['dropbox2'] = 'Dropbox2';
// // Dropbox2 Netmount driver need next two settings. You can get at https://www.dropbox.com/developers/apps
// // AND require register redirect url to "YOUR_CONNECTOR_URL?cmd=netmount&protocol=dropbox2&host=1"
// // If the elFinder HTML element ID is not "elfinder", you need to change "host=1" to "host=ElementID"
// define('ELFINDER_DROPBOX_APPKEY',    '');
// define('ELFINDER_DROPBOX_APPSECRET', '');
// ===============================================

// // Required for Google Drive network mount
// // Installation by composer
// // `composer require google/apiclient:^2.0` on php directory
// // Enable network mount
// elFinder::$netDrivers['googledrive'] = 'GoogleDrive';
// // GoogleDrive Netmount driver need next two settings. You can get at https://console.developers.google.com
// // AND require register redirect url to "YOUR_CONNECTOR_URL?cmd=netmount&protocol=googledrive&host=1"
// // If the elFinder HTML element ID is not "elfinder", you need to change "host=1" to "host=ElementID"
// define('ELFINDER_GOOGLEDRIVE_CLIENTID',     '');
// define('ELFINDER_GOOGLEDRIVE_CLIENTSECRET', '');
// // Required case when Google API is NOT added via composer
// define('ELFINDER_GOOGLEDRIVE_GOOGLEAPICLIENT', '/path/to/google-api-php-client/vendor/autoload.php');
// ===============================================

// // Required for Google Drive network mount with Flysystem
// // Installation by composer
// // `composer require nao-pon/flysystem-google-drive:~1.1 nao-pon/elfinder-flysystem-driver-ext` on php directory
// // Enable network mount
// elFinder::$netDrivers['googledrive'] = 'FlysystemGoogleDriveNetmount';
// // GoogleDrive Netmount driver need next two settings. You can get at https://console.developers.google.com
// // AND require register redirect url to "YOUR_CONNECTOR_URL?cmd=netmount&protocol=googledrive&host=1"
// // If the elFinder HTML element ID is not "elfinder", you need to change "host=1" to "host=ElementID"
// define('ELFINDER_GOOGLEDRIVE_CLIENTID',     '');
// define('ELFINDER_GOOGLEDRIVE_CLIENTSECRET', '');
// // And "php/.tmp" directory must exist and be writable by PHP.
// ===============================================

// // Required for One Drive network mount
// //  * cURL PHP extension required
// //  * HTTP server PATH_INFO supports required
// // Enable network mount
// elFinder::$netDrivers['onedrive'] = 'OneDrive';
// // OneDrive Netmount driver need next two settings. You can get at
// // https://portal.azure.com/#blade/Microsoft_AAD_IAM/ActiveDirectoryMenuBlade/RegisteredApps
// // AND require register redirect url to "YOUR_CONNECTOR_URL/netmount/onedrive/1"
// // If the elFinder HTML element ID is not "elfinder", you need to change "/1" to "/ElementID"
// define('ELFINDER_ONEDRIVE_CLIENTID',     '');
// define('ELFINDER_ONEDRIVE_CLIENTSECRET', '');
// ===============================================

// // Required for Box network mount
// //  * cURL PHP extension required
// // Enable network mount
// elFinder::$netDrivers['box'] = 'Box';
// // Box Netmount driver need next two settings. You can get at https://developer.box.com
// // AND require register redirect url to "YOUR_CONNECTOR_URL?cmd=netmount&protocol=box&host=1"
// // If the elFinder HTML element ID is not "elfinder", you need to change "host=1" to "host=ElementID"
// define('ELFINDER_BOX_CLIENTID',     '');
// define('ELFINDER_BOX_CLIENTSECRET', '');
// ===============================================


// // Zoho Office Editor APIKey
// // https://www.zoho.com/docs/help/office-apis.html
// define('ELFINDER_ZOHO_OFFICE_APIKEY', '');
// ===============================================

// // Online converter (online-convert.com) APIKey
// // https://apiv2.online-convert.com/docs/getting_started/api_key.html
// define('ELFINDER_ONLINE_CONVERT_APIKEY', '');
// ===============================================

// // Zip Archive editor
// // Installation by composer
// // `composer require nao-pon/elfinder-flysystem-ziparchive-netmount` on php directory
// define('ELFINDER_DISABLE_ZIPEDITOR', false); // set `true` to disable zip editor
// ===============================================

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string    $attr    attribute name (read|write|locked|hidden)
 * @param  string    $path    absolute file path
 * @param  string    $data    value of volume option `accessControlData`
 * @param  object    $volume  elFinder volume driver object
 * @param  bool|null $isDir   path is directory (true: directory, false: file, null: unknown)
 * @param  string    $relpath file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume, $isDir, $relpath) {
	$basename = basename($path);
	return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
			 && strlen($relpath) !== 1           // but with out volume root
		? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
		:  null;                                 // else elFinder decide it itself
}
// 新增 cjz //
$domainName=$_SERVER['HTTP_HOST'];
$myElfinderFolder=$_SESSION["username"];
#$myPath="/s3disk/files/".$myElfinderFolder;
#$myServer="https://".$domainName."/s3disk/files/".$myElfinderFolder;
$myPath="/disk/files/".$myElfinderFolder;
$myServer="https://".$domainName."/disk/files/".$myElfinderFolder;
$publicPath="/s3diskLocal/public";
$publicPath="/disk/public";
$publicServer="http://".$domainName."/s3diskLocal/public";
if (!is_dir($myPath)) { mkdir($myPath); chmod($myPath, 0755); }
//if (!is_dir($publicPath)) { mkdir($publicPath); chmod($publicPath, 0755); }
#$dataServer="http://data.biobank.org.tw/files/".$myElfinderFolder;

$s3diskPath="/S3DISK/files/".$myElfinderFolder;


// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
## 新增
$opts = array(
        // 'debug' => true,
        'roots' => array(
                // Items volume
                array(
                        'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                        'alias'         => 'HOME',
                        'path'          => $myPath,                 // path to files (REQUIRED)
                        //'URL'           => $myServer,
                        //'URL'           => dirname($_SERVER['PHP_SELF']) . '/../files/', // URL to files (REQUIRED)
						//'URL'           => $dataServer, // URL to files (REQUIRED)
                        //'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
                        'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
                        'uploadDeny'    => array(''),
                        //'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
                        'uploadAllow'    => array('all'),
                        //'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', '
                        'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
                        'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
                ),				
                // Items volume
                array(
                        #'dispInlineRegex' => '^(?:image|application/(?:vnd\.)?(?:ms(?:-office|word|-excel|-powerpoint)|openx
                        'dispInlineRegex' => '^(?:image|text/(?:plain|html)$)',
                        'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                        'path'          => $publicPath,
                        'alias'         => 'PUBLIC',
                        'dirMode'        => 0755,            // new dirs mode (default 0755)
                        'fileMode'       => 0644,            // new files mode (default 0644)
                        //'URL'           => $publicServer, // URL to files (REQUIRED)
                        'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
                        'uploadDeny'    => array(''),
                        'uploadAllow'   => array('all'),
                        'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
                        'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
                ),
                #// Items volume
                array(
                        #'dispInlineRegex' => '^(?:image|application/(?:vnd\.)?(?:ms(?:-office|word|-excel|-powerpoint)|openx
                        #'dispInlineRegex' => '^(?:image|text/(?:plain|html)$)',
                        'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                        'path'          => $s3diskPath,
                        'alias'         => 'S3DISK',
                        'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
                        'uploadDeny'    => array('all'),
                        'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
                )				
                /*
                // Trash volume
                array(
                        'id'            => '1',
                        'driver'        => 'Trash',
                        'path'          => '../files/.trash/',
                        'tmbURL'        => dirname($_SERVER['PHP_SELF']) . '/../files/.trash/.tmb/',
                        'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
                        'uploadDeny'    => array('all'),                // Recomend the same settings as the original volume t
                        'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'te
                        'uploadAllow'    => array('all'),
                        //'uploadOrder'   => array('deny', 'allow'),      // Same as above
                        'accessControl' => 'access',                    // Same as above
                ),*/
        )
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

