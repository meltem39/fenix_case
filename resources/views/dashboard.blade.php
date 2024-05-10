<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ana Sayfa
        </h2>
    </x-slot>
    <title>Laravel Datatables Tutorial</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container mt-5">
                        <h2 class="mb-4">Satın Alma Kontrol Paneli</h2>
                        <table id="myTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Device Name</th>
                                <th>Paket Adı</th>
                                <th>Kota</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    </body>

                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
                    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
                    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
                    <script type="text/javascript">
                        $(function () {
                            var table = $('#myTable').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: "{{ route('dashboard') }}",
                                columns: [
                                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                    {data: 'user_id', name: 'user_id'},
                                    {data: 'productId', name: 'productId'},
                                    {data: 'quota', name: 'quota'},
                                ]
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
