<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>
    <h2>PHP Google Drive Api </h2>
    <a href="submit.php?list_files_and_folders=1">Click here to list all files and folders</a>

    <h2>File Upload</h2>
    <form action="submit.php" method="post" enctype="multipart/form-data" >
        <label for="">Choose File</label>
        <input type="file" name="file" >
        
        <input type="submit" name="submit" value="submit" >
    </form>
</body>
</html>