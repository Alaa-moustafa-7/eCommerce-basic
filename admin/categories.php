<?php
    ob_start();
    session_start();
    $pageTitle = 'Categories';
    
    if (isset($_SESSION['Username'])){
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        // Start Manage Page
        if ($do == 'Manage'){
            $sort = 'ASC';
            $sort_array = array("ASC", "DESC");
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
                $sort = $_GET['sort'];
            }
            $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ID $sort");
            $stmt2->execute();
            $cats = $stmt2->fetchAll(); 
            if(! empty($cats)){
            ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-edit"></i>Manage Categories
                        <div class="option pull-right">
                            <i class="fa fa-sort"></i> Ordering:[
                            <a class=" <?php if($sort == 'ASC') { echo 'active';} ?>" href="?sort=ASC">ASC</a> |
                            <a class=" <?php if($sort == 'DESC') { echo 'active';} ?>" href="?sort=DESC">DESC</a>]
                            <i class="fa fa-eye"></i>  View:[
                            <span class="active" data-view="full">Full</span>
                            <span data-view="classic">Classic</span>]
                        </div>  
                    </div>
                    <div class="panel-body">
                        <?php
                            foreach($cats as $cat){
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                                        echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='btn btn-xs btn-danger'><i class='fas fa-backspace'></i>Delete</a>";
                                    echo "</div>";
                                    echo "<h3>" . $cat['Name'] . '</h3>';
                                    echo "<div class='full-view'>";
                                        echo "<p>"; if($cat['Description'] == '') {echo 'This Category Has no';} else{ echo $cat['Description']; } echo "</p>";
                                        if($cat['Visibility'] == 1) { echo '<span class="visibility"><i class="fa fa-eye"></i>Hidden </span>';}
                                        if($cat['Allow_Comment'] == 1) { echo '<span class="commenting"><i class="fas fa-backspace"></i>Comment Disabled </span>';}
                                        if($cat['Allow_Ads'] == 1) { echo '<span class="adverties"><i class="fas fa-backspace"></i>Ads Disabled </span>';}
                                    echo "<div>";
                                      // Get Chiled Categories
                                $chiledCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                                if(! empty($chiledCats)){
                                echo "<h4 class='chiled-hed'> Chiled Category</h4>";
                                echo "<ul class='list-unstyled chiled-cats'>";
                                foreach($chiledCats as $c){
                                    echo "<li class='child-link'>
                                            <a href='categories.php?do=Edit&catid=" . $c['ID'] . "'>" . $c['Name'] . "</a>
                                            <a href='categories.php?do=Delete&catid=" . $c['ID'] . "' class='show-delete confirm'></i>Delete</a>
                                        </li>";
                                }
                                echo "</ul>";
                            }     
                                echo "<div>";
                                echo "<hr>";

                            }
                        ?>
                    </div>
                </div>
                <a class="btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>
            </div><?php } else {
                echo "<div class='container'>";
                    echo "<div class='nice-message'>There is no Recored Show</div>";
                echo '<a href="categories.php?do=Add" class="btn btn-sm btn-primary">
                     <i class="fas fa-plus"></i> New Item
                     </a>';
                echo "</div>";
            }?>
        <!-- End Manage Page -->

            <?php
            // Start Add Page
        } elseif ($do == 'Add'){ ?>
            <h1 class="text-center"> Add New Category </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- Start Name Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Name </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of The Category">
                        </div>
                    </div>
                    <!-- End Name Faild -->
                   
                    <!-- Start Description Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Description </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control" placeholder="Describe the category"/>
                        </div>
                    </div>
                    <!-- End Description Faild -->

                    <!-- Start Ordering Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Ordering </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="ordering" class="form-control" placeholder="Numer to the arrange catogery"/>
                        </div>
                    </div>
                    <!-- End Ordering Faild -->
                    <!-- Start Category type -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">parent?</label>
                        <div class="col-sm-10 col-sm-4">
                            <select name="parent">    
                                <option vlaue="0">None</option>
                                <?php
                                    $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "",  "ID");
                                    foreach($allCats as $cat){
                                        echo "<option value='" . $cat['ID'] ."'>" . $cat['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Category type -->
                    <!-- Start Visibility Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Visible </label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-No"type="radio" name="visibility" value="1" />
                                <label for="vis-No">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility Faild -->

                    <!-- Start Commenting Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Allow Commenting </label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-No"type="radio" name="commenting" value="1" />
                                <label for="com-No">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Commenting Faild -->

                    <!-- Start Ads Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Allow Ads </label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-No"type="radio" name="ads" value="1" />
                                <label for="ads-No">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Faild -->

                    <!-- Start submit Faild -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                    <!-- End submit Faild -->
                </form>
            </div> 
            <!-- End Add Page -->
            <?php

            // Start Insert page
        } elseif ($do == 'Insert'){

            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo  "<h1 class='text-center'> Update Member </h1>";
                echo  "<div class='container'>";
                // get variables from the form
                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $parent     = $_POST['parent'];
                $order      = $_POST['ordering'];
                $visible    = $_POST['visibility'];
                $coment     = $_POST['commenting'];
                $ads        = $_POST['ads'];
            
                // Check the Category Exist in database
                    
                    $check = checkItem("Name", "categories", $name);
                    if ($check == 1){
                        $theMsg = "<div class='alert alert-danger'>Sorry this category is exist</div>";
                        redirectHome($theMsg, 'back');
                    } else {
            
                //Isert User Info In Database
                $stmt = $con->prepare("INSERT INTO categories(Name, Description, parent, Ordering, 
                                    Visibility, Allow_Comment, Allow_Ads)
                                    VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :Zads) ");
                $stmt->execute(array(
                    'zname'    => $name,
                    'zdesc'    => $desc,
                    'zparent'  => $parent,
                    'zorder'   => $order,
                    'zvisible' => $visible,
                    'zcomment' => $coment,
                    'Zads'     => $ads
                ));
        
                //Successfly Data
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted </div>';
                redirectHome($theMsg, 'back');
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
        } elseif ($do == 'Edit'){

            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
            $stmt->execute(array($catid));
            $cat = $stmt->fetch();
            $count = $stmt->rowCount();
            
            if ($count > 0){ ?>
            
            <h1 class="text-center"> Edit Category </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="catid" value="<?php echo $catid; ?> "/>
                    <!-- Start Name Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Name </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Category" value="<?php echo $cat['Name']?>"/>
                        </div>
                    </div>
                    <!-- End Name Faild -->
                   
                    <!-- Start Description Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Description </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control" placeholder="Describe the category" value="<?php echo $cat['Description']?>"/>
                        </div>
                    </div>
                    <!-- End Description Faild -->

                    <!-- Start Ordering Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Ordering </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="ordering" class="form-control" placeholder="Numer to the arrange catogery" value="<?php echo $cat['Ordering']?>"/>
                        </div>
                    </div>
                    <!-- End Ordering Faild -->

                    <!-- Start Category type -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">parent?</label>
                        <div class="col-sm-10 col-sm-4">
                            <select name="parent">    
                                <option vlaue="0">None</option>
                                <?php
                                    $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "",  "ID");
                                    foreach($allCats as $c){
                                        echo "<option value='" . $c['ID'] ."'";
                                        if($cat['parent'] == $c['ID']) { echo 'selected'; }
                                        echo ">". $c['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Category type -->

                    <!-- Start Visibility Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Visible </label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0) { echo 'checked';} ?> />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-No"type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1) { echo 'checked';} ?>/>
                                <label for="vis-No">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility Faild -->

                    <!-- Start Commenting Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Allow Commenting </label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0) { echo 'checked';} ?> />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-No"type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1) { echo 'checked';} ?>/>
                                <label for="com-No">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Commenting Faild -->

                    <!-- Start Ads Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Allow Ads </label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0) { echo 'checked';} ?> />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-No"type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1) { echo 'checked';} ?>/>
                                <label for="ads-No">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Faild -->

                    <!-- Start submit Faild -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg"/>
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
        } elseif ($do == 'Update'){

            echo  "<h1 class='text-center'> Update Member </h1>";
            echo  "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                // get variables from the form
    
                $id       = $_POST['catid'];
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $order    = $_POST['ordering'];
                $parent   = $_POST['parent'];

                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads     = $_POST['ads'];
    
                // Check the there's no error proced the update Operation
            
                $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?,Ordering = ?,
                                    parent = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads =?  WHERE ID = ?");
                $stmt->execute(array($name, $desc, $order, $parent, $visible ,$comment ,$ads ,$id));
        
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
        } elseif ($do == 'Delete'){

            echo  "<h1 class='text-center'> Delete Category </h1>";
            echo  "<div class='container'>";
    
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;
            $check = checkItem('ID', 'categories', $catid);
    
            if ($check > 0){ 
                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
                $stmt->bindParam(":zid", $catid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Delete </div>';    
                redirectHome($theMsg, 'back');
            }else {
                $theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>';
                redirectHome($theMsg, 'back');
            }
            echo "</div>";
            // End Delete Page

        }

        include $tpl . 'footer.php';
    } else {
        header("Location: index.php");
        exit();
    }
    ob_end_flush();
?>