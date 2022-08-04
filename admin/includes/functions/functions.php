<?php


// get All Function V2.0  
function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC")
{
    global $con;

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
}

// Function latest 

function getCat()
{
    global $con;
    $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID DESC");
    $getCat->execute();
    $cats = $getCat->fetchAll();
    return $cats;
}



function getTitle()
{

    global $pageTitle;

    if (isset($pageTitle)) {

        echo $pageTitle;
    } else {
        echo 'Defualt';
    }
}

// check Item Function V2.0

function redirectHome($theMsg, $url = null, $seconds = 3)
{
    if ($url === null) {
        $url = 'index.php';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
        } else {
            $url = 'index.php';
            $link = 'HomePage';
        }
    }
    echo $theMsg;
    echo "<div class='alert alert-info'> You Will Be Redirect to $link after $seconds Seconds.</div>";
    header("refresh:$seconds;url=$url");
    exit();
}

// check Item Function V2.0

function checkItem($select, $from, $value)
{
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}

// Count Item Function v1.0

function countItem($item, $table)
{
    global $con;
    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    echo $stmt2->fetchColumn();
}

// Function latest 

function getLatest($select, $table, $order, $limit = 5)
{
    global $con;
    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;
}
