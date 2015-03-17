
<div class="login-template">
    <form class="form-signin" method="post" action="/index.php/login/" id="login" role="form">
        <h1 class="form-signin-heading"><small>Please sign in</small></h1>
        <small><i><?php 
            if(isset($error_login)){
                echo $error_login;
            }else{
                echo validation_errors();
            }
         ?></i></small>
        <label for="inputEmail" class="sr-only">User ID</label>
        <input type="text" name="uid" class="form-control" placeholder="User ID" required autofocus>
        <br>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <br>
        <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
    </form>
</div>