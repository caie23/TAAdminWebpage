<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TA Administration</title>
</head>

<!-- For Testing: https://www.cs.mcgill.ca/~cfeng11/project/TAadministration.php -->

<body>
    <?php

    // MAIN PROGRAM
    displayActive("matter/header.txt", $_GET["Page"]);
    if (sizeof($_GET) == 0 || $_GET["Page"] == "Welcome") {
        display("matter/Welcome.txt");
    } else if ($_GET["Page"] == "Import") {
        display("matter/importTACohort.txt");
        include 'import.php';
    } else if ($_GET["Page"] == "TAInfo") {
        display("matter/TAInfo.txt");
        include 'displayTAInfo.php';
    } else if ($_GET["Page"] == "CourseTA") {
        display("matter/CourseTA.txt");
        include 'displayCourseInfo.php';
    } else if ($_GET["Page"] == "AddTA") {
        include 'addTA.php';
    } else if ($_GET["Page"] == "RemoveTA") {
        include 'removeTA.php';
    } else {
        echo "404: Invalid Page!";
    }
    display("matter/footer.txt");
    // END MAIN

    function display($path)
    {
        $file = fopen($path, "r");

        while (!feof($file)) {
            $line = fgets($file);
            echo $line;
        }
        fclose($file);
    }

    function displayActive($path, $target)
    {
        $file = fopen($path, "r");
        if (sizeof($target) == 0) $target = "Page=Welcome";
        else $target = "Page=" . $target;
        while (!feof($file)) {
            $line = fgets($file);
            if (strstr($line, $target)) $line = str_replace("class=\"empty\"", "class=\"active\"", $line);
            else $line = str_replace("class=\"active\"", "class=\"empty\"", $line);
            echo $line;
        }
        fclose($file);
    }
    ?>
</body>

</html>