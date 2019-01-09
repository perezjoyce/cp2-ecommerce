<?php 
    require_once '../../../config.php';
    $totalAmount = $_SESSION['total_amount'];
?>

<form action="<?= BASE_URL ?>/app/controllers/process_charge.php" method="post">
    <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="pk_test_akuLqp7kIjCHQQJoK1oB7ol6"
        data-amount="<?= $totalAmount ?>"
        data-name="Mamaroo"
        data-description="Widget"
        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
        data-locale="auto">
    </script>
</form>