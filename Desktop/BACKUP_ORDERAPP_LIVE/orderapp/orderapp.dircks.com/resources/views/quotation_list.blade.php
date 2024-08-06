@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12  offset-md-12">
        <table id="myTable" class="display">
           <thead>
            <tr>
                    
                    <th>Pick-up</th>
                    <th>Delivery</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Sales Person</th>
                    <th>Type</th>
                    <th>value</th>
                    <th>Final Value</th>
                    <th>Year</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Type</th>
                    <th>Inoperable</th>
                    <th>Vehicle</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    
                    <td>{{$item->origin}}</td>
                    <td>{{$item->destination}}</td>
                    <td>{{$item->customer_name}}</td>
                    <td>{{$item->customer_email_id}}</td>
                    <td>{{$item->sales_person_name}}</td>
                    <td>{{$item->quote_type}}</td>
                    <td>{{$item->quote_value}}</td>
                    <td>{{$item->final_value}}</td>
                    <td>{{$item->vehicles[0]->year}}</td>
                    <td>{{$item->vehicles[0]->make}}</td>
                    <td>{{$item->vehicles[0]->model}}</td>
					{{-- <td>{{isset($vItem->type) ? $item->vehicles[0]->type : ''}}</td> --}}
					<td>{{ isset($vItem->type) ? $vItem->type : '' }}</td>
                    <td>{{($item->vehicles[0]->isInOperable) ?  'Yes' : 'No'}}</td>
                    <td>
                        <a href="javascript:void(0)" class="popupClass" onClick="openPopup(this)"
                        data-html="
                        @if($item->vehicles)
                            @foreach($item->vehicles as $vItem)
                            <b>Year </b> :{{isset($vItem->year) ? $vItem->year : '-'}}  <br>
                            <b>Make </b> :{{isset($vItem->make) ? $vItem->make : '-'}}  <br>
                            <b>Model </b> :{{isset($vItem->model) ? $vItem->model : '-'}}  <br>
                            <b>Type </b> :{{isset($vItem->type) ? $vItem->type : '-'}}  <br>
                            <b>Inoperable </b> :{{isset($vItem->is_inoperable) ? 'Yes' : 'No'}}  
                            @endforeach
                        @else
                            '-'
                        @endif
                        "
                        >View Details</a>
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Vehicle Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('jsScript')
<script>
 //let table = new DataTable('#myTable');
 $(document).ready(function() {
        var table = $('#myTable').DataTable({
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    filename: 'custom_data_export',
                    exportOptions: {
                        columns: [0, 1,2, 3, 4,5,6,7,8,9,10,11,12] // Remove the second column (index 1) from export
                    }
                }
            ],
        });
    });
 
 function openPopup(ele){
     var a =  $(ele).data('html')
     $('.modal-body').html(a)
     $('#exampleModal').modal('show')
 }
 
</script>
@stop