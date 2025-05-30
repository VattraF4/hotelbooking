<?php


if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    echo "<script>window.location.href='../auth/logout.php';</script>";
    exit;

}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pay Page</title>
</head>

<body>
    <!-- 
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="container" style="margin-top: none">
            <a class="navbar-brand  text-white" href="#">Pay Page</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

            </div>
        </div>
    </nav> -->

    <!--  -->
    <div class="container" style="max-width: 500px; margin-left: -45px;">
        <!-- Replace "test" with your own sandbox Business account app client ID -->
        <script
            src="https://www.paypal.com/sdk/js?client-id=AZZuV1XSGSM55ouuH4jq-zq5wQqCy65rL_c876chzPkhzVFpLJ8nS4-m12hf1_b33AMRq8gNBIrqH_VE&currency=USD"></script>
        <!-- Set up a container element for the button -->
        <div id="paypal-button-container"></div>
        <script>
            paypal.Buttons({
                // Sets up the transaction when a payment button is clicked
                createOrder: (data, actions) => {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                // value: '300' // Can also reference a variable or function
                                value: '<?php echo $_SESSION['payment']; ?>'
                            }
                        }]
                    });
                },
                // Finalize the transaction after payer approval
                onApprove: (data, actions) => {
                    return actions.order.capture().then(function (orderData) {

                        window.location.href = '<?php echo APP_URL; ?>rooms/updatePayment.php?status=success&id=<?php echo $_SESSION['booking_id']; ?>';
                    });
                }
            }).render('#paypal-button-container');
        </script>

    </div>
    </div>
    </div>
    <!-- <?php echo $_SESSION['payment']; ?> -->
    <?php require '../include/footer.php';