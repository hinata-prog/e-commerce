@extends('admin.layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Discount Coupon</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('coupons.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="discountCodeForm" name="discountCodeForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code">Code</label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Code">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Coupon Code Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Coupon Code Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses">Max Uses</label>
                                <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses_user">Max Uses User</label>
                                <input type="number" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max Uses User">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="fixed">Fixed</option>
                                    <option value="percent">Percent</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_amount">Discount Amount</label>
                                <input type="number" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_amount">Min Amount</label>
                                <input type="number" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="starts_at">Starts At</label>
                                <input type="datetime" name="starts_at" id="starts_at" autocomplete="off" class="form-control" placeholder="Starts At">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expires_at">Expires At</label>
                                <input type="datetime" name="expires_at" id="expires_at" autocomplete="off" class="form-control" placeholder="Expires At">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea type="text" name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route("coupons.index") }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('customJs')
<script>
$("#discountCodeForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);

    $.ajax({
        url:'{{ route('coupons.store') }}',
        type:'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);
            if (response["status"] == true) {
                $(".error").removeClass('invalid-feedback');
                $('input[type="text"], select,textarea').removeClass('is-invalid');
                window.location.href = "{{ route('coupons.index') }}";


            } else {
                var errors = response.errors;
                handleFieldError('name', errors);
                handleFieldError('code', errors);
                handleFieldError('description', errors);
                handleFieldError('type', errors);
                handleFieldError('max_uses',errors);
                handleFieldError('max_uses_user', errors);
                handleFieldError('discount_amount', errors);
                handleFieldError('discount_code', errors);
                handleFieldError('min_amount', errors);
                handleFieldError('status',errors);
                handleFieldError('expires_at',errors);
                handleFieldError('starts_at',errors);
            }
        },
        error: function(jqXHR, exception){
            console.log("Something went wrong!");
        }
    })
});

function handleFieldError(fieldName, errors) {
    var fieldElement = $("#" + fieldName);
    var errorElement = fieldElement.siblings('p');

    if (errors[fieldName]) {
        fieldElement.addClass('is-invalid');
        errorElement.addClass('invalid-feedback').html(errors[fieldName][0]);
    } else {
        fieldElement.removeClass('is-invalid');
        errorElement.removeClass('invalid-feedback').html("");
    }
}


    $(document).ready(function(){
        $('#starts_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });

        $('#expires_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });
    });



</script>
@endsection