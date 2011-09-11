<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title><?php echo $page_title; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=" rel="icon" type="image/x-icon" />
</head>
<body>
<?php $this->load->view('menu.php'); ?>

<?php $this->load->view($page_template); ?>
    
<?php if($AUTH_FORM) $this->load->view('auth_form.php'); ?>
</body>
</html>