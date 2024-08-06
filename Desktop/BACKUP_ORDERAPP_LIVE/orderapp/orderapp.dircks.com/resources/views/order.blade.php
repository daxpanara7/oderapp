@extends('layouts.app')
@section('content')
<style>
    .ml-force {
        margin-left: -1% !important;
    }

    .mt-force {
        margin-top: -1% !important;
    }

    .mr-force {
        margin-right: -27px;
    }

    .file-upload {
        display: none;
    }

    .upload-icon {
        font-size: 24px;
    }

    label.force-align-right {
        right: 0.8rem;
        display: inline;
        position: absolute;
        top: 0.7rem;
        font-size: 14px;
    }

    #vehicle_block .form-group {
        padding: 5px !important;
    }

    /* AutoComplate */
    #suggesstion-box {
        display: none;
        background: #fff;
        border-bottom: 1px solid #ccc;
        padding: 10px;
        box-shadow: 10px 5px 5px #ccc;
        border-radius: 0px 0px 5px 5px;
        max-height: 200px;
        position: absolute;
        z-index: 999;
    }

    #suggesstion-box li {
        list-style: none;
        cursor: pointer;
        padding: 5px 0;
        border-bottom: 1px solid #ccc;
    }

    #suggesstion-box2 {
        display: none;
        background: #fff;
        border-bottom: 1px solid #ccc;
        padding: 10px;
        box-shadow: 10px 5px 5px #ccc;
        border-radius: 0px 0px 5px 5px;
        max-height: 200px;
        position: absolute;
        z-index: 999;
    }

    #suggesstion-box2 li {
        list-style: none;
        cursor: pointer;
        padding: 5px 0;
        border-bottom: 1px solid #ccc;
    }

    #suggesstion-box3 {
        display: none;
        background: #fff;
        border-bottom: 1px solid #ccc;
        padding: 10px;
        box-shadow: 10px 5px 5px #ccc;
        border-radius: 0px 0px 5px 5px;
        max-height: 200px;
        position: absolute;
        z-index: 999;
    }

    #suggesstion-box3 li {
        list-style: none;
        cursor: pointer;
        padding: 5px 0;
        border-bottom: 1px solid #ccc;
    }
    .form-control{
        font-size:12px;
    }
	.customer-payment-section{
		display: none;
	}
    .required-asterisk{
    color: red;
    }
    label.error {
    color: red;
    font-size: 0.875em; 
}

