<?php

class MedicationRequest extends Model
{
   protected $table = 'medication_requests';
   protected $allowedColumns = ['time', 'date', 'patient_id', 'doctor_id', 'state'];
}
