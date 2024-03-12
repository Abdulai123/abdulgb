<?php
use app\Models\Pagination\Pagination;
use app\Models\Product\Product;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['reject']) && $_POST['reject'] === 'Reject') {
        $pause = Product::updateStatus(decodeId($_POST['productID']), 'Rejected', $conn);
        if ($pause === 1) {
            return;
        }elseif($pause === 0){
            echo "Error Code: Unable to Pause product, create a support ticket.";
        }
    }elseif (isset($_POST['approve']) && $_POST['approve'] === 'Approve') {
        $unpause = Product::updateStatus(decodeId($_POST['productID']), 'Active', $conn);
        if ($unpause === 1) {
            return;
        }elseif($unpause === 0){
            echo "Error Code: Unable to unPause product, create a support ticket.";
        }
    }
}
?>
<div class="products-grid">
    <?php
    $pagination = new Pagination($conn, isset($_GET['page']) ? intval($_GET['page']) : 1, 12, 4,null,null,null,null,null,null,true);
    $pagination->display_pending_products();
    ?>
</div>
<?php
$pagination = new Pagination($conn, isset($_GET['page']) ? intval($_GET['page']) : 1, 12, 4);
$pagination->display_pagination();
?>
</div>