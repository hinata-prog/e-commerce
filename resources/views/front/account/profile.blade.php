@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-12">
                @include('front.account.common.message')
            </div>
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                    </div>
                    <form action="" name="profileForm" id="profileForm">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control" value="{{ $user->name }}">
                                    <p></p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="phone_number">Phone</label>
                                    <input type="number" name="phone_number" id="phone_number" placeholder="Enter Your Phone" class="form-control" value="{{ $user->phone_number }}">
                                    <p></p>
                                </div>
                                <div class="d-flex">
                                    <button class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Address Information</h2>
                    </div>
                    <form action="" name="addressForm" id="addressForm">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label for="name">First Name</label>
                                    <input type="text" name="first_name" id="first_name" placeholder="Enter Your First Name" class="form-control" value="{{ (!empty($userAddress)) ? $userAddress->first_name : '' }}">
                                    <p></p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="name">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" placeholder="Enter Your Last Name" class="form-control" value="{{ (!empty($userAddress)) ? $userAddress->last_name : '' }}">
                                    <p></p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="name">Email</label>
                                    <input type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control" value="{{ (!empty($userAddress)) ? $userAddress->email : '' }}">
                                    <p></p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="name">Mobile Number</label>
                                    <input type="text" name="mobile" id="mobile" placeholder="Enter Your Mobile Number" class="form-control" value="{{ (!empty($userAddress)) ? $userAddress->mobile : '' }}">
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="name">Province</label>
                                    <select name="province_id" id="province_id" class="form-control">
                                        <option value="">Select a Province</option>
                                        @if ($provinces->isNotEmpty())
                                        @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}" {{ (!empty($userAddress) && $userAddress->province_id == $province->id) ? 'selected' : '' }} >{{ $province->name }}</option>

                                        @endforeach

                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="name">District</label>
                                    <select name="district_id" id="district_id" class="form-control">
                                        <option value="">Select a District</option>
                                        @if (!empty($districts))
                                        @foreach ($districts as $district)
                                        <option value="{{ $district->id }}" {{ (!empty($userAddress) && $userAddress->district_id == $district->id) ? 'selected' : '' }} >{{ $district->name }}</option>

                                        @endforeach

                                        @endif
                                    </select>
                                    <p></p>
                                </div>

                                <div class="mb-3">
                                    <label for="municipality">Municipality</label>
                                    <textarea name="municipality" id="municipality" cols="30" rows="3" placeholder="Muncipality" class="form-control">{{ (!empty($userAddress)) ? $userAddress->municipality : '' }}</textarea>
                                    <p></p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="city">City</label>
                                    <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ (!empty($userAddress)) ? $userAddress->city : '' }}">
                                    <p></p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="house_no">House No.</label>
                                    <input type="text" name="house_no" id="house_no" class="form-control" placeholder="House No" value="{{ (!empty($userAddress)) ? $userAddress->house_no : '' }}">
                                    <p></p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="zip">Zip</label>
                                    <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ (!empty($userAddress)) ? $userAddress->zip : '' }}">
                                    <p></p>
                                </div>
                                <div class="d-flex">
                                    <button class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Delete Account</h2>
                    </div>
                    <div class="d-flex  p-3">
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteAccount()">Delete Account</button>
                    </div>
                </div>
                <!-- Add this modal for confirming update  -->
                <div class="modal fade" id="updateConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="updateConfirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateConfirmationModalLabel">Confirm Profile Update</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to update your profile?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmUpdateBtn">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add this modal for confirming update  -->
                <div class="modal fade" id="addressUpdateConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="addressUpdateConfirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addressUpdateConfirmationModal">Confirm Address Update</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to update your user address?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmAddressUpdateBtn">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add this modal for confirming deleteAccount -->
                <div class="modal fade" id="deleteAccountConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountConfirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteAccountConfirmationModalLabel">Confirm Profile Deletion</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete your account?</p>
                                <form id="deleteAccountForm" action="{{ route('account.deleteAccount') }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteAccountBtn">Delete Account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')

<script>
    $("#profileForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);

        // Show the delete confirmation modal
        $('#updateConfirmationModal').modal('show');

        // Handle the click on the "Delete" button in the modal
        $('#confirmUpdateBtn').click(function() {
            // Close the modal
            $('#updateConfirmationModal').modal('hide');

            $.ajax({
                url: '{{ route('account.updateProfile') }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    $(".error").removeClass('invalid-feedback');
                    $('input[type="text"], input[type="number"]').removeClass('is-invalid');
                    if (response.status == true) {
                        window.location.href = "{{ route('account.profile') }}";

                    } else {
                        console.log('error');
                        var errors = response.errors;
                        $.each(errors, function(key, value){
                            $(`#${key}`).addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value);
                        })
                    }
                }
            })
        });
    })

    $("#addressForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);

        // Show the delete confirmation modal
        $('#addressUpdateConfirmationModal').modal('show');

        // Handle the click on the "Delete" button in the modal
        $('#confirmAddressUpdateBtn').click(function() {
            // Close the modal
            $('#addressUpdateConfirmationModal').modal('hide');

            $.ajax({
                url: '{{ route('account.updateAddress') }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    $(".error").removeClass('invalid-feedback');
                    $('input[type="text"], input[type="number"]').removeClass('is-invalid');
                    if (response.status == true) {
                        window.location.href = "{{ route('account.profile') }}";

                    } else {
                        console.log('error');
                        var errors = response.errors;
                        $.each(errors, function(key, value){
                            $(`#${key}`).addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value);
                        })
                    }
                }
            })
        });
    })



    $("#province_id").change(function(event){
        var province_id = $(this).val();
    
        // Make an AJAX request to fetch districts for the selected province
        $.ajax({
            url: '{{ route('province-districts.index') }}',
            type: 'get',
            data: {province_id: province_id},
            dataType: 'json',
            success: function(response){
                // Remove existing district options, except the first one
                $("#district_id").find("option").not(":first").remove();
    
                // Populate the #district_id select with the new districts
                $.each(response['districts'], function(key, item){
                    $("#district_id").append(`<option value='${item.id}'> ${item.name} </option>`);
                });
            },
            error: function (xhr, status, error) {
                console.log("AJAX Request Failed:", status, error);
            }
        });
    });
    
    function confirmDeleteAccount() {
        // Show the delete account confirmation modal
    
        $('#deleteAccountConfirmationModal').modal('show');
    
        // Handle the click on the "Delete Account" button in the modal
        $('#confirmDeleteAccountBtn').click(function () {
            // Submit the delete account form
            $("#deleteAccountForm").submit();
        });
    }

    
</script>

@endsection


