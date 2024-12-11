@extends('template.main')
@section('title', 'Notification')
@section('content')

<div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="mt-1">@yield('title')</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    {{-- @foreach ($notificationLogs as $data)

@dd( $data);
    @endforeach --}}

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="text-right">
                                <a href="{{ route('notification') }}"></a>
                            </div>
                        </div>

                        <div class="card-body notification-card-body">
                            <table id="example1" class="table table-striped table-bordered table-hover text-center" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Entity type</th>
                                        <th>Club name</th>
                                        <th>Club ID</th>
                                        <th>Message</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notificationLogs as $data)
                                        <tr>
                                            <td>{{ ($notificationLogs->currentPage() - 1) * $notificationLogs->perPage() + $loop->iteration }}</td>
                                            <td><p class="user__name">{{ $data->entity_type }}</p></td>
                                            <td>{{ $data->club_name }}</td>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->message }}</td>
                                            <td>{{ $data->created_at }}</td>
                                            <td>
                                                <button class="btn btn-success mark-as-read mb-2" data-id="{{ $data->id }}" style="font-size: 13px;" >Mark as Read</button>
                                                <button class="btn btn-primary mark" data-id="{{ $data->id }}" style="font-size: 13px; display: none;" >Mark</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="pagination">
                    {{ $notificationLogs->links() }}
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
</div>
@endsection





