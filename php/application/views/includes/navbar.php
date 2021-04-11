<div class="container">
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #256F15" >
<!--	<a class="navbar-brand" href="#">Surobo Plant Watering</a> -->
	<a class="navbar-brand" href="#">
		<img src="/logo212314.png" height="50" alt="">
	</a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarColor02">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link text-white" href="<?=base_url("main")?>">Anasayfa
					<span class="sr-only">(current)</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-white" href="<?=base_url("main/plants")?>">Bitkilerim</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-white" href="<?=base_url("main/devices")?>">Cihazlarım</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-white" href="<?=base_url("home/logout")?>">Oturumu Kapat</a>
			</li>
			<!--
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="#">Action</a>
					<a class="dropdown-item" href="#">Another action</a>
					<a class="dropdown-item" href="#">Something else here</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#">Separated link</a>
				</div>
			</li>

			--->

		</ul>
		<!--<form class="form-inline my-2 my-lg-0">
			<input class="form-control mr-sm-2" type="text" placeholder="Search">
			<button class="btn btn-secondary my-2 my-sm-0" style="background-color: #7FE706;" type="submit">Search</button>
		</form>-->
		<i class="text-white"><?=$this->session->userdata("name") ?></i>
	</div>
</nav>
</div>
