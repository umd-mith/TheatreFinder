<?php header('Content-type: text/html; charset=UTF-8'); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $title; ?></title>

<?php echo $css; ?>
<?php echo $scripts; ?>

</head>

<!-- <div><?php echo $debug_info; ?></div> -->

<?php echo $body_id; ?> 
<a name="top_of_navbar"></a>
<!-- Login bar: For visitors, this is only login (for those logged in, see main_layout.php) -->
    <div class="login">
        <p class="login_options"><a href="<?php echo base_url();?>user">Login to Theatre Finder</a></p>
    </div>
<!-- body identifier, different foreach view -->
	<?php $this->load->view('partials_visitors_only/header.php'); ?>
	<?php echo $content; ?>
	<?php $this->load->view('partials_visitors_only/footer.php'); ?>
</body>
</html>