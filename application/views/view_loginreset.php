
<div class="login-template">
    <form class="form-signin" method="post" action="/index.php/login/reset/" id="login" role="form">
        <h1 class="form-signin-heading"><small>Please reset your password</small></h1>
        <small><i><?php echo validation_errors(); ?></i></small>
        <small>Password must be alpha-numeric and has 8-16 characters.</small>

        <label for="inputPassword" class="sr-only">New Password</label>
        <input type="password" name="npass" class="form-control" placeholder="New Password" required>
        <br>
        <label for="inputPassword" class="sr-only">Confirm Password</label>
        <input type="password" name="cpass" class="form-control" placeholder="Confirm Password" required>
        <br>
        <button type="submit" class="btn btn-lg btn-primary btn-block">Reset Password</button>
    </form>
</div>