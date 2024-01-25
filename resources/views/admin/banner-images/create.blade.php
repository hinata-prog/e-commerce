@extends('admin.layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Banner Image</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('banner-images.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="{{ route('banner-images.store') }}" method="post" name="bannerImageForm" id="bannerImageForm">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="product-gallery">

                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection


@section('customJs')

<script>

    Dropzone.autoDiscover = false;
    const dropzone = new Dropzone("#image", {
        url: "{{ route('temp-images.create') }}",
        maxFiles: 10,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response){
            var html = `<div class="col-md-3" id="image-row-${response.image_id}">
                        <input type="hidden" name="image_array[]" value="${response.image_id}">
                        <div class="card" >
                            <img src="${response['imagePath']}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <a href="javascript:void(0)" onClick="deleteImage('${response.image_id}')" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                        </div>`;

            $("#product-gallery").append(html);
        },
        removedfile: function(file){
            var id = $(file.previewElement).find("[name='image_array[]']").val();
            deleteImage(id);
            $(file.previewElement).remove();
        }
    });

    function deleteImage(id) {
        // Make an AJAX request to delete the image
        $.ajax({
            url: '{{ route('temp-images.delete') }}',
            method: 'DELETE',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status === true) {
                    // If deletion is successful, remove the HTML element from the DOM
                    $("#image-row-" + id).remove();
                } else {
                    // Handle errors or show a message to the user
                    alert(response.message);
                }
            },
            error: function (error) {
                // Handle AJAX errors
                console.error('AJAX request failed', error);
            }
        });
    }

    $("#bannerImageForm").submit(function(event){
        event.preventDefault();
        var formArray = $(this).serializeArray();
        $("button[type=submit]").prop('disabled',true);

        $.ajax({
            url: '{{ route("banner-images.store") }}',
            type: 'post',
            data: formArray,
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);

                if (response["status"] == true) {
                    $(".error").removeClass('invalid-feedback');
                    $('input[type="text"], select').removeClass('is-invalid');
                    window.location.href = "{{ route('banner-images.index') }}";
                } else {
                    alert(response["message"]);
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Request Failed:", status, error);
            }
        });
    });

</script>

@endsection
