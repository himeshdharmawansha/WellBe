<?php

//user class

class Payment extends Model
{
    protected $table = 'payment';

    protected $allowedColumns = [
        'order_id',
        'payhere_amount',
        'payhere_currency',
        'status_code',
        'merchent_secret',
        'merchant_id',
    ];

    public function addPayment($order_id,$payhere_amount,$payhere_currency,$status_code)
    {
        try {
            $paymentID = generateUUID($this->db);
            $this->db->query("INSERT INTO $this->table (paymentID,assignmentID,orderID,payhereAmount,payhereCurrency,statusCode)
            VALUES (UNHEX(:paymentID),UNHEX(:assignmentID),UNHEX(:orderID),:payhereAmount,:payhereCurrency,:statusCode);");

            $this->db->bind(':paymentID', $paymentID);
            // $this->db->bind(':assignmentID', $assignment_id);
            $this->db->bind(':orderID', $order_id);
            $this->db->bind(':payhereAmount', $payhere_amount);
            $this->db->bind(':payhereCurrency', $payhere_currency);
            $this->db->bind(':statusCode',$status_code);

            $this->db->execute();

            $this->db->query("UPDATE packageAssignment SET isPaid=TRUE WHERE assignmentID=UNHEX(:assignmentID)");
            // $this->db->bind(':assignmentID', $assignment_id);
            $this->db->execute();


            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

}
