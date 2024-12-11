@extends('template.main')
@section('title', 'Add Venue')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 d-flex">

                        <a href="{{route('vanue')}}" class=""><i class="fa-solid fa-arrow-left fa-1x my-1 text-dark"></i>
                        </a>


                            <h5 class="ml-1">@yield('title')</h5>
                    </div><!-- /.col -->
                    {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="/vanue">Venue</a></li>
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
                                    <a href="/vanue" class="btn btn-warning btn-sm"><i
                                            class="fa-solid fa-arrow-rotate-left"></i>
                                        Back
                                    </a>
                                </div>
                            </div> --}}
                            <form class="needs-validation" action="{{ route('insert_vanue') }}" method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="name" class="field_required">Name</label>
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    placeholder="Name " value="{{ old('name') }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="vanue_description">Venue description</label>
                                                <input type="text" name="vanue_description"
                                                    class="form-control @error('vanue_description') is-invalid @enderror"
                                                    id="vanue_description" placeholder="Vanue description "
                                                    value="{{ old('vanue_description') }}">
                                                @error('vanue_description')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="city" class="field_required">City</label>
                                                <input type="text" name="city"
                                                    class="form-control @error('city') is-invalid @enderror" id="city"
                                                    placeholder="city " value="{{ old('city') }}" required>
                                                @error('city')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- @dd(old('country')) --}}
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="country" class="field_required">Country</label>
                                                <select class="form-select @error('country') is-invalid @enderror"
                                                    id="country" name="country" required>
                                                    <option value="" disabled selected>Select a country</option>

                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->country_name }}"
                                                            {{ old('country') == $country->country_name ? 'selected' : '' }}>
                                                            {{ $country->country_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('country')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>



                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="state" class="field_required">State</label>
                                                <select class="form-select @error('state') is-invalid @enderror"
                                                    id="state" name="state" required>
                                                    <option value="" disabled selected>Select a state</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->Name }}"
                                                            {{ old('state') == $state->Name ? 'selected' : '' }}>
                                                            {{ $state->Name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('state')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="postal_code" class="field_required">Postal code</label>
                                                <input type="number" name="postal_code"
                                                    class="form-control @error('postal_code') is-invalid @enderror"
                                                    id="postal_code" placeholder="Postal code"
                                                    value="{{ old('postal_code') }}" required maxlength="6">
                                                @error('postal_code')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>



                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <div class="form-group">
                                                <label for="phone" class="field_required">Phone</label>
                                                <input type="number" name="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    id="phone" placeholder="Phone" value="{{ old('phone') }}"
                                                    required minlength="10" maxlength="14">
                                                @error('phone')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="website" class="field_required">Website</label>
                                                <input type="text" name="website"
                                                    class="form-control @error('website') is-invalid @enderror"
                                                    id="website" placeholder="website " value="{{ old('website') }}"
                                                    required>
                                                @error('website')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="address" class="field_required">Address</label>
                                                <input type="text" name="address"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    id="address" placeholder="address " value="{{ old('address') }}"
                                                    required>
                                                @error('address')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <div class="form-check">
                                                    <input type="checkbox" name="status"
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        id="status" {{ old('status') ? 'checked' : '' }}>
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
