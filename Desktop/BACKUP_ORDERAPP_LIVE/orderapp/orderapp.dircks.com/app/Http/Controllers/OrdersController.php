<?php

namespace App\Http\Controllers;

use App\CodeMaintain;
use App\Orders;
use App\Dispatcher;
use Illuminate\Http\Request;
use App\Http\Controllers\SuperDispatchController;
use InvalidArgumentException;
use Sunrise\Vin\Vin;
use BiegalskiLLC\NHTSAVehicleAPI\VehicleApi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;



class OrdersController extends Controller
{
    public $suberDispacheObj;
    public function __construct()
    {
        $this->suberDispacheObj =  new SuperDispatchController;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }
    
     /**
     * Show the form for creating a new order.
     *
     * @return \Illuminate\Http\Response
     */
    public function createV2()
    {
        
       
        $postFiled = [];
        $postFiled['size'] = '200';
        $postUrl = 'internal/users';
        $terminalDetails = $this->suberDispacheObj->getApiCall($postUrl, $postFiled, true);
        $sales = $dispatchers = ($terminalDetails['data']) ? $terminalDetails['data']['objects'] : null;
        //$dispatchers =  Dispatcher::where('role','Dispatcher')->select('full_name as first_name', 'email')->get();
        //$sales =  Dispatcher::where('role','Sales')->select('full_name as first_name', 'email')->get();
        $v2 = true;
        return view('order', compact('dispatchers', 'sales','v2'));
    }

    /**
     * Show the form for creating a new order.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$dispatchers =  Dispatcher::where('role','Dispatcher')->select('full_name as first_name', 'email')->get();
        //$sales =  Dispatcher::where('role','Sales')->select('full_name as first_name', 'email')->get();
        $postFiled = [];
        $postFiled['size'] = '200';
        $postUrl = 'internal/users';
        $terminalDetails = $this->suberDispacheObj->getApiCall($postUrl, $postFiled, true);
        $sales = $dispatchers = ($terminalDetails['data']) ? $terminalDetails['data']['objects'] : null;
        return view('order', compact('dispatchers', 'sales'));
    }

    /**
     * Store a newly created order to database as well as super dispatch platform.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if (isset($request->order_number)) {
            $orderNumber = $request->order_number;
        } else {
            $postFiled = [];
            $postFiled['size'] = '200';
            $postUrl = 'internal/shippers/get_and_increment_curr_load_id';
            $orderCount = $this->suberDispacheObj->getApiCall($postUrl, $postFiled, true);
            $orderNumber = $orderCount['data']['object']['load_id'];
        }

        
        $vehicleLength = $request->vehicle_length;
        //$orderNumber = $request->order_number;
        $purchaseOrderNumber = $request->po_order_number;
        $trasnsportType = $request->trasnsport_type;

        $customerName = $request->customer_name;
        $customerVenueBusinessType = $request->customer_venue_business_type;
        $customerAddress = $request->customer_address;
        $customerState = $request->customer_state;
        $customerCity = $request->customer_city;
        $customerZipcode = $request->customer_zip_code;
        $customerPhoneNumber = $request->customer_phone_number;
        $customerBillingEmail = $request->customer_billing_email;

        $pickupBusinessName = $request->pickup_business_name;
        $pickupVenueBusinessType = $request->pickup_venue_business_type;
        $pickupBusinessAddress = $request->pickup_business_address;
        $pickupBusinessState = $request->pickup_business_state;
        $pickupBusinessCity = $request->pickup_business_city;
        $pickupBusinessZipCode = $request->pickup_business_zip_code;
        $pickupContactName = $request->pickup_contact_name;
        $pickupContactTitle = $request->pickup_contact_title;
        $pickupContactPhoneNumber = $request->pickup_contact_phone_number;
        $pickupContactMobileNumber = $request->pickup_contact_mobile_number;
        $pickupContactEmailId = $request->pickup_contact_email_id;
        $pickupDateType = $request->pickup_date_type;
        $carrierPickupDate = $request->carrier_pickup_date; // Delivery note
        $carrierPickupNotes = $request->carrier_pickup_notes;


        $deliveryVenueBusinessName = $request->delivery_venue_business_name;
        $deliveryVenueBusinessType = $request->delivery_venue_business_type;
        $deliveryBusinessAddress = $request->delivery_business_address;
        $deliveryBusinessState = $request->delivery_business_state;
        $deliveryBusinessCity = $request->delivery_business_city;
        $deliveryBusinessZipCode = $request->delivery_business_zip_code;
        $deliveryContactName = $request->delivery_contact_name;
        $deliveryContactTitle = $request->delivery_contact_title;
        $deliveryContactPhoneNumber = $request->delivery_contact_phone_number;
        $deliveryContactMobileNumber = $request->delivery_contact_mobile_number;
        $deliveryContactEmailId = $request->delivery_contact_email_id;
        $deliveryNotes = $request->carrier_delivery_notes;

        $totalTarriff = $request->total_tarriff;
        $paymentNotes = $request->payment_notes;



        $trasnsportType = $request->trasnsport_type;


        $postUrl = 'v1/public/orders';
        $postFiled = (object)[];
        /* Order Number */
        $postFiled->number = $orderNumber;
        $postFiled->purchase_order_number = $purchaseOrderNumber;
        $postFiled->dispatcher_name = $request->dispatcher;
        $postFiled->sales_representative = $request->d_business_name;