</style>
<div class="row">
    <div class="col-md-12  offset-md-12">

        <form method="POST" name="order_create" id="order_create">
            @csrf
            <input type="hidden" class="redirectUrl" id="redirectUrl" name="redirectUrl" value="{{ url()->current() }}">
            <input type="hidden" name="vehicle_length" id="vehicle_length">
            <!-- <input type="hidden" class="form-control" id="order_number" name="order_number" placeholder="Order Number" value=""> -->
            @if(isset($v2) && $v2 == 'true')
            <input type="hidden" class="form-control" id="po_order_number" name="po_order_number" placeholder="PO Order Number" value="COD" required>
            @endif
            <!-- Order Details -->
            @if(isset($v2) && $v2 == 'true')
            <label class="mt-3 mb-0 ml-force font-weight-bold">COD Details</label>
            @else
            <label class="mt-3 mb-0 ml-force font-weight-bold">Order Details</label>
            @endif
            <div class="row bg-light border rounded">
                <!-- <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="order_number" class="mb-0 mt-2">Order Number*</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" placeholder="Order Number" value="">
                </div> -->
                <div class="form-group col-md-6 col-lg-6 col-sm-12 {{(isset($v2) ? (($v2==true) ? 'd-none' : '' ) : '') }}">
                    <label for="order_number" class="mb-0 mt-2">PO Order Number<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="po_order_number" name="po_order_number" placeholder="PO Order Number" value="{{(isset($v2) ? (($v2==true) ? 'COD' : '' ) : '') }}" required>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="trasnsport_type" class="mb-0 mt-2">Transport Type</label>
                    <select class="form-control" id="trasnsport_type" name="trasnsport_type">
                        <option value="OPEN">Open</option>
                        <option value="ENCLOSED">Enclosed</option>
                        <!-- <option value="DRIVEAWAY">Driveaway</option> -->
                    </select>
                </div>
            </div>

            <!-- Vehicles Details -->
            <label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Vehicles</label>
            <div class="row bg-light border rounded">
                <div id="vehicle_block" class="col-md-12  offset-md-12 row">

                </div>
                <div class="form-group col-md-12 col-lg-12 col-sm-12 text-right mt-0">
                    <a class="form-group btn-success p-2 rounded" role="button" onclick="addVehicle()">Add Vehicle</a>
                </div>
            </div>

            <!-- Order Details -->
            <!-- @if(isset($v2) && $v2 == 'true')
            <label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Customer Information / Billing Information</label>
            @endif
            <label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Billing Information</label> -->
            @if(Request::is('order/v2'))
                <label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Customer Information / Billing Information</label>
            @else
                <label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Billing Information</label>
            @endif
            <div class="row bg-light border rounded">
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="customer_name" class="mb-0 mt-2">Customer Name<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>  
                    <div id="suggesstion-box" class="col-sm-12"></div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="customer_venue_business_type" class="mb-0 mt-2">Type</label>
                    <select type="text" class="form-control" id="customer_venue_business_type" name="customer_venue_business_type" required>
                        <option value="BUSINESS">Business</option>
                        <option value="DEALER">Dealer</option>
                        <option value="PRIVATE" selected>Private</option>
                        <option value="AUCTION">Auction</option>
                        <option value="REPO_YARD">Repo Yard</option>
                        <option value="PORT">Port</option>
                    </select>
                </div>
                <div class="form-group col-md-12 col-lg-12 col-sm-12">

                    @if(Request::is('order/v2'))
                            <label for="customer_address" class="mb-0 mt-2">Address<span class="required-asterisk">*</span></label>
                             <textarea class="form-control" id="customer_address" name="customer_address" required></textarea>
                    @else
                            <label for="customer_address" class="mb-0 mt-2">Address</label>
                            <textarea class="form-control" id="customer_address" name="customer_address"></textarea>
                    @endif
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    @if(Request::is('order/v2'))
                            <label for="customer_state" class="mb-0 mt-2">State<span class="required-asterisk">*</span></label>
                            <input type="text" class="form-control" id="customer_state" name="customer_state" required>
                    @else
                    <label for="customer_state" class="mb-0 mt-2">State</label>
                    <input type="text" class="form-control" id="customer_state" name="customer_state">
                    @endif
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    @if(Request::is('order/v2'))
                    <label for="customer_city" class="mb-0 mt-2">City<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="customer_city" name="customer_city" required>
                    @else
                    <label for="customer_city" class="mb-0 mt-2">City</label>
                    <input type="text" class="form-control" id="customer_city" name="customer_city">
                    @endif
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    @if(Request::is('order/v2'))
                    <label for="customer_zip_code" class="mb-0 mt-2">Zip Code<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="customer_zip_code" name="customer_zip_code" required>
                    @else
                    <label for="customer_zip_code" class="mb-0 mt-2">Zip Code</label>
                    <input type="text" class="form-control" id="customer_zip_code" name="customer_zip_code">
                    @endif  
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                  @if(Request::is('order/v2'))
                    <label for="customer_phone_number" class="mb-0 mt-2">Phone Number<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="customer_phone_number" name="customer_phone_number" onkeyup="validateNumericInput('customer_phone_number')" required>
                    @else
                    <label for="customer_phone_number" class="mb-0 mt-2">Phone Number</label>
                    <input type="text" class="form-control" id="customer_phone_number" name="customer_phone_number" onkeyup="validateNumericInput('customer_phone_number')">
                    @endif  
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                 @if(Request::is('order/v2'))
                    <label for="customer_billing_email" class="mb-0 mt-2">Email<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="customer_billing_email" name="customer_billing_email" required>
                    @else
                    <label for="customer_billing_email" class="mb-0 mt-2">Email</label>
                    <input type="text" class="form-control" id="customer_billing_email" name="customer_billing_email">
                    @endif  
                </div>
            </div>

            <!-- Pick-up Information -->
            <label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Pick-up Information</label>
            <div class="row bg-light border rounded">
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="pickup_business_name" class="mb-0 mt-2">Customer Name</label>
                    <label class="force-align-right copy-for-pickup"><i class="fa fa-copy"></i> Same as customer</label>
                    <input type="text" class="form-control" id="pickup_business_name" name="pickup_business_name">
                    <div id="suggesstion-box2" class="col-sm-12"></div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="pickup_venue_business_type" class="mb-0 mt-2">Type</label>
                    <select type="text" class="form-control" id="pickup_venue_business_type" name="pickup_venue_business_type">
                        <option value="BUSINESS">Business</option>
                        <option value="DEALER">Dealer</option>
                        <option value="PRIVATE" selected>Private</option>
                        <option value="AUCTION">Auction</option>
                        <option value="REPO_YARD">Repo Yard</option>
                        <option value="PORT">Port</option>
                    </select>
                </div>
                <div class="form-group col-md-12 col-lg-12 col-sm-12">
                    <label for="pickup_business_address" class="mb-0 mt-2">Address</label>
                    <textarea class="form-control" id="pickup_business_address" name="pickup_business_address"></textarea>
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="pickup_business_state" class="mb-0 mt-2">State</label>
                    <input type="text" class="form-control" id="pickup_business_state" name="pickup_business_state">
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="pickup_business_zip_code" class="mb-0 mt-2">City</label>
                    <input type="text" class="form-control" id="pickup_business_city" name="pickup_business_city">
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="business_zip_code" class="mb-0 mt-2">Zip Code</label>
                    <input type="text" class="form-control" id="pickup_business_zip_code" name="pickup_business_zip_code">
                </div>

                <!-- -- Contact Information -->
                <div class="form-group col-md-12 col-lg-12 col-sa-12 mb-0">
                    <hr class="hr mb-0 mt-0">
                    <label class="mb-0 mt-0 mt-force font-weight-bold">Contact Information</label>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="contact_name" class="mb-0 mt-2">Contact Name<span class="required-asterisk">*</span></label>
                    <label class="force-align-right copy-for-pickup-contact"><i class="fa fa-copy"></i> Same as customer</label>
                    <input type="text" class="form-control" id="pickup_contact_name" name="pickup_contact_name" required>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <!-- <label for="contact_title" class="mb-0 mt-2">Title</label>
                    <input type="text" class="form-control" id="pickup_contact_title" name="pickup_contact_title"> -->
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="contact_phone_number" class="mb-0 mt-2">Phone Number<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="pickup_contact_phone_number" name="pickup_contact_phone_number" onkeyup="validateNumericInput('pickup_contact_phone_number')" required>
                </div>


           
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="contact_email_id" class="mb-0 mt-2">Email<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="pickup_contact_email_id" name="pickup_contact_email_id" required>
                </div>
                <!-- -- Date & Notes -->
                <div class="form-group col-md-12 col-lg-12 col-sa-12 mb-0">
                    <hr class="hr mb-0 mt-0">
                    <label class="mb-0 mt-0 mt-force font-weight-bold">Date & Notes</label>
                </div>
                <!-- <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="date_type" class="mb-0 mt-2">Date type</label>
                    <select class="form-control" id="pickup_date_type" name="pickup_date_type">
                        <option value="estimated">Estimated</option>
                        <option value="exact">Exact</option>
                        <option value="not_earlier_than">No Earlier than</option>
                        <option value="not_later_than">No Later than</option>
                    </select>
                </div> -->
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="carrier_pickup_date" class="mb-0 mt-2">Pick up Date<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="carrier_pickup_date" name="carrier_pickup_date" required>
                </div>
                <div class="form-group col-md-12 col-lg-12 col-sm-12">
                    <label for="carrier_pickup_notes" class="mb-0 mt-2">Notes <i class="fa fa-info-circle"></i></label>
                    <textarea class="form-control" id="carrier_pickup_notes" name="carrier_pickup_notes"></textarea>
                </div>
            </div>

            <!-- Delivery Information -->
            <label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Delivery Information</label>
            <div class="row bg-light border rounded">
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="delivery_venue_business_name" class="mb-0 mt-2">Customer Name</label>
                    <label class="force-align-right copy-for-delivery"><i class="fa fa-copy"></i> Same as customer</label>
                    <input type="text" class="form-control" id="delivery_venue_business_name" name="delivery_venue_business_name">
                    <div id="suggesstion-box3" class="col-sm-12"></div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="delivery_venue_business_type" class="mb-0 mt-2">Type</label>
                    <select type="text" class="form-control" id="delivery_venue_business_type" name="delivery_venue_business_type">
                        <option value="BUSINESS">Business</option>
                        <option value="DEALER">Dealer/option>
                        <option value="PRIVATE" selected>Private</option>
                        <option value="AUCTION">Auction</option>
                        <option value="REPO_YARD">Repo Yard</option>
                        <option value="PORT">Port</option>
                    </select>
                </div>
                <div class="form-group col-md-12 col-lg-12 col-sm-12">
                    <label for="delivery_business_address" class="mb-0 mt-2">Address</label>
                    <textarea class="form-control" id="delivery_business_address" name="delivery_business_address"></textarea>
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="delivery_business_state" class="mb-0 mt-2">State<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="delivery_business_state" name="delivery_business_state" required>
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="delivery_business_city" class="mb-0 mt-2">City<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="delivery_business_city" name="delivery_business_city" required>
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="delivery_business_zip_code" class="mb-0 mt-2">Zip Code<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="delivery_business_zip_code" name="delivery_business_zip_code" required> 
                </div>
                <!-- -- Contact Information -->
                <div class="form-group col-md-12 col-lg-12 col-sa-12 mb-0">
                    <hr class="hr mb-0 mt-0">
                    <label class="mb-0 mt-0 mt-force font-weight-bold">Contact Information</label>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="delivery_contact_name" class="mb-0 mt-2">Contact Name<span class="required-asterisk">*</span></label>
                    <label class="force-align-right copy-for-delivery-contact"><i class="fa fa-copy"></i> Same as customer</label>
                    <input type="text" class="form-control" id="delivery_contact_name" name="delivery_contact_name" required>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <!-- <label for="contact_title" class="mb-0 mt-2">Title</label>
                    <input type="text" class="form-control" id="delivery_contact_title" name="delivery_contact_title"> -->
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="delivery_contact_phone_number" class="mb-0 mt-2">Phone Number<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="delivery_contact_phone_number" name="delivery_contact_phone_number" onkeyup="validateNumericInput('delivery_contact_phone_number')" required>
                </div>
