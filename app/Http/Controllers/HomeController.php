<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{

    public function index() {
        return view("home");
    }    

    public function test() {      
        // try {
        //     (new ZohoAuth())->init();

        //     $module = 'Deals';
        //     $fields = ['Deal_Name','Account_Name','Closing_Date','Stage'];

        //     $result = ZohoRecords::get($module, $fields);
        //     dump("Deals", $result);
        // } catch (\Exception $e) {
        //     dump("Error", $e->getMessage());
        // }

        // try {
        //     (new ZohoAuth())->init();

        //     $module = 'Accounts';
        //     $data = [
        //         "Account_Name"  => "Test",
        //         "Closing_Date"  => "05.05.2000",
        //         "Deal_Name"     => "Test",
        //         "Stage"         => "Start"
        //     ];

        //     $result = ZohoRecords::create($module, $data);
        //     dump("Create Deals", $result);
        // } catch (\Exception $e) {
        //     dump("Error", $e->getMessage());
        // }
    }


}