        /* Cusotmer Payment and notes */
        $customerPayment = (object)[];
        $customerPayment->tariff = ($totalTarriff) ?  $totalTarriff : '';
        //$customerPayment->amount =  ($totalTarriff) ?  $totalTarriff : '';
        $customerPayment->notes = ($paymentNotes) ?  $paymentNotes : '';
        $postFiled->customer_payment = $customerPayment;


        /* Pickup */
        $pickupVenue = (object)[];
        $pickupVenue->address = ($pickupBusinessAddress) ? $pickupBusinessAddress : '';
        $pickupVenue->city = ($pickupBusinessCity) ?  $pickupBusinessCity :  '';
        $pickupVenue->state = ($pickupBusinessState) ?  $pickupBusinessState : '';
        $pickupVenue->zip = ($pickupBusinessZipCode) ?  $pickupBusinessZipCode :  '';
        $pickupVenue->name = ($pickupBusinessName) ?  $pickupBusinessName  : '';
        $pickupVenue->business_type = ($pickupVenueBusinessType) ? $pickupVenueBusinessType : '';
        $pickupVenue->contact_name = ($pickupContactName) ?  $pickupContactName :  '';
        $pickupVenue->contact_title = ($pickupContactTitle) ?  $pickupContactTitle : '';
        $pickupVenue->contact_email = ($pickupContactEmailId) ?  $pickupContactEmailId : '';
        $pickupVenue->contact_phone = ($pickupContactPhoneNumber) ? $pickupContactPhoneNumber : '';
        $pickupVenue->contact_mobile_phone = ($pickupContactMobileNumber) ? $pickupContactMobileNumber : '';

        $pickup = (object)[];
        if(isset($request->carrier_pickup_date)){
            $carrierPickupDate = date("Y-m-d", strtotime($carrierPickupDate));
            $pickup->scheduled_at = ($carrierPickupDate) ?  $carrierPickupDate . 'T10:30:00.112+0000' :  '';
        }
        //$carrierPickupDate = date("Y-m-d", strtotime($carrierPickupDate));
        //$pickup->scheduled_at = ($request->carrier_pickup_date) ?  (($carrierPickupDate) ?  $carrierPickupDate.'T10:30:00.112+0000' :  '') : '';
        //$pickup->scheduled_ends_at = '';
        $pickup->date_type = 'exact';
        $pickup->notes = ($carrierPickupNotes) ? $carrierPickupNotes : '';
        $pickup->venue = $pickupVenue;
        $postFiled->pickup = $pickup;