<!--
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    //<label for="delivery_contact_mobile_number" class="mb-0 mt-2">Mobile Number</label>
                    //<input type="text" class="form-control" id="delivery_contact_mobile_number" name="delivery_contact_mobile_number" onkeyup="validateNumericInput('delivery_contact_mobile_number')">
                </div>
-->
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="delivery_contact_email_id" class="mb-0 mt-2">Email<span class="required-asterisk">*</span></label>
                    <input type="text" class="form-control" id="delivery_contact_email_id" name="delivery_contact_email_id" required>
                </div>
                <!-- Date & Notes -->
                <div class="form-group col-md-12 col-lg-12 col-sa-12 mb-0">
                    <hr class="hr mb-0 mt-0">
                    <label class="mb-0 mt-0 mt-force font-weight-bold">Date & Notes</label>
                </div>
                <div class="form-group col-md-12 col-lg-12 col-sm-12">
                    <label for="carrier_delivery_notes" class="mb-0 mt-2">Notes <i class="fa fa-info-circle"></i></label>
                    <textarea class="form-control" id="carrier_delivery_notes" name="carrier_delivery_notes"></textarea>
                </div>
            </div>

            <!-- Customer Payment-->
			<div class="customer-payment-section"> 
				<label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Customer Payment</label>
				<div class="row bg-light border rounded">
					<div class="form-group col-md-4 col-lg-4 col-sm-12">
						<label for="total_tarriff" class="mb-0 mt-2">Total Customer Price</label>
						<div class="input-group">
							<span class="input-group-addon border-top border-bottom border-left pl-2 pr-2 pt-1 rounded-left">$</span>
							<input type="text" class="form-control" id="total_tarriff" name="total_tarriff" onkeyup="validateNumericInput('total_tarriff')" readonly>
						</div>
					</div>
					<div class="form-group col-md-12 col-lg-12 col-sm-12">
						<label for="payment_notes" class="mb-0 mt-2">Notes</label>
						<textarea class="form-control" id="payment_notes" name="payment_notes"></textarea>
					</div>
				</div> 	
			</div>
		


            <!-- DisPatcher & Salseperson -->
            <label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Dispatcher & Salseperson</label>
            <div class="row bg-light border rounded">
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="dispatcher" class="mb-0 mt-2">Dispatcher<span class="required-asterisk">*</span></label>
                    <select class="form-control" id="dispatcher" name="dispatcher" required>
                    <option value="">None</option>
                    @if($dispatchers)
                    @foreach($dispatchers as $key=>$value)
                    <option value="{{ $value['first_name'] }} {{ $value['last_name'] }}">{{ $value['first_name'] }} {{ $value['last_name'] }}</option>
                    @endforeach
                    @endif
                </select>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="d_business_name" class="mb-0 mt-2">Sales Person<span class="required-asterisk">*</span></label>
                    <select class="form-control" id="d_business_name" name="d_business_name" required>
                    <option value="">None</option>
                    @if($sales)
                        @foreach($sales as $key => $value)
                            <option value="{{ $value['first_name'] }} {{ $value['last_name'] }}">{{ $value['first_name'] }} {{ $value['last_name'] }}</option>
                        @endforeach
                    @endif
                    </select>
                </div>
            </div>

            <div class="form-group mt-5 col-md-12 col-lg-12 col-sm-12 text-right mt-0">
                <button type="submit" class="form-group btn-success p-2 rounded mr-force">Submit</button>
            </div>

        </form>

    </div>
