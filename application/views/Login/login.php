<section class="login-block">
    <div class="container">
		<div class="row">
			<div class="col-md-4 login-sec">
		    	<h2 class="text-center display-4">Inicia sesión</h2>
		    	<form class="form" method="post" action="<?php echo base_url('index.php/acreditando')?>">
					<div class="form-group">
						<label for="exampleInputEmail1" class="">Nombre de usuario</label>
						<input type="text" class="form-control" placeholder="Usuario" name="usuario">
					</div>
					<div class="form-group">
						<label for="exampleInputPassword1" class="">Contraseña</label>
						<input type="password" class="form-control" placeholder="Contraseña" name="password">
					</div>  
					<div class="form-check">
						<button type="submit" name="submit" class="btn float-right btn-primary">Siguiente</button>
					</div>
				</form>

				<h5 style="color: red;" class="mt-3"><?php if(isset($mensaje)) echo $mensaje; ?></h5>
				<span style="color: red;" class="mt-3"><?=validation_errors();?></span>
				<div class="copy-text"><?php echo "UNIMARK S.A. Copyright ©".date("Y").""?></div>
			</div>
			<div class="col-md-8 banner-sec">

				<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
						<!--<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>-->
					</ol>
					<div class="carousel-inner" role="listbox">						
						<div class="carousel-item active">
							<img class="d-block img-fluid" src="<?php echo base_url(); ?>assets/images/pexels-photo.jpg" alt="First slide">
							<div class="carousel-caption d-none d-md-block">
								<img class="" src="<?php echo base_url(); ?>assets/images/logogp.png" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>