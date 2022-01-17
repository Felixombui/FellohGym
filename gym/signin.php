<?php
include 'config.php';
if(isset($_POST['login'])){
    $emailaddress=addslashes($_POST['emailaddress']);
    $password=addslashes($_POST['password']);
    $loginqry=mysqli_query($config,"SELECT * FROM users WHERE emailaddress='$emailaddress' AND password='$password'");
    if(mysqli_num_rows($loginqry)>0){
        $loginrow=mysqli_fetch_assoc($loginqry);
        session_start();
        $_SESSION['user']=$loginrow['names'];
        $_SESSION['emailaddress']=$loginrow['emailaddress'];
        $_SESSION['photo']=$loginrow['photo'];
        $_SESSION['regdate']=$loginrow['regdate'];
        $_SESSION['userid']=$loginrow['id'];
        header('location:index.php');
    }else{
        $error='<img src="images/error.png" width="20" height="20" align="left">Login error! Account not found!';
    }
}
?>
<div class="header"><a href="index.php"><img src="images/menu.png" width="30" height="30" align="left"></a> &nbsp;Gym Trainer & Progress  Tracker
<div><h2>Sign in</h2></div>
</div>
<div class="loginform">
    <form method="post">
        <table width="100%"><tr><td><input type="email" name="emailaddress" placeholder="Enter your email address" style="width: 100%; padding: 5px;" required="required"></td></tr>
        <tr><td><input type="password" name="password" placeholder="Enter your password" required="required"></td></tr>
        <tr><td><input type="submit" name="login" value="Sign in"></td></tr>
        <tr><td><?php echo $error ?></td></tr>
    </table>
    </form>
</div>
<?php
include 'styles.html';
?>