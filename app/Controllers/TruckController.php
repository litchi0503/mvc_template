<?php

namespace App\Controllers;

use App\Models\Truck;

class TruckController extends CoreController
{
    public function list()
    {
        $truckList = Truck::findAll();

        $data = [
            'truckList' => $truckList
        ];

        $this->show('truck/list', $data);
    }
}