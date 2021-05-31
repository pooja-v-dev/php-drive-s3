<?php
error_reporting(E_ERROR | E_PARSE);
require __DIR__ . '/google-drive.php';

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$service = new Google_Service_Drive($GLOBALS['client']);

$folderName = 'test folder';
$parameters['q'] = "name = '" . $folderName . "' and  trashed=false";
$files = $service->files->listFiles($parameters);


if (isset($_GET['submitted_to_s3'])) {
    $url = getcwd().'/images/'.$_GET['name'];
    
    // print_r($url);die();

    //    if(!$fh = fopen($url, 'w')) {
    //     print 'Can\'t open file';
    //   }
    //   else {
    //     print 'Success open file';
    //   }
    // file_put_contents("Tmpfile.zip", fopen($url, 'r'));
    // die();

    try {

        $bucketName = '';
        $IAM_KEY = '';
        $IAM_SECRET = '';


        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => $IAM_KEY,
                    'secret' => $IAM_SECRET
                ),
                'version' => 'latest',
                'region'  => 'ap-south-1'
            )
        );
    } catch (Exception $e) {

        die("Error: " . $e->getMessage());
    }

    $keyName = 'test_example/'.$_GET['name'];
    $pathInS3 = 'https://s3.ap-south-1.amazonaws.com/' . $bucketName . '/' . $keyName;
    $result = $s3->putObject(
        array(
            'Bucket' => $bucketName,
            'Key' =>  $keyName,
            'SourceFile' => $url,
            'ACL'        => 'public-read', //for making the public url
            'StorageClass' => 'REDUCED_REDUNDANCY'
        )
    );

    $url = $result->get('ObjectURL');
    echo "<br>Image uploaded successfully. Image path is: " . $result->get('ObjectURL');
}
