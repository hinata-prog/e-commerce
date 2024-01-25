@extends('admin.layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Orders</h1>
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
                        <button type="button" onclick="window.location.href='{{ route('orders.index') }}' " class="btn btn-default btn-sm">Reset</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: full;">
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
                            <th width="60">ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th width="100">Payment</th>
                            <th width="100">Status</th>
                            <th width="100">Grand Total</th>
                            <th width="100">Date Purchased</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->isNotEmpty())
                            @foreach ( $orders as $order)
                            <tr>
                                <td><a href="{{ route('orders.detail',$order->id) }}">{{ $order->id }}</a></td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->mobile }}</td>
                                <td>{{ $order->email }}</td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @else
                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @endif
                                </td>
                                <td>
                                    @if($order->status == 'pending')
                                    <span class="badge bg-danger">Pending</span>
                                    @elseif ($order->status == 'shipped')
                                    <span class="badge bg-info">Shipped</span>
                                    @elseif ($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                    @else
                                    <span class="badge bg-danger">Cancelled</span>

                                    @endif

                                </td>
                                <td>{{ $order->grand_total }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-M-Y') }}</td>

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
                <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this category?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('customJs')

<!-- Add this script at the end of your Blade view to handle CSV export -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach click event to the export CSV button
        document.getElementById('exportCsvBtn').addEventListener('click', function () {
            // Get the current route name
            var currentRoute = window.location.pathname.split('/').pop(); // Assuming your route is in the format "/some/path/currentRoute"
        
            // Call the function to export the table as CSV with a dynamic file name
            exportTableToCsv(currentRoute + '_' + getFormattedDateTime() + '.csv');
        });
        
        function getFormattedDateTime() {
            var now = new Date();
            var formattedDateTime =
                now.getFullYear() +
                '-' +
                ('0' + (now.getMonth() + 1)).slice(-2) +
                '-' +
                ('0' + now.getDate()).slice(-2) +
                '_' +
                ('0' + now.getHours()).slice(-2) +
                ':' +
                ('0' + now.getMinutes()).slice(-2) +
                ':' +
                ('0' + now.getSeconds()).slice(-2);
        
            return formattedDateTime;
        }

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


