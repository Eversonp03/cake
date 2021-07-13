<!DOCTYPE html>
<html lang="zxx">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="keywords" content="">
	<meta name="description" content="">

	<title>CakeDigital</title>

	<!-- Loading Bootstrap -->
	<link href="<?= base_url('/css/bootstrap.css') ?>" rel="stylesheet">
	<!-- css/bootstrap.css -->

	<!-- Load JS here for greater good =============================-->
	<script src="<?= base_url('js/jquery-1.11.3.min.js') ?>"></script>
	<script src="<?= base_url('js/bootstrap.js') ?>"></script>
	<script src="<?= base_url('js/owl.carousel.min.js') ?>"></script>
	<script src="<?= base_url('js/jquery.scrollTo-min.js') ?>"></script>
	<script src="<?= base_url('js/jquery.magnific-popup.min.js') ?>"></script>
	<script src="<?= base_url('js/jquery.nav.js') ?>"></script>
	<script src="<?= base_url('js/wow.js') ?>"></script>
	<script src="<?= base_url('js/plugins.js') ?>"></script>
	<!-- <script src="<?= base_url('js/custom.js') ?>"></script> -->

	<!-- Loading Template CSS -->
	<link href="<?= base_url('/css/style.css') ?>" rel="stylesheet">
	<link href="<?= base_url('/css/style2.css') ?>" rel="stylesheet">
	<link href="<?= base_url('/css/animate.css') ?>" rel="stylesheet">
	<link href="<?= base_url('/css/style-magnific-popup.css') ?>" rel="stylesheet">
	<!-- 
    css/style.css
    css/animate.css
    css/style-magnific-popup.css -->

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cabin:500,600,700" rel="stylesheet">

	<!-- Awsome Fonts -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?= base_url('/css/pe-icon-7-stroke.css') ?>">
	<!-- css/pe-icon-7-stroke.css -->

	<!-- Optional - Adds useful class to manipulate icon font display -->
	<link rel="stylesheet" href="<?= base_url('/css/helper.css') ?>">
	<!-- css/helper.css -->

	<link rel="stylesheet" href="<?= base_url('/css/owl.carousel.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('/css/owl.theme.default.min.css') ?>">
	<!-- 
    css/owl.carousel.min.css
	css/owl.theme.default.min.css -->

	<!-- Font Favicon -->
	<link rel="shortcut icon" href="<?= base_url('/imgs/faviconico.png') ?>">
	<!-- images/favicon.ico -->

</head>

<body class="background-color: #262626;" style="background-color: #353535;">

	<!--begin header -->
	<header class="header">

		<!--begin nav -->
		<nav class="navbar navbar-default navbar-fixed-top">

			<!--begin container -->
			<div class="container">

				<!--begin navbar -->
				<div class="navbar-header">

					<button data-target="#navbar-collapse-02" data-toggle="collapse" class="navbar-toggle" type="button">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!--logo -->

					<a href="<?= base_url('inicio/index') ?>" class="navbar-brand" id="logo"><img src="<?= base_url('/imgs/newindex/logo_inteira_branca.png') ?>" style="margin-top: -10px;" alt=""></a>

				</div>


				<div id="navbar-collapse-02" class="collapse navbar-collapse">

					<ul class=" navbar-nav navbar-right">

						<li><a href="<?= base_url('inicio/index') ?>"><img src="<?= base_url('/imgs/voltar2.png') ?>" alt="" style="margin-right: 5px; max-width: 20px; margin-top:-5px">Voltar</a></li>


						<li><a href="<?= base_url('inicio/index#contato') ?>"><img src="<?= base_url('/imgs/contato.png') ?>" alt="" style="margin-right: 5px; max-width: 20px; margin-top:-5px">Contato</a></li>

					</ul>
				</div>
				<!--end navbar -->

			</div>
			<!--end container -->
		</nav>
		<!--end nav -->
	</header>
	<!--end header -->

	
	<script>
	$(document).ready(function() {
		$("li a").on("click",function(){
			var url = $(this).attr("href");
			window.location =url;
		});
	});
</script>