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
                    {{-- <button name="export" id="export" type="button" name="export" id="export"  class="btn btn-outline-danger ml-2">Excel</button>
                    <button name="export" id="exportpdf" type="button" name="export" id="exportpdf"  class="btn btn-outline-danger">PDF</button> --}}
                </div>
            </div>
        </div>

        <div class="card-datatable table-responsive p-2">
          <table id="investment_table" class="invoice-list-table table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Total Amount(RM)</th>
                    <th>Total Roi Amount(RM)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $investment)
                <tr>
                    <td><a href="{{url('commisions', ['username' => $investment->username])}}">{{ $investment->username }}</a></td>
                    <td>{{ number_format($investment->total_amount) }}</td>
                    <td>{{ number_format($investment->total_roi_amount) }}</td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#export').click(function(){
            var filter_from = $('#filter_from').val();
            var filter_to = $('#filter_to').val();

            $.ajax({
            url: "{{ route('export.investment-export') }}",
            method: 'GET',
            data:{
                filter_from:filter_from,
                filter_to: filter_to,
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'Investment-list.xlsx';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
            });
        });

        $('#exportpdf').click(function(){
            var filter_from = $('#filter_from').val();
            var filter_to = $('#filter_to').val();

            $.ajax({
            url: "{{ route('exportpdf.investment-exportpdf') }}",
            method: 'GET',
            data:{
                filter_from:filter_from,
                filter_to: filter_to,
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'investment-list.pdf';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
            });
        });
    });
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

