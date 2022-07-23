<?php

namespace App\Http\Controllers;

use App\Models\Vaccination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller{


    public function home(Request $request)
    {
        return view('home');
    }


    public function stats(Request $request)
    {
        $data = $request->all();
        //return response()->json(['debug' => $data]);
        if ( !empty($data) && !empty($data['start']) && !empty($data['end']) ){
            $govData = $this->connectToDataGovGr($data['start'], $data['end']);
            #return response()->json(['debug' => gettype($govData)]);
            if ( !is_array($govData) ){
                return response()->json(['error' => $govData]);
            } else{
                $this->addDataToDatabase($govData);
            }
            $chart = $this->fetchDataFromDatabase();
            return response()->json([
                'chart' => $chart, 
            ]);
        }
    }


    private function connectToDataGovGr($start, $end)
    {
        $url = "https://data.gov.gr/api/v1/query/mdg_emvolio?date_from={$start}&date_to={$end}";
        #return $url;
        $ch = curl_init();
        $headers = array();
        $headers[] = 'Authorization: Token cc5f2465e03496bcfb12950a438c2d082005017c';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        $error = curl_errno($ch);
        curl_close($ch);
        if ( $error ){
            return $error;
        } else if ( $output ){
            $govData = json_decode($output, true);
            return $govData;
        } else{
            return 'An unknown error occurred server-side';
        }
    }


    private function addDataToDatabase($govData)
    {
        Vaccination::truncate();
        if ( $govData ){
            foreach($govData as $obj){
                $entity = new Vaccination();
                $keys = array_keys($obj);
                if ( $keys ){
                    foreach($keys as $key){
                        $entity[$key] = $obj[$key];
                    }
                    $entity->save();
                }
            }
        }
    }


    private function fetchDataFromDatabase()
    {
        $chart = [];
        $vaccinationsPerDate = Vaccination::
        groupBy(DB::raw('DATE(referencedate)'))
        ->selectRaw('SUM(daytotal) AS cnt, SUM(dailydose1) AS cnt2, SUM(dailydose2) AS cnt3, SUM(dailydose3) AS cnt4, DATE(referencedate) as dt')
        ->get()
        ->toArray();
        if ( !empty($vaccinationsPerDate) ){
            foreach($vaccinationsPerDate as $v){
                $chart['labels'][] = date('d-m-Y', strtotime($v['dt']));  
                $chart['data1'][] = (int) $v['cnt'];
                $chart['data2'][] = (int) $v['cnt2'];
                $chart['data3'][] = (int) $v['cnt3'];
                $chart['data4'][] = (int) $v['cnt4'];
            }
        }
        return $chart;
    }

}