        /* Pickup */
        $delivery = (object)[];
        $deliveryVenue = (object)[];
        $deliveryVenue->address = ($deliveryBusinessAddress) ? $deliveryBusinessAddress : '';
        $deliveryVenue->city = ($deliveryBusinessCity) ? $deliveryBusinessCity : '';
        $deliveryVenue->state = ($deliveryBusinessState) ?  $deliveryBusinessState : '';
        $deliveryVenue->zip = ($deliveryBusinessZipCode) ?  $deliveryBusinessZipCode : '';
        $deliveryVenue->name = ($deliveryVenueBusinessName) ?  $deliveryVenueBusinessName  : '';
        $deliveryVenue->business_type = ($deliveryVenueBusinessType) ? $deliveryVenueBusinessType : '';
        $deliveryVenue->contact_name = ($deliveryContactName) ?  $deliveryContactName : '';
        $deliveryVenue->contact_title = ($deliveryContactTitle) ? $deliveryContactTitle : '';
        $deliveryVenue->contact_email = ($deliveryContactEmailId) ? $deliveryContactEmailId : '';
        $deliveryVenue->contact_phone = ($deliveryContactPhoneNumber) ? $deliveryContactPhoneNumber : '';
        $deliveryVenue->contact_mobile_phone = ($deliveryContactMobileNumber) ?  $deliveryContactMobileNumber : '';
        $delivery->notes = $deliveryNotes; // deleivery note data bind pending.
        $delivery->venue = $deliveryVenue;
        $postFiled->delivery = $delivery;

        /* Customer information */
        $customerInfo = (object)[];
        $customerInfo->address = ($customerAddress) ?  $customerAddress : '';
        $customerInfo->city = ($customerCity) ? $customerCity : '';
        $customerInfo->state = ($customerState) ?  $customerState : '';
        $customerInfo->zip = ($customerZipcode) ?  $customerZipcode : '';
        $customerInfo->name = ($customerName) ?  $customerName : '';
        $customerInfo->business_type = ($customerVenueBusinessType) ?  $customerVenueBusinessType : '';
        $customerInfo->contact_email = ($customerBillingEmail) ? $customerBillingEmail : ''; // replicated 
        $customerInfo->email = ($customerBillingEmail) ? $customerBillingEmail : '';
        $customerInfo->contact_phone = ($customerPhoneNumber) ?  $customerPhoneNumber : '';
        $customerInfo->contact_name = $customerName;

        $postFiled->customer = $customerInfo;
        $postFiled->transport_type = ($trasnsportType) ?  $trasnsportType : '';


        /* vehicle information */
        $vehicles = [];
        for ($i = 1; $i <= $vehicleLength; $i++) {
            $vehicleSignleArray = array(
                'vin' => $request['vin' . $i],
                'year' => $request['year' . $i],
                'make' => $request['make' . $i],
                'model' => $request['model' . $i],
                'tariff' => $request['tariff' . $i],
                'type' => $request['type' . $i],
                'is_inoperable' => (($request['indp' . $i]) ?  true :  false)
            );
            array_push($vehicles, $vehicleSignleArray);
        }

