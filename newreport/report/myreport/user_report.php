<?php
$times  = array();
for($month = 1; $month <= 12; $month++) {
    $first_minute = mktime(0, 0, 0, $month, 1);
    $last_minute = mktime(23, 59, 59, $month, date('t', $first_minute));
    $times[$month] = array(date('m/d/Y/F', $first_minute), date('m/d/Y/F', $last_minute));
}
foreach ($times as $value) {
  echo $value[0];
  echo "....";
  echo $value[1];
  echo "</br>";
}

echo date('m/d/Y', 1513683086); echo "</br>";
$timestamp1 = strtotime('14-01-2018');
echo $timestamp1;
echo ".....";
$timestamp2 = strtotime('16-01-2018');
echo $timestamp2;
echo ".......";
// $timestamp3 = new DateTime('2018-01-16 03:55:06');
// echo $timestamp3;
echo "</br>";
for($i=30;$i>=0;$i-=3){
  echo date("'d-m-Y'", strtotime('-' . $i . ' days'));
  echo "</br>";
}
echo "......";
$date='2016-11-30';
$t1=strtotime($date."".'00:00:00');
$t2=strtotime($date."".'24:00:00');
echo $t1;  echo ".....";
echo $t2;
 ?>
 <button onclick="show_first();">first</button>
 <button onclick="show_second();">second</button>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
 <div style="height:400px;width:400px;">
 <canvas id="myChart" width="400" height="400"></canvas>
 </div>
<script type="text/javascript">
function show_first(){
var ctx = document.getElementById("myChart").getContext("2d");
var data = {
  labels: ["1 jan", "2 jan", "3 jan", "4 jan", "5 jan"],
  datasets: [
      {
          label: "Logins",
          backgroundColor: "blue",
          // data: [3,7,4,1,2]
          data: [<?php for($i=0;$i<5;$i++){
            echo "5"; echo ",";
          }?>]
      },
      {
          label: "Course Completions",
          backgroundColor: "red",
          data: [<?php $myArray = array(1,2,3,4,5);
          foreach ($myArray as $arr) {
            echo $arr;echo ",";
          }
          ?>]
      },

  ]
};

var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: data,
  options: {
      barValueSpacing: 20,
      scales: {
          yAxes: [{
              ticks: {
                  min: 0,
              }
          }]
      }
  }
});
}



function show_second(){
var ctx = document.getElementById("myChart").getContext("2d");
var data = {
  labels: ["1 jan", "2 jan", "3 jan", "4 jan", "5 jan"],
  datasets: [
      {
          label: "Logins",
          backgroundColor: "blue",
          // data: [3,7,4,1,2]
          data: [<?php for($i=0;$i<5;$i++){
            echo "5"; echo ",";
          }?>]
      },
      {
          label: "Course Completions",
          backgroundColor: "red",
          data: [<?php $myArray = array(1,2,3,4,5);
          foreach ($myArray as $arr) {
            echo $arr;echo ",";
          }
          ?>]
      },

  ]
};

var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: data,
  options: {
      barValueSpacing: 20,
      scales: {
          yAxes: [{
              ticks: {
                  min: 0,
              }
          }]
      }
  }
});
}
</script>


<?php
echo date("'d-m-Y'",strtotime('today'));
 ?>

 

</br></br>
..........................
<form class="" action="index.html" method="post" style="display : none;" id="form">
  <div class="container">
    <label><b>From:</b></label>
    <input type="date" placeholder="Enter initial date" name="from" required>
    <label><b>To:</b></label>
    <input type="date" placeholder="Enter last date" name="to" required>
    <button type="submit">submit</button>
  </div>
</form>
<button onclick="show();">Click</button>
<script type="text/javascript">
function show(){
   document.getElementById("form").style.display = "block";
}
</script>
