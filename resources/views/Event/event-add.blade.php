@extends('template.main')
@section('title', 'Add Event')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <a href="{{ route('event') }}" class=""><i class="fa-solid fa-arrow-left fa-1x my-1 text-dark"></i>
                    </a>
                    <h5 class="ml-2">@yield('title')</h5>
                </div><!-- /.col -->
                {{-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/event">Event</a></li>
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
            <div class="card">
                <form class="needs-validation event__form" action="{{ route('insert_event') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="name" class="field_required">Add title</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Add title " value="{{ old('name') }}" required>
                                            @error('name')
                                            <span class="invalid-feedback text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-2">
                                        <label for="name" class="field_required">Description</label>
                                        <textarea name="event_description" placeholder="Product Details"
                                            class="form-control" rows="12" cols="50" required>
                                                {{ old('event_description') }}
                                            </textarea>

                                    </div>
                                    @error('event_description')
                                    <span class="text-danger mb-2">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="accordion mb-2" id="accordionPanelsStayOpenExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                                    aria-controls="panelsStayOpen-collapseOne">
                                                    Excerpt
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseOne"
                                                class="accordion-collapse collapse show"
                                                aria-labelledby="panelsStayOpen-headingOne">
                                                <div class="accordion-body">
                                                    <label for="excerpt">Excerpt</label>
                                                    <textarea name="excerpt" rows="4" cols="50"
                                                        class="w-100">{{ old('excerpt') }}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="accordion mb-2" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne" aria-expanded="false"
                                                    aria-controls="collapseOne">
                                                    Time & Date
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <table cellspacing="0" cellpadding="0" id="EventInfo">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table class="eventtable">
                                                                        <tbody>
                                                                            <tr>

                                                                                <td
                                                                                    class="tribe-datetime-block d-flex gap-3">
                                                                                    <div class="start__date">
                                                                                        <label for="startDate"
                                                                                            class="field_required">Start
                                                                                            Date:</label><br>
                                                                                        <input type="text"
                                                                                            name="startDate"
                                                                                            value="{{ old('startDate') }}"
                                                                                            id="startDate" required />
                                                                                        @error('startDate')
                                                                                        <span
                                                                                            class="invalid-feedback text-danger">{{
                                                                                            $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="end__date">
                                                                                        <label for="endDate"
                                                                                            class="field_required">End
                                                                                            Date:</label><br>
                                                                                        <input type="text"
                                                                                            name="endDate"
                                                                                            value="{{ old('endDate') }}"
                                                                                            id="endDate" required />
                                                                                        <span
                                                                                            class="helper-text hide-if-js"></span>
                                                                                        @error('endDate')
                                                                                        <span
                                                                                            class="invalid-feedback text-danger">{{
                                                                                            $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="event-dynamic-helper">
                                                                                <td class="label"> </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="accordion mb-2" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne1" aria-expanded="false"
                                                    aria-controls="collapseOne1">
                                                    Golf Club
                                                </button>
                                            </h2>

                                            <div id="collapseOne1" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body"
                                                    style="display: flex; flex-direction: column;">
                                                    <div class="event-type">
                                                        <div class="main-event">
                                                            <label for="golf_club" class="field_required">Golf
                                                                Club</label>
                                                            <div class="d-flex">
                                                                <div class="col-md-4 p-0">
                                                                    {{-- <label for="golf_club"
                                                                        class="field_required">Golf
                                                                        Club</label> --}}
                                                                    <select name="golf_club[]" id="undo_redo"
                                                                        class="form-control left_club_select" size="13"
                                                                        multiple="multiple">
                                                                        @foreach ($golf_club as $golf_clubs)
                                                                        <option value="{{ $golf_clubs->id }}"
                                                                            class="left_club_option mb-2">
                                                                            {{ $golf_clubs->club_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4 golf_club_btns p-0">
                                                                    <button type="button" id="undo_redo_rightSelected"
                                                                        class="btn btn-default btn-block"><i
                                                                            class="fa fa-arrow-right"></i></button>
                                                                    <button type="button" id="undo_redo_leftSelected"
                                                                        class="btn btn-default btn-block"><i
                                                                            class="fa fa-arrow-left"
                                                                            aria-hidden="true"></i></button>
                                                                </div>
                                                                <div class="col-md-4 p-0">
                                                                    <select name="selected_golf_club[]"
                                                                        id="undo_redo_to"
                                                                        class="form-control right_club_select" size="13"
                                                                        multiple="multiple">
                                                                        @if (is_array(old('selected_golf_club')))
                                                                        @foreach (old('selected_golf_club') as
                                                                        $selected_id)
                                                                        @foreach ($golf_club as $golf_clubs)
                                                                        @if ($selected_id == $golf_clubs->id)
                                                                        <option class="right_club_option mb-2"
                                                                            value="{{ $golf_clubs->id }}">
                                                                            {{ $golf_clubs->club_name }}
                                                                        </option>
                                                                        @endif
                                                                        @endforeach
                                                                        @endforeach
                                                                        @endif

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p id="group_msg" style="display: none;"><b>Please select at least
                                                            one group</b></p>

                                                </div>
                                            </div>
                                        </div>
                                        @error('selected_golf_club')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="accordion mb-2" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne2" aria-expanded="false"
                                                    aria-controls="collapseOne2">
                                                    Location
                                                </button>
                                            </h2>
                                            <div id="collapseOne2" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <table>
                                                        <tbody>

                                                            <td>

                                                                <label for="venue" class="field_required">Venue</label>
                                                                <select
                                                                    class="form-select @error('venue') is-invalid @enderror"
                                                                    id="venue" name="venue" required>
                                                                    <option disabled selected>Select a venue
                                                                    </option>
                                                                    @foreach ($vanue as $vanues)
                                                                    <option value="{{ $vanues->id }}" {{
                                                                        old('venue')==$vanues->id ? 'selected' : '' }}>
                                                                        {{ $vanues->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('venue')
                                                                <span class="invalid-feedback text-danger">{{ $message
                                                                    }}</span>
                                                                @enderror
                                                            </td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="accordion mb-2" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne3" aria-expanded="false"
                                                    aria-controls="collapseOne3">
                                                    Organizer
                                                </button>
                                            </h2>
                                            <div id="collapseOne3" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <table>
                                                        <tbody>
                                                            <tr class="saved-linked-post">
                                                                <!-- Additional content for saved-linked-post goes here -->
                                                            </tr>
                                                            <tr
                                                                class="linked-post venue tribe-linked-type-venue-country">
                                                                <td>
                                                                    <label for="organizer">Organizer</label>
                                                                    <select
                                                                        class="form-select @error('organizer') is-invalid @enderror"
                                                                        id="organizer" name="organizer">
                                                                        <option value="organizer" disabled selected>
                                                                            Select a Organizer</option>

                                                                        @foreach ($organizer as $organizers)
                                                                        <option value="{{ $organizers->id }}" {{
                                                                            old('organizer')==$organizers->id ?
                                                                            'selected' : '' }}>
                                                                            {{ $organizers->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <!-- Additional content for table footer goes here -->
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="accordion mb-2" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne4" aria-expanded="false"
                                                    aria-controls="collapseOne4">
                                                    Event Website
                                                </button>
                                            </h2>
                                            <div id="collapseOne4" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <table>
                                                        <tbody>
                                                            <tr class="linked-post venue tribe-linked-type-venue-city">
                                                                <label for="url">Url</label>
                                                                <input type="text" name="url" 
                                                                    class="form-control @error('url') is-invalid @enderror"
                                                                    id="url" placeholder="Url" value="{{ old('url') }}">
                                                                @error('url')
                                                                <span class="invalid-feedback text-danger">{{ $message
                                                                    }}</span>
                                                                @enderror
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>



                                <div class="row">
                                    <div class="accordion mb-2" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne5" aria-expanded="false"
                                                    aria-controls="collapseOne5">
                                                    Event Cost
                                                </button>
                                            </h2>
                                            <div id="collapseOne5" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <table id="event_cost" class="eventtable">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2" class="tribe_sectionheader">
                                                                    <label>Event Cost</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Currency Symbol:</td>
                                                                <td class="d-flex gap-2">
                                                                    <input type="text" name="Currency_symbol"
                                                                        class="form-control @error('Currency') is-invalid @enderror"
                                                                        id="Currency" placeholder="Currency Symbol"
                                                                        value="{{ old('Currency_symbol') }}">
                                                                    <select
                                                                        class="form-select @error('cost_type') is-invalid @enderror"
                                                                        id="cost_type" name="cost_type">


                                                                        <option disabled> Select the cost type
                                                                        </option>
                                                                        <option value="1" {{ old('cost_type')=='1'
                                                                            ? 'selected' : '' }}>
                                                                            Before cost </option>
                                                                        <option value="2" {{ old('cost_type')=='2'
                                                                            ? 'selected' : '' }}>
                                                                            After cost</option>
                                                                    </select>
                                                                    @error('cost_type')
                                                                    <span class="invalid-feedback text-danger">{{
                                                                        $message }}</span>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                            <tr class="mt-2">
                                                                <td class="">Cost:</td>
                                                                <td>
                                                                    <input type="number" step="0.01" name="cost"
                                                                        class="form-control @error('cost') is-invalid @enderror"
                                                                        id="cost" placeholder="cost"
                                                                        value="{{ old('cost') }}">
                                                                    @error('cost')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>


                            </div>

                        </div>

                        <div class="col-md-4 col-sm-12 pr-4">
                            <div class="row mt-5">
                                <div class="accordion mb-2" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-toggle="collapse"
                                                data-target="#collapseOne6" aria-expanded="false"
                                                aria-controls="collapseOne6">
                                                Event Categories
                                            </button>
                                        </h2>
                                        <div id="collapseOne6" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <table id="event_cost" class="eventtable">
                                                    <tbody>

                                                        <tr class="linked-post venue tribe-linked-type-venue-city">
                                                            <td>
                                                                <label for="author" class="fw-bold event__label">Event
                                                                    Categories</label>
                                                                {{-- Recursive function to build the category heirarchy
                                                                tree --}}
                                                                {{-- @php
                                                                function buildCategoryTree(
                                                                $categories,
                                                                &$processed = [],
                                                                ) {
                                                                if (count($categories) === 0) {
                                                                return '';
                                                                }

                                                                $html = '<ul class="category-tree mt-2">';
                                                                    foreach ($categories as $category) {
                                                                    if (
                                                                    !in_array(
                                                                    $category['id'],
                                                                    $processed
                                                                    )
                                                                    ) {
                                                                    $processed[] = $category['id'];

                                                                    $checked = in_array(
                                                                    $category['id'],
                                                                    old('event_cat', []),
                                                                    )
                                                                    ? 'checked'
                                                                    : '';

                                                                    $html .= '<li>';
                                                                        $html .=
                                                                        '<input name="event_cat[]" type="checkbox" id="node' .
                                                                                        $category['id'] .
                                                                                        '" value="' .
                                                                                        $category['id'] .
                                                                                        '" ' .
                                                                                        $checked .
                                                                                        '>';
                                                                        $html .=
                                                                        '<label for="node' .
                                                                                        $category['id'] .
                                                                                        '">' .
                                                                            $category['name'] .
                                                                            '</label>';
                                                                        $html .= buildCategoryTree(
                                                                        $category['children'],
                                                                        $processed,
                                                                        ); // Recursive call
                                                                        $html .= '</li>';
                                                                    }
                                                                    }
                                                                    $html .= '</ul>';

                                                                return $html;
                                                                }
                                                                @endphp --}}

                                                                @php
                                                                function buildCategoryTree($categories, $selectedCategories = [], &$processed = []) {
                                                                    if (count($categories) === 0) {
                                                                        return '';
                                                                    }
                                                                
                                                                    $html = '<ul class="category-tree mt-2">';
                                                                    foreach ($categories as $category) {
                                                                        if (!in_array($category['id'], $processed)) {
                                                                            $processed[] = $category['id'];
                                                                
                                                                            $isChecked = in_array($category['id'], $selectedCategories) ? 'checked' : '';
                                                                
                                                                            $html .= '<li>';
                                                                            $html .= '<input name="event_cat[]" type="checkbox" id="node' . $category['id'] . '" value="' . $category['id'] . '" ' . $isChecked . '>';
                                                                            $html .= '<label for="node' . $category['id'] . '">' . $category['name'] . '</label>';
                                                                            $html .= buildCategoryTree($category['children'], $selectedCategories, $processed);
                                                                            $html .= '</li>';
                                                                        }
                                                                    }
                                                                    $html .= '</ul>';
                                                                
                                                                    return $html;
                                                                }
                                                                @endphp
                                                                
                                                    

                                                                <div>
                                                                    {!! buildCategoryTree($categories) !!}
                                                                </div>
                                                                @error('author')
                                                                <span class="invalid-feedback text-danger">{{ $message
                                                                    }}</span>
                                                                @enderror
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div><!-- Allow comment end -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="accordion mb-2" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-toggle="collapse"
                                                data-target="#collapseOne7" aria-expanded="false"
                                                aria-controls="collapseOne7">
                                                Author
                                            </button>
                                        </h2>
                                        <div id="collapseOne7" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <table id="event_cost" class="eventtable">
                                                    <tbody>

                                                        <tr class="linked-post venue tribe-linked-type-venue-city">
                                                            <td>
                                                                <label for="author"
                                                                    class="field_required">Author</label>
                                                                <select
                                                                    class="form-select @error('author') is-invalid @enderror"
                                                                    id="author" name="author" required>
                                                                    <option value="" disabled selected>Select
                                                                        an Author</option>
                                                                    @foreach ($user as $users)
                                                                    <option value="{{ $users->id }}" {{
                                                                        old('author')==$users->id ? 'selected' : '' }}>
                                                                        {{ $users->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('author')
                                                                <span class="invalid-feedback text-danger">{{ $message
                                                                    }}</span>
                                                                @enderror
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div><!-- Allow comment end -->
                                </div>
                            </div>

                            <div class="row">
                                <div class="accordion mb-2" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-toggle="collapse"
                                                data-target="#collapseOne8" aria-expanded="false"
                                                aria-controls="collapseOne8">
                                                Event Status
                                            </button>
                                        </h2>
                                        <div id="collapseOne8" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <table id="event_cost" class="eventtable">
                                                    <tbody>
                                                        <tr class="linked-post venue tribe-linked-type-venue-city">
                                                            <td>
                                                                <label for="status" class="field_required">Event
                                                                    Status</label>
                                                                <div class="form-group">
                                                                    <select
                                                                        class="form-select @error('status') is-invalid @enderror"
                                                                        id="event_status" name="status" required>
                                                                        <option value="" disabled>Select
                                                                            a status</option>
                                                                        <option {{ old('status')=='published'
                                                                            ? 'selected' : '' }} value="published">
                                                                            Published</option>
                                                                        <option {{ old('status')=='draft' ? 'selected'
                                                                            : '' }} value="draft">Draft</option>
                                                                        {{-- <option {{ old('status')=='trash'
                                                                            ? 'selected' : '' }} value="trash">Trash
                                                                        </option> --}}
                                                                    </select>
                                                                    @error('status')
                                                                    <span class="invalid-feedback text-danger">{{
                                                                        $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                {{--
                                                                <div class="form-group" id="publish-option"
                                                                    style="display: none;">
                                                                    <label for="publish_time">Publish Time</label>
                                                                    <div>
                                                                        <input type="radio" id="publish_immediately"
                                                                            name="publish_option"
                                                                            value="publish_immediately" checked>
                                                                        <label for="publish_immediately">Publish
                                                                            Immediately</label>

                                                                    </div> --}}


                                                                    <div class="form-group" id="publish-options"
                                                                        style="display: none;">
                                                                        <label for="publish_time">Publish Time</label>
                                                                        <div>
                                                                            <input type="radio" id="publish_immediately"
                                                                                name="publish_option"
                                                                                value="publish_immediately" {{
                                                                                old('publish_option')=='publish_immediately'
                                                                                ? 'checked' : '' }} checked>
                                                                            <label for="publish_immediately">Publish
                                                                                Immediately</label>
                                                                        </div>
                                                                        <div>
                                                                            <input type="radio" id="publish_later"
                                                                                name="publish_option"
                                                                                value="publish_later" {{
                                                                                old('publish_option')=='publish_later'
                                                                                ? 'checked' : '' }}>
                                                                            <label for="publish_later">Publish at a
                                                                                specific time</label>
                                                                            <input type="datetime-local"
                                                                                id="publish_time" name="publish_time"
                                                                                class="form-control"
                                                                                style="display: none;"
                                                                                value="{{ old('publish_time') }}">
                                                                            <span id="publish_time_error"
                                                                                class="text-danger">
                                                                            </span>
                                                                        </div>


                                                                    </div>
                                                                    {{-- <div>
                                                                        <input type="radio" id="publish_later"
                                                                            name="publish_option" value="publish_later">
                                                                        <label for="publish_later">Publish at a
                                                                            specific time</label>
                                                                        <input type="datetime-local" id="publish_time"
                                                                            name="publish_time" class="form-control"
                                                                            style="display: none;">
                                                                        <span id="publish_time_error"
                                                                            class="text-danger">
                                                                        </span>

                                                                    </div> --}}

                                                                </div>

                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div><!-- Allow comment end -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="accordion mb-2" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-toggle="collapse"
                                                data-target="#collapseOne9" aria-expanded="false"
                                                aria-controls="collapseOne9">
                                                Featured Image
                                            </button>
                                        </h2>
                                        <div id="collapseOne9" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <table id="event_cost" class="eventtable">
                                                    <tbody>

                                                        <tr class="linked-post venue tribe-linked-type-venue-city">
                                                            <td>
                                                                <div class="col-lg-12 event_feature_image d-flex p-0">
                                                                    <div class="form-group">
                                                                        <label for="image">Featured Image</label>
                                                                        <input type="file" name="image"
                                                                            class="form-control-file  @error('image') is-invalid @enderror"
                                                                            id="image">
                                                                        @error('image')
                                                                        <span class="invalid-feedback text-danger">{{
                                                                            $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div><!-- Allow comment end -->
                                </div>
                            </div>
                            <div class=" text-right">
                                {{-- <button class="btn btn-dark mr-1" type="reset"><i
                                        class="fa-solid fa-arrows-rotate"></i>
                                    Reset</button> --}}
                                <button class="savebutton" type="submit" style="width: 110px"><i
                                        class="fa-solid fa-floppy-disk"></i>
                                    Save Event</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /.content -->
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
            // Function to toggle publish time field visibility and validation
            function togglePublishTimeRequired() {
                if ($('#publish_later').prop('checked')) {
                    $('#publish_time').prop('required', true);
                    $('#publish_time').closest('.form-group').show(); // Show the input field's form group
                } else {
                    $('#publish_time').prop('required', false);
                    $('#publish_time').closest('.form-group').hide(); // Hide the input field's form group
                    $('#publish_time').val(''); // Clear the input value
                    $('#publish_time_error').text(''); // Clear any existing error message
                }
            }

            // Initial state on page load
            togglePublishTimeRequired();
            // Handle change in publish option
            $('input[name="publish_option"]').change(function() {
                togglePublishTimeRequired();
            });

            // Reset publish_time validation and field on status change
            $('#event_status').change(function() {
                // Check if status is "draft" and reset publish_time validation and field
                if ($(this).val() === 'draft') {
                    $('#publish_immediately').prop('checked', true); // Select "Publish Immediately"
                    togglePublishTimeRequired(); // Reset publish_time field visibility and validation
                }
            });

            // Handle form submission
            $('form').submit(function(event) {
                // Reset publish_time validation error on submit
                $('#publish_time_error').text('');

                // Reset publish_time validation and field on status change
                $('#event_status').change(function() {
                    // Check if status is "draft" and reset publish_time validation and field
                    if ($(this).val() === 'draft') {
                        $('#publish_immediately').prop('checked',
                            true); // Select "Publish Immediately"
                        togglePublishTimeRequired
                            (); // Reset publish_time field visibility and validation
                    }
                });

                // Handle form submission
                $('form').submit(function(event) {
                    // Reset publish_time validation error on submit
                    $('#publish_time_error').text('');

                    // Check if publish_later is checked and publish_time is empty
                    if ($('#publish_later').prop('checked') && !$('#publish_time').val()) {
                        event.preventDefault(); // Prevent form submission
                        $('#publish_time_error').text(
                            'Please add a Publish time.'); // Display error message
                    }
                });
            });


            //    jQuery(function($) {
            // var startDateValue = "{{ old('startDate') }}";
            // var endDateValue = "{{ old('endDate') }}";

            // console.log(startDateValue);

            // $('#startDate').daterangepicker({
            //     singleDatePicker: true,
            //     timePicker: true,
            //     startDate: startDateValue ? moment(startDateValue, 'M/DD hh:mm A') : moment().startOf('hour'),
            //     locale: {
            //         format: 'M/DD hh:mm A'
            //     }
            // });

            // $('#endDate').daterangepicker({
            //     singleDatePicker: true,
            //     timePicker: true,
            //     startDate: endDateValue ? moment(endDateValue, 'M/DD hh:mm A') : moment().startOf('hour').add(32, 'hour'),
            //     locale: {
            //         format: 'M/DD hh:mm A'
            //     }
            // });

            // $('#startDate').on('apply.daterangepicker', function(ev, picker) {
            //     var newStartDate = picker.startDate;
            //     $('#endDate').daterangepicker({
            //         singleDatePicker: true,
            //         timePicker: true,
            //         startDate: newStartDate.add(1, 'hour'), // Ensuring the end date is at least one hour after the start date
            //         minDate: newStartDate, // Setting the minDate to the selected start date
            //         locale: {
            //             format: 'M/DD hh:mm A'

            //         }
            //     });

            //     $('input[name="publish_option"]').change(function() {
            //         if ($('#publish_later').is(':checked')) {
            //             $('#publish_time').show();
            //         } else {
            //             $('#publish_time').hide();
            //         }
            //     });


            //     var now = new Date();
            //     var year = now.getFullYear();
            //     var month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
            //     var day = now.getDate().toString().padStart(2, '0');
            //     var hours = now.getHours().toString().padStart(2, '0');
            //     var minutes = now.getMinutes().toString().padStart(2, '0');

            //     // Set the minimum datetime value to today's date and time
            //     var minDatetime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;

            //     // Set the minimum attribute of the datetime input field
            //     $('#publish_time').attr('min', minDatetime);


            jQuery(function($) {
                var startDateValue = "{{ old('startDate') }}";
                var endDateValue = "{{ old('endDate') }}";

                $('#event_status').change(function() {
                    var publishOptions = $('#publish-options');
                    if ($(this).val() === 'published') {
                        publishOptions.show();
                    } else {
                        publishOptions.hide();
                    }
                });

                var startDateValue = "{{ old('startDate') }}";
                var endDateValue = "{{ old('endDate') }}";



                $('#startDate').daterangepicker({
    singleDatePicker: true,
    timePicker: true,
    startDate: startDateValue ? moment(startDateValue, 'YYYY-MM-DD hh:mm A') : moment(),
    locale: {
        format: 'YYYY-MM-DD hh:mm A'  // Specify the format you want to display
    }
});


                $('#endDate').daterangepicker({
                    singleDatePicker: true,
                    timePicker: true,
                    startDate: endDateValue ? moment(endDateValue, 'YYYY-MM-DD hh:mm A') : moment(),
                    locale: {
                        format: 'YYYY-MM-DD hh:mm A'
                    }
                });


                // $('#startDate').daterangepicker({
                //     singleDatePicker: true,
                //     timePicker: true,
                //     startDate: startDateValue ? moment(startDateValue, 'M/DD hh:mm A') : moment()
                //         .startOf(
                //             'hour'),
                //     locale: {
                //         format: 'M/DD hh:mm A'
                //     }
                // });

                // $('#endDate').daterangepicker({
                //     singleDatePicker: true,
                //     timePicker: true,
                //     startDate: endDateValue ? moment(endDateValue, 'M/DD hh:mm A') : moment()
                //         .startOf('hour')
                //         .add(32, 'hour'),
                //     locale: {
                //         format: 'M/DD hh:mm A'

                //     }

                // });

            });
        });



        var publishOptions = $('#publish-options');
        var eventStatus = $('#event_status');

        // Initial check
        if (eventStatus.val() === 'published') {
            publishOptions.show();
        } else {
            publishOptions.hide();
        }

        eventStatus.change(function() {
            if ($(this).val() === 'published') {
                publishOptions.show();

            } else {
                publishOptions.hide();

            }
        });

        $('input[name="publish_option"]').change(function() {
            if ($('#publish_later').is(':checked')) {
                $('#publish_time').show();
            } else {
                $('#publish_time').hide();
            }
        });

        var now = new Date();
        var year = now.getFullYear();
        var month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
        var day = now.getDate().toString().padStart(2, '0');
        var hours = now.getHours().toString().padStart(2, '0');
        var minutes = now.getMinutes().toString().padStart(2, '0');

        // Set the minimum datetime value to today's date and time
        var minDatetime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;

        // Set the minimum attribute of the datetime input field
        $('#publish_time').attr('min', minDatetime);
</script>
@endpush