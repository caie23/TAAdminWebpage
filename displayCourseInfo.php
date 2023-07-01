<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #myForm {
            display: none;
        }

        .infoTable {
            border-collapse: collapse;
            border: 3px solid lightgray;
            margin-bottom: 120px;
        }

        .infoTable th {
            background-color: #C94540;
            color: #ffffff;
            height: 30px;
        }

        .infoTable th,
        td {
            border: 1px solid #666666;
            padding: 8px;
        }

        .coursesCell {
            width: 100px;
            overflow: auto;
        }

        .cohortCell {
            overflow: auto;
            width: 200px;
            height: 100px;
        }

        /** BACK TO TOP **/
        #toTopBtn {
            position: fixed;
            bottom: 100px;
            right: 10px;
            z-index: 98;
            padding: 15px;
        }

        #toTopBtn img {
            height: 30px;
        }

        * {
            box-sizing: border-box;
        }

        form.searchBox {
            max-width: 300px
        }

        form.searchBox input[type=text] {
            padding: 10px;
            font-size: 17px;
            border: 1px solid grey;
            float: left;
            width: 80%;
            background: #f1f1f1;
        }

        form.searchBox button {
            float: left;
            width: 20%;
            padding: 10px;
            background: #C94540;
            color: white;
            font-size: 17px;
            border: 1px solid grey;
            border-left: none;
            cursor: pointer;
        }

        form.searchBox button:hover {
            background: #EA3223;
        }

        form.searchBox::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
    <script>
        function hideTable() {
            document.getElementById('infoTable').innerHTML = "";
        }
    </script>
</head>

<body>
    <div>
        <a href="#" id="toTopBtn">
            <img src="img/backtotop.png" alt="back to top">
        </a>
    </div>
    <form method="POST" class="searchBox">
        <input type="text" name="searchCourse" id="searchCourse" placeholder="course number">
        <button type="submit" value="search" onclick="hideTable()"><i class="fa fa-search"></i></button>
    </form>
    <br>
    <?php
    $conn = new PDO('sqlite:../307.db');
    $currentTerm = 'Winter2022'; // needs update

    // display search results
    if (isset($_POST["searchCourse"])) {
        $aCourse = $_POST["searchCourse"];
        $results = $conn->query("SELECT * FROM CourseQuota WHERE course_num = '$aCourse';");
        displayCourses($results);
    } else {
        $results = $conn->query("SELECT * FROM CourseQuota;");
        displayCourses($results);
    }

    function displayCourses($results)
    {
        global $conn, $currentTerm;

        echo '<table class="infoTable" id="infoTable">';
        echo "<tr>
            <th>Course Num</th>
            <th>Course Name</th>
            <th>Current TAs</th>
            <th>Past TAs</th>
            <th>Max number of TAs</th>
            </tr>";

        foreach ($results as $eachcourse) {
            $cnum = $eachcourse['course_num'];
            // get the course name
            $coursename = "";
            $results = $conn->query("SELECT course_name FROM CourseQuota WHERE course_num='$cnum';");
            foreach ($results as $name) {
                $coursename = $name['course_name'];
            }
            // get the current TAs
            $currTAs = "";
            $results = $conn->query("SELECT B.TA_name FROM TA_course_assignment A, TACohort B
                    WHERE A.student_ID = B.student_ID
                    AND A.course_num='$cnum' AND A.term_month_year ='$currentTerm';");
            foreach ($results as $TA) {
                $currTAs = $currTAs . $TA['TA_name'] . "<br>";
            }
            // get the past TAs
            $pastTAs = "";
            $results = $conn->query("SELECT B.TA_name FROM TA_course_assignment A, TACohort B
                    WHERE A.student_ID = B.student_ID
                    AND A.course_num = '$cnum' 
                    AND A.term_month_year != '$currentTerm'
                    AND A.term_month_year != 'Fall2022'
                    AND NOT A.term_month_year like '%2023'
                    AND NOT A.term_month_year like '%2024';"); // needs update
            foreach ($results as $TA) {
                $pastTAs = $pastTAs . $TA['TA_name'] . "<br>";
            }
            // get the max number of TAs permitted for a course
            $maxnum = "";
            $results = $conn->query("SELECT TA_number FROM CourseQuota WHERE course_num='$cnum';");
            foreach ($results as $num) {
                $maxnum = $num['TA_number'];
            }

            // generate the table row
            echo "<tr>
                <td>$cnum</td>
                <td>$coursename</td>
                <td>$currTAs</td>
                <td>$pastTAs</td>
                <td>$maxnum</td>
                </tr>";
        }
        echo '</table>';
    }

    $conn->connection = null;
    ?>
</body>

</html>