<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="libraries/bootstrap-3.3.7/docs/favicon.ico">-->

    <title>Office Management | PT Aquamarine Divindo Inspection</title>
    <base href="<?= base_url(); ?>" />

    <script type="text/javascript" src="libraries/vegas/jquery-1.11.1.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="libraries/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="libraries/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="libraries/bootstrap-3.3.7/docs/examples/signin/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--<link rel="stylesheet" href="css/gaya-egateway.css" type="text/css">-->
    <link rel="stylesheet" href="css/gaya-egateway.css" type="text/css">

    <!-- VEGAS -->
    <?php /*?><link rel="stylesheet" type="text/css" href="libraries/vegas/jquery.vegas.min.css">
    <!--<link rel="stylesheet" type="text/css" href="/css/styles.css">-->
    <script type="text/javascript" src="libraries/vegas/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="libraries/vegas/jquery.vegas.min.js"></script>
    <!--<script type="text/javascript" src="/js/global.js"></script>-->
    <script>
    $( function() {
        $.vegas( 'slideshow', {
            delay: 8000,
            backgrounds: [
                { src: 'images/bg-login-1.png', fade: 4000 },
                { src: 'images/bg-login-2.png', fade: 4000 }
                //{ src: 'images/background2.jpg', fade: 4000 },
                //{ src: 'images/background1.jpg', fade: 4000 }
            ]
        } )( 'overlay' );

        $( '.documentation' ).click( function() {
            $( 'ul ul' ).slideToggle();
            return false;
        });

        $( '.credits, .contact' ).click( function() {
            $( '#overlay, #credits' ).fadeIn();
            return false;
        });

        $( '#overlay a, #credits a' ).click( function(e) {
            e.stopPropagation();
        });

        $( '#overlay, #credits, #download' ).click( function() {
            $( '#overlay, #credits, #download' ).fadeOut();
            return false;
        });

        $( '.mailto' ).click( function() {
            var a = $( this ).attr( 'href' );
            e = a.replace( '#', '' ).replace( '|', '@' ) + '.com';
            document.location = 'ma' + 'il' + 'to:' + e + "?subject=[Vegas] I'd like to hire you!";
            e.preventDefault;
            return false;
        });

        $("#superheader h6").click(function(e) {
            var $$ = $( this ),
                $menu = $('#superheader ul');

            e.stopPropagation();

            if ( $menu.is(':visible') ) {
                $menu.hide();
                $$.removeClass( 'open' );
            } else {
                $menu.show();
                $$.addClass( 'open' );
                $('body').one('click', function() {
                    $('#superheader ul').hide();
                });
            }
        });
        $( "#superheader li" ).click( function( e ) {
            document.location = $( this ).find( 'a' ).attr( 'href' );
        });

        $( '.download' ).click( function() {
            $( '#overlay, #download' ).show();
        });
    } );
    </script><?php */ ?>
    <style>
        body {
            margin-bottom: 0px !important;
            background: none;
        }
		.area-login-kiri .header{
			height: 80px;
			*background: #290396;
			*background: #476eb5;
			color: #FFFFFF;
			font-size: 30px;
			font-weight: bold;
			text-transform: uppercase;
			padding-top: 15px;
			padding-bottom: 15px;
			
			background-color: #274987; 
			background-image: url(images/linear_bg_1.png); 
			background-repeat: repeat-y; 
			background: -webkit-gradient(linear, left top, right top, from(#274987), to(#476eb5)); 
			background: -webkit-linear-gradient(left, #274987, #476eb5); 
			background: -moz-linear-gradient(left, #274987, #476eb5); 
			background: -ms-linear-gradient(left, #274987, #476eb5); 
			background: -o-linear-gradient(left, #274987, #476eb5);
		}
		.area-login-kiri .header .logo img{
			height: 50px;
		}
		.area-login-kiri .header ~ .inner{
			height: calc(100vh - 80px);
		}
    </style>

</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 area-login-kiri">
            	<div class="header">
                	<span class="logo"><img src="images/logo1.png"></span> PT Aquamarine Divindo Inspection
                </div> 
                <div class="inner">
                
                	<div class="logo">
                	<img src="images/img-login.png">
                    </div>
                </div>
                <!--<div class="logo-preventive-hero"><img src="images/logo-app.png"></div>-->
            </div>
            <div class="col-md-4 area-login-kanan">
                <form class="form-signin" method="post" action="login/action">

                    <span class="icon-user">LOGIN AREA</span>

                    <label for="inputEmail" class="sr-only">User Name</label>
                    <input type="text" name="reqUser" id="inputEmail" class="form-control" placeholder="Username" required>

                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password" name="reqPasswd" id="inputPassword" class="form-control" placeholder="Password" required>

                    <div style="color:#FFFFFF; font-size:20px;"><?= $pesan ?></div>

                    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                </form>

            </div>
        </div>

    </div> <!-- /container -->

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>

</html>