        $postFiled->vehicles = $vehicles;
        /* Create order API Call */
        $createOrder = $this->suberDispacheObj->postApiCallJson($postUrl, $postFiled);
        if (isset($createOrder['status']) && $createOrder['status'] == 'success') {
            Orders::create([
                'payload' => json_encode($postFiled),
                'guid' => 'value2',
                // Add other columns as needed
            ]);
		
			$redirectUrl = $request->input('redirectUrl');

			// Extract the path from the URL
			$parsedUrl = parse_url($redirectUrl, PHP_URL_PATH);

			// Check if the path matches /order or /order/v2
			if ($parsedUrl === '/order' || $parsedUrl === '/order/v2') {
				$pathToUse = $parsedUrl;
			} else {
				// Default value if the path doesn't match (optional)
				$pathToUse = '/order';
			}

			return response()->json([
				'status' => true, 
				'guid' => $createOrder['data'], 
				'redirectUrl' => $redirectUrl
			]);
            //return response()->json(array('status' => true, 'guid' => $createOrder['data']));
        } else {
            dd($createOrder);
            return response()->json(array('status' => false, 'error' => 'Something went wrong.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function show(orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function edit(orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(orders $orders)
    {
        //
    }

    public function getVinDetails(Request $request){
        $str =  trim(str_replace('"', '', $request->vin_number));
        if ($str) {
           /* $postUrl = 'internal/vins';
            $postFiled = [];
            $postFiled['vin'] = $str;
            $terminalDetails = $this->suberDispacheObj->getApiCall($postUrl, $postFiled, true);
            $vin = $terminalDetails['data']['object'];
            $request->vin_number;
            $data = [];*/
            $vehicles = new VehicleApi();
            $vin = $vehicles->decodeVin($str);
            
            $data = [];
            $data['model_year'] = $vin[10]['Value'];
            $data['manufacture'] = $vin[7]['Value'];
            $data['model_type'] = $vin[9]['Value'];
            $data['v_model'] = $vin[14]['Value'];
            
            /*$data['model_year'] = $vin['year'];
            $data['manufacture'] = $vin['make'];
            $data['model_type'] = $vin['model'];
            $data['v_model'] = $vin['type'];*/
            
            return response()->json($data);
        }
    }
    
    public function getTerminalListData(Request $request){
        $postUrl = 'v1/public/terminals';
        $postFiled = (object)[];
        $postFiled->size = 500;
        $postFiled->page = 0;
        $terminalData = $this->suberDispacheObj->getApiCall($postUrl, $postFiled);
        $terminalData = $terminalData['data']['objects'];
        $searchTerm = $request->search_text;
        if($searchTerm){
            $searchResults = array_filter($terminalData, function ($item) use ($searchTerm) {
                return isset($item['name']) && strpos(strtolower($item['name']), strtolower($searchTerm)) === 0;
            });
        }else{
            $searchResults = $terminalData;
        }
        
        $list = [];
        foreach($searchResults as $searchResult){
            
            array_push($list, array($searchResult['name'],$searchResult['guid']));
        }
        return response()->json($list);
    }

    public function getTerminalDetails(Request $request){
        if($request->guid){
            $postUrl = 'v1/public/terminals/'.$request->guid;
            $postFiled = [];
            $terminalDetails = $this->suberDispacheObj->getApiCall($postUrl, $postFiled);
            return response()->json($terminalDetails['data']['object']);
        }else{

        }
        
    }
    
    public function UpdateDisptachers()
    {
        $postUrl = 'internal/console/users';
        $postFiled = [];
        $postFiled['size'] = '200';
        $terminalDetails = $this->suberDispacheObj->getApiCall($postUrl, $postFiled, true);
        $dispatchers =  ($terminalDetails['data']) ? $terminalDetails['data']['objects'] : null;
        if ($dispatchers) {

            $salesDispArray = [];
            foreach ($dispatchers as $disAndSalePerson) {
                array_push(
                    $salesDispArray,
                    array(
                        "full_name" => $disAndSalePerson['first_name'] . ' ' . $disAndSalePerson['last_name'],
                        "email" => $disAndSalePerson['email'],
                        "role" => $disAndSalePerson['role']
                    )
                );
            }
            if (!empty($salesDispArray)) {
                DB::statement("TRUNCATE dispatcher");
                Dispatcher::insert($salesDispArray);
            }
           $str=
            ' Sales &  dispacher Data updated succussfully.';
            return ($str);
        }
    }
    
     public function webhookOrderUpdate(Request $request){
        $orderIdFromSuperDispatch =  $request->data['number']['new_value'];
        $orderIdFromSuperDispatchUpdated = str_replace("DL", " ", $orderIdFromSuperDispatch);
        //Log::channel('webhookLog')->info($orderIdFromSuperDispatchUpdated);
        $orderCount = CodeMaintain::select('superdispatch_last_order_id')->first();
        if($orderCount->superdispatch_last_order_id < trim($orderIdFromSuperDispatchUpdated))
            CodeMaintain::where('id','1')->update(["superdispatch_last_order_id"=>trim($orderIdFromSuperDispatchUpdated)]);
            
        return true;
    }
}
