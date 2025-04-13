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



    public function refund(){

        $query = "UPDATE patient SET e_wallet = IFNULL(e_wallet, 0) + 1500 WHERE id = ?;";
        $this->query($query,[$_SESSION['USER']->id]);
    }

    public function getPatientWalletAmount(){

        $query = "SELECT e_wallet FROM patient WHERE id = ?;";
        $amount = $this->query($query,[$_SESSION["USER"]->id]);
        return $amount;
    }
}