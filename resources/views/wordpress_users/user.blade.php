@extends('template.main')
@section('title', 'wordpress users')
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
                            <li class="breadcrumb-item active">Wordpress User</li>
                        </ol>
                    </div><!-- /.col --> --}}
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        {{-- @foreach ($user as $data)

        @dd($data)

        @endforeach --}}

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="text-right">
                                    <a href="{{ route('wordpress_create_user') }}" class="btn btn-primary"><i
                                            class="fa-solid fa-plus"></i> Add
                                        User</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body user_listing">
                                <div class="modal fade importModal1 history-model" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Added modal-lg class for large width -->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="importModalLabel">User History</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Club Name</th>
                                                                <th>Created on</th>
                                                                <th>Updated on</th>
                                                                <th>Last message</th>
                                                                <th>No of tries</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <!-- Add more rows as needed -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <!-- Footer content if needed -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <table id="example1" class="table table-striped table-bordered table-hover text-center"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $data)
                                            <tr>
                                                <td>{{ ($user->currentPage() - 1) * $user->perPage() + $loop->iteration }}</td>
                                                <td>
                                                    <p class="user__name">{{ $data->name }}</p>
                                                </td>
                                                <td>{{ $data->email }}</td>
                                                <td>{{ $data->role }}</td>
                                                <td>
                                                    @if ($data->status == true)
                                                        <i class="fa-solid fa-circle-check" style="color: #009e28;"></i></i>
                                                    @else
                                                        <i class="fa-solid fa-circle-xmark" style="color: #ff0000;"></i></i>
                                                    @endif
                                                </td>

                                                <td class="table_action_col">
                                                    <form class="d-inline"
                                                        action="{{ route('wordpress_user_edit', ['id' => $data->id]) }}"
                                                        method="GET">
                                                        <button type="submit" class="action_button border-0 btn-sm mr-1">
                                                            <i class="fa-solid fa-pen"></i></button>
                                                    </form>
                                                
                                                    <button type="button" class="action_button border-0 get_hostory w-25"   data-bs-toggle="modal" data-bs-target="#importModal" data-ref-id="{{$data->id}}" data-entity-type="user">
                                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                                    </button>
                                                   
                                                    {{-- <form class="d-inline deleteeddddddd"
                                                        action="{{ route('wordpress_delete_user', ['id' => $data->id_user]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            id="btn-delete"><i class="fa-solid fa-trash-can"></i> Delete1
                                                        </button>
                                                    </form> --}}

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <div class="pagination">
                            {{ $user->links() }}

                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
        </div>
    </div>

@endsection
