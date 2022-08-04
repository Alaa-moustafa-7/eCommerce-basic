<?php
    ob_start();
    session_start();
    $pageTitle = 'Create New Item';
    include 'init.php';
    if(isset($_SESSION['user'])){
       
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $formErrors = array();

        $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

        if(strlen($name) < 4){
            $formErrors[] = 'Item Title Must Be At Least 4 Characters';
        }

        if(strlen($desc) < 10){
            $formErrors[] = 'Item Title Must Be At Least 10 Characters';
        }

        if(strlen($country) < 2){
            $formErrors[] = 'Item Title Must Be At Least 2 Characters';
        }

        if(empty($price)){
            $formErrors[] = 'Item Price Must Be Not Empty';
        }

        if(empty($status)){
            $formErrors[] = 'Item Status Must Be Not Empty';
        }

        if(empty($category)){
            $formErrors[] = 'Item Category Must Be Not Empty';
        }

        // Check the there's no error proced the update Operation
        if (empty($formErrors)){
            
            //Isert User Info In Database
            $stmt = $con->prepare("INSERT INTO 
                        items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)
                     VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");
            $stmt->execute(array(
                'zname'    => $name,
                'zdesc'    => $desc,
                'zprice'   => $price,
                'zcountry' => $country,
                'zstatus'  => $status,
                'zcat'     => $category,
                'zmember'  => $_SESSION['uid'],
                'ztags'  => $tags
                
            ));
              // Echo Seccess Message
              if($stmt){
                  $successMsg = 'Item has Been Added';
              }  
        
        }

    }

?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo $pageTitle ?></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <!-- Start Name Faild -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label"> Name </label>
                            <div class="col-sm-10 col-md-9">
                                <input 
                                    pattern=".{4,}"
                                    title="this Faild Required At Latest 4 Character"
                                    type="text" 
                                    name="name" 
                                    class="form-control live" 
                                    required="required"                                    placeholder="Name of The Item" 
                                    data-class=".live-title"/>
                            </div>
                        </div>
                        <!-- End Name Faild -->

                        <!-- Start Description Faild -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label"> Description </label>
                            <div class="col-sm-10 col-md-9">
                                <input 
                                    type="text" 
                                    name="description" 
                                    class="form-control live" 
                                    required="required"
                                    placeholder="Description of The Item" 
                                    data-class=".live-desc"/>
                            </div>
                        </div>
                        <!-- End Description Faild -->

                        <!-- Start Price Faild -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label"> Price </label>
                            <div class="col-sm-10 col-md-9">
                                <input 
                                    type="text" 
                                    name="price" 
                                    class="form-control live" 
                                    required="required"
                                    placeholder="Price of The Item" 
                                    data-class=".live-price"/>
                            </div>
                        </div>
                        <!-- End Price Faild -->

                        <!-- Start Country Faild -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label"> Country </label>
                            <div class="col-sm-10 col-md-9">
                                <input 
                                    type="text" 
                                    name="country" 
                                    class="form-control" 
                                    required="required"
                                    placeholder="Country of Made">
                            </div>
                        </div>
                        <!-- End Country Faild -->

                        <!-- Start Status Faild -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label"> Status </label>
                            <div class="col-sm-10 col-md-9">
                                <select name="status">
                                    <option value=""></option>
                                    <option value="1">New</option>
                                    <option value="2">Like New</option>
                                    <option value="3">Used</option>
                                    <option value="4">very Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Status Faild -->

                        <!-- Start Category Faild -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Category</label>
                            <div class="col-sm-10 col-md-9">
                                <select name="category">
                                    <option value=""></option>
                                    <?php
                                        
                                        $cats = getAllFrom('*', 'categories', '', '', 'ID');
                                        foreach($cats as $cat){
                                        echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";          
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Category Faild -->

                        <!-- Start Tags Faild -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-3 control-label">Tags</label>
                            <div class="col-sm-10 col-sm-9">
                                <input
                                    type="text"
                                    name="tags"
                                    class="form-control"
                                    placeholder="Separate Tags With Comma (,)" 
                                    value=""/>
                            </div>
                        </div>
                        <!-- End Tags Faild -->

                        <!-- Start submit Faild -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-3 col-sm-9">
                                <input type="submit" value="Add Item" class="btn btn-primary btn-lg"/>
                            </div>
                        </div>
                        <!-- End submit Faild -->   
                    </form>

                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">
                                $<span class="live-price">0</span>
                            </span>
                            <img class="img-responsive" src="images.png" alt="" />
                            <div class="caption">
                                <h3 class="live-title">Title</h3>
                                <p class="live-desc">Description</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start Looping Through Errors -->
                <?php
                    if(!empty($formErrors)){
                        foreach($formErrors as $error){
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }

                    if(isset($successMsg)){
                        echo '<div class="alert alert-success">' . $successMsg . '</div>';
                    }
                ?>
                <!-- End Looping Through Errors -->
            </div>
        </div>
    </div>
</div>

<?php

    } else {
        header("Location: login.php");
        exit();
    }

    include  $tpl . 'footer.php'; 
    ob_end_flush();
?>
