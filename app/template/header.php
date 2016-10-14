<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xmlns:svg="http://www.w3.org/2000/svg"  
        xmlns:xlink="http://www.w3.org/1999/xlink" lang="en">
<head>
    <title>
        MeteoVis - <?php if (isset($_POST["inVille"])){echo $_POST["inVille"];}else{echo'Montréal';}?>
    </title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <script>
        var BASE_URL = "<?php echo Config::get('app.url'); ?>";
    </script>
</head>
<body>
    <div class="container">