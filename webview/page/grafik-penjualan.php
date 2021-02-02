<script src="chartjs/moment.min.js"></script>
<script src="chartjs/Chart.min.js"></script>
	<script src="chartjs/utils.js"></script>
	<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
<canvas id="myChart1"></canvas>
<script>
		var timeFormat = 'MM/DD/YYYY HH:mm';

		function newDate(days) {
			return moment().add(days, 'd').toDate();
		}

		function newDateString(days) {
			return moment().add(days, 'd').format(timeFormat);
		}

		var color = Chart.helpers.color;
		var barChartData = {
			labels: [
<?php
$kuweriel2 = mysqli_query($config, "SELECT tgl FROM tblpenjualan WHERE iduser='$iduser' GROUP BY tgl ORDER BY tgl DESC LIMIT 6") or die(mysql_error());
while($datael2=mysqli_fetch_array($kuweriel2)) {
?>
'<?php echo date_format(date_create($datael2[0]),"m/d/Y"); ?>',
<?php } ?>

			    ],
				datasets: [{
					label: 'Penjualan',
					backgroundColor: color(window.chartColors.yellow).alpha(0.5).rgbString(),
					borderColor: window.chartColors.yellow,
					fill: false,
					data: [
<?php
$dno = 1;
$kuweriel = mysqli_query($config, "SELECT SUM(total) FROM tblpenjualan WHERE iduser='$iduser' GROUP BY tgl ORDER BY tgl") or die(mysql_error());
while($datael=mysqli_fetch_array($kuweriel)) {
?>
						<?php echo $datael[0]; ?>
					,
<?php $dno++; } ?>
					],
				}]

		};

		window.onload = function() {
			var ctx = document.getElementById('myChart1').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
				title: {
					text: 'Grafik Penjualan'
				},
				scales: {
					xAxes: [{
						display: false,
						scaleLabel: {
							display: false,
							labelString: 'Tanggal'
						}
					}],
					yAxes: [{
					    ticks: {
            beginAtZero: true,
            callback: function(value, index, values) {
              if(parseInt(value) >= 1000){
                return '' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              } else {
                return '' + value;
              }
            }
          },
						scaleLabel: {
							display: false,
							labelString: 'Total Penjualan'
						}
					}]
				},
			}
			});

		};
	</script>