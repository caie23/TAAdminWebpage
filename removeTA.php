<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .submitBtn {
            padding: 8px 24px;
            font-size: 16px;
            background-color: #DA3739;
            color: #ffffff;
            border: 1px solid black;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .submitBtn:active {
            background-color: #ffffff;
            color: black;
        }
    </style>
</head>

<body>
    <!-- remove TA -->
    <h2>Remove TA from course</h2>
    <p style="color: #DA3739;">
        Find the student ID of a TA from the
        <b>Course TA History</b> Page.
    </p>
    <form method="post">
        <label for="termyear">Term and year:</label>
        <select name="termyear" id="termyear" required>
            <option value="Winter2022">Winter2022</option>
            <option value="Fall2022">Fall2022</option>
            <option value="Winter2023">Winter2023</option>
        </select>
        <br><br>
        <label for="studentID">Student ID of the TA:</label>
        <input type="text" name="studentID" id="studentID" required>
        <br><br>
        <label for="coursenum">Course number (e.g. COMP307):</label>
        <input type="text" name="coursenum" id="coursenum" required>
        <br><br>
        <button class="submitBtn" type="submit" name="removeTA">Submit</button>
    </form>
    <?php
    if (isset($_POST["removeTA"])) {
        $conn = new PDO('sqlite:../307.db');

        $termyear = $_POST["termyear"];
        $studentID = $_POST["studentID"];
        $coursenum = $_POST["coursenum"];

        // add the assignment records to database
        $stmt = "DELETE FROM TA_course_assignment WHERE student_ID='$studentID' AND course_num='$coursenum' AND term_month_year='$termyear';";
        $query = $conn->prepare($stmt);
        // echo $stmt, "<br>";
        $query->execute();
        echo "<span style='color: #66AC50;'>REMOVED</span>";

        $conn->connection = null;
    }
    ?>
</body>

</html>