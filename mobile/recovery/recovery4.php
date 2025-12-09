<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>Forgot Password</title>

    <link rel="stylesheet" type="text/css" href="../assets/styles/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../assets/fonts/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../others/style.css?v=1.1.0">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <style>
        .container-fluid .top{background-color: <?php echo $color; ?> !important;}
        .bottom h2{color: <?php echo $color; ?> !important;}
        .btn{
            border-radius: 5rem !important;
            background-image: linear-gradient(to bottom, <?php echo $color; ?>, <?php echo $color; ?>) !important;
        }
        .color-highlight{color: <?php echo $color; ?> !important; }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="top">
            <h1>Password Recovery</h1>
        </div>

        <div class="bottom">
            <h2><?php echo strtoupper($name); ?></h2>
            <h4 id="accountname">Reset Your Password</h4>

            <div id="step1">
                <form method="POST" id="recovery-form">
                    <div class="form-group">
                        <label for="">Email Address</label> <br>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required />
                    </div>

                    <div class="form-group">
                       <button class="btn btn-primary" id="submit-btn" type="submit"><b>Send Recovery Code</b></button>
                    </div>
                </form>
            </div>

            <div id="step2" style="display:none;">
                <form method="POST" id="verify-form">
                    <input type="hidden" id="email2" name="email" />
                    <div class="form-group">
                        <label for="">Verification Code</label> <br>
                        <input type="number" class="form-control" id="code" name="code" placeholder="Enter verification code" required />
                    </div>

                    <div class="form-group">
                       <button class="btn btn-primary" id="verify-btn" type="submit"><b>Verify Code</b></button>
                    </div>
                </form>
            </div>

            <div id="step3" style="display:none;">
                <form method="POST" id="reset-form">
                    <input type="hidden" id="email3" name="email" />
                    <input type="hidden" id="code3" name="code" />
                    <div class="form-group">
                        <label for="">New Password</label> <br>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required />
                    </div>

                    <div class="form-group">
                        <label for="">Confirm Password</label> <br>
                        <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm new password" required />
                    </div>

                    <div class="form-group">
                       <button class="btn btn-primary" id="reset-btn" type="submit"><b>Reset Password</b></button>
                    </div>
                </form>
            </div>

            <footer class="mt-3">
                <h5>Remember your password? <a href="../login/">Login</a></h5>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
    $("document").ready(function(){

        // Step 1: Send Recovery Code
        $('#recovery-form').submit(function(e){
            e.preventDefault();
            $('#submit-btn').removeClass("btn-primary");
            $('#submit-btn').addClass("btn-secondary");
            $('#submit-btn').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Sending Code ...');

            $.ajax({
                url:'../home/includes/route.php?get-user-code',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success:function(resp){
                    if(resp == 0){
                        swal('Alert!!', 'An error occurred. Please try again.', "error");
                    }
                    else if(resp == 1){
                        swal('Alert!!', 'Email address not found. Please check and try again.', "error");
                    }
                    else if(resp == 2){
                        var email = $('#email').val();
                        $('#email2').val(email);
                        $('#step1').hide();
                        $('#step2').show();
                        swal('Success!', 'Verification code sent to your email!', "success");
                    }

                    $('#submit-btn').removeClass("btn-secondary");
                    $('#submit-btn').addClass("btn-primary");
                    $('#submit-btn').html("<b>Send Recovery Code</b>");
                }
            });
        });

        // Step 2: Verify Code
        $('#verify-form').submit(function(e){
            e.preventDefault();
            $('#verify-btn').removeClass("btn-primary");
            $('#verify-btn').addClass("btn-secondary");
            $('#verify-btn').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Verifying ...');

            $.ajax({
                url:'../home/includes/route.php?verify-user-code',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success:function(resp){
                    if(resp == 0){
                        var email = $('#email2').val();
                        var code = $('#code').val();
                        $('#email3').val(email);
                        $('#code3').val(code);
                        $('#step2').hide();
                        $('#step3').show();
                        swal('Success!', 'Code verified successfully!', "success");
                    }
                    else if(resp == 1){
                        swal('Alert!!', 'Invalid verification code. Please try again.', "error");
                    }

                    $('#verify-btn').removeClass("btn-secondary");
                    $('#verify-btn').addClass("btn-primary");
                    $('#verify-btn').html("<b>Verify Code</b>");
                }
            });
        });

        // Step 3: Reset Password
        $('#reset-form').submit(function(e){
            e.preventDefault();

            var password = $('#password').val();
            var confirmPassword = $('#confirm-password').val();

            if(password !== confirmPassword){
                swal('Alert!!', 'Passwords do not match!', "error");
                return false;
            }

            if(password.length < 8){
                swal('Alert!!', 'Password must be at least 8 characters long!', "error");
                return false;
            }

            $('#reset-btn').removeClass("btn-primary");
            $('#reset-btn').addClass("btn-secondary");
            $('#reset-btn').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Resetting ...');

            $.ajax({
                url:'../home/includes/route.php?update-user-pass',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success:function(resp){
                    if(resp == 0){
                        swal('Success!', 'Password reset successful! Redirecting to login...', "success");
                        setTimeout(function(){
                            location.replace('../login/')
                        }, 2000);
                    }
                    else if(resp == 1){
                        swal('Alert!!', 'An error occurred. Please try again.', "error");
                    }

                    $('#reset-btn').removeClass("btn-secondary");
                    $('#reset-btn').addClass("btn-primary");
                    $('#reset-btn').html("<b>Reset Password</b>");
                }
            });
        });
    });
    </script>

</body>
</html>
