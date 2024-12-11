@extends('template.main')
@section('title', 'Golf Club')
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

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="text-right">
                                    <a href="{{ route('golf-club-adds') }}" class="btn btn-primary"><i
                                            class="fa-solid fa-plus"></i>
                                        Golf Club</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-striped table-bordered table-hover text-center"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Username</th>
                                            <th>Url</th>
                                            <th>Club Name</th>
                                           
                                            <th>Status</th>


                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @if (isset($golf_club))

                                            @foreach ($golf_club as $data)
                                                <tr>
                                                    <td>{{ ($golf_club->currentPage() - 1) * $golf_club->perPage() + $loop->iteration }}</td>
                                                    <td>{{ $data->username }}</td>
                                                    <td>{{ $data->url }}</td>
                                                    <td> {{ $data->club_name }}</td>
                                                 
                                                    <td>
                                                        @if ($data->status == true)
                                                            <i class="fa-solid fa-circle-check"
                                                                style="color: #009e28;"></i></i>
                                                        @else
                                                            <i class="fa-solid fa-circle-xmark"
                                                                style="color: #ff0000;"></i></i>
                                                        @endif
                                                    </td>


                                                    <td class="table_action_col">
                                                        <a type="submit"
                                                            href="{{ route('golf_club.edit', ['id' => $data->id]) }}"
                                                            class="action_button border-0 btn-sm mr-1">
                                                            <i class="fa-solid fa-pen text-dark"></i>
                                                        </a>

                                                        <form class="d-inline"
                                                            action="{{ route('golf_club_delete', ['id' => $data->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                             <button type="submit" class="action_button border-0 btn-sm"
                                                                id="btn-delete">
                                                                <i class="fa-solid fa-trash-can"></i> 
                                                            </button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="pagination">
                            {{ $golf_club->links() }}

                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
        </div>
    </div>

@endsection
