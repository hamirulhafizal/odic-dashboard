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
@section('title', 'Investment Management')
@section('content')

<div class="row">
{{-- Add new user modal --}}
    <div class="modal fade" id="addUserModel" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addUserFormLabel">Create New Role</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="addUserForm" name="addUserForm" class="form-horizontal" method="POST">
                    {{-- {!! Form::open(array('route' => 'roles.store','method'=>'POST' 'id' => "addRoleForm")) !!} --}}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong> <label for="name" class="form-label">Name:</label></strong>
                                {{-- {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!} --}}
                                <input class="form-control" type="text" name="name" id="name" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong><label for="email" class="form-label">Email:</label></strong>
                                <input class="form-control" type="email"  name="email"  id="email" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong><label for="role" class="form-label">Select Role:</label></strong>
                                <select class="form-select" id="role" >
                                    <option selected>Open this select menu</option>


                                    {{-- <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option> --}}
                                </select>
                                {{-- <input class="form-control" type="role"  name="role"  id="role" required> --}}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong><label for="password" class="form-label">Password:</label></strong>
                                <input class="form-control" type="password" name="password" id="password" required>
                            </div>
                        </div>

                    </div>
                    {{-- {!! Form::close() !!} --}}
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="btn-save" data-bs-dismiss="modal">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
   {{-- End add user modal --}}

   {{-- Edit user mnodal --}}
   <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserFormLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="javascript:void(0)" id="editUserForm" name="editUserForm" class="form-horizontal" method="POST">
                {{-- {!! Form::open(array('route' => 'roles.store','method'=>'POST' 'id' => "addRoleForm")) !!} --}}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong> <label for="name" class="form-label">Name:</label></strong>
                            {{-- {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!} --}}
                            <input class="form-control" type="text" name="name" id="edit_name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="email" class="form-label">Email:</label></strong>
                            <input class="form-control" type="email"  name="email"  id="edit_email">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="edit_role" class="form-label">Select Role:</label></strong>
                            <input class="form-control" type="text"  name="edit_role"  id="edit_role" disabled>
                            {{-- <select class="form-select" id="edit_role" >
                                <option selected disabled><label id="role_name"></label></option>
                            </select> --}}
                            {{-- <input class="form-control" type="role"  name="role"  id="role" required> --}}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="password" class="form-label">Password:</label></strong>
                            <input class="form-control" type="password" name="password" id="password" disabled>
                        </div>
                    </div>

                </div>
                {{-- {!! Form::close() !!} --}}
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-edit" data-bs-dismiss="modal">Save changes</button>
        </div>
      </div>
    </div>
  </div>

{{-- end edit user modal --}}


{{-- show user modal --}}
<div class="modal fade" id="showUserModal" tabindex="-1" aria-labelledby="showUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="showUserFormLabel">Investment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="javascript:void(0)" id="showUserForm" name="showUserForm" class="form-horizontal" method="POST">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong> <label for="name" class="form-label">Username:</label></strong>
                            <input class="form-control-plaintext"  type="text" name="username" id="show_username"
                            style="padding-top:0px; position: relative; top: -6px" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="email" class="form-label">Amount:</label></strong>
                            <input class="form-control-plaintext" type="text"  name="amount"  id="show_amount"
                            style="padding-top:0px; position: relative; top: -6px" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="email" class="form-label">Roi:</label></strong>
                            <input class="form-control-plaintext" type="text"  name="roi"  id="show_roi"
                            style="padding-top:0px; position: relative; top: -6px" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="email" class="form-label">Slot:</label></strong>
                            <input class="form-control-plaintext" type="text"  name="slot"  id="show_slot"
                            style="padding-top:0px; position: relative; top: -6px" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="edit_role" class="form-label"> Roi Amount:</label></strong>
                            <input class="form-control-plaintext" type="text"  name="roi_amount"  id="show_roi_amount"
                            style="padding-top:0px; position: relative; top: -6px" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="edit_role" class="form-label"> Receipt:</label></strong>
                            <div style="text-align: center; z-index: 1; position: relative;">
                                <input type="checkbox" id="zoomCheck">
                                <label for="zoomCheck">
                                    <img id="view_frame" src="" class="rounded mx-auto d-block " width="350"
                                    style="padding-top:0px; position: relative; top: -6px" disabled/>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="edit_role" class="form-label">Status:</label></strong><br>
                            {{-- <input class="form-control-plaintext badge-light-info" type="text"  name="status"  id="show_status"
                            style="padding-top:0px; position: relative; top: -6px" disabled> --}}
                            <span class="badge rounded-pill badge-light-info" id="show_status" ></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" id="btn-reject" class="btn btn-danger" data-bs-dismiss="modal">Reject</button>
          <button type="button" id="btn-approve" class="btn btn-primary" data-bs-dismiss="modal">Approve</button>
        </div>
      </div>
    </div>
  </div>
{{-- end show user modal --}}

