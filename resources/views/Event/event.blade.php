@extends('template.main')
@section('title', 'Event')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h5 class="m-0">@yield('title')</h5>
                    </div><!-- /.col -->
                     
                    <!-- /.col -->
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
                        <div class="filters mb-3">
                            <form action="{{ route('event') }}" class="form-inline">
                                <div class="row">
                                    <div class="col-6 d-flex">
                                        <select name="club_filter" id="club_filter" class="form-control">
                                            <option value=''>Search by Club</option>
                                            @foreach ($event_clubs as $club)
                                                <option value="{{ $club->club_name }}">{{ $club->club_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="status_filter" id="status_filter" class="form-control ml-2">
                                            <option value=''>Search by status</option>
                                            {{-- @foreach ($event_status as $status) --}}
                                            <option value="publish_later">Publish Later</option>
                                            <option value="publish_immediately">Publish Immediately</option>
                                            <option value="draft">Draft</option>
                                            <option value="trash">Trash</option>
                                            {{-- @endforeach --}}
                                        </select>
                                        <div class="form-group">
                                            <button class="btn btn-primary ml-2 button-color "
                                                type="submit">Search</button>
                                        </div>
                                    </div>
                                    {{-- <div class="col-6 d-flex p-0">
                                        <div class="start__date">
                                            <input type="text" name="startDate" class="form-control"
                                                value="{{ old('startDate') }}" id="startDate" />
                                        </div>
                                        <div class="end__date">
                                            <input type="text" class="form-control" name="endDate"
                                                value="{{ old('endDate') }}" id="endDate" />
                                            <span class="helper-text hide-if-js"></span>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary ml-2" type="submit">Search</button>
                                        </div>
                                    </div> --}}
                                </div>
                            </form>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="text-right">
                                    <a href="{{ route('create_event') }}" class="btn btn-primary"><i
                                            class="fa-solid fa-plus"></i>
                                        Event</a>
                                </div>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-striped table-bordered table-hover text-center"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Clubs</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @if (isset($events))

                                            @foreach ($events as $index => $data)
                                                <tr>
                                                    <td>{{ ($events->currentPage() - 1) * $events->perPage() + $index + 1 }}
                                                    </td>
                                                    <td>{{ $data->event_name }}</td>
                                                    {{-- <td>{{ strip_tags($data->description) }}</td> --}}
                                                    {{-- @foreach ($clubNames as $event_id => $club_names)
                                                        @if ($loop->parent->index + 1 == $loop->iteration)
                                                            <td>
                                                                @foreach ($club_names as $index => $club_name)
                                                                    {{ $club_name }}
                                                                    @if ($index < count($club_names) - 1)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                        @endif
                                                    @endforeach --}}
                                                    <td>{{ $data->club_names }}</td>

                                                    <td>{{ $data->event_status }}</td>

                                                    <td class="table_action_col align-middle text-center">
                                                        <form class="d-inline"
                                                            action="{{ route('event_edit', ['id' => $data->id]) }}"
                                                            method="get">
                                                            <button type="submit"
                                                                class="action_button border-0 btn-sm mr-1">
                                                                <i class="fa-solid fa-pen"></i>
                                                        </form>
                                                        {{-- <form class="d-inline"
                                                            action="{{ route('event-delete', ['id' => $data->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="action_button border-0 btn-sm"
                                                                id="btn-delete">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </form> --}}
                                                        <form id="delete-form-{{ $data->id }}" class="d-inline"
                                                            action="{{ route('event-delete', ['id' => $data->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="action_button border-0 btn-sm"
                                                                id="btn-delete-{{ $data->id }}" style="display: none;">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </form>

                                                        <a href="#" class="text-decoration-none text-dark"
                                                            onclick="event.preventDefault(); confirmDelete({{ $data->id }});">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a>

                                                        <button type="button"
                                                            class="action_button border-0 w-25 get_hostory"
                                                            data-bs-toggle="modal" data-bs-target="#importModal"
                                                            data-ref-id="{{ $data->id }}" data-entity-type="events">

                                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>

                        <div class="card-body user_listing">
                            <div class="modal fade importModal1 history-model" id="importModal" tabindex="-1"
                                aria-labelledby="importModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <!-- Added modal-lg class for large width -->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="importModalLabel">Event History</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
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


                                        <!-- /.row -->
                                    </div><!-- /.container-fluid -->
                                   
                                </div>
                                <!-- /.content -->
                               
                            </div>
                            <div class="pagination">
                                {{ $events->links() }}

                            </div>
                        </div>
                    </div>

                @endsection
                @push('scripts')
                    <script>
                        jQuery(function($) {
                            $('#startDate').daterangepicker({
                                singleDatePicker: true,
                                timePicker: true,
                                startDate: moment().startOf('hour'),
                                locale: {
                                    format: 'M/DD hh:mm A'
                                }
                            });

                            $('#endDate').daterangepicker({
                                singleDatePicker: true,
                                timePicker: true,
                                startDate: moment().startOf('hour').add(32, 'hour'),
                                locale: {
                                    format: 'M/DD hh:mm A'
                                }
                            });

                        });

                        function confirmDelete(id) {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('delete-form-' + id).submit();
                                }
                            })
                        }
                    </script>
                @endpush
