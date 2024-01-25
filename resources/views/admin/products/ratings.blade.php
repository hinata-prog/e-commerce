@extends('admin.layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Ratings</h1>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route('products.productRatings') }}' " class="btn btn-default btn-sm">Reset</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 350px;">
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                            <button type="button" class="btn btn-success ml-3" id="exportCsvBtn">
                                <i class="fas fa-download"></i> Export CSV
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($ratings))
                        @foreach ($ratings as $rating )
                        <tr>
                            <td>{{ $rating->productTitle }}</td>
                            <td>{{ $rating->rating }}</td>
                            <td>{{ $rating->comment }}</td>
                            <td>{{ $rating->username }}</td>
                            <td>{{ $rating->email }}</td>
                            <td>
                                @if ($rating->status == 1)
                                <a href="javascript:void(0);" onclick="changeStatus(0,'{{ $rating->id }}');">
                                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                                @else
                                <a href="javascript:void(0);" onclick="changeStatus(1,'{{ $rating->id }}');">
                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                                @endif
                            </td>

                        </tr>
                        @endforeach

                        @else
                            <tr>
                                <td colspan="5">Records not found</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
                <!-- Add this modal for confirming deletion  -->
                <div class="modal fade" id="changeConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="changeConfirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="changeConfirmationModalLabel">Confirm Status Change</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to change this status?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmChangeBtn">Change</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{ $ratings->links() }}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('customJs')

<script>
    function changeStatus(status,id) {
        var url = '{{ route('products.changeRatingStatus', "ID") }}';
        var newUrl = url.replace("ID", id);

        // Show the delete confirmation modal
        $('#changeConfirmationModal').modal('show');

        // Handle the click on the "Change" button in the modal
        $('#confirmChangeBtn').click(function() {
            // Close the modal
            $('#changeConfirmationModal').modal('hide');

            // Perform the delete action
            $.ajax({
                url: newUrl,
                type: 'get',
                data: {status: status, id:id},
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ route('products.productRatings') }}";
                }
            });
        });
    }


    document.addEventListener('DOMContentLoaded', function () {
        // Attach click event to the export CSV button
        document.getElementById('exportCsvBtn').addEventListener('click', function () {
            // Call the function to export the table as CSV
            exportTableToCsv('Ratings.csv');
        });

        function exportTableToCsv(filename) {
            const csv = [];

            // Select thead row directly
            const rowHead = document.querySelector('table thead tr');

            // Process the th elements in thead
            const colsHead = rowHead.querySelectorAll('th');
            const rowDataHead = Array.from(colsHead).map(col => col.innerText.trim());
            csv.push(rowDataHead.join(','));

            // Select tbody rows
            const rowsBody = document.querySelectorAll('table tbody tr');
            rowsBody.forEach(function (row) {
                const cols = row.querySelectorAll('td, th');
                const rowData = Array.from(cols).map(col => col.innerText.trim());
                csv.push(rowData.join(','));
            });

            const csvContent = 'data:text/csv;charset=utf-8,' + csv.join('\n');
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    });
</script>

@endsection