{{-- Delete popup modal --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUserFormLabel">Delete User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="javascript:void(0)" id="deleteUserForm" name="deleteUserForm" class="form-horizontal" method="POST">
                {{-- {!! Form::open(array('route' => 'roles.store','method'=>'POST' 'id' => "addRoleForm")) !!} --}}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                           <label class="form-label">Are you sure you want to delete user<strong> <label id="delete_user" class="form-label"></label></strong> ?</label>
                        </div>
                    </div>
                </div>
                {{-- {!! Form::close() !!} --}}
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" id="btn-delete" data-bs-dismiss="modal">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- End delete popup modal --}}

    <div class="col-12">
      <div class="card invoice-list-wrapper">
        <div class="row p-2">
            <div class="col-sm-4">
                <label for="filter_from">From:</label>
                <input type="date" id="filter_from" placeholder="From" class="form-control">
            </div>
            <div class="col-sm-4">
                <label for="filter_to">To:</label>
                <input type="date" id="filter_to" placeholder="To" class="form-control">
            </div>
            <div class="w-10 col-sm-4 pt-2">
                <button  name="filter" id="filter" type="button" class="btn btn-warning ">Filter</button>
                <button name="reset" id="reset" type="button" class="btn btn-warning ">Reset</button>
                <div class="float-end d-inline-flex justify-content-between align-bottom gap-04">
                    <button name="export" id="export" type="button" name="export" id="export"  class="btn btn-outline-danger ml-2">Excel</button>
                    <button name="export" id="exportpdf" type="button" name="export" id="exportpdf"  class="btn btn-outline-danger">PDF</button>
                </div>

            </div>
        </div>



        <div class="card-datatable table-responsive p-2">
            {{-- <div class="pull-right">
                @can('user-create')
                <button type="button" class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#addUserModel">
                    Create New User
                </button>
                @endcan
            </div> --}}
          <table id="investment_table" class="invoice-list-table table">
            <thead>
                <tr>
                    <th>Investment ID</th>
                    <th>Username</th>
                    <th>Amount(RM)</th>
                    <th>Roi(%)</th>
                    <th>Slot</th>
                    <th>Roi Amount(RM)</th>
                    {{-- <th>Receipt</th> --}}
                    <th>Status</th>
                    {{-- <th>Referrel URL</th> --}}
                    <th>Registered Date</th>
                    <th width="">Action</th>
                </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>




    <script type="text/javascript">
        $(document).ready(function(){
           fill_datatable();

           function fill_datatable(filter_from = '', filter_to ='')
           {
               var table = $('#investment_table').DataTable({
                   responsive: true,
                   searching: true,
                   processing: true,
                   serverSide: true,
                   ajax:{
                       type:"GET",
                       url:"{{ route('investment.index') }}",
                       data:{filter_from:filter_from, filter_to: filter_to},
                   },
                   columns: [
                       {data: 'id', name: 'id'},
                       {data: 'username', name: 'username'},
                       {data: 'amount', name: 'amount'},
                       {data: 'roi', name: 'roi'},
                       {data: 'slot', name: 'slot'},
                       {data: 'roi_amount', name: 'roi_amount'},
                    //    {data: 'receipt', name: 'receipt'},
                       {data: 'status', name: 'status'},
                       {data: 'created_at', name: 'created_at'},
                       {data: 'action', name: 'action', orderable: false, searchable: false},
                   ],columnDefs: [
                    {
                    // Label
                        targets:[6],
                        render: function (data, type, full, meta) {
                            var status_number = full['status'];
                            var status = {
                                Pending: { title: 'Pending', class: 'badge-light-info' },
                                Progress: { title: 'Progress', class: 'badge-light-danger' },
                                Withdraw: { title: 'Withdraw', class: 'badge-light-success' },
                                Floating: { title: 'Floating', class: 'badge-light-danger' },
                                Completed: { title: 'Completed', class: 'badge-light-success' },
                                Fail: { title: 'Fail', class: 'badge-light-warning' },
                            };
                            if (typeof status[status_number] === 'undefined') {
                            return data;
                            }
                            return (
                            '<span class="badge rounded-pill ' +
                            status[status_number].class +
                            '">' +
                            status[status_number].title +
                            '</span>'
                            );
                        },
                    }, ]
               });
           }

           $('#filter').click(function(){
               var filter_from = $('#filter_from').val();
               var filter_to = $('#filter_to').val();

               if(filter_from != '' || filter_to != '')
               {
                   $('#user_table').DataTable().destroy();
                   fill_datatable(filter_from, filter_to);
               }
               else
               {
                   alert('Select Both filter option');
               }
           });

           $('#reset').click(function(){
               $('#filter_from').val('');
               $('#filter_to').val('');
               $('#user_table').DataTable().destroy();
               fill_datatable();
           });

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



           $('body').on('click', '#btn-save', function (event) {
            var name = $("#name").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var role =  $("#role").val();;
            $("#btn-save").html('Please Wait...');
            $("#btn-save"). attr("disabled", true);


            // TODO - Ajax validation
            if ($('#name').val() == '' ) {
                console.log('here');
            }if ($('#email').val() == '' ) {
                console.log('here');
            } if ($('#password').val() == '' ) {
                console.log('here');
            }

            // if (name || email || password == '') {
                // ajax
                $.ajax({
                    type:"POST",
                    url: "{{ route('investment.store') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        name:name,
                        email:email,
                        password:password,
                        "confirm-password":password,
                        roles:role,
                    },
                    dataType: 'json',
                    success: function(res){
                        console.log(res);
                        // $('#role-modal').modal('hide');
                        setTimeout(function(){
                            window.location.reload();
                        }, 500);
                        $("#btn-save").html('Submit');
                        $("#btn-save"). attr("disabled", false);
                    },
                    error: function(errors) {
                        console.log(errors);

                        // err.innerHTML = ''
                    }
                });
            // }

        });

        $('body').on('click', '.edit', function () {
            var name = $(this).data('name')
            var id = $(this).data('id')
            var role = $(this).data('role')
            var email = $(this).data('email')

            $("#btn-edit"). attr("data-id", id);
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_role').val(role);
            $('#role_name').val(role);

        });

        $('body').on('click', '#show', function () {
            var username = $(this).data('username')
            var id = $(this).data('id')
            var amount = $(this).data('amount')
            var roi = $(this).data('roi')
            var slot = $(this).data('slot')
            var roi_amount = $(this).data('roi_amount')
            var receipt = $(this).data('receipt')
            var status = $(this).data('status')

            $("#btn-approve"). attr("data-id", id);
            $("#btn-reject"). attr("data-id", id);
            $('#show_username').val(username);
            $('#show_amount').val('RM '+amount);
            $('#show_roi').val(roi +'%');
            $('#show_slot').val(slot);
            $('#show_roi_amount').val('RM ' +roi_amount);
            // $('#show_receipt').val(receipt);
            $('#view_frame').attr("src",  `{{asset('investment/${receipt}')}}`);
            $("#show_status").text(status);

        });

        $('body').on('click', '#btn-approve', function (event) {
            var id = $(this).data('id')
            var url = "{{ route('investment.approval', ['investment' => ":id"]) }}";
            url = url.replace(':id', id);
            // console.log(id);
            $.ajax({
                type:"PUT",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    status: 'Progress'
                },
                dataType: 'json',
                success: function(data){
                    setTimeout(() => {
                        toastr.success(data.message, data.title);
                        window.location.reload();
                    },1500)
                    $("#btn-save").html('Submit');
                    $("#btn-save"). attr("disabled", false);
                },
                error: function(errors) {
                    setTimeout(() => {
                        toastr.error(errors.responseJSON.message, errors.responseJSON.title);
                    },500)

                    // err.innerHTML = ''
                }
            });
        });

        $('body').on('click', '#btn-reject', function (event) {
            var id = $(this).data('id')
            var url = "{{ route('investment.approval', ['investment' => ":id"]) }}";
            url = url.replace(':id', id);
            // console.log(id);
            $.ajax({
                type:"PUT",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    status: 'Fail'
                },
                dataType: 'json',
                success: function(data){
                    setTimeout(() => {
                        toastr.success(data.message, data.title);
                        window.location.reload();
                    },1500)
                    $("#btn-save").html('Submit');
                    $("#btn-save"). attr("disabled", false);
                },
                error: function(errors) {
                    setTimeout(() => {
                        toastr.error(errors.responseJSON.message, errors.responseJSON.title);
                    },500)

                    // err.innerHTML = ''
                }
            });
        });

        $('body').on('click', '.delete', function () {
            var name = $(this).data('name')
            var id = $(this).data('id')
            $("#btn-delete"). attr("data-id", id);

            document.getElementById('delete_user').innerHTML = name;
        });

        $('body').on('click', '#btn-edit', function (event) {
            var name =  $('#edit_name').val();
            var email =  $('#edit_email').val();
            var id = $(this).data('id')

            console.log(name, email);

        $("#btn-save").html('Please Wait...');
        $("#btn-save"). attr("disabled", true);

        var url = "{{ route('users.update', ['user' => ":id"]) }}";
        url = url.replace(':id', id);

            $.ajax({
                type:"PUT",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    name:name,
                    email: email,
                },
                dataType: 'json',
                success: function(res){
                    console.log(res);

                    setTimeout(function(){
                        window.location.reload();
                    }, 500);
                    $("#btn-save").html('Submit');
                    $("#btn-save"). attr("disabled", false);
                },
                error: function(errors) {
                    console.log(errors);
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

