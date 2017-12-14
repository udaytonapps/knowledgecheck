<!DOCTYPE html>
<html>
  <head>
    <title>Knowledge Check Preview</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="javascript:void(0);">Learning Apps</a>
<?php
	if(isset($_GET["PHPSESSID"])) {
		echo('<a class="btn btn-warning" style="margin-top:.5em;" href="/tsugi/lti/store/index.php?PHPSESSID='.$_GET["PHPSESSID"].'">Back to Store</a>');
	}
?>

        </div>

      </div>
    </nav>


       <!-- Main jumbotron for a primary marketing message or call to action -->
       <div class="jumbotron">
        <div class="container-fluid"> 
   	   <div class="col-sm-7">
   		<h2>Knowledge Check</h2>
   	        <pi style="font-size:18px">Are your students getting it? Wouldn’t it be nice if they could easily test themselves and find out before test days?  You can now create small quizzes that include multiple choice or true/false questions that your students can use to test their knowledge of important class material in your course sites. It’s quick, fun and it works great on a browser or mobile device. Knowledge Checks are great study tools for students to use after completing a reading, watching a video, or in preparation for an upcoming test.</p>
   	        <p><a class="btn btn-primary btn-lg" target="_blank" href="https://ewiki.udayton.edu/isidore/Knowledge_Check" role="button">Learn more &raquo;</a></p>
   	   </div>
   	   <div class="col-sm-5">
		<div class="videoWrapper">
			<iframe width="560" height="315" src="https://www.youtube.com/embed/xK73Ekq4x1k" frameborder="0" allowfullscreen></iframe>
		</div>
       </div>
	</div>
      </div>
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-sm-4">
          <h3>Track Usage</h3>
          <p>Instructors can easily see which students have taken a knowledge check through the ‘Usage’ button. Knowledge Check records a student's best score as well as their number of attempts. Usage data can also be exported to MS Excel. </p>
        </div>
        <div class="col-sm-4">
          <h3>Unlimited Attempts & Feedback</h3>
          <p>Knowledge checks are configured to give students an unlimited amount of attempts. This makes them great study aids. Students can use the ‘Review’ button to see feedback on their most recent attempt so they can see what they already know and what they still need to focus on.</p>
       </div>
        <div class="col-sm-4">
          <h3>Randomization</h3>
          <p>Instructors can easily randomize the order of the questions, the order of answer options, or both within each Knowledge Check to keep students on their feet while studying.</p>
        </div>
      </div>

    </div> <!-- /container -->


  </body>
</html>

