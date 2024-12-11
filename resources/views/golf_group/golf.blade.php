@extends('template.main')
@section('title', 'Golf Group')
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
                                    <a href="{{ route('golf-group-adds') }}" class="btn btn-primary"><i
                                            class="fa-solid fa-plus"></i>
                                        Golf Group</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-striped table-bordered table-hover text-center"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Group Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @if (isset($golf_group))
                                            @foreach ($golf_group as $data)
                                                <tr>
                                                    <td class="align-middle text-center">
                                                        {{ ($golf_group->currentPage() - 1) * $golf_group->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td class="word__break align-middle text-center">{{ $data->gname }}
                                                    </td>
                                                    <td>
                                                        @if (!empty($data->image))
                                                            <img src="{{ asset($data->image) }}" class="circular-image-list"
                                                                style="height: 30px">
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        @if ($data->status == true)
                                                            <i class="fa-solid fa-circle-check"
                                                                style="color: #009e28;"></i></i>
                                                        @else
                                                            <i class="fa-solid fa-circle-xmark"
                                                                style="color: #ff0000;"></i></i>
                                                        @endif
                                                    </td>
                                                    <td class="table_action_col align-middle text-center">
                                                        <form class="d-inline"
                                                            action="{{ route('group_edit', ['id' => $data->id]) }}"
                                                            method="get">
                                                            <button type="submit"
                                                                class="action_button border-0 btn-sm mr-1">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </button>
                                                        </form>
                                                        <form class="d-inline"
                                                            action="{{ route('golf-group-delete', ['id' => $data->id]) }}"
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
                            {{ $golf_group->links() }}

                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
        </div>
    </div>

@endsection
