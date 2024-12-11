@extends('template.main')
@section('title', 'Edit Event')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 d-flex">
                        <a href="{{ route('event') }}" class=""><i
                                class="fa-solid fa-arrow-left fa-1x my-1 text-dark"></i>
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
        {{-- @foreach ($organised_event_meta as $combinedItemsc) --}}


        {{-- @endforeach --}}
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="card">
                    <form class="needs-validation event__form" action="{{ route('event-updates') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">

                                {{-- <div class="card-header">
                                <div class="text-right">
                                    <a href="/vanue" class="btn btn-warning btn-sm"><i
                                            class="fa-solid fa-arrow-rotate-left"></i>
                                        Back
                                    </a>
                                </div>
                            </div> --}}


                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="hidden" name="id" value="{{ $event->id }}">
                                                <label for="name" class="field_required">Add title</label>
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    placeholder="Add title " value="{{ $event->name }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <label for="name" class="field_required">Description</label>
                                            <textarea name="event_description" placeholder="Product Details" class="form-control" rows="12" cols="50"
                                                required>{{ $event->description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="accordion mb-2" id="accordionPanelsStayOpenExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                                        aria-controls="panelsStayOpen-collapseOne">
                                                        Excerpt
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseOne"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingOne">
                                                    <div class="accordion-body">
                                                        <label for="name">Excerpt</label>
                                                        <textarea name="excerpt" rows="4" cols="50" class="w-100 mt-4">{{ isset($organised_event_meta['Excerpt']) ? $organised_event_meta['Excerpt'] : '' }}
                                                        </textarea>

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
                                                                                    {{-- @dd($organised_event_meta['startDate']); --}}
                                                                                    <td
                                                                                        class="tribe-datetime-block d-flex gap-3">
                                                                                        <div class="start__date">
                                                                                            <label for="startDate"
                                                                                                class="field_required">Start
                                                                                                Date:</label><br>
                                                                                            <input type="text"
                                                                                                name="startDate"
                                                                                                id="startDate"
                                                                                                value="{{ $organised_event_meta['Start_date'] }}"
                                                                                                required />
                                                                                        </div>
                                                                                        <div class="end__date">
                                                                                            <label for="endDate"
                                                                                                class="field_required">End
                                                                                                Date:</label><br>
                                                                                            <input type="text"
                                                                                                name="endDate"
                                                                                                id="endDate"
                                                                                                value="{{ $organised_event_meta['End_date'] }}"
                                                                                                required />
                                                                                            <span
                                                                                                class="helper-text hide-if-js"></span>
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
                                                    <button class="accordion-button" type="button"
                                                        data-toggle="collapse" data-target="#collapseOne1"
                                                        aria-expanded="false" aria-controls="collapseOne1">
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
                                                                        <select name="golf_club[]" id="undo_redo"
                                                                            class="form-control left_club_select" size="13"
                                                                            multiple="multiple">
                                                                            @foreach ($golf_club as $golf_club_update)
                                                                                <option
                                                                                class="left_club_option mb-2"
                                                                                    value="{{ $golf_club_update->id }}">
                                                                                    {{ $golf_club_update->club_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-4 golf_club_btns">
                                                                        <button type="button"
                                                                            id="undo_redo_rightSelected"
                                                                            class="btn btn-default btn-block"><i
                                                                                class="fa fa-arrow-right"></i></button>
                                                                        <button type="button" id="undo_redo_leftSelected"
                                                                            class="btn btn-default btn-block"><i
                                                                                class="fa fa-arrow-left"
                                                                                aria-hidden="true"></i></button>
                                                                    </div>

                                                                    <div class="col-md-4 p-0">
                                                                        <select name="selected_golf_club[]"
                                                                            id="undo_redo_to" class="form-control right_club_select"
                                                                            size="13" multiple="multiple">
                                                                            @foreach ($selected_event_clubs as $selected_event_club)
                                                                                <option
                                                                                class="right_club_option mb-2"
                                                                                    value="{{ $selected_event_club->id }}">
                                                                                    {{ $selected_event_club->club_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p id="group_msg" style="display: none;"><b>Please select at least
                                                            one group</b></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="accordion mb-2" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button"
                                                        data-toggle="collapse" data-target="#collapseOne2"
                                                        aria-expanded="false" aria-controls="collapseOne2">
                                                        Location
                                                    </button>
                                                </h2>
                                                <div id="collapseOne2" class="accordion-collapse collapse show"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <table>
                                                            <tbody>

                                                                <td>

                                                                    <label for="venue"
                                                                        class="field_required">Venue</label>
                                                                    <select
                                                                        class="form-select @error('venue') is-invalid @enderror"
                                                                        id="venue" name="venue" required>
                                                                        <option value="" disabled>Select a venue
                                                                        </option>

                                                                        @foreach ($vanue as $venue)
                                                                            <option value="{{ $venue->id }}"
                                                                                {{ $organised_event_meta['Venue'] == $venue->id ? 'selected' : '' }}>
                                                                                {{ $venue->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                    

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
                                                    <button class="accordion-button" type="button"
                                                        data-toggle="collapse" data-target="#collapseOne3"
                                                        aria-expanded="false" aria-controls="collapseOne3">
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
                                                                            <option value="" disabled>Select an
                                                                                Organizer</option>

                                                                            @foreach ($organizer as $organizers)
                                                                            
                                                                                <option value="{{ $organizers->id }}"
                                                                                    {{ $organizers->id == ($organised_event_meta['Organizer'] ?? '') ? 'selected' : '' }}>
                                                                                    {{ $organizers->name }}
                                                                                </option>
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
                                                    <button class="accordion-button" type="button"
                                                        data-toggle="collapse" data-target="#collapseOne4"
                                                        aria-expanded="false" aria-controls="collapseOne4">
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
                                                                        id="url" placeholder="Url"
                                                                        value="{{ isset($organised_event_meta['Website Url']) ? $organised_event_meta['Website Url'] : '' }}">
                                                                    @error('url')
                                                                        <span
                                                                            class="invalid-feedback text-danger">{{ $message }}</span>
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
                                                    <button class="accordion-button" type="button"
                                                        data-toggle="collapse" data-target="#collapseOne5"
                                                        aria-expanded="false" aria-controls="collapseOne5">
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
                                                                            value="{{ isset($organised_event_meta['Price Symbol']) ? $organised_event_meta['Price Symbol'] : '' }}">

                                                                        <select
                                                                            class="form-select @error('cost_type') is-invalid @enderror"
                                                                            id="cost_type" name="cost_type">
                                                                            <option value=""> Select the cost type
                                                                            </option>
                                                                            <option value="1"
                                                                                {{ $organised_event_meta['Cost Type'] == 1 ? 'selected' : '' }}>
                                                                                Before cost </option>
                                                                            <option value="2"
                                                                                {{ $organised_event_meta['Cost Type'] == 2 ? 'selected' : '' }}>
                                                                                After cost </option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="">Cost:</td>
                                                                    <td>
                                                                        <input type="number" step="0.001"
                                                                            name="cost" 
                                                                            class="form-control @error('cost') is-invalid @enderror"
                                                                            id="cost" placeholder="cost"
                                                                            @if (isset($organised_event_meta['Price'])) value="{{ $organised_event_meta['Price'] }}"
                                                                            @else
                                                                            value="" @endif>
                                                                        @error('cost')
                                                                            <span
                                                                                class="invalid-feedback text-danger">{{ $message }}</span>
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
                                <div class="row">
                                    <div class="accordion mb-2 mt-5" id="accordionExample">
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
                                                                    <label for="author"
                                                                        class="fw-bold event__label">Event
                                                                        Categories</label>
                                                                    {{-- Recursive function to build the category heirarchy tree --}}
                                                                    {{-- @php
                                                                        function buildCategoryTree(
                                                                            $categories,
                                                                            $selectedCategories = [],
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
                                                                                        $processed,
                                                                                    )
                                                                                ) {
                                                                                    $processed[] = $category['id'];

                                                                                    $isChecked = in_array(
                                                                                        $category['id'],
                                                                                        $selectedCategories,
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
                                                                                        $isChecked .
                                                                                        '>';
                                                                                    $html .=
                                                                                        '<label for="node' .
                                                                                        $category['id'] .
                                                                                        '">' .
                                                                                        $category['name'] .
                                                                                        '</label>';
                                                                                    $html .= buildCategoryTree(
                                                                                        $category['children'],
                                                                                        $selectedCategories,
                                                                                        $processed,
                                                                                    );
                                                                                    $html .= '</li>';
                                                                                }
                                                                            }
                                                                            $html .= '</ul>';

                                                                            return $html;
                                                                        }
                                                                    @endphp --}}
                                                                    {{-- @php
                                                                    function buildCategoryTree($categories, &$processed = []) {
                                                                        if (count($categories) === 0) {
                                                                            return '';
                                                                        }
                                                                    
                                                                        $html = '<ul class="category-tree mt-2">';
                                                                        foreach ($categories as $category) {
                                                                            if (!in_array($category['id'], $processed)) {
                                                                                $processed[] = $category['id'];
                                                                    
                                                                                $checked = in_array($category['id'], old('event_cat', [])) ? 'checked' : '';
                                                                    
                                                                                $html .= '<li>';
                                                                                $html .= '<input name="event_cat[]" type="checkbox" id="node' . $category['id'] . '" value="' . $category['id'] . '" ' . $checked . '>';
                                                                                $html .= '<label for="node' . $category['id'] . '">' . $category['name'] . '</label>';
                                                                                $html .= buildCategoryTree($category['children'], $processed); // Recursive call
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
                                                                    

                                                                    {{-- Output the category tree --}}
                                                                    <div>
                                                                        {!! buildCategoryTree($categories, $selected_event_category) !!}
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
                                                                        <option value="" selected>Select an
                                                                            Author</option>
                                                                        @foreach ($user as $users)
                                                                            <option value="{{ $users->id }}"
                                                                                {{ $users->id == $organised_event_meta['Author'] ? 'selected' : '' }}>
                                                                                {{ $users->name }}</option>
                                                                        @endforeach
                                                                    </select>
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
                                                                            <option value="" disabled>Select a status
                                                                            </option>

                                                                            @foreach (['published', 'draft', 'trash'] as $statusOption)
                                                                                <option value="{{ $statusOption }}"
                                                                                    {{ old('status', isset($event) ? $event->status : '') == $statusOption ? 'selected' : '' }}>
                                                                                    {{ ucfirst($statusOption) }}
                                                                                </option>
                                                                            @endforeach

                                                                        </select>
                                                                        @error('status')
                                                                            <span
                                                                                class="invalid-feedback text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>



                                                                    <div class="form-group" id="publish-options">
                                                                        <label for="publish_time">Publish Time</label>
                                                                        <div>
                                                                            <input type="radio" id="publish_immediately"
                                                                                name="publish_option"
                                                                                value="publish_immediately"
                                                                                {{ !isset($event->status) || $event->status != 'publish_later' ? 'checked' : '' }}>
                                                                            <label for="publish_immediately">Publish
                                                                                Immediately</label>

                                                                        </div>
                                                                        <div>
                                                                            <input type="radio" id="publish_later"
                                                                                name="publish_option"
                                                                                value="publish_later"
                                                                                {{ isset($event->status) && $event->status == 'publish_later' ? 'checked' : '' }}>

                                                                            <label for="publish_later">Publish at a
                                                                                specific time</label>
                                                                            <input type="datetime-local" id="publish_time"
                                                                                name="publish_time" class="form-control"
                                                                                style="{{ isset($event->status) && $event->status == 'publish_later' ? '' : 'display: none;' }}"
                                                                                value="{{ isset($organised_event_meta['publish_time']) ? $organised_event_meta['publish_time'] : '' }}"
                                                                                {{ isset($event->status) && $event->status == 'publish_later' ? 'required' : '' }}>
                                                                            <span id = "publish_time_error"
                                                                                class="text-danger">
                                                                            </span>

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
                                                                            <input type="hidden" name="old_image" value="{{ isset($organised_event_meta['Image']) ? $organised_event_meta['Image'] : '' }}">

                                                                            <input type="file"
                                                                                value="{{ isset($organised_event_meta['Image'])? $organised_event_meta['Image']:""}}"
                                                                                name="image"
                                                                                class="form-control-file  @error('image') is-invalid @enderror"
                                                                                id="image"
                                                                                style="color: transparent;">
                                                                            @error('image')
                                                                                <span
                                                                                    class="invalid-feedback text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        @if (isset($organised_event_meta['Image']))
                                                                            <div class="image">
                                                                                <img src="{{ asset($organised_event_meta['Image']) }}"
                                                                                    alt="">
                                                                            </div>
                                                                        @endif
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
                            <!-- /.content -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {


            jQuery(function($) {
               $start_date = "{{ $organised_event_meta['Start_date'] }}";
                var startDateValue = "{{ $organised_event_meta['Start_date'] }}";
                var endDateValue = "{{ $organised_event_meta['End_date'] }}";

                $('#event_status').change(function() {
                    var publishOptions = $('#publish-options');
                    if ($(this).val() === 'published') {
                        publishOptions.show();
                    } else {
                        publishOptions.hide();
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
            // Function to toggle publish time field visibility and validation
            function togglePublishTimeRequired() {
                if ($('#publish_later').prop('checked')) {
                    $('#publish_time').prop('required', true);
                    $('#publish_time').closest('.form-group').show(); // Show the input field's form group
                } else {
                    $('#publish_time').prop('required', false);
                    // $('#publish_time').closest('.form-group').hide(); // Hide the input field's form group
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

            $('#event_status').change(function() {
                // Check if status is "draft" and reset publish_time validation and field
                if ($(this).val() === 'trash') {
                    $('#publish_immediately').prop('checked', true); // Select "Publish Immediately"
                    togglePublishTimeRequired(); // Reset publish_time field visibility and validation
                }
            });

            // Handle form submission
            $('form').submit(function(event) {
                // Reset publish_time validation error on submit
                $('#publish_time_error').text('');

                // Check if publish_later is checked and publish_time is empty
                if ($('#publish_later').prop('checked') && !$('#publish_time').val()) {
                    event.preventDefault(); // Prevent form submission
                    $('#publish_time_error').text('Please add a Publish time.'); // Display error message
                }
                console.log(eventStatus.val(), 'object')

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
