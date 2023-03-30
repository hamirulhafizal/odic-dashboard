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
@section('title', 'User Management')
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
                                    <option value="Partner">Partner</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong><label for="password" class="form-label">Password:</label></strong>
                                <input class="form-control" type="password" name="password" id="password" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong> <label for="name" class="form-label">Team Member Name:</label></strong>
                                <input class="form-control" type="text" name="team_member" id="team_member" required>
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
<div class="modal fade modal-xl" id="showUserModal" tabindex="-1" aria-labelledby="showUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="showUserFormLabel">Show User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="javascript:void(0)" id="showUserForm" name="showUserForm" class="form-horizontal" method="POST">
                <div class="row">
                    <div class="row">
                        <div class="col-6">
                            <strong> <label for="name" class="form-label">Name:</label></strong>
                            <input class="form-control-plaintext"  type="text" name="name" id="show_name"
                            style="padding-top:0px; position: relative; top: -6px" disabled>

                            <div class="form-group">
                                <strong><label for="email" class="form-label">Email:</label></strong>
                                <input class="form-control-plaintext" type="email"  name="email"  id="show_email"
                                style="padding-top:0px; position: relative; top: -6px" disabled>
                            </div>
                        </div>
                        <div class="col-6">
                            <strong><label for="profile_frame" class="form-label"> Profile Image:</label></strong>
                            <div style="text-align: center; z-index: 1; position: relative; display: flex; padding-bottom: 6%;
                            ">
                                <input type="checkbox" id="zoomCheck">
                                <label for="zoomCheck">
                                    <img id="profile_frame" src="" class="rounded mx-auto d-block " width="100"
                                    style="padding-top:0px; position: relative; top: -6px" disabled/>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <strong><label for="show_fullname" class="form-label">Fullname:</label></strong>
                                <div style=" display: flex">
                                    <textarea class="form-control-plaintext" type="text"  name="fullname"  id="show_fullname"
                                     disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <strong><label for="show_phone" class="form-label">Phone No:</label></strong>
                                <div style=" display: flex">
                                    <input class="form-control-plaintext" type="phone_no"  name="phone_no"  id="show_phone"
                                    style="padding-top:0px; position: relative; top: -6px; width: 94px" disabled>
                                    <a id="the-link" href="" target="_blank"><img src="{{asset('images/icons/whatsapp.png')}}" height="auto" width ="30" style="position: relative; top: -10px;" alt=""></a>

                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <strong><label for="show_bank_account" class="form-label">Bank Account:</label></strong>
                                <div style=" display: flex">
                                    <input class="form-control-plaintext" type="text"  name="bank_account"  id="show_bank_account"
                                    style="padding-top:0px; position: relative; top: -6px; " disabled>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <strong><label for="show_bank_account" class="form-label">Bank Account:</label></strong>
                                <div style=" display: flex">
                                    <input class="form-control-plaintext" type="text"  name="bank_account"  id="show_bank_account"
                                    style="padding-top:0px; position: relative; top: -6px; " disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <strong><label for="show_bank_name" class="form-label">Bank Name:</label></strong>
                                <div style=" display: flex">
                                    <input class="form-control-plaintext" type="text"  name="bank_name"  id="show_bank_name"
                                    style="padding-top:0px; position: relative; top: -6px; " disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <strong><label for="show_address" class="form-label">Address:</label></strong>
                                <div style=" display: flex">
                                    <input class="form-control-plaintext" type="text"  name="address"  id="show_address"
                                    style="padding-top:0px; position: relative; top: -6px; " disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <strong><label for="show_city" class="form-label">City:</label></strong>
                                <div style=" display: flex">
                                    <input class="form-control-plaintext" type="text"  name="city"  id="show_city"
                                    style="padding-top:0px; position: relative; top: -6px; " disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <strong><label for="show_state" class="form-label">State:</label></strong>
                                <div style=" display: flex">
                                    <input class="form-control-plaintext" type="text"  name="state"  id="show_state"
                                    style="padding-top:0px; position: relative; top: -6px; " disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <strong><label for="show_postcode" class="form-label">Postcode:</label></strong>
                                <div style=" display: flex">
                                    <input class="form-control-plaintext" type="text"  name="postcode"  id="show_postcode"
                                    style="padding-top:0px; position: relative; top: -6px; " disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="edit_role" class="form-label"> Identity Card:</label></strong>
                            <div style="text-align: center; z-index: 1; position: relative; display: flex; justify-content: start; padding-bottom: 4%;">
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
                            <strong><label for="edit_role" class="form-label"> Referrel URL:</label></strong>
                            <input class="form-control-plaintext" type="text"  name="referrel_url"  id="show_referrel_url"
                            style="padding-top:0px; position: relative; top: -6px" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong><label for="edit_role" class="form-label"> Verified Status:</label></strong><br>
                            <span class="badge rounded-pill badge-light-info" id="show_verified_status" ></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            <div class="pull-right">
                @can('user-create')
                <button type="button" class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#addUserModel">
                    Create New User
                </button>
                @endcan
            </div>
          <table id="user_table" class="invoice-list-table table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    {{-- <th>Phone No</th> --}}
                    {{-- <th>Identity Card</th> --}}
                    <th>Email Verified</th>
                   
                    <th>Approved Status</th>
                    {{-- <th>Referrel URL</th> --}}
                    <th>Role</th>
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
               var table = $('#user_table').DataTable({
                   responsive: true,
                   searching: true,
                   processing: true,
                   serverSide: true,
                   ajax:{
                       type:"GET",
                       url:"{{ route('users.index') }}",
                       data:{filter_from:filter_from, filter_to: filter_to},
                   },
                   columns: [
                       {data: 'id', name: 'id'},
                       {data: 'name', name: 'name'},
                       {data: 'username', name: 'username'},
                       {data: 'email', name: 'email'},
                       
                    //    {data: 'phone_no', name: 'phone_no'},
                    //    {data: 'identity_card', name: 'identity_card'},
                       {data: 'email_verified_at', name: 'email_verified_at'},
                       {data: 'verified_status', name: 'verified_status'},
                       {data: 'user_role', name: 'user_role'},
                    //    {data: 'referrel_url', name: 'referrel_url'},
                       {data: 'created_at', name: 'created_at'},
                       {data: 'action', name: 'action', orderable: false, searchable: false},
                   ],columnDefs: [
                    {
                    // Label
                        targets:[5],
                        render: function (data, type, full, meta) {
                            var status_number = full['verified_status'];
                            var status = {
                                Approved: { title: 'Approved', class: 'badge-light-success' },
                                Pending: { title: 'Pending', class: 'badge-light-info' },
                                Failed: { title: 'Failed', class: 'badge-light-warning' },
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
                    },
                    {
                    // Label
                        targets:[4],
                        render: function (data, type, full, meta) {
                            var status_number = full['email_verified_at'];
                            var status = {
                                yes: { title: 'Yes', class: 'badge-light-success' },
                                no: { title: 'No', class: 'badge-light-warning' },
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
                url: "{{ route('export.user-export') }}",
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
                    a.download = 'user-list.xlsx';
                    document.body.append(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                },error: function (error){
                    console.log(error)
                }
                });

            });

            $('#exportpdf').click(function(){
                var filter_from = $('#filter_from').val();
                var filter_to = $('#filter_to').val();

                $.ajax({
                url: "{{ route('exportpdf.user-exportpdf') }}",
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
                    a.download = 'user-list.pdf';
                    document.body.append(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                }
                });

            });

        //    $('#identity_card_zoom').zoom();

           $('body').on('click', '#btn-save', function (event) {
            var name = $("#name").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var role =  $("#role").val();
            var team_member =  $("#team_member").val();
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
                    url: "{{ route('users.store') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        name:name,
                        email:email,
                        password:password,
                        "confirm-password":password,
                        roles:role,
                        team_member:team_member,
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
            var name = $(this).data('name')
            var id = $(this).data('id')
            var email = $(this).data('email')
            var phone_no = $(this).data('phone_no')
            var verified_status = $(this).data('verified_status')
            var referrel_url = $(this).data('referrel_url')
            var username = $(this).data('username')
            var fullname = $(this).data('fullname')
            var bank_account = $(this).data('bank_account')
            var bank_name = $(this).data('bank_name')
            var identity_card_no = $(this).data('identity_card_no')
            var profile_image = $(this).data('profile_image')
            var identity_card = $(this).data('identity_card')
            var address = $(this).data('address')
            var city = $(this).data('city')
            var state = $(this).data('state')
            var postcode = $(this).data('postcode')

            $("#btn-approve"). attr("data-id", id);
            $('#show_name').val(name);
            $('#show_username').val(username);
            $('#show_email').val(email);
            $('#show_phone').val(phone_no);
            $('#show_fullname').val(fullname);
            $('#show_bank_account').val(bank_account);
            $('#show_bank_name').val(bank_name);
            $('#show_address').val(address);
            $('#show_city').val(city);
            $('#show_state').val(state);
            $('#show_postcode').val(postcode);
            // $('#show_bank_name').val(bank_name);
            // $('#show_identity_card').val(identity_card);
            $('#show_referrel_url').val(referrel_url);
            $("#show_verified_status").text(verified_status);
            // console.log(identity_card == '')
            if(identity_card == ''){
                $('#view_frame').attr("src",  `{{asset('images/logo/no_image.jpg')}}`);
            }else{
                $('#view_frame').attr("src",  `{{asset('profile/${identity_card}')}}`);
            }

            if(profile_image == ''){
                $('#profile_frame').attr("src",  `{{asset('images/logo/no_image.jpg')}}`);
            }else{
                $('#profile_frame').attr("src",  `{{asset('profile/${profile_image}')}}`);
            }

            // document.querySelector('#the-link').setAttribute('href',  `https://api.whatsapp.com/send/?phone=${receipt}`);
         if(phone_no != '') document.querySelector('#the-link').setAttribute('href', 'https://api.whatsapp.com/send/?phone='+phone_no);
        });

        $('body').on('click', '#btn-approve', function (event) {
            var id = $(this).data('id')
            var url = "{{ route('users.approved', ['user' => ":id"]) }}";
            url = url.replace(':id', id);
            // console.log(id);
            $.ajax({
                type:"PUT",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
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

