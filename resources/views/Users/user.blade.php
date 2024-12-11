@extends('template.main')
@section('title', 'Users')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h5 class="mt-1">@yield('title')</h5>
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
                                    <a href="{{ route('create_user') }}" class="btn btn-primary"><i
                                            class="fa-solid fa-plus"></i> Add
                                        User</a>
                                </div>
                            </div>

                            <div class="card-body">
                                <table id="example1" class="table table-striped table-bordered table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            
                                            <th>Status</th>
                                            <th class = "action-column">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $data)
                                            <tr>
                                                <td class="align-middle text-center">
                                                    {{ ($user->currentPage() - 1) * $user->perPage() + $loop->iteration }}
                                                </td>
                                                <td>
                                                    <p class="user__name m-0">{{ $data->name }}</p>
                                                </td>
                                                <td class="align-middle text-center">{{ $data->email }}</td>
                                                {{-- <td>{{ $data->role }}</td> --}}
                                                <td class="align-middle text-center">
                                                    @if ($data->status == true)
                                                        <i class="fa-solid fa-circle-check" style="color: #009e28;"></i></i>
                                                    @else
                                                        <i class="fa-solid fa-circle-xmark" style="color: #ff0000;"></i></i>
                                                    @endif
                                                </td>

                                                <td class="table_action_col align-middle text-center">
                                                    <form class="d-inline"
                                                        action="{{ route('user_edit', ['id' => $data->id]) }}"
                                                        method="GET">
                                                        <button type="submit" class="action_button border-0  btn-sm mr-1">
                                                            <i class="fa-solid fa-pen"></i></button>
                                                    </form>

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
