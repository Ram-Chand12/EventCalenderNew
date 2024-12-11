@extends('template.main')
@section('title', 'Log')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h5 class="m-0">@yield('title')</h5>
                    </div><!-- /.col -->
                    {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div><!-- /.col --> --}}
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        {{-- @if (isset($golf_club))
        @foreach ($golf_club as $golf_club_name)
        @dd($datas)
        @endforeach --}}
        {{-- @endif --}}
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div v class="filters mb-3">
                            <form action="{{ route('log') }}" class="form-inline">
                                <div class="row">
                                    <div class="col-6 d-flex">
                                        {{-- <div class="select_drop_down"> --}}
                                            <select name="club_filter" id="club_filter" class="form-control">
                                                <option value=''>Search by Club</option>
                                                @foreach ($clubs as $club)
                                                    <option value="{{ $club->id }}">{{ $club->club_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{-- <i class="fa-solid fa-chevron-down drop__down"></i> --}}
                                        {{-- </div> --}}
                                        <div class="form-group">
                                            <button class="btn btn-primary ml-2 button-color "
                                                type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card">

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-striped table-bordered table-hover text-center"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Entity type</th>
                                            <th>Club name</th>
                                            <th>Message Type</th>
                                            <th>Message</th>
                                            <th>Created At</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $data)
                                            <tr>
                                                <td>{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                                                </td>
                                                <td>
                                                    <p class="user__name">{{ $data->entity_type }}</p>
                                                </td>
                                                <td>{{ $data->club_name }}</td>
                                                <td>{{ $data->message_type }}</td>
                                                <td>{{ $data->message }}</td>
                                                <td>{{ $data->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <div class="pagination">
                            {{ $logs->links() }}

                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        // $('.drop__down').click((){
        //     $('#club_filter').click();

        // })
    </script>
@endpush
