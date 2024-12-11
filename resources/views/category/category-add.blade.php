@extends('template.main')
@section('title', 'Add Category')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 d-flex">
                        <a href="{{route('category')}}" class=""><i class="fa-solid fa-arrow-left fa-1x my-1 text-dark"></i>
                        </a>
                        <h5 class="ml-2">@yield('title')</h5>
                    </div><!-- /.col -->
                    {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="/category">Category</a></li>
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
                                    <a href="/category" class="btn btn-warning btn-sm"><i
                                            class="fa-solid fa-arrow-rotate-left"></i>
                                        Back
                                    </a>
                                </div>
                            </div> --}}
                            <form class="needs-validation" action="{{ route('insert_category') }}" method="post">
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
                                                <label for="parent">Parent</label>
                                                <select name="parent" class="form-select @error('parent') is-invalid @enderror" id="parent">
                                                    <option value="" selected>None</option>
                                                    @foreach ($categoriesData as $categoryId => $category)
                                                        <option value="{{ $categoryId }}" @if (old('parent') == $categoryId) selected @endif>{{ $category }}</option>
                                                    @endforeach
                                                </select>
                                                @error('parent')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <div class="form-check">
                                                        <input type="checkbox" name="status"
                                                            class="form-check-input @error('status') is-invalid @enderror"
                                                            id="status" {{ old('status') ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="status">Active</label>
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
