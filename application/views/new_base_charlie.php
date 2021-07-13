<?php $this->load->view("page_header") ?>
	<!--begin home section -->
	<section class="home-section" id="home_wrapper">

		<div class="home-section-overlay"></div>

		<!--begin container -->
		<div class="container">

			<!--begin row -->
			<div class="row">

				<!--begin col-md-7-->
				<div class="col-md-7 padding-top-40">

					<h1>BaseCharlie</h1>

					<p>Comercio de opticos e acessórios.</p>

					<!--begin home-benefits -->
					<ul class="home-benefits">
						<li><i class="fa fa-check"></i> Tendis tempor ante acu ipsum finibus.</li>
						<li><i class="fa fa-check"></i> Atimus etims urnatis quisle ratione netis.</li>
						<li><i class="fa fa-check"></i> Ratione lorem nets et sequi tempor.</li>
						<li><i class="fa fa-check"></i> Santis netsi quias magni.</li>
					</ul>
					<!--end home-benefits -->

					<a href="#about" class="btn-green scrool">Explore Benefits</a>

				</div>
				<!--end col-md-7-->

				<!--begin col-md-5-->
				<div class="col-md-5 wow bounceIn" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: bounceIn;">

					<!--begin register-form-wrapper-->
					<div class="register-form-wrapper wow bounceIn" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: bounceIn;">

						<h3>Aqui img BaseCharlei</h3>

						<!--begin form-->
						<div>

							<!--begin success message -->
							<p class="register_success_box" style="display:none;">We received your message and you'll hear from us soon. Thank You!</p>
							<!--end success message -->

							<!--begin register form -->
							<form id="register-form" class="register-form register" action="php/register.php" method="post">


								<input class="register-input white-input" required="" name="register_names" placeholder="Full Name*" type="text">

								<input class="register-input white-input" required="" name="register_email" placeholder="Email Adress*" type="email">

								<input class="register-input white-input" required="" name="register_phone" placeholder="Phone Number*" type="text">

								<input value="Start My Free 14-Day Trial" class="register-submit-top" type="submit">

							</form>
							<!--end register form -->

						</div>
						<!--end form-->

					</div>
					<!--end register-form-wrapper-->

				</div>
				<!--end col-md-5-->

			</div>
			<!--end row -->

		</div>
		<!--end container -->

	</section>
	<!--end home section -->

	<!--begin section-white -->
	<section class="section-white small-padding-top">

		<!--begin container-->
		<div class="container">

			<!--begin row-->
			<div class="row">

				<!--begin col-md-6-->
				<div class="col-md-6 wow slideInLeft" data-wow-delay="0.25s" style="visibility: visible; animation-delay: 0.5s; animation-name: slideInLeft;">

					<img src="<?= base_url('/imgs/newbase/benefits.png') ?>" class="width-100" alt="pic">

				</div>
				<!--end col-sm-6-->

				<!--begin col-md-6-->
				<div class="col-md-6 padding-top-20">

					<h3>Get ready to discover all the benefits and secrets of a perfect launch</h3>

					<p>Velis demo enim ipsam voluptatem quia voluptas sit aspernatur netsum lorem fugit, sed quia magni dolores eos qui ratione sequi nesciunt neque et poris ratione sequi enim quia tempor magni.</p>

					<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur netsum lorem fugit, sed quia magni dolores eos qui ratione sequi.</p>

					<ul class="benefits">
						<li><i class="fa fa-check"></i> Quia magni netsum eos qui ratione sequi.</li>
						<li><i class="fa fa-check"></i> Venis ratione sequi enim quia tempor magni.</li>
						<li><i class="fa fa-check"></i> Enim ipsam voluptatem quia voluptas.</li>
						<li><i class="fa fa-check"></i> Ratione nes sequi nesciunt neque.</li>
					</ul>

					<a href="#download-app" class="btn-lyla scrool">Download App</a>

				</div>
				<!--end col-md-6-->

			</div>
			<!--end row-->

		</div>
		<!--end container-->

	</section>
	<!--end section-white-->

	<?php $this->load->view("page_footer") ?>

