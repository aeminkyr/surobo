<div class="container h-100">
	<div class="d-flex justify-content-center h-100">
		<div class="user_card">
			<div class="d-flex justify-content-center">
				<div class="">
					<img src="/logo212314.png"
						 class="brand_logo" alt="Logo">
				</div>
			</div>
			<div class="d-flex justify-content-center form_container">
				<form method="POST" action="<?= base_url("home/login") ?>">
					<div class="input-group mb-3">
						<div class="input-group-append">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" name="email" class="form-control input_user" value="" placeholder="Email">
					</div>
					<div class="input-group mb-2">
						<div class="input-group-append">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" name="password" class="form-control input_pass" value=""
							   placeholder="Şifre">
					</div>
					<!---<div class="form-group">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id="customControlInline">
							<label class="custom-control-label" for="customControlInline">Remember me</label>
						</div>
					</div>--->
					<div class="d-flex justify-content-center mt-3 login_container">
						<button type="submit" name="button" class="btn login_btn">Giriş Yap</button>
					</div>
				</form>
			</div>
			<div class="mt-4">

				<?php $error_msg = $this->session->flashdata('info');
				if ($error_msg) { ?>
					<div class="alert alert-dismissible alert-danger w-60">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Olamaz!</strong> <?= $error_msg ?>
					</div>
				<?php }
				?>
			</div>
			<div class="mt-4">
				<!--<div class="d-flex justify-content-center links">
					Don't have an account? <a href="#" class="ml-2">Sign Up</a>
				</div>-->
				<!--<div class="d-flex justify-content-center links">
					<a href="#">Şifrenizi mi unuttunuz?</a>
				</div>-->
			</div>
		</div>
	</div>
</div>
