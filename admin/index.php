<?php
$noNavbar = '';
$pageTitle = 'Login';
session_start();
if (isset($_SESSION['Username'])) {
    header('Location: dashb.php');
}
include 'init.php';

// Check If Your Coming Form HTTP Post Request

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedpass = sha1($password);

    // Check If The User Exist In Database

    $stmt = $con->prepare("SELECT 
                                        UserID, Username, Password 
                                    FROM 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        Password = ? 
                                    AND 
                                        GroupID = 1
                                    LIMIT 1");

    $stmt->execute(array($username, $hashedpass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    // If Count > 0 Mean The Database Contain Record Abut This Username

    if ($count > 0) {
        $_SESSION['Username'] = $username;  // Register Session Name
        $_SESSION['ID'] = $row['UserID'];  // Register Session Id
        header('Location: dashb.php');  // Redirect To Dashboard Page
        exit();
    }
}

?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center"> First Project </h4>
    <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplate="off" />
    <input class="form-control input-lg" type="password" name="pass" placeholder="password" autocomplete="new-password" />
    <input class="btn btn-lg btn-primary btn-block" type="submit" value="login" />
</form>

<?php include  $tpl . 'footer.php'; ?>