@extends('template.main')
@section('title', 'Edit Wordpress User')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 d-flex">
                        <a href="{{ route('wordpress_user') }}" class=""><i
                            class="fa-solid fa-arrow-left fa-1x my-1 text-dark"></i>
                        </a>
                        <h5 class="ml-2">@yield('title')</h5>
                    </div><!-- /.col -->
                    {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="/wordpress-users">Wordpress Users</a></li>
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
                                <a href="/wordpress-users" class="btn btn-warning btn-sm"><i class="fa-solid fa-arrow-rotate-left"></i>
                                    Back
                                </a>
                            </div>
                        </div> --}}
                            <form class="needs-validation" action="{{ route('wordpress_update_user') }}" method="POST">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">

                                            {{-- hidden user type  --}}
                                            <input type="text" name="user_type"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                value="wordpress user" hidden>


                                            <div class="form-group">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <label for="name" class="field_required">User Name</label>
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    placeholder="User name" value="{{ $data->name }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email" class="field_required">Email Address</label>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    placeholder="Email" value="{{ $data->email }}" required>
                                                @error('email')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                                @if (session('error'))
                                                    <span class="text-danger">
                                                        {{ session('error') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="fname" class="field_required">First Name</label>
                                                <input type="fname" name="fname"
                                                    class="form-control @error('fname') is-invalid @enderror" id="fname"
                                                    placeholder="First Name" value="{{ $data->fname }}" required>
                                                @error('fname')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="lname" class="field_required">Last Name</label>
                                                <input type="lname" name="lname"
                                                    class="form-control @error('lname') is-invalid @enderror" id="lname"
                                                    placeholder="Last Name" value="{{ $data->lname }}" required>
                                                @error('lname')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="password" class="field_required">Password</label>
                                                <input type="password" min="1" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" placeholder="Password">
                                                @error('password')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- @dd(old('role')) --}}
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="role" class="field_required">Select Role</label>
                                                <select class="form-select @error('role') is-invalid @enderror"
                                                    id="role" name="role" required>
                                                    <option value="" disabled>Select Role</option>
                                                    <option value="administrator"
                                                        {{ $data->role == 'administrator' ? 'selected' : '' }}>
                                                        Administrator</option>
                                                </select>
                                                @error('role')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>


                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <div class="form-check">
                                                    <input type="checkbox" name="status_check"
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        id="status" {{ $data->status == true ? 'checked' : '' }}>
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
