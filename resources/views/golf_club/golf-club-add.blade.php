@extends('template.main')
@section('title', 'Add Golf Club')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 d-flex">
                        <a href="{{ route('golf-club') }}" class=""><i
                            class="fa-solid fa-arrow-left fa-1x my-1 text-dark"></i>
                        </a>
                        <h5 class="ml-2">@yield('title')</h5>
                    </div><!-- /.col -->
                    {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="/golf-club">Golf Club</a></li>
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
                    <div class="col-md-12">
                        <div class="card">
                            {{-- <div class="card-header">
                                <div class="text-right">
                                    <a href="/golf-club" class="btn btn-warning btn-sm"><i
                                            class="fa-solid fa-arrow-rotate-left"></i>
                                        Back
                                    </a>
                                </div>
                            </div> --}}
                            <form class="needs-validation" action="{{ route('golf-club-insert') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="name" class="field_required">Username</label>
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    placeholder="username" value="{{ old('name') }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="password" class="field_required">Password</label>
                                                <input type="password" min="1" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" placeholder="password" value="{{ old('password') }}"
                                                      required>
                                                @error('password')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="url" class="field_required">Url</label>
                                                <input type="url" name="url"
                                                    class="form-control @error('url') is-invalid @enderror" id="url"
                                                    placeholder="Url" value="{{ old('url') }}" required>
                                                @error('url')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="group_name" class="field_required">Group</label>
                                                <select name="group_name"
                                                    class="form-select @error('group_name') is-invalid @enderror"
                                                    id="group_name" required>
                                                    <option value="" disabled selected>Select a Group</option>

                                                    @foreach ($group_name as $item)
                                                        <option value="{{ $item->id }}" {{ old('group_name') == $item->id ? 'selected' : '' }}>{{ $item->gname }}</option>
                                                    @endforeach

                                                </select>
                                                @error('group_name')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="Club_Name" class="field_required">Club Name</label>
                                                <input type="text" name="Club_Name"
                                                    class="form-control @error('Club_Name') is-invalid @enderror"
                                                    id="Club_Name" placeholder="Club Name" value="{{ old('Club_Name') }}"
                                                    required>
                                                @error('Club_Name')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <div class="form-check">
                                                    <input type="checkbox" name="status_check"
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        id="status" {{ old('status_check') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="status">Active</label>
                                                </div>
                                                @error('status')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-right">
                                    <button class="btn btn-dark reset__button" type="reset"><i
                                            class="fa-solid fa-arrows-rotate"></i>
                                        Reset</button>
                                        <button class="savebutton" type="submit"><i
                                            class="fa-solid fa-floppy-disk"></i>
                                        Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.content -->
                </div>
            </div>
        </div>
    </div>

@endsection