<?php

session_start();
$pageTitle = 'Members';
if (isset($_SESSION['Username'])) {

    include 'init.php';

    // Start Manage Page
    $do =  isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') { // Mange Member Page 
        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
?>
            <h1 class="text-center"> Manage Member </h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-members table text-center table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>FullName</td>
                            <td>Registerd Date</td>
                            <td>Controle</td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>";
                            if (empty($row['avatar'])) {
                                echo '<img src="layout/css/images/avatar.png" alt="no image" />';
                            } else {
                                echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='no image' />";
                            }
                            echo "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row["FullName"] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                                    <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                    <a href='members.php?do=Delete&userid=" . $row['UserID'] . "'  class='btn btn-danger confirm'><i class='fas fa-times'></i>Delete</a>";
                            if ($row['RegStatus'] == 0) {
                                echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "'  class='btn btn-info activate'><i class='fas fa-times'></i> Activate</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        ?>
                    </table>
                </div>
                <a href="members.php?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i> New Member </a>
            </div>
        <?php } else {
            echo "<div class='container'>";
            echo "<div class='nice-message'>There is no Recored Show</div>";
            echo '<a href="members.php?do=Add" class="btn btn-primary">
                  <i class="fas fa-plus"></i> New Member 
                  </a>';
            echo "</div>";
        } ?>
        <!-- End manage Page -->

        <!-- Start Add Page -->
    <?php } elseif ($do == 'Add') { // Add Page this page to insert 
    ?>
        <h1 class="text-center"> Add New Member </h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                <!-- Start Username Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Username </label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Username To Login Into Shop">
                    </div>
                </div>
                <!-- End Username Faild -->

                <!-- Start Password Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Password </label>
                    <div class="col-sm-10 col-md-4">
                        <input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password mandatory" />
                        <i class="far fa-eye fa-2x"></i>
                    </div>
                </div>
                <!-- End Password Faild -->

                <!-- Start Email Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Email </label>
                    <div class="col-sm-10 col-md-4">
                        <input type="email" name="email" value="" class="form-control" required="required" placeholder="Enter Email true" />
                    </div>
                </div>
                <!-- End Fullname Faild -->

                <!-- Start Email Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Fullname </label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="full" value="" class="form-control" required="required" placeholder="Full name enter" />
                    </div>
                </div>
                <!-- End Fullname Faild -->

                <!-- Start Avatar Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> User Avatar </label>
                    <div class="col-sm-10 col-md-4">
                        <input type="file" name="avatar" value="" class="form-control" required="required" />
                    </div>
                </div>
                <!-- End Avatar Faild -->

                <!-- Start submit Faild -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- End submit Faild -->
            </form>
        </div>
        <!-- End Add Page -->

        <!-- Start Insert Page -->
        <?php } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo  "<h1 class='text-center'> Update Member </h1>";
            echo  "<div class='container'>";

            // Upload Variables

            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp  = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            // List Of Allowed File Typed To Upload
            $avatarAllowedExtention = array("jpeg", "jpg", "png", "gif");

            // Get Avatar Extention

            $avatarExtention = strtolower(end(explode('.', $avatarName)));


            // get variables from the form
            $user    = $_POST['username'];
            $pass    = $_POST['password'];
            $email   = $_POST['email'];
            $name    = $_POST['full'];

            $hashPass = sha1($_POST['password']);
            // Validate Form

            $formErrors = array();

            if (strlen($user) < 3) {
                $formErrors[] = "FullNsme less than 5 char<stronge> Empty </stromge>";
            }

            if (strlen($user) > 20) {
                $formErrors[] = "charecter more than 20 <stronge> Empty </stromge>";
            }

            if (empty($user)) {
                $formErrors[] = "charecter Username <stronge> Empty </stromge>";
            }

            if (empty($pass)) {

                $formErrors[] = "this password  <stronge> Empty </stromge>";
            }

            if (empty($email)) {
                $formErrors[] = "this Email <stronge> Empty </stromge>";
            }

            if (empty($name)) {
                $formErrors[] = "FullName <stronge> Empty </stromge> ";
            }

            if (!empty($avatarName) && !in_array($avatarExtention, $avatarAllowedExtention)) {
                $formErrors[] = "This Extention Is Not <stronge> Allowed </stronge> ";
            }

            if (empty($avatarName)) {
                $formErrors[] = "Avatar Is <stronge> Required </stronge> ";
            }

            if ($avatarSize > 4194304) {
                $formErrors[] = "Avatar Cant Be Larger Than <stronge> 4MB </stronge> ";
            }

            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check the there's no error proced the update Operation
            if (empty($formErrors)) {

                $avatar = rand(0, 1000000) . '_' . $avatarName;

                move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);


                $check = checkItem("Username", "users", $user);
                if ($check == 1) {
                    $theMsg = "<div class='alert alert-danger'>this user yes</div>";
                    redirectHome($theMsg, 'back');
                } else {

                    //Isert User Info In Database
                    $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, RegStatus, Date, avatar)
                            VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar) ");
                    $stmt->execute(array(
                        'zuser'     => $user,
                        'zpass'     => $hashPass,
                        'zmail'     => $email,
                        'zname'     => $name,
                        'zavatar'   => $avatar
                    ));

                    //Successfly Data
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted </div>';
                    redirectHome($theMsg, 'back');
                }
            }
        } else {
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'> sorry you cant browse this page directly </div>";
            redirectHome($theMsg, 'back');
            echo "</div>";
        }
        echo  "</div>";
        // End Insert Page

        // Start Edit Page
    } elseif ($do == 'Edit') { // Page Edit 
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>


            <h1 class="text-center"> Edit Member </h1>

            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?> " />
                    <!-- Start Username Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Username </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="username" value="<?php echo $row['Username'] ?>" required="required" class="form-control">
                        </div>
                    </div>
                    <!-- End Username Faild -->
                    <!-- Start Password Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Password </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" class="form-control" />
                            <input type="password" name="newpassword" value="" class="form-control" placeholder="Leave Blanlk If You Dont Want To Change" />
                        </div>
                    </div>
                    <!-- End Password Faild -->
                    <!-- Start Email Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Email </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="email" name="email" value="<?php echo $row['Email'] ?>" required="required" class="form-control" />
                        </div>
                    </div>
                    <!-- End Fullname Faild -->
                    <!-- Start Email Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Fullname </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="full" value="<?php echo $row['FullName'] ?>" required="required" class="form-control" />
                        </div>
                    </div>
                    <!-- End Fullname Faild -->
                    <!-- Start submit Faild -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End submit Faild -->
                </form>
            </div>

<?PHP } else {
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>theres no such ID</div>";
            redirectHome($theMsg, 'back');
            echo "</div>";
        }
        // End Edit Page

        // Start Update Page
    } elseif ($do == 'Update') { // page Update
        echo  "<h1 class='text-center'> Update Member </h1>";
        echo  "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // get variables from the form

            $id      = $_POST['userid'];
            $user    = $_POST['username'];
            $email   = $_POST['email'];
            $name    = $_POST['full'];

            // Password Trick

            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            // Validate Form

            $formErrors = array();

            if (strlen($user) < 3) {
                $formErrors[] = "FullNsme less than 5 char<stronge> Empty </stromge>";
            }

            if (strlen($user) > 20) {
                $formErrors[] = "charecter more than 20 <stronge> Empty </stromge>";
            }

            if (empty($user)) {

                $formErrors[] = "this user VALIDATE <stronge> Empty </stromge>";
            }

            if (empty($email)) {
                $formErrors[] = "this Email VALIDATE<stronge> Empty </stromge>";
            }

            if (empty($name)) {
                $formErrors[] = "FullName <stronge> Empty </stromge> ";
            }

            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check the there's no error proced the update Operation
            if (empty($formErrors)) {
                $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?,FullName = ?,
                                    Password = ? WHERE UserID = ?");
                $stmt->execute(array($user, $email, $name, $pass, $id));

                //Successfly Data
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Update </div>';
                redirectHome($theMsg, 'back');
            }
        } else {
            $theMsg = "<div class='alert alert-danger'>sorry you cant browse this page directly</div>";
            redirectHome($theMsg, 'back');
        }

        echo  "</div>";
        // End Update Page

        // Start Delete Page
    } elseif ($do == 'Delete') { // Delete Member Page
        echo  "<h1 class='text-center'> Delete Member </h1>";
        echo  "<div class='container'>";

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check = checkItem('userid', 'users', $userid);

        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
            $stmt->bindParam(":zuser", $userid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Delete </div>';
            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirectHome($theMsg, 'back');
        }
        echo "</div>";
        // End Delete Page

        // Start Activate Page
    } elseif ($do == 'Activate') {
        echo  "<h1 class='text-center'> Activate Member </h1>";
        echo  "<div class='container'>";

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check = checkItem('userid', 'users', $userid);

        if ($check > 0) {
            $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            $stmt->execute(array($userid));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated </div>';
            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirectHome($theMsg, 'back');
        }
        echo "</div>";
    }
    // End Activate page

    include  $tpl . 'footer.php';
} else {

    header('location: index.php');

    exit();
}
