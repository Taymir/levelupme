<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?= html_escape($page_title); ?></title>
<link href="<?= base_url() ?>favicon.ico" rel="shortcut icon" />
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>styles/style.css" />
<?= isset($styles) ? $styles : '' ?>
<?= isset($scripts) ? $scripts : '' ?>

<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>styles/ie6style.css" />
	<script type="text/javascript" src="<?= base_url(); ?>scripts/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('#logo, #images img, #testimonial, .testimonials, .service img.icon, #footer .widget ul li, #switcher-left, #switcher-right, #switcher-content a, #switcher-content a.active');</script>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>styles/ie7style.css" />
<![endif]-->
</head>
<body id="home">
<div id="header-wrapper">
<div id="header">
<div class="container">
<a href="<?= base_url(); ?>"><img src="<?= base_url(); ?>styles/images/logo.png" alt="Logo" id="logo"/></a>
<div id="top-box">
    <p>Успеваемость <strong>ваших детей</strong><br/>&ndash; наша главная цель</p>
    <a href="#" class="featured-button"><span>Подключиться</span></a>
</div> <!-- end #top-box -->
</div> <!-- end .container -->
</div> <!-- end #header -->
</div> <!-- end #header-wrapper -->

<div id="content">
<div id="content-wrap">
<div class="container clearfix">
<div id="content-left">

<div id="featured-wrap">
	<ul id="featured-control">
		<li class="active"><a href="#">Электронный дневник</a></li>
		<li><a href="#">Родителям</a></li>
		<li><a href="#">Школам</a></li>
		<li><a href="#">Пользователям</a></li>
	</ul>
	<div id="featured">
	<div class="slide clearfix">
        <?php $this->load->view($page_template); ?>
        </div> <!-- end .slide -->
        </div> <!-- end #featured -->
</div> <!-- end #featured-wrap -->	
</div> <!-- end #content-left -->
    
<div id="sidebar">
<?php
$ci = & get_instance();
$ci->load->library('user_agent');
$ie6 = $ci->agent->is_browser('Internet Explorer') && (int)$ci->agent->version() == 6;

if(!$ie6):
?>
<img src="<?= base_url(); ?>styles/images/iphone.png" style="width: 202px; height: 450px; margin-top: -400px; margin-left: 20px; margin-bottom: -35px;" />
<?php endif; ?>

        <?php if($AUTH_FORM) $this->load->view('auth_form.php'); ?>

        <div class="sidebar-block">
         <?php $this->load->view('menu.php'); ?>
        </div> <!-- end .sidebar-block -->
        <div class="sidebar-block">
                <h3>Контакты</h3>
                <p class="mail-text"><a href="mailto:mail@dnevnikam.net">mail@dnevnikam.net</a></p>
                <nobr class="phone-text"><a href="callto:+79091234567">+7 (909) 123-45-67</a></nobr>
        </div> <!-- end .sidebar-block -->
</div> <!-- end .sidebar -->
</div> <!-- end .container -->

</div> <!-- end #content-wrap -->
</div> <!-- end #content -->

<div id="footer-copyright" class="clearfix">
	<div class="container">
		<p id="copyright">&copy; 2011, <a href="#">dnevnikam.net</a></p>
	</div> <!-- end .container -->	
</div> <!-- end #footer-copyright -->
</body>
</html>