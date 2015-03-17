
<html>
<div class="login-template">
					<table>
						<tr><td><h1><small>Please reset your password</small></h1></td></tr>
						<tr><td><h2><small>A good and accepted password must be alpha-numeric and has 8-16 characters.</small></h2></td></tr>
					<tr>
					<?php 
						echo '<font color=red><i>'.validation_errors().'</i></font><br/><br/>';
						echo form_open('login/verifycpass'); ?>
						<td> <?php echo form_label("New Password: "); ?> </td>
						<td> <?php echo form_password("npass"); ?> </td>
					</tr>
					<tr>
						<td> <?php echo form_label("Confirm Password: "); ?> </td>
						<td> <?php echo form_password("cpass"); ?> </td>
					</tr>
						   <?php echo "<br/>" ?>
					<tr> 
						<td></td>
						<td align='right'> <?php echo form_submit("","Reset Password"); ?> </td>
						   <?php echo form_close();?>
					</tr>
					
					
					</table>
				</div>
</html>