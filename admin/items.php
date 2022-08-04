<?php

/*
    =================================
    === Items Page
    =================================
    */

ob_start();  // OutPut Buffering Start

session_start();

$pageTitle = 'Items';

if (isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $stmt = $con->prepare("SELECT 
                                        items.*, categories.Name AS category_name, users.Username 
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
                                    ORDER BY item_ID ASC");
        $stmt->execute();
        $items = $stmt->fetchAll();
        if (!empty($items)) {
?>
            <h1 class="text-center"> Manage Items </h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table table text-center table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Controle</td>
                        </tr>
                        <?php
                        foreach ($items as $item) {
                            echo "<tr>";
                            echo "<td>" . $item['item_ID'] . "</td>";
                            echo "<td>" . $item['Name'] . "</td>";
                            echo "<td>" . $item['Description'] . "</td>";
                            echo "<td>" . $item["Price"] . "</td>";
                            echo "<td>" . $item['Add_Date'] . "</td>";
                            echo "<td>" . $item['category_name'] . "</td>";
                            echo "<td>" . $item['Username'] . "</td>";
                            echo "<td>
                                        <a href='items.php?do=Edit&itemid=" . $item['item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                        <a href='items.php?do=Delete&itemid=" . $item['item_ID'] . "'  class='btn btn-danger confirm'><i class='fas fa-times'></i>Delete</a>";
                            if ($item['Approve'] == 0) {
                                echo "<a href='items.php?do=Approve&itemid=" . $item['item_ID'] . "'
                                            class='btn btn-info activate'>
                                            <i class='fas fa-check'></i> Approve</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        ?>
                    </table>
                </div>
                <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> New Item </a>
            </div>
        <?php } else {
            echo "<div class='container'>";
            echo "<div class='nice-message'>There is no Recored Show</div>";
            echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary">
                     <i class="fas fa-plus"></i> New Item
                     </a>';
            echo "</div>";
        } ?>
        <!-- End manage Page -->

    <?php
        // Start Add Page
    } elseif ($do == 'Add') { ?>

        <h1 class="text-center"> Add New Item </h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start Name Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Name </label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Item">
                    </div>
                </div>
                <!-- End Name Faild -->

                <!-- Start Description Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Description </label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="description" class="form-control" required="required" placeholder="Description of The Item">
                    </div>
                </div>
                <!-- End Description Faild -->

                <!-- Start Price Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Price </label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="price" class="form-control" required="required" placeholder="Price of The Item">
                    </div>
                </div>
                <!-- End Price Faild -->

                <!-- Start Country Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Country </label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="country" class="form-control" required="required" placeholder="Country of Made">
                    </div>
                </div>
                <!-- End Country Faild -->

                <!-- Start Status Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Status </label>
                    <div class="col-sm-10 col-md-4">
                        <select name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">very Old</option>
                        </select>
                    </div>
                </div>
                <!-- End Status Faild -->

                <!-- End Member Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-4">
                        <select name="member">
                            <option value="0">...</option>
                            <?php
                            $allMember = getAllFrom("*", "users", "", "", "UserID");
                            foreach ($allMember as $user) {
                                echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Member Faild -->

                <!-- Start Category Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-4">
                        <select name="category">
                            <option value="0">...</option>
                            <?php
                            $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID");
                            foreach ($allCats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                $allChild = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                                foreach ($allChild as $child) {
                                    echo "<option value='" . $child['ID'] . "'>----" . $child['Name'] . $cat['Name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Faild -->

                <!-- Start Tags Faild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-sm-4">
                        <input type="text" name="tags" class="form-control" palceholder="Separate Tags With Comma (,)" />
                    </div>
                </div>
                <!-- End Tags Faild -->

                <!-- Start submit Faild -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Item" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- End submit Faild -->
            </form>
        </div>
        <!-- End Add Page -->

        <?php
        // Start Insert Page
    } elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo  "<h1 class='text-center'> Update Member </h1>";
            echo  "<div class='container'>";
            // get variables from the form
            $name    = $_POST['name'];
            $desc    = $_POST['description'];
            $price   = $_POST['price'];
            $country = $_POST['country'];
            $status  = $_POST['status'];
            $member  = $_POST['member'];
            $cat     = $_POST['category'];
            $tags    = $_POST['tags'];

            // Validate Form

            $formErrors = array();

            if (empty($name)) {
                $formErrors[] = "Name Can't be <strong>Empty</strong>";
            }

            if (empty($desc)) {
                $formErrors[] = "Description Can't be <strong>Empty</strong>";
            }

            if (empty($price)) {
                $formErrors[] = "Price Can't be <strong>Empty</strong>";
            }

            if (empty($country)) {
                $formErrors[] = "Country Can't be <strong>Empty</strong>";
            }

            if ($status == 0) {
                $formErrors[] = "You Must Choose The <strong>Status</strong>";
            }

            if ($member == 0) {
                $formErrors[] = "You Must Choose The <strong>Member</strong>";
            }

            if ($cat == 0) {
                $formErrors[] = "You Must Choose The <strong>Category</strong>";
            }

            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check the there's no error proced the update Operation
            if (empty($formErrors)) {

                //Isert User Info In Database
                $stmt = $con->prepare("INSERT INTO 
                            items(Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID, tags)
                         VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zmember, :zcat, :ztags)");
                $stmt->execute(array(
                    'zname'    => $name,
                    'zdesc'    => $desc,
                    'zprice'   => $price,
                    'zcountry' => $country,
                    'zstatus'  => $status,
                    'zmember'  => $member,
                    'zcat'     => $cat,
                    'ztags'    => $tags
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
    } elseif ($do == 'Edit') {

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");
        $stmt->execute(array($itemid));
        $item = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>

            <h1 class="text-center"> Edit Item </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $item; ?> " />
                    <!-- Start Name Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Name </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Item" value="<?php echo $item['Name'] ?>" />
                        </div>
                    </div>
                    <!-- End Name Faild -->

                    <!-- Start Description Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Description </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control" required="required" placeholder="Description of The Item" value="<?php echo $item['Description'] ?>" />
                        </div>
                    </div>
                    <!-- End Description Faild -->

                    <!-- Start Price Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Price </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="price" class="form-control" required="required" placeholder="Price of The Item" value="<?php echo $item['Price'] ?>" />
                        </div>
                    </div>
                    <!-- End Price Faild -->

                    <!-- Start Country Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Country </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="country" required="required" class="form-control" placeholder="Country of Made" value="<?php echo $item['Country_Made'] ?>" />
                        </div>
                    </div>
                    <!-- End Country Faild -->

                    <!-- Start Status Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Status </label>
                        <div class="col-sm-10 col-md-4">
                            <select name="status">
                                <option value="0">...</option>
                                <option value="1" <?php if ($item['Status'] == 1) {
                                                        echo 'selected';
                                                    } ?>>New</option>
                                <option value="2" <?php if ($item['Status'] == 2) {
                                                        echo 'selected';
                                                    } ?>>Like New</option>
                                <option value="3" <?php if ($item['Status'] == 3) {
                                                        echo 'selected';
                                                    } ?>>Used</option>
                                <option value="4" <?php if ($item['Status'] == 4) {
                                                        echo 'selected';
                                                    } ?>>very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Faild -->

                    <!-- End Member Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="member">
                                <option value="0">...</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                    echo "<option value='" . $user['UserID'] . "'";
                                    if ($item['Member_ID'] == $user['UserID']) {
                                        echo 'selected';
                                    }
                                    echo ">"  . $user['Username'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Member Faild -->

                    <!-- Start Category Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="category">
                                <option value="0">...</option>
                                <?php
                                $stmt2 = $con->prepare("SELECT * FROM categories");
                                $stmt2->execute();
                                $cats = $stmt2->fetchAll();
                                foreach ($cats as $cat) {
                                    echo "<option value='" . $cat['ID'] . "'";
                                    if ($item['Cat_ID'] == $cat['ID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $cat['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Category Faild -->

                    <!-- Start Tags Faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-sm-4">
                            <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" value="<?php echo $item['tags'] ?>" />
                        </div>
                    </div>
                    <!-- End Tags Faild -->

                    <!-- Start submit Faild -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Item" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End submit Faild -->
                </form>

                <?php

                $stmt = $con->prepare("SELECT 
                                        comments.*, users.Username
                                    FROM
                                        comments
                                    INNER JOIN
                                        users
                                    ON  
                                        users.UserID = comments.user_id
                                    WHERE
                                        item_id = ?");

                $stmt->execute(array($itemid));
                $rows = $stmt->fetchAll();

                ?>

    <?PHP
        } else {
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>theres no such ID</div>";
            redirectHome($theMsg, 'back');
            echo "</div>";
        }
        // End Edit Page

        // Start Update Page
    } elseif ($do == 'Update') {

        echo  "<h1 class='text-center'> Update Item </h1>";
        echo  "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // get variables from the form

            $id      = $_POST['itemid'];
            $name    = $_POST['name'];
            $desc    = $_POST['description'];
            $price   = $_POST['price'];
            $country = $_POST['country'];
            $status  = $_POST['status'];
            $cat     = $_POST['category'];
            $member  = $_POST['member'];
            $tags    = $_POST['tags'];

            $formErrors = array();

            if (empty($name)) {
                $formErrors[] = "Name Can't be <strong>Empty</strong>";
            }

            if (empty($desc)) {
                $formErrors[] = "Description Can't be <strong>Empty</strong>";
            }

            if (empty($price)) {
                $formErrors[] = "Price Can't be <strong>Empty</strong>";
            }

            if (empty($country)) {
                $formErrors[] = "Country Can't be <strong>Empty</strong>";
            }

            if ($status == 0) {
                $formErrors[] = "You Must Choose The <strong>Status</strong>";
            }

            if ($cat == 0) {
                $formErrors[] = "You Must Choose The <strong>Category</strong>";
            }

            if ($member == 0) {
                $formErrors[] = "You Must Choose The <strong>Member</strong>";
            }

            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check the there's no error proced the update Operation
            if (empty($formErrors)) {

                $stmt2 = $con->prepare("SELECT 
                                                * 
                                            FROM 
                                                items 
                                            WHERE 
                                                Name = ?
                                            AND 
                                                item_ID = ?");

                $stmt2->execute(array($name, $id));
                $count = $stmt2->rowCount();
                if ($count == 1) {
                    echo "Sorry This Users Exist";
                    redirectHome($theMsg, 'back');
                } else {

                    $stmt = $con->prepare("UPDATE 
                                                items 
                                            SET 
                                                Name = ?, 
                                                Description = ?,
                                                Price = ?,
                                                Country_Made = ?,
                                                Status = ?,
                                                Cat_ID = ?,
                                                Member_ID =?,
                                                tags = ?
                                            WHERE
                                                item_ID = ?");

                    $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

                    //Successfly Data
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Update </div>';
                    redirectHome($theMsg, 'back');
                }
            }
        } else {
            $theMsg = "<div class='alert alert-danger'>sorry you cant browse this page directly</div>";
            redirectHome($theMsg, 'back');
        }
        echo  "</div>";
        // End Update Page

        // Start Delete Page
    } elseif ($do == 'Delete') {

        echo  "<h1 class='text-center'> Delete Item </h1>";
        echo  "<div class='container'>";

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check = checkItem('item_ID', 'items', $itemid);

        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zid");
            $stmt->bindParam(":zid", $itemid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Delete </div>';
            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirectHome($theMsg, 'back');
        }
        echo "</div>";
        // End Delete Page

        // Start Approve Page
    } elseif ($do == 'Approve') {

        echo  "<h1 class='text-center'> Approve Item </h1>";
        echo  "<div class='container'>";

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check = checkItem('item_ID', 'items', $itemid);

        if ($check > 0) {
            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
            $stmt->execute(array($itemid));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated </div>';
            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirectHome($theMsg, 'back');
        }
        echo "</div>";
    }
    // End Activate page

    include $tpl . 'footer.php';
} else {

    header("Location: index.php");
    exit();
}

ob_end_flush(); // Release the OutPut

    ?>