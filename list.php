<?php


require __DIR__ . '/google-drive.php';

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$service = new Google_Service_Drive($GLOBALS['client']);

$folderName = 'test folder';
$parameters['q'] = "name = '" . $folderName . "' and  trashed=false";
$files = $service->files->listFiles($parameters);

echo "<h3>List of files in the " . $folderName . " </h3>";
echo "<ul>";

$data = $files->getFiles();

foreach ($files as $file) {

    echo "<li>  {$file['name']}";
    try {
        // subfiles
        $sub_files = $service->files->listFiles(array('q' => "'{$file['id']}' in parents"));
        echo "<ul>";
        foreach ($sub_files as $sub_file) {
            echo "<li> {$sub_file['name']} </li>";
            $webLink = $service->files->get($sub_file['id'], array('fields' => 'webViewLink'));
            // print_r($webLink);
            echo $webLink['webViewLink'] . "<br>";
            $url = $webLink['webViewLink'];
            $name = $sub_file['name'];
            $url = "submit.php?submitted_to_s3=true&file=$url&name=$name";
            echo "<button><a href=$url style='color:black;'>Upload File to S3</a></button>";


            $content = $service->files->get($sub_file['id'], array("alt" => "media"));

            // Open file handle for output.
            
            $outHandle = fopen("images/".$name, "w+");
            // Until we have reached the EOF, read 1024 bytes at a time and write to the output file handle.
            
            while (!$content->getBody()->eof()) {
                    fwrite($outHandle, $content->getBody()->read(1024));
            }
            
            // Close output file handle.
            
            fclose($outHandle);
            echo "Done.\n";

        }
        echo "</ul>";
    } catch (Throwable $th) {
        dd($th);
    }

    echo "</li>";
}
echo "</ul>";


function dd(...$d)
{
    echo "<pre style='background-color:#000;color:#fff;' >";
    print_r($d);
    exit;
}
