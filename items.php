<?php
    session_start();
    $pageTitle = 'Show Item';
    include 'init.php';

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
    $stmt = $con->prepare("SELECT 
                                items.*, categories.Name, users.Username 
                            FROM 
                                items 
                            INNER JOIN
                                categories
                            ON
                                categories.ID = items.Cat_ID
                            INNER JOIN
                                users
                            ON
                                users.UserID = items.Member_ID
                            WHERE 
                                item_ID = ?
                            AND
                                Approve = 1");

    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();
    if($count > 0){
    
    $item = $stmt->fetch();
    
?>

<h1 class="text-center"><?php echo $item['Name'];?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img class="img-responsive img-thumbnail center-block" src="images.png" alt="" />
        </div>
        <div class="col-md-9 item-info">
            <h2><?php echo $item['Name'] ?></h2>
            <p><?php echo $item['Description'] ?></p>
            <ul class="list-unstyled">
                <li>
                    <i class="fa fa-calendar fa-fw"></i>
                    <span> Add Date</span> : <?php echo $item['Add_Date'] ?>
                </li>
                <li>
                    <i class="far fa-money-bill-alt"></i>
                    <span> Price</span> : $ <?php echo $item['Price'] ?>
                </li>
                <li>
                    <i class="fa fa-building fa-fw"></i>
                    <span> Made In</span> : <?php echo $item['Country_Made'] ?>
                </li>
                <li>
                    <i class="fa fa-tags fa-fw"></i>
                    <span> Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['Name'] ?> </a>
                </li>
                <li>
                    <i class="fa fa-user fa-fw"></i>
                    <span> Added By</span> : <a href="#"><?php echo $item['Username'] ?></a>
                </li>
                <li class="tags-items">
                    <i class="fa fa-user fa-fw"></i>
                    <span>Tags</span> : 
                    <?php
                        $allTags = explode(",", $item['tags']);
                        foreach($allTags as $tag){
                            $tag = str_replace(' ','', $tag);
                            $lowertag = strtolower($tag);
                            if(! empty($tag)){
                            echo  "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
                        }
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <hr class="custom-hr">
    <?php if(isset($_SESSION['user'])){ ?>
    <!-- Start Add Comment -->
    <div class="row">
        <div class="col-md-offset-3">
            <div class="add-comment">
                <h3>Add Your Comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['item_ID'] ?>" method="POST">
                    <textarea name="comment" required></textarea>
                    <input class="btn btn-primary" type="submit" value="Add Comment" />
                </form>
                <?php 
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        
                        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                        $itemid  = $item['item_ID'];
                        $userid  = $_SESSION['uid'];

                        if(! empty($comment)){

                            $stmt = $con->prepare("INSERT INTO 
                                    comments (comment, status, comment_date, item_id, user_id)
                                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");
                            $stmt->execute(array(

                                ':zcomment' => $comment,
                                ':zitemid'  => $itemid,
                                ':zuserid'  => $userid

                            ));
                            if($stmt){
                                echo '<div class="alert alert-success">Added Comment</div>';
                            } 

                        } else {
                            echo '<div class="alert alert-danger">Null Comment</div>';
                        }

                    }
                ?>
            </div>
        </div>
    </div>
     <!-- End Add Comment -->
     <?php } else {
         echo 'Login <a href="login.php">Or Register</a> To Add Comment';
     } ?>
    <hr class="custom-hr">
    <?php 
        // Select All User Except Admin
        $stmt = $con->prepare("SELECT
                                    comments.*, users.Username
                                FROM
                                    comments
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = comments.user_id
                                WHERE
                                    item_id = ?
                                AND
                                    status = 1
                                ORDER BY
                                    c_id DESC");
        
        $stmt->execute(array($item['item_ID']));
        $comments = $stmt->fetchAll();

    ?>

    <?php foreach($comments as $comment){ ?>
        <div class="comment-box">
            <div class="row">
                <div class="col-sm-2 text-center">  
                    <img class="img-responsive img-thumbnail img-circle center-block" src="images.png" alt="" />  
                    <?php echo $comment['Username'] ?>
                </div>
                <div class="col-sm-10"> 
                    <p class="lead"> <?php echo $comment['comment'] ?> </p>
                </div> 
            </div> 
        </div>
        <hr class="custom-hr">
    <?php }  ?>
</div>

<?php
    }else {
        echo 'There\'s No Such ID NO Approval';
    }
    include  $tpl . 'footer.php'; 
?>
