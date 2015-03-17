<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="logo16x16.png">
    <title>IA: Leave Tracker</title>
    
    <link href="http://192.168.111.173/assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="http://192.168.111.173/assets/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
		html {
		    position: relative;
		    min-height: 100%;
		}
		body {
		    margin-bottom: 30px;
		}

		.navbar-inverse {
		    background-color:#ededed;
		    border-color:#bfd746;
		}

		.navbar-inverse .lt-header {
		    background-image:url('http://192.168.111.173/assets/images/IALT-headbanner.png');
		    background-repeat:no-repeat;
		    background-position:right bottom;
		}

		.no-close .ui-dialog-titlebar-close {
		    display: none;
		}

		.footer {
		    position: absolute;
		    bottom: 0;
		    width: 100%;
		    /* Set the fixed height of the footer here */
		    height: 30px;
		}

		.footer .lt-footer {
		    background-color: #00b0f0;
		    background-image:url('http://192.168.111.173/assets/images/IALT-footerlogo.png');
		    background-repeat:no-repeat;
		    background-position:right bottom;
		    height: 30px;
		}
    </style>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container lt-header">
            <div class="navbar-header">
                <a class="navbar-brand" style="padding:0;" href="http://192.168.111.173/"><img src="http://192.168.111.173/assets/images/leave-tracker-logo.png" /></a>
            </div>
        </div>
    </nav>

    <?php if ($header == 'user') : ?>
    <div class="container">
	    Hi, <?php echo $newUser_data['FirstName'] ?>
		<br/>
		Good day!<br/><br/>
		Below are your IA Leave Tracker login details:
		<br/><br/>
		User ID: <?php echo $newUser_data['userID'] ?>
		<br/>
		Password: <?php echo $newUser_data['temp_Passcode'] ?>
		<br/><br/>
		Click on this link :<a href="http://192.168.111.173/"><strong>IA Leave Tracker</strong></a> to reset your password.
		<br/><br/>
		Sincerely Yours,
		<br/>IA Leave Administrator 
	</div>
	<?php endif; ?>

	<?php if ($header == 'leave') : ?>
		Hi, <?php echo $newUser_data['approverName'] ?>
		<br/><br/>
		Good day!<br/><br/>
		<?=$newUser_data['fullName']?> has filed a leave request.
		<br/><br/>
		Click on this link :<a href="http://192.168.111.173/"><strong>IA Leave Tracker</strong></a> for details.
	<?php endif; ?>

    <footer class="footer ">
        <div class="container lt-footer"></div>
    </footer><!-- /.footer -->

    <!-- Bootstrap core JavaScript https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js
    ================================================== -->
    <script src="http://192.168.111.173/assets/js/jquery-1.11.1.min.js"></script>
    <script src="http://192.168.111.173/assets/js/jquery-ui.min.js"></script>
    <script src="http://192.168.111.173/assets/js/bootstrap.min.js"></script>
</body>
</html>