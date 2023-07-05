<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SensorController extends Controller
{
    //sensor A storing data
    public function storeSensorDataA (Request $request){
        error_log($request);
       $sensorData = $request->json()->all();
       $sensorName = $sensorData['sensor_name'];
       $flowRate = $sensorData['flow_rate'];
        $volume = $sensorData['volume'];
       // Get the current UTC time
$currentDateTime = Carbon::now();

// Add 3 hours to the current time
$adjustedTime = $currentDateTime->addHours(3);

// Convert the adjusted time to a specific format
$formattedTime = $adjustedTime->format('H:i:s');

 //inserting into the maintank table        
 $result = DB::table('main_tank')->insert(
    ['sensor_name'=> $sensorName,'flow_rate'=>$flowRate,'volume'=>$volume,'time_stamp'=>$formattedTime] 
);
       

    }

    //sensor B storing data
    public function storeSensorDataB (Request $request){
        // Get the current UTC time
$currentDateTime = Carbon::now();

// Add 3 hours to the current time
$adjustedTime = $currentDateTime->addHours(3);

// Convert the adjusted time to a specific format
$formattedTime = $adjustedTime->format('H:i:s');
         

// echo "Current system time (HH:MM:SS): " . $formattedTime;
error_log($request);
$sensorDataB = $request->json()->all();
$sensorName = $sensorDataB['sensor_name'];
$flowRate = $sensorDataB['flow_rate'];
$volume = $sensorDataB['volume'];

 //inserting into the maintank table        
 $result = DB::table('branch_tank')->insert(
    ['sensor_name'=> $sensorName,'flow_rate'=>$flowRate,'volume'=>$volume,'time_stamp'=>$formattedTime] 
);


    }

//Dashboard function
public function getData(){
    $sensorAData = DB::table('main_tank')
                    ->orderByDesc('main_tank_data_id')
                    ->limit(1)
                    ->get();
    $volumeA = $sensorAData[0]->volume;
    
    
    $sensorBData = DB::table('branch_tank')
                    ->orderByDesc('branch_tank_data_id')
                    ->limit(1)
                    ->get();
    $sensorBId = $sensorBData[0]->branch_tank_data_id;
    $volumeB = $sensorBData[0]->volume;
    $leakage=false;
    $difference = $volumeA - $volumeB;
    
    if($difference>=0.4){
        $result = DB::table('leakage')->insert(

        ['branch_tank_data_id'=> $sensorBId]

        );
        $leakage=true;
    };

    //charts data
    $sensorAChartData = DB::table('main_tank')
                    ->limit(200)
                    ->get();
    $sensorBChartData = DB::table('branch_tank')
                        ->limit(200)
                        ->get();
     $response = [
         'main_tank_data' => $sensorAData,
        'branch_tank_data' => $sensorBData,
        'sensorA_chart_data'=>$sensorAChartData,
        'sensorB_chart_data'=>$sensorBChartData,
        'leakage'=>$leakage
        ];
    
    return response()->json($response);
}

public function getVolumeA(){
    $results = DB::table('main_tank')
                 ->orderByDesc('main_tank_data_id')
                 ->limit(30)
                 ->get();
    return response()->json($results);
}

public function getVolumeB(){
    $results = DB::table('branch_tank')
                 ->orderByDesc('branch_tank_data_id')
                 ->limit(30)
                 ->get();
    return response()->json($results);
}

//leakage function
public function getLeakage(){
    $results = DB::table('leakage')
                ->join('branch_tank','branch_tank.branch_tank_data_id','=','leakage.branch_tank_data_id')
                ->select('leakage_id','sensor_name','time_stamp')
                ->limit(10)
                ->get();
    return response()->json($results);
}

}
