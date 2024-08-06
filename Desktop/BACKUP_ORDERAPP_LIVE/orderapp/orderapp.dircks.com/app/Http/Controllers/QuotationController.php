<?php

namespace App\Http\Controllers;

use App\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyEmail;
use App\Markup;

class QuotationController extends Controller
{

    public $suberDispacheObj;
    public $markupList;
    public function __construct()
    {
        $this->suberDispacheObj =  new SuperDispatchController;
        //$this->markupList =  Config::get('app.QUOTATION_MARKUP');;
		$this->markupList = Markup::all()->pluck('value', 'type')->toArray();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        //return view('quotation',compact('type'));
        //$markupListFind = (array)json_decode($this->markupList);
		$markupListFind = $this->markupList;
        if (array_key_exists($type, $markupListFind)) {
            return view('quotation', compact('type'));
        } else {
            abort('404');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postFiled = (object)[];
        $postFiledStore = (object)[];
        $type = $request->type1;
        $pickupAddress = $request->pickup_address;
        $deliveryAddress = $request->delivery_address;
        /* Order Number */
        $postFiled->origin = $pickupAddress;
        $postFiled->destination   = $deliveryAddress;
		$postFiled->isOpen = (($request['trailer_type']=='true') ?  true :  false);
		//$postFiled->isOpen = $request->trailer_type;

        $postFiledStore->origin = $pickupAddress;
        $postFiledStore->destination   = $deliveryAddress;
        $postFiledStore->customer_name   = $request->customer_name;
        $postFiledStore->customer_email_id   = $request->customer_email_id;
        $postFiledStore->sales_person_name   = $request->sales_person_name;

        $vehicles = [];
        $vehicleSignleArray = [];
        $vehicleLength = $request->vehicle_length;
        for ($i = 1; $i <= $vehicleLength; $i++) {
            $vehicleSignleArray = array(
                'year' => $request['year' . $i],
                'make' => $request['make' . $i],
                'model' => $request['model' . $i],
				'type' => isset($request['type' . $i]) ? $request['type' . $i] : null, // Include 'type' field if available
                'isInOperable' => (($request['indp' . $i]) ?  true :  false)
            );
            array_push($vehicles, $vehicleSignleArray);
        }
        $postFiledStore->vehicles = $vehicles;
		$postFiledStore->trailer_type = $request->trailer_type;
        $getQuotation = $this->xApi(json_encode(array_merge((array)$postFiled, $vehicleSignleArray)));
        $getQuotation = json_decode($getQuotation);
		//dd($vehicleSignleArray,$postFiled,array_merge((array)$postFiled, $vehicleSignleArray),$getQuotation);
        if (($getQuotation->totalPrice)) {
            if (($request->quote_type) != '' && $request->quote_type != null) {
                $getQuoteValue = $getQuotation->totalPrice;
                //$markupListFind = (array)json_decode($this->markupList);
				$markupListFind = $this->markupList;
                $selectedMarkupValue = $markupListFind[$request->quote_type];
                //$finalValue =  (($getQuoteValue * $selectedMarkupValue) / 100) + $getQuoteValue;
				$finalValue = $getQuoteValue / (1 - ($selectedMarkupValue / 100));
				 $finalValue = ceil($finalValue * 100) / 100;
                $finalValue = sprintf("%.2f", $finalValue);
                $postFiledStore->quote_value = $getQuoteValue;
                $postFiledStore->quote_type = $request->quote_type;
                $postFiledStore->final_value = $finalValue;
            }
            Quotation::create([
                'payload' => json_encode($postFiledStore),
            ]);
            /* Mail */
            $name = ($request->customer_name) ? $request->customer_name : 'Dear'; // You can pass the necessary data to the Mailable constructor
            if ($request->customer_email_id) {
                //Mail::to($request->customer_email_id)->send(new MyEmail($name,$finalValue));
                //Mail::to($request->customer_email_id)->send(new MyEmail($name, $finalValue, $vehicles, $pickupAddress, $deliveryAddress));
				//Mail::to($request->customer_email_id)->send(new MyEmail($name, $finalValue, $vehicles, $pickupAddress, $deliveryAddress));
				Mail::to($request->customer_email_id)->send(new MyEmail($name, $finalValue, $vehicles, $pickupAddress, $deliveryAddress, $request->trailer_type));
            }
            /* Mail */

            return response()->json(array('status' => true, 'quote_value' => $finalValue));
        } else {
            return response()->json(array('status' => false, 'error' => 'Something went wrong.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show(Quotation $quotation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit(Quotation $quotation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quotation $quotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {
        //
    }
    public function list()
    {
        $qData =  Quotation::select('payload')->orderBy('id', 'DESC')->get();
        $data = [];
        foreach ($qData as $value) {
             array_push($data, json_decode($value['payload']));
        }
		

        return view('quotation_list', compact('data'));
    }

    public function xApi($data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://squotation.xelentor.com/WeatherForecast',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
