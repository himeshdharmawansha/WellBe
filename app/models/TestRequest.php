<?php

class TestRequest extends Model
{
   protected $table = 'test_requests';
   protected $allowedColumns = [ 'date', 'patient_id', 'doctor_id', 'state'];
}
