<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetPayment extends Model
{
    use HasFactory;
    protected $table = 'budget_payment';
    
    public $timestamps = false;
}

