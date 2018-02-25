<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Your <?php echo $site_name; ?>! username</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Your <?php echo $site_name; ?>! username</h2>
Thanks for joining <?php echo $site_name; ?>. We listed your sign in details below, make sure you keep them safe.<br />
<br />
<?php if (strlen($username) > 0) { ?>Your username: <?php echo $username; ?><br /><?php } ?>
Your email address: <?php echo $email; ?><br />
<?php if (isset($password)) { /* ?>Your password: <?php echo $password; ?><br /><?php */ } ?>
<br />
<br />
Have fun!<br />
The <?php echo $site_name; ?> Team
</td>
</tr>
</table>
</div>
</body>
</html>