<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title><?= html_escape($page_title); ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=" rel="icon" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>styles/style.css" />
</head>
<body>
<div id="main">
    <div id="header">
    &nbsp;
    </div>
    <div id="content">
    <?php $this->load->view($page_template); ?>
    </div>
    <div id="sidebar">
        <div id="sidebaritem">
        <?php if($AUTH_FORM) $this->load->view('auth_form.php'); ?>
        </div>
        <div id="sidebaritem">
         <?php $this->load->view('menu.php'); ?>
        </div>
    </div>
    <div id="footer">
    &nbsp;
    </div>
</div>
</body>
</html>