
<html>
<div class="login-template">
					<table>
						<tr><td><h1><small>Please sign in</small></h1></td></tr>
					<tr>
					<?php 
						echo '<font color=red><i>'.validation_errors().'</i></font><br/><br/>';
						echo form_open('login/verify'); ?>
						<td> <?php echo form_label("User ID: "); ?> </td>
						<td> <?php echo form_input("uid"); ?> </td>
					</tr>
					<tr>
						<td> <?php echo form_label("Password: "); ?> </td>
						<td> <?php echo form_password("password"); ?> </td>
					</tr>
						   <?php echo "<br/>" ?>
					<tr> 
						<td></td>
						<td align='right'> <?php echo form_submit("","Sign In"); ?> </td>
						   <?php echo form_close();?>
					</tr>
					
					
					</table>
				</div>
</html>