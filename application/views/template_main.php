<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="<?php echo asset_url()."images/logo16x16.png";?>">
    <title>IA: Leave Tracker</title>
    
    <link href="<?php echo asset_url()."css/jquery-ui.min.css";?>" rel="stylesheet">
    <link href="<?php echo asset_url()."css/bootstrap.min.css";?>" rel="stylesheet">

    <link href="<?php echo asset_url()."datatables/css/jquery.dataTables.min.css";?>" rel="stylesheet">
    <link href="<?php echo asset_url()."datatables/css/dataTables.bootstrap.css";?>" rel="stylesheet">
    <link href="<?php echo asset_url()."datatables/css/dataTables.responsive.css";?>" rel="stylesheet">

    <link href="<?php echo asset_url()."css/bootstrapValidator.min.css";?>" rel="stylesheet">
    <link href="<?php echo asset_url()."css/custom.css";?>" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container lt-header">
            <div class="navbar-header">
                <a class="navbar-brand" style="padding:0;" href="#"><img src="<?php echo base_url()."assets/images/leave-tracker-logo.png";?>" /></a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if ( isset($is_Login) && $is_Login) { ?>
                <div style="text-align: right;" class="col-lg-12">
                    <h4><?php echo $user_summary["LastName"] .", ". $user_summary["FirstName"]; ?> | <a href="/login/logout">Logout</a>
                    <br><small><?php echo date("F d, Y | l | ") ?></small><small id="time"></small></h4></div>
                <div id="navbar" class="navbar-collapse">
                    <ul class="nav nav-tabs">
                        <?php if($this->session->userdata('userID') != 'SSCu' || $this->session->userdata('userID') != 'PJHTanchi') { ?>
                        <li role="presentation" <?php echo (($page=="personal") ? 'class="active"':"") ?>><a href="/personal">Personal Tracker</a></li>
                        <?php } ?>
                        <?php if($user_summary["IsApprover"] == 1 || $user_summary["IsAdmin"] == 1) {  ?>
                        <li role="presentation" <?php echo (($page=="approver") ? 'class="active"':"") ?>><a href="/approver">Approver's Tracker</a></li>
                        <?php } ?>
                        <?php if($user_summary["IsAdmin"] == 1) { ?>
                        <li role="presentation" <?php echo (($page=="admin") ? 'class="active"':"") ?>><a href="/admin_dashboard">Admin Dashboard</a></li>
                        <?php } ?>
                        <li role="presentation" <?php echo (($page=="FAQ") ? 'class="active"':"") ?>><a href="/faq">FAQ</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
        <?php } ?>

        <?php $this->load->view($content); ?>
    </div><!-- /.container -->


    <footer class="footer ">
        <div class="container lt-footer"></div>
    </footer><!-- /.footer -->

    <!-- Bootstrap core JavaScript https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js
    ================================================== -->
    <script src="<?php echo asset_url()."js/jquery-1.11.1.min.js";?>"></script>
    <script src="<?php echo asset_url()."js/jquery-ui.min.js";?>"></script>
    <script src="<?php echo asset_url()."js/jquery.dialogOptions.js";?>"></script>
    <script src="<?php echo asset_url()."js/bootstrap.min.js";?>"></script>

    <script src="<?php echo asset_url()."datatables/js/jquery.dataTables.min.js";?>"></script>
    <script src="<?php echo asset_url()."datatables/js/dataTables.bootstrap.js";?>"></script>
    <script src="http://cdn.datatables.net/responsive/1.0.3/js/dataTables.responsive.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo asset_url()."js/ie10-viewport-bug-workaround.js";?>"></script>

    <?php $this->load->view($jsscript); ?>
    <script type="text/javascript">
        function startTime() {
            var today=new Date();
            var h=today.getHours();
            var m=today.getMinutes();
            var s=today.getSeconds();
            var mil=(h < 12) ? " AM" : " PM";
            var hour=(h > 12) ? h-12 : h;
            
            m = checkTime(m);
            s = checkTime(s);
            $('#time').html(hour +":"+ m +":"+ s + mil);
            
            setTimeout(function(){startTime();},500);
        }

        function checkTime(i) {
            if (i<10) {i = "0" + i;}  // add zero in front of numbers < 10
            return i;
        }
        
        startTime();
    </script>
</body>
</html>