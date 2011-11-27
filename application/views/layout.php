<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?= html_escape($page_title); ?></title>
<link href="<?= base_url() ?>favicon.ico" rel="shortcut icon" />
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>styles/style.css" />
<?= isset($styles) ? $styles : '' ?>
<?= isset($scripts) ? $scripts : '' ?>
<?php 
$ci = & get_instance();
$ci->load->library('user_agent');
$ie6 = $ci->agent->is_browser('Internet Explorer') && (int)$ci->agent->version() == 6;
?>
<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>styles/ie6style.css" />
	<script type="text/javascript" src="<?= base_url(); ?>scripts/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('#logo, #images img, #testimonial, .testimonials, .service img.icon, #footer .widget ul li, #switcher-left, #switcher-right, #switcher-content a, #switcher-content a.active');</script>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>styles/ie7style.css" />
<![endif]-->
</head>
<body <?= $SHORT_VIEW ? '' : 'id="home"' ?>>
<div id="header-wrapper">
<div id="header">
<div class="container">
<a href="<?= base_url(); ?>"><img src="<?= base_url(); ?>styles/images/logo.png" alt="Logo" id="logo"/></a>
<? if(!$SHORT_VIEW): ?>
<div id="top-box">
	<div id="textslider">
		<div style="">
		<p>Высокая успеваемость <strong>ваших детей</strong><br/> &ndash; наша главная цель</p> </div>
		<div style="display:none">
		<p>Мы усердно работаем <strong>каждый день</strong>,<br/> чтобы сохранить ваше время</p> </div>
		<div style="display:none">
		<p>Мы делаем то, <strong>что мы умеем</strong>:<br/> сохраняем ваше время</p> </div>
	</div>
    <a href="/join" class="featured-button"><span>Подключиться</span></a>
</div> <!-- end #top-box -->

<? if(!$ie6): ?>
<div id="switcher">
<div id="switcher-left"></div>
<div id="switcher-content">
<a href="#" class="active">1</a>
<a href="#">2</a>
<a href="#">3</a>
</div> <!-- end #switcher-content -->
<div id="switcher-right"></div>
</div><!-- end #switcher -->
<? endif; ?>

<? endif; ?>
</div> <!-- end .container -->
</div> <!-- end #header -->
</div> <!-- end #header-wrapper -->

<div id="content">
<div id="content-wrap">
<div class="container clearfix">
<div id="content-left">

<div id="featured-wrap">
    <? if(!$SHORT_VIEW): ?>
	<ul id="featured-control">
		<li <?= isset($page_name) && $page_name == 'home' ? 'class="active"' : '' ?>><a href="/">О системе Levelup</a></li>
		<li <?= isset($page_name) && $page_name == 'join' ? 'class="active"' : '' ?>><a href="/join">Подключение</a></li>
		<li <?= isset($page_name) && $page_name == 'help' ? 'class="active"' : '' ?>><a href="/help">Пользователям</a></li>
	</ul>
    <? endif; ?>
	<div id="featured">
	<div class="slide clearfix">
        <?php $this->load->view($page_template); ?>
        </div> <!-- end .slide -->
        </div> <!-- end #featured -->
</div> <!-- end #featured-wrap -->	
</div> <!-- end #content-left -->
    
<div id="sidebar">
<? if(!$SHORT_VIEW && !$ie6): ?>
<div id="imageslider">
<div style=""><img src="<?= base_url(); ?>styles/images/iphone.png" style="width: 202px; height: 430px; margin-top: -400px; margin-left: 20px; margin-bottom: -35px;" /></div>
<div style="display:none"><img src="<?= base_url(); ?>styles/images/monitor.png" style="width: 413px; height: 349px; margin-top: -320px; margin-left: -85px; margin-bottom: -56px;" /></div>
<div style="display:none"><img src="<?= base_url(); ?>styles/images/nokia.png" style="width: 189px; height: 396px; margin-top: -400px; margin-left: 30px; margin-bottom: -89px;" /></div>
</div>
<? endif; ?>

        <?php if($AUTH_FORM) $this->load->view('auth_form.php'); ?>

        <div class="sidebar-block">
         <?php $this->load->view('menu.php'); ?>
        </div> <!-- end .sidebar-block -->
        <div class="sidebar-block">
                <h3>Контакты</h3>
                <p class="mail-text"><a href="mailto:mail@levelupme.ru">mail@levelupme.ru</a></p>
                <!--<nobr class="phone-text"><a href="callto:+79091234567">+7 (909) 123-45-67</a></nobr>-->
        </div> <!-- end .sidebar-block -->
</div> <!-- end .sidebar -->
</div> <!-- end .container -->

</div> <!-- end #content-wrap -->
</div> <!-- end #content -->

<div id="footer-copyright" class="clearfix">
	<div class="container">
		<p id="copyright">&copy; 2011, <a href="/">levelupme.ru</a></p>
	</div> <!-- end .container -->	
</div> <!-- end #footer-copyright -->
</body>
</html>