</div>
@endsection
@section('jsScript')
<script>
    var i = 0;
    jQuery(document).ready(function() {
        /* On page load add  first vehicle row*/
        addVehicle();
        $('.delete_row').addClass('d-none');
        /* delete vehicle row */
        jQuery(document).on('click', '.delete_row', function() {
            var rowId = $(this).data('row');
            $('.delete_row').addClass('d-none');
            var vehicleLength = jQuery('.vehicle-row-exist').length;
            if (vehicleLength == 1) {

                toastr.error('Vehicle can\'t be deleted.');
                return false;
            }
            jQuery('.vehicleRow' + rowId).remove();
            if (vehicleLength != 1)
                $('.delete_' + (rowId - 1)).removeClass('d-none');
            $('#vehicle_length').val(jQuery('.vehicle-row-exist').length)
        });

        /* Form Submit with validation check */
        $("#order_create").submit(function(e) {
                e.preventDefault();
            }).validate({
                rules: {
                    customer_phone_number: {
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    pickup_contact_phone_number: {
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    pickup_contact_mobile_number: {
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    delivery_contact_phone_number: {
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    delivery_contact_mobile_number: {
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    delivery_contact_email_id: {
                        email: true
                    },
                    pickup_contact_email_id: {
                        email: true
                    },
                    customer_billing_email: {
                        email: true
                    }
                },
                submitHandler: function(form) {
                    $('.loading').show();
                    var formData = new FormData(form); // Create FormData object from form

                    // If you need to add additional data, you can do it here
                    formData.append('referer', document.referrer);

                    $.ajax({
                        url: '{{route("orderCreate")}}',
                        method: 'POST',
                        data: formData,
                        processData: false, // Prevent jQuery from automatically transforming the data into a query string
                        contentType: false, // Set contentType to false as jQuery will tell the server its a query string request
                        success: function(response) {
                            $('.loading').hide();
                            if (response && response.status) {
                                window.location.href = '/thankyou?redirectUrl=' + encodeURIComponent(response.redirectUrl) + '&jsonResponse=' + encodeURIComponent(JSON.stringify(response));
                            } else {
                                console.error("Property 'mode' is undefined in the response.");
                            }
                        },
                        error: function(xhr, status, error) {
                            $('.loading').hide();
                            console.error("AJAX request failed:", status, error);
                        }
                    });
                },
            });


        //Pickup  carrier date initilized
        dateChange('carrier_pickup_date', 'single')

        /* Date type change */
        $('#pickup_date_type').change(function() {
            var value = $(this).val();
            if (value == 'estimated') {
                dateChange('carrier_pickup_date', 'range')
            } else {
                dateChange('carrier_pickup_date', 'single')
            }
        })

        /* Same as customer copy*/
        $('.copy-for-pickup, .copy-for-delivery, .copy-for-pickup-contact, .copy-for-delivery-contact').click(function() {
            var customerName = $('#customer_name').val();
            var customerBusinessType = $('#customer_venue_business_type').val();
            var customerAddress = $('#customer_address').val();
            var customerState = $('#customer_state').val();
            var customerCity = $('#customer_city').val();
            var customerZipCode = $('#customer_zip_code').val();
            if ($(this).attr("class") == 'force-align-right copy-for-pickup') {
                $('#pickup_business_name').val(customerName);
                $('#pickup_venue_business_type').val(customerBusinessType);
                $('#pickup_business_address').val(customerAddress);
                $('#pickup_business_state').val(customerState);
                $('#pickup_business_city').val(customerCity);
                $('#pickup_business_zip_code').val(customerZipCode);
            } else if ($(this).attr("class") == 'force-align-right copy-for-delivery') {
                $('#delivery_venue_business_name').val(customerName);
                $('#delivery_venue_business_type').val(customerBusinessType);
                /*$('#delivery_business_address').val(customerAddress);
                $('#delivery_business_state').val(customerState);
                $('#delivery_business_city').val(customerCity);
                $('#delivery_business_zip_code').val(customerZipCode);*/
            } else if ($(this).attr("class") == 'force-align-right copy-for-pickup-contact') {
                var customerPhoneNumber = $('#customer_phone_number').val();
                var customerBillingEmail = $('#customer_billing_email').val();
                $('#pickup_contact_name').val(customerName);
                $('#pickup_contact_phone_number').val(customerPhoneNumber);
                $('#pickup_contact_mobile_number').val(customerPhoneNumber);
                $('#pickup_contact_email_id').val(customerBillingEmail);
            } else if ($(this).attr("class") == 'force-align-right copy-for-delivery-contact') {
                var customerPhoneNumber = $('#customer_phone_number').val();
                var customerBillingEmail = $('#customer_billing_email').val();
                $('#delivery_contact_name').val(customerName);
                $('#delivery_contact_phone_number').val(customerPhoneNumber);
                $('#delivery_contact_mobile_number').val(customerPhoneNumber);
                $('#delivery_contact_email_id').val(customerBillingEmail);
            }
        });
    })

    /* Add vehicle row on add vehicle button click */
    function addVehicle() {
        var vehicleLength = jQuery('.vehicle-row-exist').length;
        if (vehicleLength > 4) {
            toastr.error('You can not add more than 5 vehicle');
            //alert("You can not add more than 5 vehicle");
            return false;
        }
        i = vehicleLength + 1;
        $('.delete_row').addClass('d-none');
        var html = '';
        html += '<div class="col-md-12  offset-md-12 row vehicleRow' + i + ' vehicle-row-exist">';
        // html += '    <div class="form-group col-md-1 col-lg-1 col-sm-12">';
        // html += '        <label for="image" class="mb-0 mt-2">Image</label>';
        // html += '        <label for="image' + i + '" class="mb-0 mt-2 upload-icon"><i class="fa fa-upload ml-2"></i></label>';
        // html += '        <input type="file" class="form-control file-upload" id="image' + i + '" name="image' + i + '" />';
        // html += '    </div>';
        html += '    <div class="form-group col-md-2 col-lg-2 col-sm-12">';
        html += '        <label for="vin' + i + '" class="mb-0 mt-2">VIN<span class="required-asterisk">*</span></label>';
        html += '        <input class="form-control vinkeypress" id="vin' + i + '" name="vin' + i + '" data-id="' + i + '" maxlength="17" minlength="17" required/ >';
        html += '    </div>';
        html += '    <div class="form-group col-md-1 col-lg-1 col-sm-12">';
        html += '        <label for="year' + i + '" class="mb-0 mt-2">Year<span class="required-asterisk">*</span></label>';
        html += '        <input class="form-control" id="year' + i + '" name="year' + i + '" required/>';
        html += '    </div>';
        html += '    <div class="form-group col-md-2 col-lg-2 col-sm-12">';
        html += '        <label for="make' + i + '" class="mb-0 mt-2">Make<span class="required-asterisk">*</span></label>';
        html += '        <input class="form-control" id="make' + i + '" name="make' + i + '" required/>';
        html += '    </div>';
        html += '    <div class="form-group col-md-2 col-lg-2 col-sm-12">';
        html += '        <label for="model' + i + '" class="mb-0 mt-2">Model<span class="required-asterisk">*</span></label>';
        html += '        <input class="form-control" id="model' + i + '" name="model' + i + '" required/>';
        html += '    </div>';
        html += '    <div class="form-group col-md-2 col-lg-2 col-sm-12">';
        html += '        <label for="type' + i + '" class="mb-0 mt-2">Type<span class="required-asterisk">*</span></label>';
        html += '        <select class="form-control" id="type' + i + '" name="type' + i + '" required>';
   		html += '           <option value="">Select Type</option>'; // Add an empty option for validation
        html += '           <option value="sedan">Sedan</option>';
        html += '           <option value="2_door_coupe">Coupe (2 Door)</option>';
        html += '           <option value="suv">SUV</option>';
        html += '           <option value="pickup">Pickup (2 Door)</option>';
        html += '           <option value="4_door_pickup">Pickup (4 Door)</option>';
        html += '           <option value="van">Van</option>';
        html += '           <option value="truck_daycab">Truck (daycab)</option>';
        html += '           <option value="truck_sleeper">Truck (with sleeper)</option>';
        html += '           <option value="motorcycle">Motorcycle</option>';
        html += '           <option value="boat">Boat</option>';
        html += '           <option value="rv">RV</option>';
        html += '           <option value="heavy_machinery">Heavy Machinery</option>';
        html += '           <option value="freight">Freight</option>';
        html += '           <option value="livestock">Livestock</option>';
        html += '           <option value="atv">ATV</option>';
        html += '           <option value="trailer_bumper_pull">Trailer (Bumper Pull)</option>';
        html += '           <option value="trailer_gooseneck">Trailer (Gooseneck)</option>';
        html += '           <option value="trailer_5th_wheel">Trailer (5th Wheel)</option>';
        html += '           <option value="other">Other</option>';
        html += '        </select>';
        html += '    </div>';
        html += '    <div class="form-group col-md-1 col-lg-1 col-sm-12">';
        html += '        <label for="tariff' + i + '" class="mb-0 mt-2">Price<span class="required-asterisk">*</span></label>';
        html += '        <input class="form-control tariffCal" id="tariff' + i + '" name="tariff' + i + '" required/>';
        html += '    </div>';
        html += '    <div class="form-group col-md-1 col-lg-1 col-sm-12">';
        html += '        <label for="indp' + i + '" class="mb-0 mt-2 ml-3">INOP</label>';
        html += '        <input type="checkbox" class="form-control" id="indp' + i + '" name="indp' + i + '" />';
        html += '    </div>';
        html += '    <div class="form-group col-md-1 col-lg-1 col-sm-12">';
        html += '        <label for="exampleFormControlTextarea1" class="mb-0 mt-2"></label>';
        html += '        <div class="form-group mt-3">';
        html += '            <a class="delete_' + i + ' delete_row" data-row="' + i + '" class="mb-0 mt-2"><i class="fa fa-trash"></i></a>';
        html += '        </div>';
        html += '    </div>';
        html += '</div>';
        jQuery('#vehicle_block').append(html);
        $('#vehicle_length').val(jQuery('.vehicle-row-exist').length)
    }

    /* Date Picker Script */
    function dateChange(id, type) {
        $('#' + id).val('');
        $('#' + id).datepicker('destroy'); // Destroy the current instance
        if (type == 'range') {
            var datePickerRangeOptions = {
                format: 'yyyy-mm-dd',
                autoclose: false,
                clearBtn: true,
                multidateSeparator: " to ",
                multidate: 2 // Allow selecting a range of dates
            };
            $('#' + id).datepicker(datePickerRangeOptions);
        } else {
            var datepickerOptions = {
                format: 'yyyy-mm-dd',
                autoclose: true,
                clearBtn: true,
                todayHighlight: true
            };
            $('#' + id).datepicker(datepickerOptions);
        }
    }

    /* Only number allowed */
    function validateNumericInput(elemId) {
        var inputValue = $('#' + elemId).val();
        var numericValue = inputValue.replace(/[^0-9]/g, '');
        $('#' + elemId).val(numericValue);
    }
    jQuery(document).on('keyup', '#customer_name,#pickup_business_name,#delivery_venue_business_name', function() {
        var id = $(this).attr('id');
        var valueText = $(this).val()
        $.ajax({
            type: "GET",
            url: '{{route("terminalListData")}}',
            data: {
                search_text: $(this).val(),
            },
            success: function(response) {
               if (valueText.trim()) {
                    if (response.length > 0) {
                        if (id == 'customer_name') {
                            const dataHtml = response.map(feature => `<li onclick="getTerminal('${feature[1]}')">${feature[0]}</li>`).join('');
                            setTimeout(() => {
                                $("#suggesstion-box").show().html(dataHtml);
                                $("#search-box").css("background", "#FFF");
                            }, 500);
                        }
                        if (id == 'pickup_business_name') {
                            const dataHtml = response.map(feature => `<li onclick="getTerminal2('${feature[1]}')">${feature[0]}</li>`).join('');
                            setTimeout(() => {
                                $("#suggesstion-box2").show().html(dataHtml);
                                $("#search-box").css("background", "#FFF");
                            }, 500);
                        }
                        if (id == 'delivery_venue_business_name') {
                            const dataHtml = response.map(feature => `<li onclick="getTerminal3('${feature[1]}')">${feature[0]}</li>`).join('');
                            setTimeout(() => {
                                $("#suggesstion-box3").show().html(dataHtml);
                                $("#search-box").css("background", "#FFF");
                            }, 500);
                        }
                    } else {
                        $(document).find("#suggesstion-box").hide();
                        $("#search-box").css("background", "unset");
                    }
                } else {
                    $(document).find("#suggesstion-box").hide();
                    $(document).find("#suggesstion-box2").hide();
                    $(document).find("#suggesstion-box2").hide();
                    $("#search-box").css("background", "#FFF");
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus, errorThrown);
            }
        });
    });
    
    jQuery(document).on('blur','.tariffCal',function(){
        var total = 0;
        jQuery(document).find('.tariffCal').each(function(){
            var invTariff = $(this).val();
            total = parseFloat(total)  + parseFloat(invTariff.trim())
        })
        jQuery('#total_tarriff').val(total);
    });
    
    jQuery(document).on('change', '#customer_name,#pickup_business_name,#delivery_venue_business_name', function() {
        setTimeout(() => {
            $(document).find("#suggesstion-box").hide();
            $(document).find("#suggesstion-box2").hide();
            $(document).find("#suggesstion-box2").hide();
            $("#search-box").css("background", "unset");
        }, 500);

    });
    jQuery(document).on('blur', '.vinkeypress', function() {
        var i = jQuery(this).data('id');
        $.ajax({
            type: "GET",
            url: '{{route("vinDetails")}}',
            data: {
                vin_number: $(this).val(),
            },
            success: async function(response) {
                jQuery('#make' + i).val(response.manufacture);
                jQuery('#year' + i).val(response.model_year);
                //jQuery('#type' + i).val(response.v_model);
                jQuery('#model' + i).val(response.model_type);
                 /*  */
                const result = await type(response.manufacture, response.model_type);
                console.log(result);
                    const dataHtml =
                        result.map(res => `${res}`).join('');

                    $(`#type${i}`).val((dataHtml) ?  dataHtml  : 'other');
                /*  */
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus, errorThrown);
            }
        });
    })

    function getTerminal(guid) {
        $.ajax({
            type: "GET",
            url: '{{route("terminalDetails")}}',
            data: {
                guid: guid,
            },
            success: function(response) {
                $(document).find("#suggesstion-box").hide();
                $("#search-box").css("background", "unset");
                $('#customer_name').val(response.name);
                $('#customer_venue_business_type').val();
                $('#customer_address').val(response.address);
                $('#customer_state').val(response.state);
                $('#customer_city').val(response.city);
                $('#customer_zip_code').val(response.zip);
                $('#customer_phone_number').val(response.phone);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus, errorThrown);
            }
        });
    }
    function getTerminal2(guid) {
        $.ajax({
            type: "GET",
            url: '{{route("terminalDetails")}}',
            data: {
                guid: guid,
            },
            success: function(response) {
                $(document).find("#suggesstion-box2").hide();
                $("#search-box").css("background", "unset");
                $('#pickup_business_name').val(response.name);
                $('#customer_venue_business_type').val();
                $('#pickup_business_address').val(response.address);
                $('#pickup_business_state').val(response.state);
                $('#pickup_business_city').val(response.city);
                $('#pickup_business_zip_code').val(response.zip);
                $('#pickup_contact_phone_number').val(response.phone);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus, errorThrown);
            }
        });
    }
    function getTerminal3(guid) {
        $.ajax({
            type: "GET",
            url: '{{route("terminalDetails")}}',
            data: {
                guid: guid,
            },
            success: function(response) {
                $(document).find("#suggesstion-box3").hide();
                $("#search-box").css("background", "unset");
                $('#delivery_venue_business_name').val(response.name);
                $('#customer_venue_business_type').val();
                $('#delivery_business_address').val(response.address);
                $('#delivery_business_state').val(response.state);
                $('#delivery_business_city').val(response.city);
                $('#delivery_business_zip_code').val(response.zip);
                $('#delivery_contact_phone_number').val(response.phone);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus, errorThrown);
            }
        });
    }
    
    async function type(makeId, modelId) {
        try {
            const response = await fetch("https://dircks.xelentor.com/public/vehicle.json");
            const data = await response.json();

            const uniqueTypes = [...new Set(
                data.filter((x) => (x.make).toLowerCase() === makeId.toLowerCase() && (x.model).toLowerCase() === modelId.toLowerCase())
                .map((x) => x.type)
            )];
            // Return the array of unique vehicle types
            return uniqueTypes;
        } catch (error) {
            // Return a default value or an empty array in case of an error
            return [];
        }
    }
    
     /* async function getStateAndCity() { */
    $("#customer_zip_code,#pickup_business_zip_code,#delivery_business_zip_code").on("input", async function() {
        const searchValue = $(this).val();
        const attrId = $(this).attr('id');
        if (searchValue.length == 5) {
            try {
                const response = await $.ajax({
                    type: "GET",
                    url: "https://api.mapbox.com/geocoding/v5/mapbox.places/" + searchValue + ".json",
                    data: {
                        country: 'US',
                        types: 'place,postcode,locality',
                        autocomplete: true,
                        access_token: 'pk.eyJ1IjoiZGlyY2tzYXV0byIsImEiOiJjbHI2YXpuMGcyMGw4MmxwbjltNzRnMG1iIn0.wV6jb2HX_OCIgDnDM0ySww'
                    }
                });
                const placeName = response.features.map(feature => feature.place_name).join('');
                var placeNameArray = placeName.split(",");

				const stateContext = response.features[0].context.find(c => c.id.startsWith("region"));
                const stateShortCode = stateContext.short_code.split('-')[1].toUpperCase();

                console.log(placeNameArray);
                setTimeout(() => {
                    if (attrId == 'customer_zip_code') {
                        $("#customer_city").val(placeNameArray[0]);
                        $("#customer_state").val(stateShortCode);
                    } else if (attrId == 'pickup_business_zip_code') {
                        $("#pickup_business_city").val(placeNameArray[0]);
                        $("#pickup_business_state").val(stateShortCode);
                    } else if (attrId == 'delivery_business_zip_code') {
                        $("#delivery_business_city").val(placeNameArray[0]);
                        $("#delivery_business_state").val(stateShortCode);
                    }
                    $("#customer_city,#customer_state,#pickup_business_city,#pickup_business_state,#delivery_business_city,#delivery_business_state").trigger('change');

                }, 1000);
            } catch (error) {
                console.error(error);
            }
        }
    });
    /*  } */
</script>
@stop