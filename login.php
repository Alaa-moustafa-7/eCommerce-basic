<?php
ob_start();
session_start();
$pageTitle = 'Login';
if (isset($_SESSION['user'])) {
    header("Location: index.php");
}
include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hashedpass = sha1($pass);
        // Check If The User Exist In Database
        $stmt = $con->prepare("SELECT 
                                      UserID, Username, Password 
                                FROM 
                                     users 
                                WHERE 
                                     Username = ? 
                                AND 
                                    Password = ?");

        $stmt->execute(array($user, $hashedpass));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();

        // If Count > 0 Mean The Database Contain Record Abut This Username

        if ($count > 0) {
            $_SESSION['user'] = $user;  // Register Session Name
            $_SESSION['uid'] = $get['UserID']; // Register User ID In Session
            header('Location: index.php');  // Redirect To Dashboard Page
            exit();
        }
    } else {
        $formErrors = array();

        $username  = $_POST['username'];
        $password  = $_POST['password'];
        $password2 = $_POST['password2'];
        $email     = $_POST['email'];

        if (isset($username)) {
            $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);
            if (strlen($filterdUser) < 4) {
                $formErrors[] = 'Username Must Be Larger Than 4 Characters';
            }
        }

        if (isset($password) && isset($password2)) {
            if (empty($password)) {
                $formErrors[] = 'Sorry Password Cant Br Empty';
            }

            if (sha1($password) !== sha1($password2)) {
                $formErrors[] = 'Sorry Password Is Not Match';
            }
        }
        if (isset($email)) {
            $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] = 'This Email Is Not Valid';
            }
        }

        // Check the there's no error proced the user Add
        if (empty($formErrors)) {


            $check = checkItem("Username", "users", $username);
            if ($check == 1) {
                $formErrors[] = 'This User Is Exist';
            } else {

                //Isert User Info In Database
                $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, RegStatus, Date)
                                        VALUES(:zuser, :zpass, :zmail, 0, now())");
                $stmt->execute(array(
                    'zuser' => $username,
                    'zpass' => sha1($password),
                    'zmail' => $email
                ));

                $successMsg = 'Congrate You Are Now Register user';
            }
        }
    }
}

?>
<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> |
        <span data-class="signup">Signup</span>
    </h1>
    <!-- Start Login form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" />
        <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your password" />
        <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
    </form>
    <!-- End Login form -->
    <!-- Start Signup form -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <input pattern=".{4,}" title="User Must Beetwen 4" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" />
        <input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type a Complex password" />
        <input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type a password again" />
        <input class="form-control" type="email" name="email" placeholder="Type a Valid email" />
        <input class="btn btn-success btn-block" name="signup" type="submit" value="Login" />
    </form>
    <!-- End Signup form -->
    <div class="the-errors text-center">
        <div class="msg">
            <?php
            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {
                    echo '<div class="errors">' . $error . '<div><br>';
                }
            }

            if (isset($successMsg)) {
                echo '<div class="msg success">' . $successMsg . '</div>';
            }
            ?>
        </div>
    </div>
</div>

<?php
include $tpl . 'footer.php';
ob_end_flush();
?>