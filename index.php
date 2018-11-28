

<?php
require 'connectDB.php';
?>

<html>
  <head>
	<meta charset = "UTF-8">
	<link rel="icon" type="image/icon" href="UsfFavicon.ico" /> 
	<link rel="stylesheet" href="CSS/AsTh.css" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawTemp);
      google.charts.setOnLoadCallback(drawHumidity);
      google.charts.setOnLoadCallback(drawWind);

      // Callback that creates and populates a data table,
      // instantiates the Line chart, passes in the data and
      // draws it.
      function drawTemp() {

        // Create the data table.
	var data = google.visualization.arrayToDataTable([
	['Date', 'Temperature'],
	<?php
		$sqlQuery = "SELECT * FROM weather";
		$result = mysqli_query($conn,$sqlQuery);

		while($row = mysqli_fetch_array($result)){
			echo "['".date('g:i A',strtotime($row['date']))."', ".$row['temp']."],";
	}
	?>	
        ]);

        // Set chart options
        var options = {'title':'Temperature (*F)',
			'width':1500,
			'height':250,
			'curveType': 'function',
			pointSize: 5,
			series: {0:{pointShape:'circle'}},
			chartArea:{width:"65%",height:"55%"}
			
			};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('tempID'));
        chart.draw(data, options);
      }

	function drawHumidity() {
        // Create the data table.
	var data = google.visualization.arrayToDataTable([
	['Date', 'Humidity', 'Pressure'],
	<?php
		$sqlQuery = "SELECT date, humidity, pressure FROM weather";
		$result = mysqli_query($conn,$sqlQuery);

		while($row = mysqli_fetch_array($result)){
			echo "['".date('g:i A',strtotime($row['date']))."', ".$row['humidity'].", ".round(($row['pressure'] / 33.864), 1)."],";
	}
		
	?>	
        ]);

        // Set chart options
        var options = {'title':'Humidity (%) & Pressure (inhg)',
			'width':1500,
			'height':250,
			'curveType': 'function',
			pointSize: 5,
			series: {0:{pointShape:'circle'}},
			chartArea:{width:"65%",height:"55%"}
			};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('humID'));
        chart.draw(data, options);
      }
	
	function drawWind() {

        // Create the data table.
	var data = google.visualization.arrayToDataTable([
	['Date', 'Wind Speed'],
	<?php
		$sqlQuery = "SELECT windSpeed, date FROM weather";
		$result = mysqli_query($conn,$sqlQuery);

		while($row = mysqli_fetch_array($result)){
			echo "['".date('g:i A',strtotime($row['date']))."', ".$row['windSpeed']."],";
	}
	?>	
        ]);

        // Set chart options
        var options = {'title':'Wind Speed (mph)',
			'width':1500,
			'height':250,
			'curveType': 'function',
                      	pointSize: 5,
			series: {0:{pointShape:'circle'}},
			chartArea:{width:"65%",height:"55%"}
			};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('windID'));
        chart.draw(data, options);
      }

	

    </script>
  </head>

  <body>
	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="http://mariodiaz.myweb.usf.edu/AstroThunder.html">AstroThunder</a>

    <div class="dropdown">
  <button type="button" class="btn btn-primary bg-dark border-dark dropdown-toggle" data-toggle="dropdown">
    Weather
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="http://astroweather.ddns.net">Weather Station</a>
    <a class="dropdown-item" href="http://mariodiaz.myweb.usf.edu/index.php">Weather Forecast</a>    
  </div>
</div>
        
    <div class="dropdown">
  <button type="button" class="btn btn-primary bg-dark border-dark dropdown-toggle" data-toggle="dropdown">
    About
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="http://mariodiaz.myweb.usf.edu/testingDoc.html">Testing Doc</a>
    <a class="dropdown-item" href="http://mariodiaz.myweb.usf.edu/contact.html">Contact Us</a>    
  </div>
</div>    

        
</nav>
<br>

	<p style="font-size:20px;">
	<?php
		$sqlQuery = "SELECT date FROM weather ORDER BY ID DESC LIMIT 1";
		$result = mysqli_query($conn,$sqlQuery);
		$row = mysqli_fetch_array($result);
		echo "Last update: ".date('l jS \of F Y h:i:s A',strtotime($row['date']))."";
	?>
	</p>

	<h1 style="font-size:45px;"><center>Tampa, FL</center></h1>

	<table align="center"style="margin: auto;">
	<tr>
	<td width=25%;>
	<div style="font-size:200%;">
		<?php
		$sqlQuery = "SELECT temp FROM weather ORDER BY ID DESC LIMIT 1";
		$result = mysqli_query($conn,$sqlQuery);
		$row = mysqli_fetch_array($result);
		echo "Temperature: " . $row['temp'] . " F";
		?>
	</div>
	</td>

	<td width=25%;>
	<div style="font-size:200%;">
		<?php
		$sqlQuery = "SELECT humidity FROM weather ORDER BY ID DESC LIMIT 1";
		$result = mysqli_query($conn,$sqlQuery);
		$row = mysqli_fetch_array($result);
		echo "Humidity: " . $row['humidity'] . " %";
		?>
	</div>
	</td>

	<td width=25%;>
	<div style="font-size:200%;">
		<?php
		$sqlQuery = "SELECT pressure FROM weather ORDER BY ID DESC LIMIT 1";
		$result = mysqli_query($conn,$sqlQuery);
		$row = mysqli_fetch_array($result);
		echo "Pressure: " . round(($row['pressure'] / 33.864), 1) . " inhg";
		?>
	</div>
	</td>

	<td width=25%;>
	<div style="font-size:200%;">
		<?php
		$sqlQuery = "SELECT windSpeed, windDir FROM weather ORDER BY ID DESC LIMIT 1";
		$result = mysqli_query($conn,$sqlQuery);
		$row = mysqli_fetch_array($result);
		echo "Wind Speed: " . $row['windSpeed'] . " mph, " . $row['windDir'];
		?>
	</div>
	
	</td>
	</tr>
	
	</table><br>

	<p align="center" style="font-size:25px;"><strong>Recent Forecast:</strong>
	<?php
		$sqlQuery = "SELECT whatsitlike FROM weather ORDER BY ID DESC LIMIT 1";
		$result = mysqli_query($conn,$sqlQuery);
		$row = mysqli_fetch_array($result);
		echo $row['whatsitlike'];
		?>
	</p>
	
    <table class="columns" align="center">
	<tr>
    		<!--Div that will hold the Line chart-->
    	<td><div id="tempID"></div></td>
	</tr>
	<tr>
	<td><div id="humID"></div>
	</tr>
	<tr>
	<td><div id="windID"></div></td>
	</tr></td>
    </table>
    
  </body>
</html>
