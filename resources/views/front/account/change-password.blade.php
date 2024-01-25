@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>
                <li class="breadcrumb-item">Change Password</li>
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
                        <h2 class="h5 mb-0 pt-2 pb-2">Change Password</h2>
                    </div>
                    <form action="" method="post" class="changePasswordForm" id="changePasswordForm">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="old_password">Old Password</label>
                                    <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="password">New Password</label>
                                    <input type="password" name="password" id="password" placeholder="New Password" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="form-control">
                                </div>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-dark">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Add this modal for confirming update  -->
            <div class="modal fade" id="updateConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="updateConfirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateConfirmationModalLabel">Confirm Password Change</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to change your password?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmUpdateBtn">Update</button>
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
    $("#changePasswordForm").submit(function(event){
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
                url: '{{ route('account.changePassword') }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    $(".error").removeClass('invalid-feedback');
                    $('input[type="password"]').removeClass('is-invalid');

                    if (response.status == true) {
                        window.location.href = "{{ route('account.showChangePasswordForm') }}";

                    } else {
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


</script>

@endsection
