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

    .pt-4_5 {
        padding-top: 2.4rem !important;
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

    /* AutoComplate */

    /* Select2 Css */
    .select2-container--default .select2-selection--single {
        padding: 3px 0px 0px;
        height: 37px;
        width: 148px;
        font-size: 1em;
        position: relative;
        border: #ccc solid 1px
    }

    /* Select2 Css */
</style>
<div class="row">
    <div class="col-md-12  offset-md-12">
        <form name="getQuotation" id="getQuotation" method="POST">
            @csrf
            <input type="hidden" name="quote_type" value="{{$type}}">
            <input type="hidden" name="vehicle_length" id="vehicle_length">
            <!-- DisPatcher & Salseperson -->
            <label class="mt-3 mb-0 ml-0 pl-0 ml-force font-weight-bold">Shipping Details</label>
            <div class="row bg-light border rounded">
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="customer_name" class="mb-0 mt-2">Customer Name</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name">
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="customer_email_id" class="mb-0 mt-2">Email Id</label>
                    <input type="text" class="form-control" id="customer_email_id" name="customer_email_id">
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                    <label for="sales_person_name" class="mb-0 mt-2">Salesperson Name</label>
                    <input type="text" class="form-control" id="sales_person_name" name="sales_person_name">
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="pickup_address" class="mb-0 mt-2">Routes*</label>
                    <input type="text" class="form-control" id="pickup_address" name="pickup_address" placeholder="Enter Pickup City or Zip" required>
                    <div id="suggesstion-box" class="col-sm-12"></div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="delivery_address" class="mb-0 mt-2">&nbsp;</label>
                    <input type="text" class="form-control" id="delivery_address" name="delivery_address" placeholder="Enter Delivery City or Zip" required>
                    <div id="suggesstion-box2"></div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label for="d_business_name" class="mb-0 mt-2">Trailer Type</label>
                    <div class="input-group">
                        <input type="radio" groupname="tt" name="trailer_type" value="true" id="tt_open" checked class="mr-2"><label for="tt_open" class="mr-5 mt-1">Open</label>
                        <input type="radio" groupname="tt" name="trailer_type" value="false" id="tt_enclosed" class="mr-2"><label for="tt_enclosed" class="mt-1">Enclosed</label>
                    </div>
                </div>
                <div class="form-group col-md-12 col-lg-12 col-sm-12">
                    <label for="" class="mb-0 mt-2">Vehicle Information</label>
                    <div id="vehicle_block" class="col-md-12  offset-md-12 row">

                    </div>
                </div>

                <div class="form-group mt-5 col-md-12 col-lg-12 col-sm-12 text-right mt-0 ">
                    <a class="form-group btn-secondary rounded pl-5 pr-5 pt-2 pb-2 font-weight-bold" id="clearId" href="javascript:void(0)">Clear</a>
                    <button type="submit" class="form-group btn-success pl-5 pr-5 pt-2 pb-2 rounded font-weight-bold">Submit</button>
                </div>
        </form>
    </div>
    <div class="col-md-6 mt-5 offset-md-6 d-none" id="estimatedBox">
        <div class="row mt-10 bg-light border p-3 rounded">
            <span class="col-md-12 p-2"> 
                Pricing Recommendation
            </span>
            <span class="col-md-12 p-2">
                Estimated Carrier Price
            </span>
            <span class="col-md-12 p-2" id="estimated">
                $1,180
            </span>
        </div>
    </div>
</div>
@endsection
@section('jsScript')
<script>
    var i = 0;
    jQuery(document).ready(function() {
        /* On page load add  first vehicle row*/
        addVehicle();

        /* Form Submit with validation check */
        $("#getQuotation").submit(function(e) {
            e.preventDefault();
        }).validate({
            rules: {
                pickup_address: {
                    required: true,
                },
                delivery_address: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                $('.loading').show();
                $.ajax({
                    url: '{{route("getQuotation")}}',
                    method: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response && response.status) {
                            $('#estimated').html('$ '+response.quote_value)
                            jQuery('#estimatedBox').removeClass('d-none');
                            toastr.success(response.quote_value);
                            $('.loading').hide();
                        } else {
                            console.error("Property 'mode' is undefined in the response.");
                            $('.loading').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed:", status, error);
                    }
                });
            },
        });

        $(document).ready(function() {
            $("#pickup_address").on("input", async function() {
                const searchValue = $(this).val();
                if (searchValue.length >= 2) {
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
                        const dataHtml = response.features.map(feature => `<li onclick="origin('${(feature.place_name).replace(/United States/g, "US")}')">${(feature.place_name).replace(/United States/g, "US")}</li>`).join('');
                        setTimeout(() => {
                            $("#suggesstion-box").show().html(dataHtml);
                            $("#search-box").css("background", "#FFF");
                        }, 1000);
                    } catch (error) {
                        console.error(error);
                    }
                }
            });

            $("#delivery_address").on("input", async function() {
                const searchValue = $(this).val().trim();
                if (searchValue.length >= 2) {
                    try {
                        const response = await $.ajax({
                            type: "GET",
                            url: "https://api.mapbox.com/geocoding/v5/mapbox.places/" + searchValue + ".json",
                            data: {
                                country: 'US',
                                types: 'place,postcode,locality',
                                autocomplete: true,
                                access_token: 'pk.eyJ1IjoiZGlyY2tzYXV0byIsImEiOiJjbHI2YXpuMGcyMGw4MmxwbjltNzRnMG1iIn0.wV6jb2HX_OCIgDnDM0ySww'
                            },
                        });
                        const features = response.features || [];
                        const dataHtml = features.map(feature => `<li onclick="destination('${(feature.place_name).replace(/United States/g, "US")}')">${(feature.place_name).replace(/United States/g, "US")}</li>`).join('');
                        setTimeout(function() {
                            $("#suggesstion-box2").show().html(dataHtml);
                            $("#search-box2").css("background", "#FFF");
                        }, 1000);
                    } catch (error) {
                        console.error(error);
                    }
                }
            });
        });
    });

    function origin(val) {
        $("#pickup_address").val(val);
        $("#suggesstion-box").hide();
    }

    function destination(val) {
        $("#delivery_address").val(val);
        $("#suggesstion-box2").hide();
    }

    function addVehicle() {
        var vehicleLength = jQuery('.vehicle-row-exist').length;
        if (vehicleLength > 4) {
            toastr.error('You can not add more than 5 vehicle');
            return false;
        }
        i = vehicleLength + 1;
        $('.add-btn').addClass('d-none');
        var html = '';
        html += '<div class="row vehicleRow' + i + ' vehicle-row-exist">';
        html += '<div class="form-group col-md-2 col-lg-2 col-sm-12">';
        html += '    <label for="year' + i + '" class="mb-0 mt-2">Year</label>';
        html += '    <input type="text" class="form-control" id="year' + i + '" name="year' + i + '" required>';
        html += '</div>';
        html += '<div class="form-group col-md-2 col-lg-2 col-sm-12">';
        html += '    <label for="make' + i + '" class="mb-0 mt-2">Make*</label>';
        html += '    <select type="text" class="form-control js-example-basic-single" data-id="' + i + '" id="make' + i + '" name="make' + i + '" required></select>';
        html += '</div>';
        html += '<div class="form-group col-md-2 col-lg-2 col-sm-12">';
        html += '    <label for="model' + i + '" class="mb-0 mt-2">Model*</label>';
        html += '    <select type="text" class="form-control js-example-basic-single-model" id="model' + i + '" name="model' + i + '" data-id="' + i + '" required></select>';
        html += '</div>';
        html += '<div class="form-group col-md-2 col-lg-2 col-sm-12">';
        html += '    <label for="type' + i + '" class="mb-0 mt-2">Type</label>';
        html += '    <input type="text" class="form-control" id="type' + i + '" name="type' + i + '" required  readonly/>';
        /*  html += '           <option value="sedan">Sedan</option>';
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
         html += '    </select>';    */
        html += '</div>';
        html += '<div class="form-group col-md-1 col-lg-1 col-sm-12">';
        html += '    <label for="indp' + i + '" class="mb-0 mt-2 ml-1">INOP</label>';
        html += '    <input type="checkbox" class="form-control" id="indp' + i + '" name="indp' + i + '">';
        html += '</div>';
        //html += '<div class="form-group col-md-2 col-lg-2 col-sm-12 text-left pt-4_5">';
        //html += '    <a onClick="addVehicle()" class="form-group btn-success p-2 rounded add-btn add-vehicle-btn-' + i + '"><i class="fa fa-plus mr-2"></i> Vehicle</a>';
        //html += '</div>';
        html += '</div>';

        jQuery('#vehicle_block').append(html);
        $('#vehicle_length').val(jQuery('.vehicle-row-exist').length)

        makes().then((result) => {
            var dataHtml = '<option value="">Select</option>';
            for (const res of result) {
                dataHtml += '<option value="' + res + '">' + res + '</li>'
            }
            jQuery(document).find('#make' + i).html(dataHtml);
            jQuery(document).find('#make' + i).select2();
            jQuery(document).find('#model' + i).select2();

        });


    }

    function makes() {
        return fetch("/vehicle.json")
            .then((res) => res.json())
            .then((data) => {
                const uniqueMakes = [...new Set(data?.map((x) => x.make))];
                return uniqueMakes;
            })
            .catch((error) => {
                console.error(error);
                return []; // Return an empty array in case of an error
            });
    }

    function model(makeId) {
        return fetch("/vehicle.json")
            .then((res) => res.json())
            .then((data) => {
                const uniqueModels = [...new Set(data.filter((x) => x.make === makeId).map((x) => x.model))];
                return uniqueModels;
            })
            .catch((error) => {
                console.error(error);
                return []; // Return an empty array in case of an error
            });
    }

    async function type(makeId, modelId) {
        try {
            const response = await fetch("/vehicle.json");
            const data = await response.json();

            const uniqueTypes = [...new Set(
                data.filter((x) => x.make === makeId && x.model === modelId)
                .map((x) => x.type)
            )];
            // Return the array of unique vehicle types
            return uniqueTypes;
        } catch (error) {
            // Return a default value or an empty array in case of an error
            return [];
        }
    }

    $(document).on("change", '.js-example-basic-single', async function() {
        try {
            const make = $(this).val();
            const i = $(this).data('id');
            const result = await model(make);

            const dataHtml = '<option value="">Select</option>' +
                result.map(res => `<option value="${res}">${res}</option>`).join('');

            $(`#model${i}`).html(dataHtml).select2("destroy").select2();
        } catch (error) {
            console.error(error);
        }
    });

    $(document).on("change", '.js-example-basic-single-model', async function() {
        try {
            const i = $(this).data('id');
            const make = $(`#make${i}`).val();
            const model = $(`#model${i}`).val();
            const result = await type(make, model);

            const dataHtml =
                result.map(res => `${res}`).join('');

            $(`#type${i}`).val(dataHtml);
        } catch (error) {
            console.error(error);
        }
    });
	$('#clearId').click(function(){
		window.location.reload()
	});

</script>
@stop