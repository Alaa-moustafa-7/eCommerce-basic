<?php

session_start();    
$pageTitle ='Comments';
if(isset($_SESSION['Username'])){
        
    include 'init.php';

    // Start Manage Page
    $do =  isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage'){ // Mange Member Page 
       
        $stmt = $con->prepare("SELECT 
                                    comments.*, items.Name, users.Username
                                FROM
                                    comments
                                INNER JOIN
                                    items
                                ON
                                    items.item_ID = comments.item_id
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = comments.user_id");

        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(! empty($rows)){
        ?>
        <h1 class="text-center"> Manage Comments </h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table text-center table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Controle</td>
                    </tr>
                    <?php
                        foreach($rows as $row){
                        echo "<tr>";
                            echo "<td>" . $row['c_id'] . "</td>";
                            echo "<td>" . $row['comment'] . "</td>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . $row["Username"] . "</td>";
                            echo "<td>" . $row['comment_date'] . "</td>";
                            echo "<td>
                                    <a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                    <a href='comments.php?do=Delete&comid=" . $row['c_id'] . "'  class='btn btn-danger confirm'><i class='fas fa-times'></i>Delete</a>";
                                    if ($row['status'] == 0){
                                    echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . "'  
                                    class='btn btn-info activate'><i class='fas fa-check'></i>Approve </a>";
                                    }
                            echo "</td>";
                        echo "</tr>";
                                    
                        }
                    
                    ?>
                </table>
            </div>
        </div><?php } else {
                echo "<div class='container'>";
                    echo "<div class='nice-message'>There is no Recored Show</div>";
                echo '<a href="comments.php?do=Add" class="btn btn-sm btn-primary">
                     <i class="fas fa-plus"></i> New Item
                     </a>';
                echo "</div>";
            }?>
        <!-- End manage Page -->

    <?php
    // Start Edit Page
}elseif ($do == 'Edit'){ // Page Edit 
    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;
    $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ? ORDER BY ASC");
    $stmt->execute(array($comid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    
    if ($count > 0){ ?>
    

    <h1 class="text-center"> Edit Comment </h1>

    <div class="container">
        <form class="form-horizontal" action="?do=Update" method="POST">
            <input type="hidden" name="comid" value="<?php echo $comid; ?> "/>
            <!-- Start Comment Faild -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label"> Comment </label>
                <div class="col-sm-10 col-md-4">
                    <textarea class="form-control" name="comment"> <?php echo $row['comment'] ?> </textarea>
                </div>
            </div>
            <!-- End Comment Faild -->
            
            <!-- Start submit Faild -->
            <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="save" class="btn btn-primary btn-lg"/>
                </div>
            </div>
            <!-- End submit Faild -->
        </form>
    </div>
    
<?PHP } else{
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>theres no such ID</div>";
            redirectHome($theMsg, 'back');
            echo "</div>";
        }
    // End Edit Page

// Start Update Page
    }elseif ($do == 'Update'){ // page Update
        echo  "<h1 class='text-center'> Update Member </h1>";
        echo  "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            // get variables from the form

            $comid      = $_POST['comid'];
            $comment    = $_POST['comment'];
            
            // Check the there's no error proced the update Operation
                $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                $stmt->execute(array($comment, $comid));
        
                //Successfly Data
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Update </div>';
                redirectHome($theMsg, 'back'); 
          
            }else{
                $theMsg = "<div class='alert alert-danger'>sorry you cant browse this page directly</div>";
                redirectHome($theMsg, 'back');
        }
        
        echo  "</div>";
        // End Update Page

        // Start Delete Page
    } elseif ($do == 'Delete'){ // Delete Member Page
        echo  "<h1 class='text-center'> Delete Comment </h1>";
        echo  "<div class='container'>";

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;
        $check = checkItem('c_id', 'comments', $comid);

        if ($check > 0){ 
            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
            $stmt->bindParam(":zid", $comid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Delete </div>';    
            redirectHome($theMsg, 'back');
        }else {
            $theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirectHome($theMsg, 'back');
        }
        echo "</div>";
        // End Delete Page

        // Start Activate Page
        } elseif ($do == 'Approve'){
            echo  "<h1 class='text-center'> Approve Comment </h1>";
            echo  "<div class='container'>";

            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;
            $check = checkItem('c_id', 'comments', $comid);

            if ($check > 0){ 
                $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
                $stmt->execute(array($comid));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved </div>';    
                redirectHome($theMsg, 'back');
            }else {
                $theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>';
                redirectHome($theMsg, 'back');
            }
            echo "</div>"; 
        }
        // End Activate page

        include  $tpl . 'footer.php';

    }else{

        header('location: index.php');

        exit();

}