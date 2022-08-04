<?php
ob_start();
session_start();
$pageTitle = 'dashB';
if (isset($_SESSION['Username'])) {
    include 'init.php';

    // Start Dahbord Page
    $numUsers = 4; // Number of latest users 
    $latestUsers = getLatest("*", "users", "UserID", $numUsers); // latest user Array
    $numItems = 6; // Number Of Latest items
    $latestItems = getLatest("*", 'items', 'item_ID', $numItems);
    $numComments = 4;

?>
    <div class="home-stats">
        <div class="container text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-Members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"><?php echo countItem('UserID', 'users') ?></a><span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-Pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Member
                            <span>
                                <a href="members.php?do=Manage&page=Pending">
                                    <?php echo checkItem("RegStatus", "users", 0) ?>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-Item">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Item
                            <span><a href="items.php"><?php echo countItem('item_ID', 'items') ?></a><span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="stat st-comment">
                        <i class="fa fa-comment"></i>
                        <div class="info">
                            Total Comment
                            <span><a href="comments.php"><?php echo countItem('c_id', 'comments') ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>
                            latest <?php echo $numUsers ?> registerd Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                if (!empty($latestItems)) {
                                    foreach ($latestUsers as $user) {
                                        echo '<li>';
                                        echo  $user['Username'];
                                        echo  '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                        echo  '<span class="btn btn-success pull-right">';
                                        echo  '<i class="fa fa-edit"></i>Edit';
                                        if ($user['RegStatus'] == 0) {
                                            echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . "'  class='btn btn-info pull-right activate'><i class='fas fa-times'></i> Activate</a>";
                                        }
                                        echo '</span>';
                                        echo '</a>';
                                        echo '</li>';
                                    }
                                } else {
                                    echo "There No Recored To Show";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> latest <?php echo $numComments ?> Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                if (!empty($latestItems)) {
                                    foreach ($latestItems as $item) {
                                        echo '<li>';
                                        echo  $item['Name'];
                                        echo  '<a href="items.php?do=Edit&itemid=' . $item['item_ID'] . '">';
                                        echo  '<span class="btn btn-success pull-right">';
                                        echo  '<i class="fa fa-edit"></i>Edit';
                                        if ($item['Approve'] == 0) {
                                            echo "<a href='items.php?do=Approve&itemid=" . $item['item_ID'] . "'  
                                                                class='btn btn-info pull-right activate'>
                                                                <i class='fas fa-check'></i> Approve</a>";
                                        }
                                        echo '</span>';
                                        echo '</a>';
                                        echo '</li>';
                                    }
                                } else {
                                    echo "There No Recored Comment To Show";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start Latest Comment -->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> latest <?php echo $numComments ?> Comment
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                            $stmt = $con->prepare("SELECT 
                                        comments.*, users.Username
                                    FROM
                                        comments
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = comments.user_id
                                        ORDER BY c_id DESC
                                        LIMIT $numComments");

                            $stmt->execute();
                            $comments = $stmt->fetchAll();
                            if (!empty($comments)) {
                                foreach ($comments as $comment) {
                                    echo "<div class='comment-box'>";
                                    echo '<span class="member-n"> 
                                                <a href="members.php?do=Edit&userid=' . $comment['user_id'] . '">
                                                ' . $comment['Username'] . '</a></span>';
                                    echo '<p class="member-c">' . $comment['comment'] . '</span>';
                                    echo "</div>";
                                }
                            } else {
                                echo "There Is No Comment Show";
                            }
                            ?>
                        </div>
                    </div>
                    <div>
                    </div>

                    <!-- End Latest Comment-->

                </div>

            </div>
            <!-- End Dashboard Page -->

        <?php
        include  $tpl . 'footer.php';
    } else {
        header('location: index.php');
        exit();
    }
    ob_end_flush();
        ?>