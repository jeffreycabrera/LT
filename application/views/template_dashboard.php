<?php if(!$ajax_REQUESTED) : ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo asset_url()."images/logo16x16.png";?>">

    <title>IA: Leave Tracker</title>
    <link href="<?php echo asset_url()."css/fonts.css?family=Myriad Set Pro&v=1";?>" rel="stylesheet" />

    <link href="<?php echo asset_url()."css/jquery-ui.min.css";?>" rel="stylesheet">
    <link href="<?php echo asset_url()."css/bootstrap.min.css";?>" rel="stylesheet">
    <link href="<?php echo asset_url()."css/font-awesome.min.css";?>" rel="stylesheet">

    <link href="<?php echo asset_url()."datatables/css/jquery.dataTables.min.css";?>" rel="stylesheet">
    <link href="<?php echo asset_url()."datatables/css/dataTables.bootstrap.css";?>" rel="stylesheet">
    <link href="<?php echo asset_url()."datatables/css/dataTables.responsive.css";?>" rel="stylesheet">
    <link href="<?php echo asset_url()."font-awesome/css/font-awesome.min.css";?>" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="<?php echo asset_url()."css/dashboard.css";?>" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="http://getbootstrap.com/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo asset_url()."js/ie-emulation-modes-warning.js";?>"></script>
    <!--<script src="http://getbootstrap.com/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <nav class="navbar navbar navbar-fixed-top" style="box-shadow: 0px 1px 10px rgba(0, 0, 0, 0.35); height: 80px; background-color: #fff;">
        <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="true" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="<?php echo asset_url()."images/leave-tracker-logo2.png";?>"></a>
        </div>
        <div style="" aria-expanded="true" id="navbar" class="navbar-collapse collapse in">
          <ul style="margin: 30px 0px 0px;" class="nav navbar-nav navbar-right">
            <li>
            <div style="min-width: 100px; height: 50px; position: relative;" class="timeman-container planner-container am-pm-mode">
                <div class="timeman-wrap planner-wrap">
                    <span style="display: inline-block; white-space: nowrap; height: 50px;" class="timeman-block" id="timeman-block">
                        <span style="display: inline-block; color:#777777; font-size: 25px; font-family: &quot;OpenSans-Light&quot;,sans-serif; line-height: 30px;" id="timeman-timer" class="time">
                            <span style="font-size: 20px; font-weight: normal;" class="date" id="date-actual"></span>
                            <span style="font-size: 20px; font-weight: normal;" class="date" id="date-day"></span>
                            <span class="time-hours"></span>
                            <span class="time-semicolon">:</span>
                            <span class="time-minutes"></span>
                            <span style="font-size: 15px; vertical-align: top; line-height: 23px; display: inline-block;" class="time-am-pm"></span>
                        </span>
                    </span>
                </div>
            </div>
            </li>
          </ul>
        </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

        <div style="box-shadow: 0px 10px 10px rgba(0, 0, 0, 0.35);" class="col-sm-3 col-md-2 sidebar">
            <h3 style="color:#fff;">Hi, <?= $this->session->userdata('lt_logged_FullName') ?></h3>
            <?php if ($this->session->userdata('lt_isAdmin')) : ?>
            <hr>
            <ul class="nav nav-sidebar">
                <li><a class="main-link" href="employees">Employees</a></li>
                <li><a class="main-link" href="approvers">Approvers List</a></li>
                <li><a class="main-link" href="adjustment">Adjustments</a></li>
            </ul>
            <hr>
            <?php endif; ?>

            <?php if ($this->session->userdata('lt_isApprover')) : ?>
            <ul class="nav nav-sidebar">
                <li><a class="main-link" href="pending">Pending Leaves</a></li>
                <li><a class="main-link" href="summary">PTO Summary</a></li>
            </ul>
            <hr>
            <?php endif; ?>
            
            <ul class="nav nav-sidebar">
                <?php if ($this->session->userdata('lt_logged_ID') != 'SSCu') : ?>
                <li><a class="main-link" id="personal-trigger" href="personal">Personal Tracker</a></li>
                <?php endif; ?>
                <li><a href="/login/logout">Log Out</a></li>
            </ul>
        </div>

        <div id="main-container" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<?php endif; ?>

            <?php $this->load->view($content); ?>

<?php if(!$ajax_REQUESTED) : ?>
        </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo asset_url()."js/jquery-1.11.1.min.js";?>"></script>
    <script src="<?php echo asset_url()."js/jquery-ui.min.js";?>"></script>
    <script src="<?php echo asset_url()."jquery-ui/jquery-ui.min.js";?>"></script>
    <script src="<?php echo asset_url()."js/bootstrap.min.js";?>"></script>
    <script src="<?php echo asset_url()."js/bootstrap-typeahead.min.js";?>"></script>
    
    <script src="<?php echo asset_url()."datatables/js/jquery.dataTables.min.js";?>"></script>
    <script src="<?php echo asset_url()."datatables/js/dataTables.bootstrap.js";?>"></script>
    <script scr="<?php echo asset_url()."js/dataTables.responsive.js";?>"></script>
    <script scr="<?php echo asset_url()."validate/jquery-validate.min.js";?>"></script>
    <!--<script src="http://cdn.datatables.net/responsive/1.0.3/js/dataTables.responsive.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo asset_url()."js/ie10-viewport-bug-workaround.js";?>"></script>

    <script type="text/javascript">
    $("a.main-link").click(function(event){
        event.preventDefault();
        $('.nav-sidebar li').removeClass('active');
        $(this).parent().addClass('active');
        $('#main-container').html('<div><i class="fa fa-refresh fa-spin fa-3x fa-fw margin-bottom"></i> Loading...</div>');
        $('#main-container').load('<?php echo base_url() ?>index.php/'+ $(this).attr("href"));
    });
    </script>
<?php endif; ?>

    <?php $this->load->view($jsscript); ?>

<?php if(!$ajax_REQUESTED) : ?>
</body>
</html>
<?php endif; ?>

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
            $('.time-hours').html(hour);
            $('.time-minutes').html(m);
            $('.time-am-pm').html(mil);
            
            setTimeout(function(){startTime();},500);
        }

        function checkTime(i) {
            if (i<10) {i = "0" + i;}  // add zero in front of numbers < 10
            return i;
        }
        
        startTime();

        var d = new Date();
        var weekday = new Array(7);
        weekday[0]=  "Sunday";
        weekday[1] = "Monday";
        weekday[2] = "Tuesday";
        weekday[3] = "Wednesday";
        weekday[4] = "Thursday";
        weekday[5] = "Friday";
        weekday[6] = "Saturday";
        var n = weekday[d.getDay()];

        $('#date-day').html(n + " |");

        // var d = new Date();
        var month = new Array();
        month[0] = "January";
        month[1] = "February";
        month[2] = "March";
        month[3] = "April";
        month[4] = "May";
        month[5] = "June";
        month[6] = "July";
        month[7] = "August";
        month[8] = "September";
        month[9] = "October";
        month[10] = "November";
        month[11] = "December";
        var mm = month[d.getMonth()];
        var dd = d.getDate();
        var yyyy = d.getFullYear();

        $('#date-actual').html(mm + " " + dd + ", " + yyyy + " |");
        
    </script>