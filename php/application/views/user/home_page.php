<div class="container mt-4 ">
	<style>
		.lds-heart {
			display: inline-block;
			position: relative;
			width: 80px;
			height: 80px;
			transform: rotate(45deg);
			transform-origin: 40px 40px;
		}

		.lds-heart div {
			top: 0px;
			left: 32px;
			position: absolute;
			width: 32px;
			height: 32px;
			background: #00CC00;
			animation: lds-heart 1.2s infinite cubic-bezier(0.215, 0.61, 0.355, 1);
		}

		.lds-heart div:after,
		.lds-heart div:before {
			content: " ";
			position: absolute;
			display: block;
			width: 32px;
			height: 32px;
			background: #00CC00;
		}

		.lds-heart div:before {
			left: -24px;
			border-radius: 50% 0 0 50%;
		}

		.lds-heart div:after {
			top: -24px;
			border-radius: 50% 50% 0 0;
		}


		@keyframes lds-heart {
			0% {
				transform: scale(0.95);
			}
			5% {
				transform: scale(1.1);
			}
			39% {
				transform: scale(0.85);
			}
			45% {
				transform: scale(1);
			}
			60% {
				transform: scale(0.95);
			}
			100% {
				transform: scale(0.9);
			}
		}

	</style>
	<?php
	foreach ($bitkis as $bitki) {
		$mouisturePercent = ($bitki->mois * 100) / 500;
		if ($mouisturePercent > 100) $mouisturePercent = 100;
		?>
		<div class="jumbotron">
			<h1 class="display-3"><?= $bitki->dname ?></h1>
			<hr class="my-4">
			<div class="jumbotron">
				<div class="row">
					<div class="col-md-6">
						<legend><u>Anlık Durum</u></legend>

					</div>
					<div class="col-md-6">
						<div class="lds-heart float-right mr-2">
							<div></div>
						</div>
					</div>

				</div>
				<div class="row  ">

					<div class="col-md-4 mt-4">


						<div class="mousture">
							<p id="moisturep">Toprak Nem Oranı : <?= $mouisturePercent ?>%</p>
							<div class="d-flex justify-content-center">
								<div id="fluid-meter-mois" class=""></div>
							</div>
							<!--	<div class="progress">
						<div class="progress-bar progress-bar-striped bg-success" id="moisture" role="progressbar"
							 style="width: <?= $mouisturePercent ?>%;"
							 aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							 		</div>
					-->
						</div>
					</div>

					<div class="temp col-md-4 mt-4">
						<p id="tempp">Sıcaklık : <?= $bitki->temp ?>c°</p>
						<div class="d-flex justify-content-center">
							<div id="fluid-meter-temp" class="mx-auto"></div>
						</div>
						<!--<div class="progress">
							<div class="progress-bar progress-bar-striped bg-danger" id="temperature" role="progressbar"
								 style="width: <?= $bitki->temp ?>%;"
								 aria-valuenow="<?= $bitki->temp ?>" aria-valuemin="0" aria-valuemax="50"></div>
						</div>-->
					</div>
					<div class="humid col-md-4 mt-4">
						<p id="humidd">Nem(hava) : %<?= $bitki->hum ?></p>
						<!--<div class="progress">
							<div class="progress-bar progress-bar-striped bg-info" id="humidity" role="progressbar"
								 style="width: <?= $bitki->hum ?>%;"
								 aria-valuenow="<?= $bitki->hum ?>" aria-valuemin="0" aria-valuemax="100"></div>
						</div>-->
						<div class="d-flex justify-content-center">

							<div id="fluid-meter-humid" class=""></div>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-4 p-3">
				<legend>Sulama aralıkları</legend>
				<div class="col-md-6">
					<fieldset class="form-group">
						<p id="minMosVal">***</p>

						<input type="range" class="custom-range" id="minMosRange" value="<?= $bitki->minMois ?>"
							   max="<?= $bitki->maxMois ?>">
					</fieldset>
				</div>
				<div class="col-md-6">
					<fieldset class="form-group">
						<p id="maxMosVal">***</p>

						<input type="range" class="custom-range" id="maxMosRange" value="<?= $bitki->maxMois ?>">
					</fieldset>
				</div>
				<div class="col-md-12">
					<div class="float-right">
						<button type="button" id="saveButton" data-mac="<?= $bitki->mac ?>"
								class="btn btn-success ml-auto" style="display: none;">
							Kaydet
						</button>
					</div>
				</div>

			</div>

			<div class="row mt-4">

				<div class="col-md-6">
					<p>Su pompası</p>

					<!-- Rounded switch -->
					<label class="switch">
						<input class="watering" type="checkbox"
							   data-mac="<?= $bitki->mac ?>" <?php if ($bitki->pomp) echo "checked" ?>>
						<span class="slider round"></span>
					</label>
				</div>
			</div>

		</div>
		<?php
	}
	?>

	<ul id="messages"></ul>
