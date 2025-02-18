<?php
{
public function getPaymentData()
    {


        try {
            $merchant_id         = $_POST['merchant_id'];
            $assignment_id       = $_POST['custom_1'];
            $order_id            = $_POST['order_id'];
            $payhere_amount      = $_POST['payhere_amount'];
            $payhere_currency    = $_POST['payhere_currency'];
            $status_code         = $_POST['status_code'];
            $md5sig              = $_POST['md5sig'];

            $merchant_secret = $_ENV['PAYHERE_SECRET']; // Replace with your Merchant Secret

            $local_md5sig = strtoupper(
                md5(
                    $merchant_id .
                        $order_id .
                        $payhere_amount .
                        $payhere_currency .
                        $status_code .
                        strtoupper(md5($merchant_secret))
                )
            );

            if (($local_md5sig === $md5sig) and ($status_code === "2")) {
                $payment = new Payment();
                $result = $payment->addPayment($appointment_id, $status_code);
                if($result == true){
                header('Content-Type:application/json');
                echo json_encode(["status" => "success", "message" => "Payment Successfully Recorded"]);
                }
                else{
                header('Content-Type:application/json');
                echo json_encode(["status" => "failed", "message" => "Payment Failed to  Record"]);

                }
            } else {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(['error' => 'Invalid Payment Details or Signature']);
            }
        } catch (Exception $e) {
            error_log($e);
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error fetching Data']);
        }
    }
}
?>