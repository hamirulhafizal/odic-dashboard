@extends('layouts/contentLayoutMaster')

@can('user-list')
@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
<style>
    input[type=checkbox] {
      display: none;
    }

    .container img {
      margin: 100px;
      transition: transform 0.25s ease;
      cursor: zoom-in;
    }

    input[type=checkbox]:checked ~ label > img {
      transform: scale(2);
      cursor: zoom-out;
    }
</style>
@section('title', 'Commision Management')
@section('content')

<div class="row">
    <div class="col-12">
      <div class="card invoice-list-wrapper">
        <div class="row p-2">
            <div class="col">
                <div class="float-end d-inline-flex justify-content-between align-bottom gap-04">
                    <button name="export" id="export" type="button" name="export" id="export"  class="btn btn-outline-danger ml-2">Excel</button>
                    <button name="export" id="exportpdf" type="button" name="export" id="exportpdf"  class="btn btn-outline-danger">PDF</button>
                    <input name="username" id="username" value={{$username}} hidden>
                </div>
            </div>
        </div>

        <div class="card-datatable table-responsive p-2">
          <table id="investment_table" class="invoice-list-table table">
            <thead>
                <tr>
                    <th>Slot ID</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>2%</th>
                </tr>
                
            </thead>
            <tbody>
                @foreach($data as $investment)
                <tr>
                    <td>{{ $investment->id }}</td>
                    <td>{{ $investment->username }}</td>
                    @if($investment->status == 'Withdraw')
                      <td><span class="badge bg-primary">{{ $investment->status}}</span></td>
                    @elseif($investment->status == 'Pending')  
                      <td><span class="badge bg-info">{{ $investment->status}}</span></td>
                    @elseif($investment->status == 'Primary') 
                      <td><span class="badge bg-warning">{{ $investment->status}}</span></td>
                    @endif
                    <td>{{ number_format($investment->total_direct_sales) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th><a href="{{url('commisions/approval', ['username' => $investment->username])}}"" type="button" class="btn btn-success rounded">Withdraw</a></th>
                {{-- <th><button type="button" onclick="acceptWithdraw({{ $investment->id }})" class="btn btn-success rounded">Withdraw</button></th> --}}
                <th>Total</th>
                <th>RM {{number_format($total)}}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#export').click(function(){
            var username = $('#username').val();
            $.ajax({
            url: "{{ route('export.commision-export') }}",
            method: 'GET',
            data:{
              username: username
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'Commision-list.xlsx';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
            });
        });

        $('#exportpdf').click(function(){
            var username = $('#username').val();
            console.log(username)
            $.ajax({
            url: "{{ route('exportpdf.commision-exportpdf') }}",
            method: 'GET',
            data:{
              username: username
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'Commision-list.pdf';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
            });
        });
       
    });
</script>
<script>
   function acceptWithdraw(username){
          alert(username)
   }

</script>

@endsection
@endcan
@section('vendor-script')
  {{-- vendor files --}}
  <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection

