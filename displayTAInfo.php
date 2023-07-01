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
        <input type="text" name="searchTAid" id="searchTAid" placeholder="student ID">
        <button type="submit" value="search" onclick="hideTable()"><i class="fa fa-search"></i></button>
    </form>
    <br>
    <?php
    $conn = new PDO('sqlite:../307.db');
    $currentTerm = 'Winter2022'; // needs update

    // display search results
    if (isset($_POST["searchTAid"])) {
        $sid = $_POST["searchTAid"];
        $results = $conn->query("SELECT * FROM TA_course_assignment WHERE student_ID = '$sid';");
        displayTAs($results);
    } else {
        $results = $conn->query("SELECT * FROM TA_course_assignment;");
        displayTAs($results);
    }

    function displayTAs($results)
    {
        global $conn, $currentTerm;
        echo '<table class="infoTable" id-"infoTable">';
        echo "<tr>
            <th>Student ID</th>
            <th>TA Cohort</th>
            <th>Student Rating Avg.</th>
            <th>Professors' Logs</th>
            <th>Students' Comments</th>
            <th>Prof Wishlist</th>
            <th><div class='coursesCell'>Current Course(s)</div></th>
            <th><div class='coursesCell'>Past Course(s)</div></th>
        </tr>";
        foreach ($results as $eachAssig) {
            $sid = $eachAssig['student_ID'];
            $TA_Cohort = "";
            // get TA Cohort for each TA
            $results = $conn->query("SELECT * FROM TACohort WHERE student_ID=$sid;");
            foreach ($results as $row) {
                $TA_Cohort = "
                    <b>Term&Year</b>: $row[term_month_year]<br>
                    <b>StudentID</b>: $row[student_ID]<br>
                    <b>LegalName</b>: $row[legal_name]<br>
                    <b>Email</b>: $row[email]<br>
                    <b>Grad/Ugrad</b>: $row[grad_ugrad]<br>
                    <b>Supervisor</b>: $row[supervisor_name]<br>
                    <b>Priority</b>: $row[priority_]<br>
                    <b>Hours</b>: $row[hours_]<br>
                    <b>DateApplied</b>: $row[date_applied]<br>
                    <b>Location</b>: $row[location_]<br>
                    <b>Phone#</b>: $row[phone]<br>
                    <b>Degree</b>: $row[degree]<br>
                    <b>AppliedToCourses</b>: $row[courses_applied_for]<br>
                    <b>OpenToOtherCourses</b>: $row[open_to_other_courses]<br>
                    <b>Notes</b>: $row[notes]<br>
                ";
            }

            // $currterm = $_POST["termyear"];
            $currentTerm = 'Winter2022'; // needs update
            // get course currently assiged
            $currcourses = "";
            $results = $conn->query("SELECT course_num
            FROM TA_course_assignment
            WHERE student_ID = $sid
            AND term_month_year = '$currentTerm';");
            foreach ($results as $course) {
                $currcourses = $currcourses . $course['course_num'] . "<br>";
            }
            // get course previously assiged
            $prevcourses = "";
            $results = $conn->query("SELECT course_num
            FROM TA_course_assignment
            WHERE student_ID = $sid
            AND term_month_year != '$currentTerm'
            AND term_month_year != 'Fall2022'
            AND NOT term_month_year like '%2023'
            AND NOT term_month_year like '%2024';"); // needs update
            foreach ($results as $course) {
                $prevcourses = $prevcourses . $course['course_num'] . "<br>";
            }
            // get student rating avg
            $avgscore = "";
            $results = $conn->query("SELECT AVG(score) as average FROM studentTARating WHERE student_ID=$sid;");
            foreach ($results as $avg) {
                $avgscore = strval($avg['average']);
            }
            // get prof TA performence log
            $proflog = "";
            $results = $conn->query("SELECT comment FROM ProfTAPerformanceLog WHERE student_ID=$sid;");
            foreach ($results as $log) {
                $proflog = $proflog . $log['comment'] . "<br>";
            }
            // get student rating comments
            $studentcomments = "";
            $results = $conn->query("SELECT comment FROM studentTARating WHERE student_ID=$sid;");
            foreach ($results as $comment) {
                $studentcomments = $studentcomments . $comment['comment'] . "<br>";
            }
            // get profs that wish for this TA
            $wishingprofs = "";
            $results = $conn->query("SELECT prof_name FROM TAWishList WHERE student_ID=$sid;");
            foreach ($results as $prof) {
                $wishingprofs = $wishingprofs . $prof['prof_name'] . "<br>";
            }

            // generate the table row
            echo "<tr>
                <td>$eachAssig[student_ID]</td>
                <td><div class='cohortCell'>$TA_Cohort</div></td>
                <td>$avgscore</td>
                <td>$proflog</td>
                <td>$studentcomments</td>
                <td>$wishingprofs</td>
                <td>$currcourses</td>
                <td>$prevcourses</td>
            </tr>";
        }
        echo '</table>';
    }
    $conn->connection = null;
    ?>
</body>

</html>