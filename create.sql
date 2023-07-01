
-- table initialization

DROP TABLE IF EXISTS CourseQuota;
CREATE TABLE CourseQuota (
    term_month_year VARCHAR(10),
    course_num CHAR(7),
    course_type CHAR(4),
    course_name VARCHAR(50),
    instructor_name VARCHAR(30),
    course_enrollment_num INTEGER,
    TA_quota INTEGER,
    PRIMARY KEY (course_name)
);
DROP TABLE IF EXISTS TACohort;
CREATE TABLE TACohort (
    term_month_year VARCHAR(10),
    TA_name VARCHAR(30),
    student_ID INTEGER,
    legal_name VARCHAR(30),
    email VARCHAR(50),
    grad_ugrad VARCHAR(15),
    supervisor_name VARCHAR(30),
    priority_ BOOLEAN,
    hours_ INTEGER,
    date_applied DATE,
    location_ VARCHAR(50),
    phone INTEGER,
    degree VARCHAR(10),
    courses_applied_for VARCHAR(100),
    open_to_other_courses BOOLEAN,
    notes VARCHAR(100),
    PRIMARY KEY (student_ID)
);

DROP TABLE IF EXISTS TA_course_assignment;
CREATE TABLE TA_course_assignment (
    term_month_year VARCHAR(10),
    student_ID INTEGER,
    course_num CHAR(7),
    PRIMARY KEY (term_month_year, student_ID, course_num)
);
INSERT INTO TA_course_assignment(term_month_year, student_ID, course_num)
VALUES ('Fall2021', '260123456', 'COMP307'),
    ('Fall2021', '260234567', 'COMP308'),
    ('Fall2021', '260345678', 'COMP322'),
    ('Winter2022', '260456789', 'COMP350'),
    ('Winter2022', '260567890', 'COMP360'),
    ('Winter2022', '260472859', 'COMP303'),
    ('Fall2022', '260654321', 'COMP330'),
    ('Fall2022', '260765432', 'COMP251'),
    ('Winter2023', '260876543', 'COMP206'),
    ('Winter2023', '260098765', 'COMP350');

DROP TABLE IF EXISTS TAWishList;
CREATE TABLE TAWishList (
    term_month_year VARCHAR(10),
    course_num CHAR(7),
    prof_name VARCHAR(30),
    student_ID INTEGER,
    PRIMARY KEY (term_month_year, course_num, student_ID)
);
INSERT INTO TAWishList VALUES
('Fall2022','COMP322','Chad',260123456),
('Fall2022','COMP350','Lu',260234567),
('Fall2022','COMP360','Robert',260345678),
('Fall2022','COMP307','Joseph',260456789),
('Fall2022','COMP308','Joseph',260567890),
('Fall2022','COMP421','Joseph',260472859),
('Winter2023','COMP202','Giulia',260654321),
('Winter2023','COMP206','Joseph',260765432),
('Winter2023','COMP330','Claude',260876543),
('Winter2023','COMP310','Mahesh',260098765);

DROP TABLE IF EXISTS ProfTAPerformanceLog;
CREATE TABLE ProfTAPerformanceLog (
    term_month_year VARCHAR(10),
    course_num CHAR(7),
    student_ID INTEGER,
    comment VARCHAR(100),
    PRIMARY KEY (term_month_year, course_num, student_ID)
);
INSERT INTO ProfTAPerformanceLog VALUES
('Fall2021','COMP307',260123456,'GREAT'),
('Fall2021','COMP308',260234567,'GREAT'),
('Fall2021','COMP322',260345678,'GREAT'),
('Winter2022','COMP350',260456789,'GOOD'),
('Winter2022','COMP360',260567890,'GOOD'),
('Winter2022','COMP303',260472859,'GOOD'),
('Fall2022','COMP330',260654321,'OK'),
('Fall2022','COMP251',260765432,'OK'),
('Winter2023','COMP206',260876543,'NOTOK'),
('Winter2023','COMP350',260098765,'HAHA');

DROP TABLE IF EXISTS studentTARating;
CREATE TABLE studentTARating (
    student_ID INTEGER,
    term_month_year VARCHAR(10),
    course_num CHAR(7),
    score INTEGER,
    comment VARCHAR(100),
    PRIMARY KEY (term_month_year, course_num, student_ID)
);
INSERT INTO studentTARating VALUES
(260123456,'Fall2021','COMP307',5,'not very patient'),
(260234567,'Fall2021','COMP308',5,'very responsible and helpful'),
(260345678,'Fall2021','COMP322',5,'nice TA and knowledgable'),
(260456789,'Winter2022','COMP350',4,'very good'),
(260567890,'Winter2022','COMP360',4,'good'),
(260472859,'Winter2022','COMP303',4,'not good'),
(260654321,'Fall2022','COMP330',3,'nice and patient'),
(260765432,'Fall2022','COMP251',3,'good TA'),
(260876543,'Winter2023','COMP206',2,'very nice'),
(260098765,'Winter2023','COMP350',1,'super friendly');

-- queries

-- current term
SELECT course_num
FROM TA_course_assignment
WHERE student_ID = $sid
    AND term_month_year = 'Winter2022';
-- past terms
SELECT student_ID,
    course_num
FROM TA_course_assignment
WHERE term_month_year != 'Winter2022'
    AND term_month_year!='Fall2022'
    AND NOT term_month_year LIKE '%2023';


-- get student rating avg
SELECT AVG(score) as average FROM studentTARating WHERE student_ID=260123456;
-- get prof TA performence log
SELECT comment FROM ProfTAPerformanceLog WHERE student_ID=260123456;
-- get student rating comments
SELECT comment FROM studentTARating WHERE student_ID=260123456;
-- get profs that wish for this TA
SELECT prof_name FROM TAWishList WHERE student_ID=260123456;

-- get the course name
SELECT course_name FROM CourseQuota WHERE course_num='COMP330';
-- get the current TAs
SELECT student_ID FROM TA_course_assignment WHERE course_num='COMP330' AND term_month_year ='Winter2022';
SELECT B.TA_name FROM TA_course_assignment A, TACohort B
WHERE A.student_ID = B.student_ID
AND A.course_num='COMP330' AND A.term_month_year ='Winter2022';
-- get the past TAs
SELECT student_ID FROM TA_course_assignment WHERE course_num='COMP330' AND term_month_year!='Winter2022';
-- get the max # of TAs
SELECT TA_number FROM CourseQuota WHERE course_num='COMP330';

-- remove TA
DELETE FROM TA_course_assignment WHERE student_ID=260123456 AND course_num='COMP551' AND term_month_year='Winter2022';

-- search TA by name
SELECT A.student_ID as student_ID FROM TA_course_assignment A, TACohort B 
WHERE A.student_ID=B.student_ID 
AND TA_name LIKE '%addie%';