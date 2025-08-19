<?php

namespace App\Http\Controllers;

 class Controller
{
    
    public $start;

    public $end;

    public $schemaNames=[];

    public function __construct()
    {
        // You can add any common functionality for all controllers here
        $this->start = date('Y-01-01');
        $currentMonth = now()->format('Y-m');
        $this->end = \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->endOfMonth()->toDateString();

    }
}
