<div class="container mt-4">
	<div class="jumbotron">
		<h1 class="display-3">Papatyam</h1>
		<hr class="my-4">
		<canvas id="myChart"></canvas>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
		<script type="text/javascript">
			var ctx = document.getElementById('myChart').getContext('2d');
			var chart = new Chart(ctx, {
				// The type of chart we want to create
				type: 'line',

				// The data for our dataset
				data: {
					labels: [ '18:00', '21:00','00:00', '03:00', '06:00', '09:00', '12:00', '15:00'],
					datasets: [{
						label: 'Sıcaklık (C) ',
						backgroundColor: 'rgb(255, 242, 243)',
						borderColor: 'rgb(255, 4, 72)',
						data: [22.5, 21, 20, 20.2, 20.5, 21, 22],
						fill:false
					},
						{
							label: '% Nem (Hava)',
							backgroundColor: 'rgb(29, 5, 0)',
							borderColor: 'rgb(255, 218, 10)',
							data: [60, 65, 72.5, 60, 69, 70, 72],
							fill:false
						},
						{
							label: '% Nem (Toprak)',
							backgroundColor: 'rgb(0, 247, 7)',
							borderColor: 'rgb(0, 25, 100)',
							data: [60, 58, 56, 53, 54, 51, 46],
							fill:false
						},


					]
				},

				// Configuration options go here
				options: {}
			});


		</script>

	</div>
</div>