</div>
<script>
	var fm = new FluidMeter();
	fm.init({
		targetContainer: document.getElementById("fluid-meter-temp"),
		fillPercentage: <?= $bitki->temp ?>,
		options: {
			fontFamily: "Raleway",
			drawPercentageSign: false,
			drawDegreeSign:true,
			drawBubbles: true,
			size: 250,
			borderWidth: 10,
			backgroundColor: "#262626",
			foregroundColor: "#4C4A4A",
			foregroundFluidLayer: {
				fillStyle: "purple",
				angularSpeed: 100,
				maxAmplitude: 12,
				frequency: 30,
				horizontalSpeed: -150
			},
			backgroundFluidLayer: {
				fillStyle: "pink",
				angularSpeed: 100,
				maxAmplitude: 9,
				frequency: 30,
				horizontalSpeed: 150
			}
		}
	});

	var fm2 = new FluidMeter();
	fm2.init({
		targetContainer: document.getElementById("fluid-meter-mois"),
		fillPercentage: <?= $mouisturePercent ?>,
		options: {
			fontFamily: "Raleway",
			fontSize: "50px",
			drawPercentageSign: true,
			drawBubbles: true,
			size: 250,
			borderWidth: 10,
			backgroundColor: "#262626",
			foregroundColor: "#4C4A4A",
			foregroundFluidLayer: {
				fillStyle: "#55DD10",
				angularSpeed: 90,
				maxAmplitude: 11,
				frequency: 25,
				horizontalSpeed: -200
			},
			backgroundFluidLayer: {
				fillStyle: "#CDDD10",
				angularSpeed: 100,
				maxAmplitude: 13,
				frequency: 23,
				horizontalSpeed: 230
			}
		}
	});

	var fm3 = new FluidMeter();
	fm3.init({
		targetContainer: document.getElementById("fluid-meter-humid"),
		fillPercentage: <?=$bitki->hum?>,
		options: {
			fontFamily: "Raleway",
			fontSize: "50px",
			drawPercentageSign: true,
			drawBubbles: false,
			size: 250,
			borderWidth: 10,
			backgroundColor: "#262626",
			foregroundColor: "#4C4A4A",
			foregroundFluidLayer: {
				fillStyle: "#16E1FF",
				angularSpeed: 30,
				maxAmplitude: 5,
				frequency: 30,
				horizontalSpeed: -100
			},
			backgroundFluidLayer: {
				fillStyle: "#4F8FC6",
				angularSpeed: 100,
				maxAmplitude: 13,
				frequency: 22,
				horizontalSpeed: 100
			}
		}
	});

</script>


<script src="http://47.254.131.220:3000/socket.io/socket.io.js"></script>
<script>
	var socket = io("http://47.254.131.220:3000"); // Kullanıcı tarafında da bağlantı nesnemizi oluşturuyoruz.

	//const sessionID = socket.socket.sessionid;

	socket.on('connect', function () {
		const sessionID = socket.id; //
		console.log("socket id " + sessionID);
		let userLogin = {
			userid: "<?=$this->session->userdata("userid")?>",
			socketid: sessionID

		}
		socket.emit("userLogin", userLogin);
	});

	$(".watering").change(function () {
		if ($(this).is(':checked')) {
			switchStatus = $(this).is(':checked');
			var info = {
				mac: $(this).data('mac'),
				'status': true
			}
			socket.emit('watering', info);

		} else {
			var info = {
				mac: $(this).data('mac'),
				'status': false
			}
			switchStatus = $(this).is(':checked');
			socket.emit('watering', info);
		}
	});

	socket.on("watering", function (msg) {
		$(".watering").prop("checked", msg);

	});

	//------------------------
	var alert1 = true;
	socket.on("sensorfail", function (msg) {
		if (alert1) {
			alert("Sensör hatası : " + msg);
			alert1 = false;
		}
		console.log(msg);
	});
	//-----------------------

	$(function () {

		socket.on('id', function (msg) { /* yukarıda emit diyerek mesajı yayınladığımız gibi eğer bana chat message başlığı ile bir mesaj gelirse onu ekrana yazdır diyoruz. */
			$('#messages').append($('<li>').text(msg)); /* gelen mesajı message id'sine sahip elemente text olarak yazdır diyoruz. */
			console.log(msg);
		});

		socket.on('envData', function (msg) { /* yukarıda emit diyerek mesajı yayınladığımız gibi eğer bana chat message başlığı ile bir mesaj gelirse onu ekrana yazdır diyoruz. */

			var sensorData = msg.sensors;
			//consoles.log(sensorData.temp);
			$('#temperature').css('width', (sensorData.temp * 100) / 50 + '%');
			$('#tempp').text("Sıcaklık : " + sensorData.temp + "c°");
			fm.setPercentage(Number(sensorData.temp));


			$('#humidity').css('width', sensorData.humid + '%');
			$('#humidd').text("Nem(hava) : %" + sensorData.humid);
			fm3.setPercentage(Number(sensorData.humid));


			$('#moisture').css('width', sensorData.mois + '%');
			$('#moisturep').text("Toprak Nem Oranı : %" + sensorData.mois);
			fm2.setPercentage(Number(sensorData.mois));


			console.log(msg.sensors);
		});
	});

	$('#minMosVal').text("Minimum Nem(Toprak) : %" + $('#minMosRange').val());
	$(document).on('input', '#minMosRange', function () {
		$('#minMosVal').text("Minimum Nem(Toprak) : %" + $(this).val());
		//socket.emit('minMos',  $(this).val());
		//	$("#saveButton").removeAttr("hidden");
		$("#saveButton").show("slow", function () {
			// Animation complete.
		});

	});

	$('#maxMosVal').text("Maximum Nem(Toprak) : %" + $('#maxMosRange').val());
	$(document).on('input', '#maxMosRange', function () {
		$('#maxMosVal').text("Maximum Nem(Toprak) : %" + $(this).val());
		$('#minMosRange').attr("max", $(this).val());
		$('#minMosVal').text("Minimum Nem(Toprak) : %" + $('#minMosRange').val());

		//socket.emit('maxMos',  $(this).val());
		//$("#saveButton").removeAttr("hidden");
		$("#saveButton").show("slow", function () {
			// Animation complete.
		});


	});

	$("#saveButton").click(function () {
		$(this).fadeOut(300, function () {
			let min = $("#minMosRange").val();
			let max = $("#maxMosRange").val();

			var moistureRange = {
				'min': min,
				'max': max,
				'mac': $(this).data('mac')
			}

			socket.emit("moistureRange", moistureRange);

		});

	});


</script>


