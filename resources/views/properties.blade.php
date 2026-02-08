@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.manager'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => trans('general.properties'), 'route' => route('properties'), 'class' => 'active']
        ]
    ]
])

@section('title', trans('general.properties'))

@section('css')
    <link href="{{ asset('/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/croppie/croppie.css') }}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">

    <style>
        td.details-control {
            background: url('{{asset('/images/details_open.png')}}') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('{{asset('/images/details_close.png')}}') no-repeat center center;

        }

        .ctable {
            width: 50vw;
            height: 20vh;
            display: flex;
            flex-direction: column;
            background: rgba(148, 147, 8, 0.32) !important;
        }

        .ctable-row {
            width: 100%;
            height: 3vw;
            display: flex;
            border-bottom: 1px solid #ccc;
        }

        .ctable-header {
            font-weight: bold;
        }

        .ctable-row div:first-child {
            width: 10% !important;
        }

        .ctable-row-column {
            width: 30%;
            height: 80%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .highlight_holiday {
            background-color: #fdefdd !important;
        }

        .highlight_sunday {
            background-color: #fddddd !important;
        }

        .highlight_saturday {
            background-color: #ddf3fd !important;
        }

    </style>
@stop

@php($user = Auth::user())

@section('content_body')
    <div class="container-fluid">
        @component('components.datatable', ['title' => trans('property.show_title'), 'table_id' => 'property_datatable'])
            @slot('buttons')
                <button class="btn btn-primary" onClick="addPropertyClick({{$user->id}})">
                    <i class="fa fa-plus" title="{{trans('property.add_property_title')}}"></i> {{trans('general.add')}}
                </button>
            @endslot

            <th>#</th>
            @if($user->is_broker)
                <th>{{ trans('property.owner') }}</th>
                <th>Broker Phone</th>
            @endif
            <th>{{ trans('general.title') }}</th>
            <th>{{ trans('general.type') }}</th>
            <th>{{ trans('property.zipcode') }}</th>
            <th>{{ trans('general.price') }}</th>
            <th>{{ trans('general.hall_price') }}</th>
            <th>{{ trans('property.cancellation_type') }}</th>
            <th>{{ trans('general.active') }}</th>
            <th>{{ trans('general.actions') }}</th>
        @endcomponent
    </div>

    @component('components.modal', ['set_default'=> true, 'modal_id' => 'add_property_modal', 'form' => true, 'modal_title' => trans('property.add_property_title'), 'button_text' => trans('general.create'), 'modal_size' => 'modal-lg'])
        <div class="default-tab">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab"
                       href="#add_property_modal_tab_property">{{trans('general.property')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#add_property_modal_tab_location">{{trans('property.location')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#add_property_modal_tab_additional">{{trans('general.additional')}}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="add_property_modal_tab_property" role="tabpanel">
                    @if($user->is_broker)
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_owner_id">{{ trans('property.owner') }}</label>
                                    <select id="add_property_modal_owner_id" name="owner_id" class="form-control"
                                            data-tab="property" role="select2"
                                            data-dropdown-parent="#add_property_modal">
                                        @foreach($owners as $item)
                                            <option value="{{ $item->id }}">{{ user_full_name($item) }}</option>
                                        @endforeach
                                    </select>

                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- <input type="text" class="form-control" id="add_property_modal_owner_full_name"
                                           disabled data-tab="property"
                                           value="{{ user_full_name($user) }}"> -->
                        <input type="hidden" id="add_property_modal_owner_id" name="owner_id"
                               value="{{$user->id}}">
                    @endif

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_title">{{ trans('general.title') }}</label>
                                <input name="title" type="text" class="form-control" data-tab="property"
                                       id="add_property_modal_title"
                                       placeholder="{{ trans('general.enter_title') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_property_type">{{ trans('general.type') }}</label>
                                <select id="add_property_modal_property_type" name="property_type"
                                        data-dropdown-parent="#add_property_modal"
                                        class="form-control" data-tab="property"
                                        role="select2"
                                        data-target1="add_property_modal_suitable_hall"
                                        data-target2="add_property_modal_has_elevator"
                                        data-target3="add_property_modal_couple_quantity"
                                        data-target4="add_property_modal_floor_location"
                                        data-target5="add_property_modal_couple_quantity_label"
                                        data-target6="add_property_modal_floor_location_label"
                                        data-target7="add_property_modal_hall_price"
                                        data-target8="add_property_modal_hall_price_label"
                                        data-target9="add_property_modal_when_is_available"
                                        data-target10="add_property_modal_when_is_available_label"
                                        data-target11="add_property_modal_how_long_is_available"
                                        data-target12="add_property_modal_how_long_is_available_label"
                                        data-role="short-term-type">
                                    @foreach(array_trans($property_types, 'property.type.') as $item => $key)
                                        <option value="{{ $item }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_price">{{ trans('general.price') }}</label>
                                <input name="price" type="text" class="form-control" id="add_property_modal_price"
                                       placeholder="{{ trans('property.enter_price') }}" role="price"
                                       data-target="add_property_modal_cancellation_type">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_guest_count">{{ trans('property.guest_count') }}</label>
                                <input name="guest_count" type="text" class="form-control"
                                       id="add_property_modal_guest_count" data-tab="property"
                                       placeholder="{{ trans('property.enter_guest_count') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_bed_count">{{ trans('property.bed_count') }}</label>
                                <input name="bed_count" type="text" class="form-control"
                                       id="add_property_modal_bed_count"
                                       placeholder="{{ trans('property.enter_bed_count') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_bedroom_count">{{ trans('property.bedroom_count') }}</label>
                                <input name="bedroom_count" type="text" class="form-control"
                                       id="add_property_modal_bedroom_count" data-tab="property"
                                       placeholder="{{ trans('property.enter_bedroom_count') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_bathroom_count">{{ trans('property.bathroom_count') }}</label>
                                <input name="bathroom_count" type="text" class="form-control"
                                       id="add_property_modal_bathroom_count" data-tab="property"
                                       placeholder="{{ trans('property.enter_bathroom_count') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label id="add_property_modal_when_is_available_label" for="add_property_modal_when_is_available">When is Available</label>
                                    <select id="add_property_modal_when_is_available" name="when_is_available"
                                            class="form-control"
                                            data-dropdown-parent="#add_property_modal"
                                            data-default="everyday"
                                            role="when-is-available">
                                            <option value="everyday">Everyday</option>
                                            <option value="weekends">Weekends</option>
                                            <option value="holidays">Holidays</option>
                                            <option value="holidays-and-weekends">Weekends & Holidays</option>
                                    </select>
                                    <div class="text-danger"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group mb-1">
                                    <label id="add_property_modal_how_long_is_available_label" for="add_property_modal_how_long_is_available">How long to accept reservations (in months)</label>
                                    <input name="how_long_is_available" type="text" class="form-control"
                                           id="add_property_modal_how_long_is_available" data-tab="property"
                                           disabled="disabled">
                                    <div class="text-danger"></div>
                                </div>
                            </div>
                        </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_cancellation_type">{{ trans('property.cancellation_type') }}</label>
                                <select id="add_property_modal_cancellation_type" name="cancellation_type"
                                        class="form-control" data-role="select2"
                                        data-dropdown-parent="#add_property_modal"
                                        data-target="add_property_modal_cancellation_cut"
                                        data-source="add_property_modal_price"
                                        data-default="{{ \SMD\Common\ReservationSystem\Enums\CancellationType::NONE }}"
                                        role="cancellation-type">
                                    @foreach(array_trans($cancellation_types, 'property.cancellation.') as $item => $key)
                                        <option value="{{ $item }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_cancellation_cut">{{ trans('property.cancellation_cut') }}</label>
                                <input name="cancellation_cut" type="text" class="form-control"
                                       id="add_property_modal_cancellation_cut" data-tab="property"
                                       placeholder="{{ trans('property.enter_cancellation_cut') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_property_option_group">{{ trans('property.option_group') }}</label>
                                <select id="add_property_modal_property_option_group" name="property_option_group"
                                        class="form-control" data-tab="property" data-role="select2"
                                        data-dropdown-parent="#add_property_modal"
                                        role="property-option-group">
                                    @foreach(array_trans($property_options, 'property.options.') as $item => $key)
                                        <option value="{{ $item }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger"></div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group mb-1 form-group-check">
                                <div class="form-check" id="add_property_modal_active">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"
                                               id="add_property_modal_active_input" data-tab="property"
                                               name="active" value="1" checked> {{ trans('general.active') }}</label>
                                </div>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                        <div class="form-row" {{$user->is_broker ? '' : 'hidden'}}>
                            <div class="col-3">
                                <div class="form-group mb-1">
                                    <label for="add_property_modal_phone_number">Broker {{ trans('profile.phone') }}</label>
                                    <div class="input-group">
                                        <input name="phone_number" type="text" class="form-control"
                                               id="add_property_modal_phone_number" autocomplete="off"
                                               placeholder="{{ trans('address.enter.phone_number') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="add_property_modal_phone_number_search"
                                                 style="cursor: pointer">
                                                <!-- <i class="fa fa-search"></i> -->
                                                <i class="fa fa-phone"></i>
                                            </div>
                                        </div>
                                        <div class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <hr>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1 form-group-check">
                                <div class="form-check" id="add_property_modal_suitable_hall">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"
                                               id="add_property_modal_suitable_hall_input" data-tab="property"
                                               name="suitable_hall" value="0"> Suitable as a Hall</label>
                                </div>
                                <div class="text-danger"></div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group mb-1 form-group-check">
                                <div class="form-check" id="add_property_modal_has_elevator">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"
                                               id="add_property_modal_has_elevator_input" data-tab="property"
                                               name="has_elevator" value="0"> Elevator Available</label>
                                </div>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label id="add_property_modal_couple_quantity_label" for="add_property_modal_couple_quantity">Couples Quantity</label>
                                <input name="couple_quantity" type="text" class="form-control"
                                       id="add_property_modal_couple_quantity" data-tab="property"
                                       placeholder="How many couples can seat">
                                <div class="text-danger"></div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group mb-1">
                                <label id="add_property_modal_floor_location_label" for="add_property_modal_floor_location">Floor Location</label>
                                <input name="floor_location" type="text" class="form-control"
                                       id="add_property_modal_floor_location" data-tab="property"
                                       placeholder="Enter floor location (1,2,3,...)">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-6">
                            <div class="form-group mb-1">
                                <label id="add_property_modal_hall_price_label" for="add_property_modal_hall_price">{{ trans('general.hall_price') }}</label>
                                <input name="hall_price" type="text" class="form-control" id="add_property_modal_hall_price"
                                       placeholder="{{ trans('property.enter_price') }}" role="hall_price"
                                       data-tab="property">
                                <div class="text-danger"></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_property_billing_mode">Posting mode</label>
                                <select id="add_property_modal_property_billing_mode" name="billing_mode"
                                        data-dropdown-parent="#add_property_modal"
                                        class="form-control" data-tab="property">
                                        <option value="commission">Commission</option>
                                        <option value="subscription">Subscription</option>
                                </select>
                                <div class="text-danger"></div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="tab-pane fade" id="add_property_modal_tab_location">
                    <div class="form-row">
                        <div class="col-3">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_zipcode_id">{{ trans('property.zipcode') }}</label>
                                <input name="zipcode_id" type="text" class="form-control"
                                       id="add_property_modal_zipcode_id" data-tab="location"
                                       placeholder="{{ trans('property.enter_zipcode') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>

                        <div class="col-9">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_area_name">Area</label>
                                <input name="area_name" type="text" class="form-control"
                                       id="add_property_modal_area_name" data-tab="location"
                                       placeholder="area name" readonly>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-3">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_house_number">{{ trans('property.house_number') }}</label>
                                <input name="house_number" type="text" class="form-control"
                                       id="add_property_modal_house_number" data-tab="location"
                                       placeholder="{{ trans('property.enter_house_number') }}">
                                <div class="text-danger"></div>
                                <input name="map_lat" type="hidden" id="add_property_modal_map_lat"/>
                                <input name="map_lng" type="hidden" id="add_property_modal_map_lng"/>
                                <input name="map_address" type="hidden" id="add_property_modal_map_address"/>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_street_name">{{ trans('property.street_name') }}</label>
                                <input name="street_name" type="text" class="form-control"
                                       id="add_property_modal_street_name" data-tab="location"
                                       placeholder="{{ trans('property.enter_street_name') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-3">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_apt_number">{{ trans('property.apt_number') }}</label>
                                <input name="apt_number" type="text" class="form-control"
                                       id="add_property_modal_apt_number" data-tab="location"
                                       placeholder="{{ trans('property.enter_apt_number') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="tab-pane fade" id="add_property_modal_tab_additional">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_additional_luxury">{{ trans('property.luxury') }}</label>
                                <textarea class="form-control" id="add_property_modal_additional_luxury"
                                          name="additional_luxury"
                                          data-tab="additional"></textarea>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-1">
                                <label for="add_property_modal_additional_information">{{ trans('property.information') }}</label>
                                <textarea class="form-control" id="add_property_modal_additional_information"
                                          name="additional_information"
                                          data-tab="additional"></textarea>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'edit_property_modal', 'form' => true, 'modal_title' => trans('property.edit_property_title'), 'button_text' => trans('general.save'), 'modal_size' => 'modal-lg'])
        <input name="id" type="hidden" id="edit_property_modal_property_id">
        <div class="default-tab">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab"
                       href="#edit_property_modal_tab_property">{{trans('general.property')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#edit_property_modal_tab_location">{{trans('property.location')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#edit_property_modal_tab_additional">{{trans('general.additional')}}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#edit_property_modal_tab_criteria">{{trans('criteria.search_criteria')}}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#edit_property_modal_tab_embed_link">Promote Link</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="edit_property_modal_tab_property" role="tabpanel">
                    @if($user->is_broker)
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_owner_id">{{ trans('property.owner') }}</label>
                                    <select id="edit_property_modal_owner_id" name="owner_id" class="form-control"
                                            data-tab="property" role="select2"
                                            data-dropdown-parent="#edit_property_modal">
                                        @foreach($owners as $item)
                                            <option value="{{ $item->id }}">{{ user_full_name($item) }}</option>
                                        @endforeach
                                    </select>

                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- <input type="text" class="form-control" id="edit_property_modal_owner_full_name"
                                           disabled data-tab="property"
                                           value="{{user_full_name($user)}}"> -->
                        <input type="hidden" id="edit_property_modal_owner_id" name="owner_id"
                               value="{{$user->id}}">
                    @endif


                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_title">{{ trans('general.title') }}</label>
                                <input name="title" type="text" class="form-control" data-tab="property"
                                       id="edit_property_modal_title"
                                       placeholder="{{ trans('general.enter_title') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_property_type">{{ trans('general.type') }}</label>
                                <select id="edit_property_modal_property_type" name="property_type" role="select2"
                                        class="form-control" data-tab="property"
                                        data-dropdown-parent="#edit_property_modal"
                                        data-target1="edit_property_modal_suitable_hall"
                                        data-target2="edit_property_modal_has_elevator"
                                        data-target3="edit_property_modal_couple_quantity"
                                        data-target4="edit_property_modal_floor_location"
                                        data-target5="edit_property_modal_couple_quantity_label"
                                        data-target6="edit_property_modal_floor_location_label"
                                        data-target7="edit_property_modal_hall_price"
                                        data-target8="edit_property_modal_hall_price_label"
                                        data-target9="edit_property_modal_when_is_available"
                                        data-target10="edit_property_modal_when_is_available_label"
                                        data-target11="edit_property_modal_how_long_is_available"
                                        data-target12="edit_property_modal_how_long_is_available_label"
                                        data-role="short-term-type">
                                    @foreach(array_trans($property_types, 'property.type.') as $item => $key)
                                        <option value="{{ $item }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_price">{{ trans('general.price') }}</label>
                                <input name="price" type="text" class="form-control" id="edit_property_modal_price"
                                       placeholder="{{ trans('property.enter_price') }}" role="price"
                                       data-target="edit_property_modal_cancellation_type" data-tab="property">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_guest_count">{{ trans('property.guest_count') }}</label>
                                <input name="guest_count" type="text" class="form-control" data-tab="property"
                                       id="edit_property_modal_guest_count"
                                       placeholder="{{ trans('property.enter_guest_count') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_bed_count">{{ trans('property.bed_count') }}</label>
                                <input name="bed_count" type="text" class="form-control" data-tab="property"
                                       id="edit_property_modal_bed_count"
                                       placeholder="{{ trans('property.enter_bed_count') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_bedroom_count">{{ trans('property.bedroom_count') }}</label>
                                <input name="bedroom_count" type="text" class="form-control"
                                       id="edit_property_modal_bedroom_count" data-tab="property"
                                       placeholder="{{ trans('property.enter_bedroom_count') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_bathroom_count">{{ trans('property.bathroom_count') }}</label>
                                <input name="bathroom_count" type="text" class="form-control" data-tab="property"
                                       id="edit_property_modal_bathroom_count"
                                       placeholder="{{ trans('property.enter_bathroom_count') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label id="edit_property_modal_when_is_available_label" for="edit_property_modal_when_is_available">When is Available</label>
                                    <select id="edit_property_modal_when_is_available" name="when_is_available"
                                            class="form-control"
                                            data-dropdown-parent="#edit_property_modal"
                                            data-default="everyday"
                                            role="when-is-available">
                                        <option value="everyday">Everyday</option>
                                        <option value="weekends">Weekends</option>
                                        <option value="holidays">Holidays</option>
                                        <option value="holidays-and-weekends">Weekends & Holidays</option>
                                    </select>
                                    <div class="text-danger"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group mb-1">
                                    <label id="edit_property_modal_how_long_is_available_label" for="edit_property_modal_how_long_is_available">How long to accept reservations (in months)</label>
                                    <input name="how_long_is_available" type="text" class="form-control"
                                           id="edit_property_modal_how_long_is_available" data-tab="property"
                                           disabled="disabled">
                                    <div class="text-danger"></div>
                                </div>
                            </div>
                        </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_cancellation_type">{{ trans('property.cancellation_type') }}</label>
                                <select id="edit_property_modal_cancellation_type" name="cancellation_type"
                                        class="form-control" data-tab="property" data-role="select2"
                                        data-dropdown-parent="#edit_property_modal"
                                        data-target="edit_property_modal_cancellation_cut"
                                        data-source="edit_property_modal_price"
                                        role="cancellation-type">
                                    @foreach(array_trans($cancellation_types, 'property.cancellation.') as $item => $key)
                                        <option value="{{ $item }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_cancellation_cut">{{ trans('property.cancellation_cut') }}</label>
                                <input name="cancellation_cut" type="text" class="form-control"
                                       id="edit_property_modal_cancellation_cut" data-tab="property"
                                       placeholder="{{ trans('property.enter_cancellation_cut') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_property_option_group">{{ trans('property.option_group') }}</label>
                                <select id="edit_property_modal_property_option_group" name="property_option_group"
                                        class="form-control" data-tab="property" data-role="select2"
                                        data-dropdown-parent="#edit_property_modal"
                                        role="property-option-group">
                                    @foreach(array_trans($property_options, 'property.options.') as $item => $key)
                                        <option value="{{ $item }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger"></div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group mb-1 form-group-check">
                                <div class="form-check" id="edit_property_modal_active">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"
                                               id="edit_property_modal_active_input" data-tab="property"
                                               name="active" value="1"> {{ trans('general.active') }}</label>
                                </div>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                        <div class="form-row" {{$user->is_broker ? '' : 'hidden'}}>
                            <div class="col-3">
                                <div class="form-group mb-1">
                                    <label for="edit_property_modal_phone_number">Broker {{ trans('profile.phone') }}</label>
                                    <div class="input-group">
                                        <input name="phone_number" type="text" class="form-control"
                                               id="edit_property_modal_phone_number" autocomplete="off"
                                               placeholder="{{ trans('address.enter.phone_number') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="edit_property_modal_phone_number_search"
                                                 style="cursor: pointer">
                                                <!-- <i class="fa fa-search"></i> -->
                                                <i class="fa fa-phone"></i>
                                            </div>
                                        </div>
                                        <div class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <hr>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1 form-group-check">
                                <div class="form-check" id="edit_property_modal_suitable_hall">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"
                                               id="edit_property_modal_suitable_hall_input" data-tab="property"
                                               name="suitable_hall" value="0"> Suitable as a Hall</label>
                                </div>
                                <div class="text-danger"></div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group mb-1 form-group-check">
                                <div class="form-check" id="edit_property_modal_has_elevator">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"
                                               id="edit_property_modal_has_elevator_input" data-tab="property"
                                               name="has_elevator" value="0"> Elevator Available</label>
                                </div>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label id="edit_property_modal_couple_quantity_label" for="edit_property_modal_couple_quantity">Couples Quantity</label>
                                <input name="couple_quantity" type="text" class="form-control"
                                       id="edit_property_modal_couple_quantity" data-tab="property"
                                       placeholder="How many couples can seat">
                                <div class="text-danger"></div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group mb-1">
                                <label id="edit_property_modal_floor_location_label" for="edit_property_modal_floor_location">Floor Location</label>
                                <input name="floor_location" type="text" class="form-control"
                                       id="edit_property_modal_floor_location" data-tab="property"
                                       placeholder="Enter floor location (1,2,3,...)">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-6">
                            <div class="form-group mb-1">
                                <label id="edit_property_modal_hall_price_label" for="edit_property_modal_hall_price">{{ trans('general.hall_price') }}</label>
                                <input name="hall_price" type="text" class="form-control" id="edit_property_modal_hall_price"
                                       placeholder="{{ trans('property.enter_price') }}" role="hall_price"
                                       data-tab="property">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="edit_property_modal_tab_location">
                    <div class="form-row">
                        <div class="col-3">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_zipcode_id">{{ trans('property.zipcode') }}</label>
                                <input name="zipcode_id" type="text" class="form-control"
                                       id="edit_property_modal_zipcode_id" data-tab="location"
                                       placeholder="{{ trans('property.enter_zipcode') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>

                        <div class="col-9">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_area_name">Area</label>
                                <input name="area_name" type="text" class="form-control"
                                       id="edit_property_modal_area_name" data-tab="location"
                                       placeholder="area name" readonly>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-3">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_house_number">{{ trans('property.house_number') }}</label>
                                <input name="house_number" type="text" class="form-control"
                                       id="edit_property_modal_house_number"
                                       placeholder="{{ trans('property.enter_house_number') }}" data-tab="location">
                                <div class="text-danger"></div>
                                <input name="map_lat" type="hidden" id="edit_property_modal_map_lat"/>
                                <input name="map_lng" type="hidden" id="edit_property_modal_map_lng"/>
                                <input name="map_address" type="hidden" id="edit_property_modal_map_address"/>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_street_name">{{ trans('property.street_name') }}</label>
                                <input name="street_name" type="text" class="form-control custom-autocomplete"
                                       id="edit_property_modal_street_name"
                                       placeholder="{{ trans('property.enter_street_name') }}" data-tab="location">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-3">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_apt_number">{{ trans('property.apt_number') }}</label>
                                <input name="apt_number" type="text" class="form-control"
                                       id="edit_property_modal_apt_number" data-tab="location"
                                       placeholder="{{ trans('property.enter_apt_number') }}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="edit_property_modal_tab_additional">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_additional_luxury">{{ trans('property.luxury') }}</label>
                                <textarea class="form-control" id="edit_property_modal_additional_luxury"
                                          name="additional_luxury"
                                          data-tab="additional"></textarea>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_additional_information">{{ trans('property.information') }}</label>
                                <textarea class="form-control" id="edit_property_modal_additional_information"
                                          name="additional_information"
                                          data-tab="additional"></textarea>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CRITERIA TAB -->
                <div class="tab-pane fade" id="edit_property_modal_tab_criteria">

                    <button type="button" class="btn btn-primary add-criteria-button">
                    <i class="fa fa-plus" title="{{trans('criteria.title.add_criterion')}}"></i>{{trans('general.add')}}
                    </button>

                    <table class="table mb-0 table-striped" id="property_criteria_table">
                        <thead>
                        <tr>
                            <th>Group</th>
                            <th>Criteria</th>
                            <th>Quantity</th>
                            <th>Walking Distance</th>
                            <th>Driving Distance</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!-- END CRITERIA TAB -->

                <!-- EMBED LINK TAB -->
                <div class="tab-pane fade" id="edit_property_modal_tab_embed_link">

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_embed_link">Copy the code below and paste it in your web</label>
                                <textarea type="text" name="embed_link" class="form-control" data-tab="property" rows="6"
                                          id="edit_property_modal_embed_link" readonly></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_property_modal_embed_link">Preview</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <div style="width:460px; height:460px; position:relative; padding:0;">
                                    <iframe id="iframe_embed_link" src="" style="position:absolute; top:0px;
                                        left:0px; width:100%; height:100%; border: none; overflow: hidden;">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                        <div class="col"></div>
                    </div>
                </div>
                <!-- END EMBED LINK TAB -->
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'del_property_modal', 'modal_class' => 'modal-danger', 'modal_title' => trans('property.delete_property_title'), 'button_class' => 'btn-danger', 'button_text' => trans('general.delete')])
    @endcomponent

    @component('components.modal', ['modal_id' => 'images_property_modal', 'form' => true, 'modal_title' => trans('property.image.items'), 'button_close'=> true, 'button_text' => trans('general.close')])
        <h5>{{trans('property.image_add_title')}}</h5>
        <div class="input-group mb-3">
            <div class="custom-file">
                <input id="images_property_modal_file" type="file" class="custom-file-input"
                       onchange="imageChoose(this)">
                <label id="images_property_modal_file_label"
                       class="custom-file-label">{{trans('property.btn.image.choose')}}</label>
            </div>
            <div class="input-group-append">
                <button class="btn btn-info" id="images_property_modal_upload" type="button" style="line-height: 1.25"
                        onclick="uploadImage()">
                    {{trans('property.btn.image.upload')}}
                </button>
            </div>
        </div>
        <div id="images_property_modal_croppie_wrapper">
            <h5>{{trans('property.btn.image.create')}}</h5>
            <div id="images_property_modal_croppie"></div>
            <button id="images_property_modal_croppie_btn" type="button" onclick="saveCroppie(this)"
                    class="btn btn-sm btn-info mb-3">{{trans('property.btn.image.save')}}
            </button>
            <button id="images_property_modal_croppie_close" type="button" onclick="closeCroppie()"
                    class="btn btn-sm btn-warning mb-3">{{trans('general.close')}}
            </button>
        </div>
        <h5>{{trans('property.image.current')}}</h5>
        <div id="images_property_modal_list">
            <div style="height: 100px;">
                <div class="loader spinner-3"></div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'availability_property_modal', 'form' => true, 'modal_title' => trans('property.availability.items'), 'button_close'=> true, 'button_text' => trans('general.close')])
        <table class="table mb-0 table-striped" id="availability_property_modal_table">
            <thead>
            <tr>
                <th>{{trans('reservation.date_start')}}</th>
                <th>{{trans('reservation.date_end')}}</th>
                <th>{{trans('general.active')}}</th>
                <th>{{trans('general.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="4" class="text-center"><i
                            class="fa fa-spinner spinning"></i> {{trans('property.availability.loading')}}</td>
            </tr>
            </tbody>
        </table>
    @endcomponent

    @component('components.modal', ['set_default' => true, 'modal_id' => 'add_criterion_modal', 'form' => true, 'modal_title' => 'Add Criteria To Property', 'button_text' => trans('general.save')])
        <input name="property_id" type="hidden" id="add_criterion_modal_property_id">
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_criterion_modal_type">{{trans('general.name')}}</label>

                    <select id="add_criterion_modal_type" name="criterion_type_id"
                            class="form-control">
                        <option value="" selected disabled hidden>Choose...</option>
                        @foreach($criteria_types as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>

                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_criterion_modal_criterion">{{trans('criteria.menu_order')}}</label>

                    <select id="add_criterion_modal_criterion" name="criterion_id"
                            class="form-control">

                    </select>

                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row" id="show_has_quantity" style="display:none">
            <div class="col">
                <div class="form-group mb-1 form-group-check">
                        <label for="add_criterion_modal_quantity">Quantity</label>
                        <input name="quantity" type="text" class="form-control" id="add_criterion_modal_quantity"
                               placeholder="Insert quantity">
                   <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row" id="show_has_distance_w" style="display:none">
            <div class="col">
                <div class="form-group mb-1 form-group-check">
                    <label for="add_criterion_modal_distance_w">Minutes by Walking</label>
                    <input name="walking_distance" type="text" class="form-control" id="add_criterion_modal_distance_w"
                           placeholder="Insert minutes">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row" id="show_has_distance_d" style="visibility :hidden">
            <div class="col">
                <div class="form-group mb-1 form-group-check">
                    <label for="add_criterion_modal_distance_d">Minutes by Driving</label>
                    <input name="driving_distance" type="text" class="form-control" id="add_criterion_modal_distance_d"
                           placeholder="Insert minutes">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'pause_property_modal', 'modal_class' => 'modal-secondary', 'modal_title' => 'Pause Property', 'button_class' => 'btn-secondary', 'button_text' => 'Pause'])
    @endcomponent

    @component('components.modal', ['modal_id' => 'resume_property_modal', 'modal_class' => 'modal-primary', 'modal_title' => 'Resume Property', 'button_class' => 'btn-primary', 'button_text' => 'Resume'])
    @endcomponent

    @component('components.modal', ['modal_id' => 'restriction_property_modal', 'form' => true, 'modal_title' => 'This property won\'t be listed under these restrictions', 'modal_size' => 'modal-lg', 'button_close'=> true, 'button_text' => trans('general.close')])
        <table class="table mb-0 table-striped" id="restriction_property_modal_table">
            <thead>
            <tr>
                <th>Restriction type</th>
                <th>Day of week</th>
                <th>Day on Month</th>
                <th>Start hour</th>
                <th>End hour</th>
                <th>{{trans('general.actions')}}</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center">
                        <i class="fa fa-spinner spinning"></i>
                        {{trans('property.availability.loading')}}
                    </td>
                </tr>
            </tbody>
        </table>
    @endcomponent

    @component('components.modal', ['modal_id' => 'packages_property_modal', 'form' => true, 'modal_title' => 'Property\'s Packages', 'modal_size' => 'modal-lg', 'button_close'=> true, 'button_text' => trans('general.close')])

        <div class="default-tab">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" id="packages_property_modal_tab_link_jew_holidays"
                       href="#packages_property_modal_tab_jew_holidays">Jew Holidays</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" id="packages_property_modal_tab_link_us_holidays"
                       href="#packages_property_modal_tab_us_holidays">US Holidays</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" id="packages_property_modal_tab_link_weekend_holidays"
                       href="#packages_property_modal_tab_weekend_holidays">Weekends and other packages</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="packages_property_modal_tab_jew_holidays" role="tabpanel">
                    <table class="table mb-0 table-striped" id="packages_property_modal_jew_table">
                        <thead>
                        <tr>
                            <th style="width: 1% !important;"></th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="packages_property_modal_tab_us_holidays" role="tabpanel">
                    <table class="table mb-0 table-striped" id="packages_property_modal_us_table">
                        <thead>
                        <tr>
                            <th style="width: 1% !important;"></th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="packages_property_modal_tab_weekend_holidays" role="tabpanel">
                    <table class="table mb-0 table-striped" id="packages_property_modal_weekend_table">
                        <thead>
                        <tr>
                            <th style="width: 1% !important;"></th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'user_fee_property_modal', 'modal_class' => 'modal-danger', 'modal_title' => 'Stripe Account', 'button_class' => 'btn-danger', 'button_text' => 'Go to profile'])
    @endcomponent

    @component('components.modal', ['modal_id' => 'user_fee_pay_property_modal', 'modal_class' => 'modal-danger', 'modal_title' => 'Posting payment', 'button_class' => 'btn-danger', 'button_text' => 'Proceed'])
    @endcomponent

    @component('components.modal', ['set_default'=> true, 'form_files'=> true, 'modal_id' => 'payment_modal', 'form' => true, 'modal_title' => 'Subscription fee', 'button_text' => 'Proceed with payment'])
        <h6 class="text-danger">${{env('SUBSCRIPTION_PRICE')}}.00 will be charged to post the property by subscription.</h6>
        <h6 class="text-danger">Afterward ${{env('SUBSCRIPTION_PRICE')}}.00 will be charged monthly.</h6>

        <div>
            <div class="form-row">
                <div class="col-9">
                    <div class="form-group mb-1">
                        <label for="payment_modal_number">{{ trans('profile.card.number') }}</label>
                        <input name="number" type="text" class="form-control"
                               id="payment_modal_number"
                               placeholder="{{ trans('profile.card.enter_number') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group mb-1">
                        <label for="payment_modal_cvc">{{ trans('profile.card.cvc') }}</label>
                        <input name="cvc" type="text" class="form-control"
                               id="payment_modal_cvc"
                               placeholder="{{ trans('profile.card.enter_cvc') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="payment_modal_exp_month">{{ trans('profile.card.exp_month') }}</label>
                        <select name="exp_month" class="form-control" id="payment_modal_exp_month">
                            @for($i =1; $i<=12;$i++)
                                <option value="{{$i}}">{{sprintf("%02d", $i)}}</option>
                            @endfor
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="payment_modal_exp_year">{{ trans('profile.card.exp_year') }}</label>
                        <select name="exp_year" class="form-control" id="payment_modal_exp_year">
                            @for($i = date("Y"); $i<=date("Y") + 19;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent

@stop

@section('scripts')
    <script src="{{ asset('/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable-init/datatable-basic.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/plugins/croppie/croppie.min.js') }}"></script>

    <script src="{{ asset('/plugins/autocomplete/jquery.autocomplete.min.js') }}"></script>

    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        window.croppie = null;
        window.template = {
            'images': {
                'list': '<div class="media">\n' +
                    '   <img class="align-self-end mr-3" style="width: 250px" src="__href__" alt="">\n' +
                    '   <div class="media-body">\n' +
                    '        <div class="form-group">\n' +
                    '            <div class="form-check mb-3">\n' +
                    '                <label for="property_image_active___id__" class="form-check-label">\n' +
                    '                    <input id="property_image_active___id__" type="checkbox" ' +
                    '                       class="form-check-input" __active__ value="">{{trans('general.active')}}\n' +
                    '                </label>\n' +
                    '            </div>\n' +
                    '            <div class="form-check mb-3">\n' +
                    '               <label for="property_image_show_on_search___id__" class="form-check-label">\n' +
                    '                   <input id="property_image_show_on_search___id__" __show_on_search__ ' +
                    '                       type="checkbox" class="form-check-input" value="">{{trans('property.show_on_search')}}\n' +
                    '               </label>\n' +
                    '           </div>\n' +
                    '       </div>\n' +
                    '       <p class="text-danger">__thumbnail__</p>\n' +
                    '       <div class="btn-group pull-right">\n' +
                    '           <button class="btn btn-sm btn-outline-info" type="button" ' +
                    '               onclick="createThumbnail(\'__id__\', \'__url__\', \'__property_id__\')">{{trans('property.btn.image.create')}}</button>\n' +
                    '           <button class="btn btn-sm btn-success" type="button" ' +
                    '               onclick="imageChanges(\'save\', \'__id__\', \'__property_id__\')">{{trans('general.save')}}</button>\n' +
                    '           <button class="btn btn-sm btn-outline-danger" type="button" ' +
                    '               onclick="imageChanges(\'delete\', \'__id__\', \'__property_id__\')">{{trans('general.delete')}}</button>\n' +
                    '        </div>\n' +
                    '   </div>\n' +
                    '</div>\n' +
                    '<div style="border-bottom:1px solid #d4d4d4;margin-top:5px;margin-bottom:5px;"></div>',
                'not_found': '<div class="mt-3 mb-2">\n' +
                    '    <h5 class="text-center text-gray">{{trans('property.image.not_found')}}</h5>\n' +
                    '</div>'
            },
            'availabilities': {
                'items': '<tr class="item" id="tr_availability___property_id_____id__">\n' +
                    '   <td><input id="availability_start___property_id_____id__" class="form-control" type="text" ' +
                    '       style="width:100px" value="__start_date__" /></td>\n' +
                    '   <td><input id="availability_end___property_id_____id__" class="form-control" type="text" ' +
                    '       style="width:100px" value="__end_date__" /></td>\n' +
                    '   <td><input id="availability_active___property_id_____id__" type="checkbox" ' +
                    '       __is_active__ /></td>\n' +
                    '   <td>\n' +
                    '       <input type="hidden" value="__id__" id="availability_id___property_id_____id__" />' +
                    '       <input type="hidden" value="__property_id__" id="availability_property___property_id_____id__" />' +
                    '       <div class="btn-group">\n' +
                    '           <button id="availability_save___property_id_____id__" type=button ' +
                    '               class="btn btn-sm btn-success" data-active="__active__"' +
                    '               data-start="__start_date__" data-end="__end_date__" data-id="__id__"' +
                    '               data-property="__property_id__" onclick="actionAvailability(this, \'save\')" ' +
                    '               style="display:none;"><i class="fa fa-check"></i></button>\n' +
                    '           <button id="availability_close___property_id_____id__" type=button ' +
                    '               class="btn btn-sm btn-secondary" data-active="__active__"' +
                    '               data-start="__start_date__" data-end="__end_date__" data-id="__id__"' +
                    '               data-property="__property_id__" onclick="actionAvailability(this, \'close\')"' +
                    '               style="display:none;"><i class="fa fa-times"></i></button>\n' +
                    '           <button id="availability_edit___property_id_____id__" type=button ' +
                    '               class="btn btn-sm btn-info" data-active="__active__"' +
                    '               data-start="__start_date__" data-end="__end_date__" data-id="__id__"' +
                    '               data-property="__property_id__" onclick="actionAvailability(this, \'edit\')"' +
                    '               ><i class="fa fa-pencil"></i></button>\n' +
                    '           <button id="availability_delete___property_id_____id__" type=button ' +
                    '               class="btn btn-sm btn-danger" data-active="__active__"' +
                    '               data-start="__start_date__" data-end="__end_date__" data-id="__id__"' +
                    '               data-property="__property_id__" onclick="actionAvailability(this, \'delete\')"' +
                    '               ><i class="fa fa-trash"></i></button>\n' +
                    '       </div>\n' +
                    '   </td>\n' +
                    '</tr>\n',
                'not_found': '<td colspan="4" class="text-center">{{trans('property.availability.not_found')}}</td>',
                'add_new': '<tr class="item" id="tr_availability___property_id__">' +
                    '   <td><input id="availability_start___property_id__" class="form-control" type="text" ' +
                    '       style="width:100px" /></td>\n' +
                    '   <td><input id="availability_end___property_id__" class="form-control" type="text" ' +
                    '       style="width:100px" /></td>\n' +
                    '   <td><input id="availability_active___property_id__" type="checkbox" checked/></td>\n' +
                    '   <td>\n' +
                    '       <div class="btn-group">\n' +
                    '           <button id="availability_save___property_id__" type=button ' +
                    '               class="btn btn-sm btn-success" data-property="__property_id__" style="display:none;"' +
                    '               onclick="actionAvailability(this, \'create\')"><i class="fa fa-check"></i></button>\n' +
                    '           <button id="availability_add___property_id__" type=button ' +
                    '               class="btn btn-sm btn-info" data-property="__property_id__" ' +
                    '               onclick="actionAvailability(this, \'add\')"><i class="fa fa-plus"></i></button>\n' +
                    '           <button id="availability_close___property_id__" type=button ' +
                    '               class="btn btn-sm btn-secondary" data-property="__property_id__" style="display:none;"' +
                    '               onclick="actionAvailability(this, \'exit\')"><i class="fa fa-times"></i></button>\n' +
                    '       </div>\n' +
                    '   </td>\n' +
                    '</tr>\n',
            },
            'restrictions':{
                'items': '<tr class="item" id="tr_restriction___property_id_____id__">\n' +
                    '	<td>\n' +
                    '		<select id="restriction_type___property_id_____id__" class="form-control" style="width:135px" value="__type__" disabled>\n' +
                    '			<option value="none"></option>\n' +
                    '			<option value="day_of_week">Day of week</option>\n' +
                    '			<option value="day_of_month">Day of month</option>\n' +
                    '			<option value="hour">Hour</option>\n' +
                    '		</select>\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<select id="restriction_day_week___property_id_____id__" class="form-control" style="width:130px" value="__day_week__" disabled>\n' +
                    '			<option value="0"></option>\n' +
                    '			<option value="1">Sunday</option>\n' +
                    '			<option value="2">Monday</option>\n' +
                    '			<option value="3">Tuesday</option>\n' +
                    '			<option value="4">Wednesday</option>\n' +
                    '			<option value="5">Thursday</option>\n' +
                    '			<option value="6">Friday</option>\n' +
                    '			<option value="7">Saturday</option>\n' +
                    '		</select>\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<select id="restriction_day_month___property_id_____id__" class="form-control" style="width:130px" value="__day_month__" disabled>\n' +
                    '			<option value="0"></option>\n' +
                        @for($i = 1; $i <= 31; $i++)
                            '   <option value="{{$i}}">{{$i}}</option>\n' +
                        @endfor
                            '		</select>\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<input id="restriction_start___property_id_____id__" value="__start__" disabled\n' +
                    '			   class="form-control" type="text" style="width:80px" />\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<input id="restriction_end___property_id_____id__" value="__end__" disabled\n' +
                    '			   class="form-control" type="text" style="width:80px" />\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<input type="hidden" value="__id__" id="restriction_id___property_id_____id__" />\n' +
                    '		<input type="hidden" value="__property_id__" id="restriction_property___property_id_____id__" />\n' +
                    '		<div class="btn-group">\n' +
                    '			<button id="restriction_save___property_id_____id__" type=button\n' +
                    '					class="btn btn-sm btn-success" data-type="__type__"\n' +
                    '					data-start="__start__" data-end="__end__" data-id="__id__"\n' +
                    '					data-week="__day_week__" data-month="__day_month__"\n' +
                    '					data-property="__property_id__" onclick="actionRestriction(this, \'save\')"\n' +
                    '					style="display:none;"><i class="fa fa-check"></i></button>\n' +
                    '			<button id="restriction_close___property_id_____id__" type=button\n' +
                    '					class="btn btn-sm btn-secondary" data-type="__type__"\n' +
                    '					data-start="__start__" data-end="__end__" data-id="__id__"\n' +
                    '					data-week="__day_week__" data-month="__day_month__"\n' +
                    '					data-property="__property_id__" onclick="actionRestriction(this, \'close\')"\n' +
                    '					style="display:none;"><i class="fa fa-times"></i></button>\n' +
                    '			<button id="restriction_edit___property_id_____id__" type=button\n' +
                    '					class="btn btn-sm btn-info" data-type="__type__"\n' +
                    '					data-start="__start__" data-end="__end__" data-id="__id__"\n' +
                    '					data-week="__day_week__" data-month="__day_month__"\n' +
                    '					data-property="__property_id__" onclick="actionRestriction(this, \'edit\')"\n' +
                    '			><i class="fa fa-pencil"></i></button>\n' +
                    '			<button id="restriction_delete___property_id_____id__" type=button\n' +
                    '					class="btn btn-sm btn-danger" data-type="__type__"\n' +
                    '					data-start="__start__" data-end="__end__" data-id="__id__"\n' +
                    '					data-week="__day_week__" data-month="__day_month__"\n' +
                    '					data-property="__property_id__" onclick="actionRestriction(this, \'delete\')"\n' +
                    '			><i class="fa fa-trash"></i></button>\n' +
                    '		</div>\n' +
                    '	</td>\n' +
                    '</tr>\n',
                'not_found': '<td colspan="4" class="text-center">{{trans('property.availability.not_found')}}</td>',
                'add_new': '<tr class="item" id="tr_restriction___property_id__">\n' +
                    '	<td>\n' +
                    '		<select id="restriction_type___property_id__" class="form-control" style="width:135px" disabled>\n' +
                    '		   <option value="none"></option>\n' +
                    '		   <option value="day_of_week">Day of week</option>\n' +
                    '		   <option value="day_of_month">Day of month</option>\n' +
                    '		   <option value="hour">Hour</option>\n' +
                    '		</select>\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<select id="restriction_day_week___property_id__" class="form-control" style="width:130px" disabled>\n' +
                    '			<option value="0"></option>\n' +
                    '			<option value="1">Sunday</option>\n' +
                    '			<option value="2">Monday</option>\n' +
                    '			<option value="3">Tuesday</option>\n' +
                    '			<option value="4">Wednesday</option>\n' +
                    '			<option value="5">Thursday</option>\n' +
                    '			<option value="6">Friday</option>\n' +
                    '			<option value="7">Saturday</option>\n' +
                    '		</select>\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<select id="restriction_day_month___property_id__" class="form-control" style="width:130px" disabled>\n' +
                    '			<option value="0"></option>\n' +
                    			@for($i = 1; $i <= 31; $i++)
                    '			<option value="{{$i}}">{{$i}}</option>\n' +
                    			@endfor
                    '		</select>\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<input id="restriction_start___property_id__" disabled\n' +
                    '			   class="form-control" type="text" style="width:80px" />\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<input id="restriction_end___property_id__" disabled\n' +
                    '			   class="form-control" type="text" style="width:80px" />\n' +
                    '	</td>\n' +
                    '	<td>\n' +
                    '		<div class="btn-group">\n' +
                    '			<button id="restriction_save___property_id__" type="button"\n' +
                    '					class="btn btn-sm btn-success"\n' +
                    '					data-property="__property_id__"\n' +
                    '					style="display: none;"\n' +
                    '					onclick="actionRestriction(this,\'create\')">\n' +
                    '				<i class="fa fa-check"></i>\n' +
                    '			</button>\n' +
                    '			<button id="restriction_add___property_id__" type="button"\n' +
                    '					class="btn btn-sm btn-info"\n' +
                    '					data-property="__property_id__"\n' +
                    '					onclick="actionRestriction(this,\'add\')">\n' +
                    '				<i class="fa fa-plus"></i>\n' +
                    '			</button>\n' +
                    '			<button id="restriction_close___property_id__" type="button"\n' +
                    '					class="btn btn-sm btn-secondary"\n' +
                    '					data-property="__property_id__"\n' +
                    '					style="display: none;"\n' +
                    '					onclick="actionRestriction(this,\'exit\')">\n' +
                    '				<i class="fa fa-times"></i>\n' +
                    '			</button>\n' +
                    '		</div>\n' +
                    '	</td>\n' +
                    '</tr>\n'
            }
        }

        function getMapProperties(section) {
            const houseNumber = $('#' + section + '_house_number').val();
            const streetName = $('#' + section + '_street_name').val();
            const zipcode = $('#' + section + '_zipcode_id').val();

            const address = houseNumber + ' ' + streetName + ' ' + zipcode;

            return new Promise(function (resolve) {
                new google.maps.Geocoder().geocode({address}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        $('#' + section + '_map_lat').val(results[0].geometry.location.lat());
                        $('#' + section + '_map_lng').val(results[0].geometry.location.lng());
                        $('#' + section + '_map_address').val(results[0].formatted_address);

                        resolve({
                            status
                        });
                    } else {
                        $('#' + section + '_map_lat').val('');
                        $('#' + section + '_map_lng').val('');
                        $('#' + section + '_map_address').val('');

                        resolve({
                            status
                        });
                    }
                });
            });
        }

        function isThumbnail(image) {
            if (image == null || image == '') return '{{trans('property.image.no_thumbnail')}}';
            return '';
        }

        function getImage(image) {
            if (image['thumbnail_url'] == '' || image['thumbnail_url'] == null) return image['url'];
            return image['thumbnail_url'];
        }

        function createThumbnail(id, url, propertyId) {
            $('#images_property_modal_form :input').attr('disabled', 'disabled');
            $('#images_property_modal_croppie_wrapper').css({display: 'block'});
            $('#images_property_modal_croppie_btn, #images_property_modal_croppie_close').removeAttr('disabled');

            window.croppie = $('#images_property_modal_croppie').croppie({
                viewport: {
                    width: 382,
                    height: 220
                },
                boundary: {
                    width: 450,
                    height: 300
                }
            });

            window.croppie.croppie('bind', {
                url: url
            });

            $('#images_property_modal_croppie_btn').data('id', id);
            $('#images_property_modal_croppie_btn').data('propertyId', propertyId);
        }

        function closeCroppie() {
            $('#images_property_modal_croppie_wrapper').css({display: 'none'});
            if (window.croppie) {
                window.croppie.croppie('destroy');
                window.croppie = null;
            }
            $('#images_property_modal_form :input').removeAttr('disabled');
            $('#images_property_modal_upload').attr('disabled', 'disabled');
        }

        function imageChanges(action, id, propertyId) {
            const form = {
                'image_id': id
            };

            if (action == 'save') {
                form['active'] = $('#property_image_active_' + id).prop("checked");
                form['show_on_search'] = $('#property_image_show_on_search_' + id).prop("checked");

                RSApp.jsonPost('{{route('property_images')}}/' + propertyId + '/save', form, true, function () {
                    $('#images_property_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        console.log('save image', data);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('save image', textStatus, errorThrown);
                    })
                    .always(function () {
                        $('#images_property_modal_form :input').removeAttr('disabled');
                        loadImages(propertyId);
                    });
            }

            if (action == 'delete' && confirm('{{trans('property.image.delete')}}')) {
                RSApp.jsonPost('{{route('property_images')}}/' + propertyId + '/delete', form, true, function () {
                    $('#images_property_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        console.log('delete image', data);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('delete image', textStatus, errorThrown);
                    })
                    .always(function () {
                        $('#images_property_modal_form :input').removeAttr('disabled');
                        loadImages(propertyId);
                    });
            }
        }

        function imageChoose(file) {
            if ($(file).val()) {
                $('#images_property_modal_file_label').text(file.files[0].name);
                $('#images_property_modal_upload').removeAttr('disabled');
            } else {
                $('#images_property_modal_file_label').text('{{trans('property.btn.image.choose')}}');
            }
        }

        function uploadImage() {
            const files = $('#images_property_modal_file')[0].files;

            if (files.length > 0) {
                const file = files[0], reader = new FileReader();

                reader.onloadend = function () {
                    const property = $('#images_property_modal_file').data();

                    const form = {
                        'base_64': reader.result
                    }

                    $('#images_property_modal_file').val('').trigger('change');

                    const url = '{{route('property_images')}}/' + property['propertyId'] + '/upload';

                    RSApp.jsonPost(url, form, true, function () {
                        $('#images_property_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            console.log('upload image', data);
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.log('upload image', textStatus, errorThrown);
                        })
                        .always(function () {
                            $('#images_property_modal_form :input').removeAttr('disabled');
                            loadImages(property['propertyId']);
                        });

                };
                reader.readAsDataURL(file);
            }
        }

        function saveCroppie(btn) {
            const image = $(btn).data();

            window.croppie.croppie('result', {type: 'canvas'})
                .then(function (base_64) {
                    const form = {
                        base_64,
                        image_id: image['id']
                    };

                    RSApp.jsonPost('{{route('property_images')}}/' + image['propertyId'] + '/croppie', form, true, function () {
                        $('#images_property_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            console.log('save thumbnail', data);
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.log('save thumbnail', textStatus, errorThrown);
                        })
                        .always(function () {
                            $('#images_property_modal_form :input').removeAttr('disabled');
                            $('#images_property_modal_croppie_wrapper').css({display: 'none'});
                            if (window.croppie) {
                                window.croppie.croppie('destroy');
                                window.croppie = null;
                            }
                            loadImages(image['propertyId']);
                        });
                });
        }

        function loadImages(propertyId) {
            const list = $('#images_property_modal_list');

            RSApp.jsonPost('{{route('property_images')}}/' + propertyId + '/list', {}, true, function () {
                list.html('<div style="height: 100px;"><div class="loader spinner-3"></div></div>');
                $('#images_property_modal_form :input').attr('disabled', 'disabled');
            })
                .done(function (data) {
                    if (data.done) {
                        if (data.data.length > 0) {
                            list.html('');
                            $.each(data.data, function (i, item) {
                                const element = window.template['images']['list']
                                    .replace(/__id__/g, item['id'])
                                    .replace(/__property_id__/g, item['property_id'])
                                    .replace(/__active__/g, item['active'] ? 'checked' : '')
                                    .replace(/__show_on_search__/g, item['show_on_search'] ? 'checked' : '')
                                    .replace(/__href__/g, getImage(item))
                                    .replace(/__url__/g, item['url'])
                                    .replace(/__thumbnail__/g, isThumbnail(item['thumbnail_url']));

                                list.append(element);
                            });
                        } else {
                            list.html(window.template['images']['not_found']);
                        }
                    } else {
                        console.log('load images', data.error);
                        list.html(window.template['images']['not_found']);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    console.log('load images', textStatus, errorThrown);
                    list.html(window.template['images']['not_found']);
                })
                .always(function () {
                    $('#images_property_modal_form :input').removeAttr('disabled');
                    $('#images_property_modal_upload').attr('disabled', 'disabled');
                });
        }

        function loadAvailabilities(propertyId) {
            const list = $('#availability_property_modal_table tbody');

            RSApp.jsonPost('{{route('property_availabilities')}}/' + propertyId + '/list', {}, true, function () {
                $('#availability_property_modal_form :input').attr('disabled', 'disabled');
                list.html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner spinning"></i> {{trans('property.availability.loading')}}</td></tr>');
            })
                .done(function (data) {
                    if (data.done) {
                        if (data.data.length > 0) {
                            list.html('');
                            $.each(data.data, function (i, item) {
                                const element = window.template['availabilities']['items']
                                    .replace(/__id__/g, item['id'])
                                    .replace(/__property_id__/g, item['property_id'])
                                    .replace(/__start_date__/g, item['date_start'])
                                    .replace(/__end_date__/g, item['date_end'])
                                    .replace(/__active__/g, item['active'])
                                    .replace(/__is_active__/g, item['active'] ? 'checked="checked"' : '')

                                list.append(element);
                            });
                        } else {
                            list.html(window.template['availabilities']['not_found']);
                        }
                    } else {
                        console.log(data.error);
                        list.html(window.template['availabilities']['not_found']);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                    list.html(window.template['availabilities']['not_found']);
                })
                .always(function () {
                    $('#availability_property_modal_form :input').removeAttr('disabled');
                    list.append(window.template['availabilities']['add_new'].replace(/__property_id__/g, propertyId));
                    $('#availability_property_modal_table tr.item :input:not(:button)').attr('disabled', 'disabled');
                });
        }

        function actionAvailability(element, action) {
            const item = $(element).data();

            if (action == 'delete' && confirm('{{trans('property.availability.delete')}}')) {
                const form = {
                    'availability_id': item['id']
                };

                RSApp.jsonPost('{{route('property_availabilities')}}/' + item['property'] + '/delete', form, true, function () {
                    $('#availability_property_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        console.log('delete availability', data);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('delete availability', textStatus, errorThrown);
                    })
                    .always(function () {
                        $('#availability_property_modal_form :input').removeAttr('disabled');
                        loadAvailabilities(item['property']);
                    });
            }

            if (action == 'edit') {
                const element = '__property_id_____id__'
                    .replace('__id__', item['id'])
                    .replace('__property_id__', item['property']);

                $('#availability_start_' + element)
                    .datepicker({
                        format: 'yyyy-mm-dd',
                        startDate: new Date()
                    })
                    .on('change', function () {
                        $('#availability_end_' + element).datepicker('setDate', $(this).val());
                        $('#availability_end_' + element).datepicker('setStartDate', $(this).val());
                    });

                $('#availability_end_' + element)
                    .datepicker({
                        format: 'yyyy-mm-dd',
                        startDate: $('#availability_start_' + element).val()
                    });

                $('#tr_availability_' + element + ' :input:not(:button)').removeAttr('disabled');

                const hide = '#availability_delete_' + element + ', #availability_edit_' + element;
                const show = '#availability_save_' + element + ', #availability_close_' + element;

                $(hide).css({display: 'none'});
                $(show).css({display: 'inline-block'});
            }

            if (action == 'close') {
                const element = '__property_id_____id__'
                    .replace('__id__', item['id'])
                    .replace('__property_id__', item['property']);

                $('#tr_availability_' + element + ' :input:not(:button)').attr('disabled', 'disabled');

                $('#availability_start_' + element).datepicker('setDate', item['start']);
                $('#availability_end_' + element).datepicker('setDate', item['end']);
                $('#availability_active_' + element).prop('checked', item['active']);

                const hide = '#availability_delete_' + element + ', #availability_edit_' + element;
                const show = '#availability_save_' + element + ', #availability_close_' + element;

                $(show).css({display: 'none'});
                $(hide).css({display: 'inline-block'});
            }

            if (action == 'save') {
                const element = '__property_id_____id__'
                    .replace('__id__', item['id'])
                    .replace('__property_id__', item['property']);

                const form = {
                    'availability_id': item['id'],
                    'start': $('#availability_start_' + element).val(),
                    'end': $('#availability_end_' + element).val(),
                    'active': $('#availability_active_' + element).prop('checked')
                };

                RSApp.jsonPost('{{route('property_availabilities')}}/' + item['property'] + '/save', form, true, function () {
                    $('#availability_property_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        console.log('edit availability', data);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('edit availability', textStatus, errorThrown);
                    })
                    .always(function () {
                        $('#availability_property_modal_form :input').removeAttr('disabled');
                        loadAvailabilities(item['property']);
                    });
            }

            if (action == 'add') {
                const element = '__property_id__'
                    .replace('__property_id__', item['property']);

                $('#tr_availability_' + element + ' :input:not(:button)').removeAttr('disabled');

                $('#availability_start_' + element).val('');
                $('#availability_end_' + element).val('');
                $('#availability_active_' + element).prop('checked', true);

                $('#availability_start_' + element)
                    .datepicker({
                        format: 'yyyy-mm-dd',
                        startDate: new Date()
                    })
                    .on('change', function () {
                        $('#availability_end_' + element).datepicker('setDate', $(this).val());
                        $('#availability_end_' + element).datepicker('setStartDate', $(this).val());
                    });

                $('#availability_end_' + element)
                    .datepicker({
                        format: 'yyyy-mm-dd',
                        startDate: new Date()
                    });

                const hide = '#availability_add_' + element;
                const show = '#availability_save_' + element + ', #availability_close_' + element;

                $(hide).css({display: 'none'});
                $(show).css({display: 'inline-block'});
            }

            if (action == 'exit') {
                const element = '__property_id__'
                    .replace('__property_id__', item['property']);

                $('#tr_availability_' + element + ' :input:not(:button)').attr('disabled', 'disabled');

                $('#availability_start_' + element).datepicker('setDate', '');
                $('#availability_end_' + element).datepicker('setDate', '');
                $('#availability_active_' + element).prop('checked', true);

                const hide = '#availability_add_' + element;
                const show = '#availability_save_' + element + ', #availability_close_' + element;

                $(show).css({display: 'none'});
                $(hide).css({display: 'inline-block'});
            }

            if (action == 'create') {
                const element = '__property_id__'
                    .replace('__property_id__', item['property']);

                const form = {
                    'start': $('#availability_start_' + element).val(),
                    'end': $('#availability_end_' + element).val(),
                    'active': $('#availability_active_' + element).prop('checked')
                };

                RSApp.jsonPost('{{route('property_availabilities')}}/' + item['property'] + '/create', form, true, function () {
                    $('#availability_property_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        console.log('create availability', data);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('create availability', textStatus, errorThrown);
                    })
                    .always(function () {
                        $('#availability_property_modal_form :input').removeAttr('disabled');
                        loadAvailabilities(item['property']);
                    });
            }
        }

        function loadRestrictions(propertyId) {
            const list = $('#restriction_property_modal_table tbody');

            RSApp.jsonPost('{{route('property_restrictions')}}/' + propertyId + '/list', {}, true, function () {
                $('#restriction_property_modal_form :input').attr('disabled', 'disabled');
                list.html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner spinning"></i> {{trans('property.availability.loading')}}</td></tr>');
            })
                .done(function (data) {
                    if (data.done) {
                        if (data.data.length > 0) {
                            list.html('');
                            $.each(data.data, function (i, item) {
                                const ident = '__property_id_____id__'
                                    .replace('__id__', item['id'])
                                    .replace('__property_id__', item['property_id']);

                                const element = window.template['restrictions']['items']
                                    .replace(/__id__/g, item['id'])
                                    .replace(/__property_id__/g, item['property_id'])
                                    .replace(/__type__/g, item['restriction_type'])
                                    .replace(/__day_week__/g, item['day_of_week'])
                                    .replace(/__day_month__/g, item['day_of_month'])
                                    .replace(/__start__/g, item['start_time'] == null ? '' : item['start_time'].substring(0,5))
                                    .replace(/__end__/g, item['end_time'] == null ? '' : item['end_time'].substring(0,5));

                                list.append(element);

                                $('#restriction_type_' + ident).val(item['restriction_type']);
                                $('#restriction_day_week_' + ident).val(item['day_of_week']);
                                $('#restriction_day_month_' + ident).val(item['day_of_month']);

                            });
                        } else {
                            list.html(window.template['restrictions']['not_found']);
                        }
                    } else {
                        console.log(data.error);
                        list.html(window.template['restrictions']['not_found']);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                    list.html(window.template['restrictions']['not_found']);
                })
                .always(function () {
                    $('#restriction_property_modal_form :input').removeAttr('disabled');
                    list.append(window.template['restrictions']['add_new'].replace(/__property_id__/g, propertyId));
                    $('#restriction_property_modal_table tr.item :input:not(:button)').attr('disabled', 'disabled');
                });
        }

        function actionRestriction(element, action){
            const item = $(element).data();

            if (action == 'add') {
                const element = '__property_id__'
                    .replace('__property_id__', item['property']);

                $('#tr_restriction_' + element + ' :input:not(:button)').removeAttr('disabled');

                $('#restriction_type_' + element).val('');
                $('#restriction_day_week_' + element).val('');
                $('#restriction_day_month_' + element).val('');
                $('#restriction_start_' + element).val('');
                $('#restriction_end_' + element).val('');


                const hide = '#restriction_add_' + element;
                const show = '#restriction_save_' + element + ', #restriction_close_' + element;

                $(hide).css({display: 'none'});
                $(show).css({display: 'inline-block'});
            }

            if (action == 'create') {
                const element = '__property_id__'
                    .replace('__property_id__', item['property']);

                const form = {
                    'restriction_type': $('#restriction_type_' + element).val(),
                    'day_of_week': $('#restriction_day_week_' + element).val(),
                    'day_of_month': $('#restriction_day_month_' + element).val(),
                    'start': $('#restriction_start_' + element).val(),
                    'end': $('#restriction_end_' + element).val()
                };

                RSApp.jsonPost('{{route('property_restrictions')}}/' + item['property'] + '/create', form, true, function () {
                    $('#restriction_property_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        console.log('create restriction', data);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('create restriction', textStatus, errorThrown);
                    })
                    .always(function () {
                        $('#restriction_property_modal_form :input').removeAttr('disabled');
                        loadRestrictions(item['property']);
                    });
            }

            if (action == 'exit') {
                const element = '__property_id__'
                    .replace('__property_id__', item['property']);

                $('#tr_restriction_' + element + ' :input:not(:button)').attr('disabled', 'disabled');

                $('#restriction_type_' + element).val('');
                $('#restriction_day_week_' + element).val('');
                $('#restriction_day_month_' + element).val('');
                $('#restriction_start_' + element).val('');
                $('#restriction_end_' + element).val('');

                const hide = '#restriction_add_' + element;
                const show = '#restriction_save_' + element + ', #restriction_close_' + element;

                $(show).css({display: 'none'});
                $(hide).css({display: 'inline-block'});
            }

            //==================================================================
            if (action == 'edit') {
                const element = '__property_id_____id__'
                    .replace('__id__', item['id'])
                    .replace('__property_id__', item['property']);

                /*$('#restriction_start_' + element)
                    .datepicker({
                        format: 'yyyy-mm-dd',
                        startDate: new Date()
                    })
                    .on('change', function () {
                        $('#availability_end_' + element).datepicker('setDate', $(this).val());
                        $('#availability_end_' + element).datepicker('setStartDate', $(this).val());
                    });*/

                $('#tr_restriction_' + element + ' :input:not(:button)').removeAttr('disabled');

                const hide = '#restriction_delete_' + element + ', #restriction_edit_' + element;
                const show = '#restriction_save_' + element + ', #restriction_close_' + element;

                $(hide).css({display: 'none'});
                $(show).css({display: 'inline-block'});
            }

            if(action == 'delete' && confirm('Are you sure you want to delete this record?')) {
                const form = {
                    'restriction_id': item['id']
                };

                RSApp.jsonPost('{{route('property_restrictions')}}/' + item['property'] + '/delete', form, true, function () {
                    $('#arestriction_property_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        console.log('delete restriction', data);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('delete restriction', textStatus, errorThrown);
                    })
                    .always(function () {
                        $('#restriction_property_modal_form :input').removeAttr('disabled');
                        loadRestrictions(item['property']);
                    });
            }

            if (action == 'close') {
                const element = '__property_id_____id__'
                    .replace('__id__', item['id'])
                    .replace('__property_id__', item['property']);

                $('#tr_restriction_' + element + ' :input:not(:button)').attr('disabled', 'disabled');

                $('#restriction_type_' + element).val(item['restriction_type']);
                $('#restriction_day_week_' + element).val(item['day_week']);
                $('#restriction_day_month_' + element).val(item['day_month']);
                $('#restriction_start_' + element).val(item['start']);
                $('#restriction_end_' + element).val(item['end']);


                const hide = '#restriction_delete_' + element + ', #restriction_edit_' + element;
                const show = '#restriction_save_' + element + ', #restriction_close_' + element;

                $(show).css({display: 'none'});
                $(hide).css({display: 'inline-block'});
            }

            if (action == 'save') {
                const element = '__property_id_____id__'
                    .replace('__id__', item['id'])
                    .replace('__property_id__', item['property']);

                const form = {
                    'restriction_id': item['id'],
                    'restriction_type': $('#restriction_type_' + element).val(),
                    'day_of_week': $('#restriction_day_week_' + element).val(),
                    'day_of_month': $('#restriction_day_month_' + element).val(),
                    'start': $('#restriction_start_' + element).val(),
                    'end': $('#restriction_end_' + element).val()
                };

                RSApp.jsonPost('{{route('property_restrictions')}}/' + item['property'] + '/save', form, true, function () {
                    $('#restriction_property_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        console.log('edit restriction', data);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('edit restriction', textStatus, errorThrown);
                    })
                    .always(function () {
                        $('#restriction_property_modal_form :input').removeAttr('disabled');
                        loadRestrictions(item['property']);
                    });
            }
        }

        function loadSearchCriteria(propertyId){
            $('#add_criterion_modal_property_id').val(propertyId);

            RSApp.jsonPost("{{route('properties_criteria')}}/" + propertyId, this, false, null)
                .done(function (data) {
                    //console.log('property_criteria', data);
                   //===========================
                    var tableRef = document.getElementById('property_criteria_table').getElementsByTagName('tbody')[0];

                    //clean criteria table
                    while(tableRef.rows.length > 0) {
                        tableRef.deleteRow(0);
                    }

                    var index;
                    for (index = 0; index < data.length; ++index) {

                        // Insert a row in the table at the last row
                        var newRow   = tableRef.insertRow();

                        // Insert a cell in the row at index 0
                        var newCell1  = newRow.insertCell(0);
                        var newCell2  = newRow.insertCell(1);
                        var newCell3  = newRow.insertCell(2);
                        var newCell4  = newRow.insertCell(3);
                        var newCell5  = newRow.insertCell(4);
                        var newCell6  = newRow.insertCell(5);

                        // Append a text node to the cell
                        //group
                        newCell1.innerHTML = data[index].search_criterion.criterion_type.name;
                        //criteria
                        newCell2.innerHTML = data[index].search_criterion.name;

                        //quantity
                        if(data[index].has_quantity === 1) {
                            newCell3.innerHTML = data[index].quantity;
                        } else {
                            newCell3.innerHTML = '';
                        }
                        //walking distance
                        if(data[index].has_distance === 1) {
                            newCell4.innerHTML = data[index].walking_distance + ' Minutes';
                        } else {
                            newCell4.innerHTML = '';
                        }

                        //driving distance
                        if(data[index].has_distance === 1) {
                            newCell5.innerHTML = data[index].driving_distance + ' Minutes';
                        } else {
                            newCell5.innerHTML = '';
                        }

                        //Action
                        newCell6.innerHTML = '<div class="btn-group">' +
                            '<button type="button" class="btn btn-danger btn-sm delete-criteria-button"' +
                            'onclick="deletePropertyCriterion(' + data[index].property_id + ', ' + data[index].criterion_id+');">' +
                            '<i class="fa fa-times" title="{{trans('criteria.title.delete_criterion')}}"></i>' +
                            '</button>' +
                            '</div>';
                    }
                   //===========================
                })
                .fail(function (jqXHR, textStatus, errorThrown){
                    console.log(errorThrown);
                });
        }

        function deletePropertyCriterion(propertyId, criterionId) {
                RSApp.jsonPost("{{route('delete_property_criteria')}}/" + propertyId + '/' + criterionId, this, false, null)
                    .done(function (data) {
                        //console.log(data);
                        //===========================
                        loadSearchCriteria(propertyId);
                        //===========================
                    })
                    .fail(function (jqXHR, textStatus, errorThrown){
                        console.log(errorThrown);
                    });
        }

        //=============================================================
        //=============================================================
        function detailControlClick(element, propertyId, holidayId){
            var tr = $(element).closest('tr');

            if(tr.hasClass('shown')){
                $('#packages_inner_table_' + propertyId + '_' + holidayId).hide();
                tr.removeClass('shown');
            } else {
                $('#packages_inner_table_' + propertyId + '_' + holidayId).show();
                tr.addClass('shown');
            }
        }

        let dataOriginals;

        function loadPackages(propertyId){
            const listJEW = $('#packages_property_modal_jew_table tbody');
            const listUS = $('#packages_property_modal_us_table tbody');
            const listWK = $('#packages_property_modal_weekend_table tbody');

            RSApp.jsonPost('{{route('property_packages')}}/' + propertyId + '/list', {}, true, function(){
                $('#packages_property_modal_form :input').attr('disabled', 'disabled');
                listJEW.html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner spinning"></i> {{trans('property.availability.loading')}}</td></tr>');
                listUS.html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner spinning"></i> {{trans('property.availability.loading')}}</td></tr>');
                listWK.html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner spinning"></i> {{trans('property.availability.loading')}}</td></tr>');
            }).done(function(data){
                if(data.done) {
                    if(data.data.length > 0) {
                        console.log('data', data);

                        dataOriginals = data;

                        listJEW.html('');
                        listUS.html('');
                        listWK.html('');

                        const headerTemplate = '<tr class="item mb-0" id="tr_package_header___property_id_____holiday_id__">\n' +
                            '   <td class="details-control" style="width: 1% !important;" onclick="detailControlClick(this,__property_id__,__holiday_id__)"></td>\n' +
                            '	<td>\n'+
                            '       <div class="row">\n'+
                            '           <div class="col-5"><h5>__holiday_name__</h5></div>\n'+
                            '           <div class="col" style="visibility:__from_hide__;">from __holiday_start__ to __holiday_end__</div>\n'+
                            '       </div>\n'+
                            '   </td>\n' +
                            '</tr>\n' +

                            '<tr>\n' +
                            '<td></td>\n' +
                            '   <td>\n' +
                            '       <table class="table mb-0 table-striped collapse" id="packages_inner_table___property_id_____holiday_id__">\n'+
                            '	        <thead>\n'+
                            '	            <tr>\n'+
                            '		            <th>Check-in</th>\n'+
                            '		            <th>Checkout</th>\n'+
                            '		            <th>Price</th>\n'+
                            '		            <th>Active</th>\n'+
                            '		            <th>Actions</th>\n'+
                            '	            </tr>\n'+
                            '	        </thead>\n'+
                            '	        <tbody>\n';

                        const footerTemplate = '	        </tbody>\n'+
                            '       </table>\n' +
                            '   </td>\n' +
                            '</tr>\n';

                        const itemTemplate = '<tr class="item" id="tr_package___property_id_____holiday_id_____package_id__">\n' +
                            //'	<td>__holiday_name__</td>\n' +

                            //'	<td style="line-height: 0.8!important;">__holiday_date_checkin__</td>\n' +
                            '<td><input id="package_start___property_id_____holiday_id_____package_id__" class="form-control" type="text" style="width:100px" value="__start_date__"/></td>\n' +

                            //'	<td style="line-height: 0.8!important;">__holiday_date_checkout__</td>\n' +
                            '<td><input id="package_end___property_id_____holiday_id_____package_id__" class="form-control" type="text" style="width:100px" value="__end_date__"/></td>\n' +

                            '	<td style="line-height: 0.8!important;">\n' +
                            '<input id="package_price___property_id_____holiday_id_____package_id__" class="form-control" type="text"\n'+
                            'style="width:80px" value="__price__" disabled/>\n' +
                            '</td>\n' +

                            '   <td style="line-height: 0.8!important;"><input id="package_active___property_id_____holiday_id_____package_id__" type="checkbox" ' +
                            '       __holiday_active__ /></td>\n' +

                            '	<td style="line-height: 0.8!important;">\n' +
                            '       <div class="btn-group">\n'+
                            '			<button type=button class="btn btn-sm btn-success"\n' +
                            '                   id="package_save___property_id_____holiday_id_____package_id__"\n' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="__package_id__"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price__"' +
                            '                   data-active="__active__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '					onclick="actionPackages(this, \'save\')"\n' +
                            '					style="display:none;"><i class="fa fa-check"></i>\n'+
                            '           </button>\n' +

                            '			<button type=button class="btn btn-sm btn-warning"\n' +
                            '                   id="package_edit___property_id_____holiday_id_____package_id__"\n' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="__package_id__"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price__"' +
                            '                   data-active="__active__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '					onclick="actionPackages(this, \'edit\')">\n' +
                            '					<i class="fa fa-pencil"></i>\n'+
                            '           </button>\n' +

                            '			<button type=button class="btn btn-sm btn-warning"\n' +
                            '                   id="package_cancel___property_id_____holiday_id_____package_id__"\n' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="__package_id__"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price__"' +
                            '                   data-active="__active__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '					onclick="actionPackages(this, \'cancel\')"\n' +
                            '					style="display:none;"><i class="fa fa-times"></i>\n'+
                            '           </button>\n' +

                            '       </div>\n' +
                            '	</td>\n' +
                            '</tr>\n';

                        //==================================================================================================================
                        const allWeekendsTemplate = '<tr class="item mb-0" id="tr_package_header___property_id_____holiday_id__">\n' +
                            '   <td class="details-control" style="width: 1% !important;" onclick="detailControlClick(this,__property_id__,__holiday_id__)"></td>\n' +
                            '	<td>\n'+
                            '       <div class="row">\n'+
                            '           <div class="col-5"><h5>__holiday_name__</h5></div>\n'+
                            '       </div>\n'+
                            '   </td>\n' +
                            '</tr>\n' +

                            '<tr>\n' +
                            '<td></td>\n' +
                            '   <td>\n' +
                            '       <table class="table mb-0 table-striped collapse" id="packages_inner_table___property_id_____holiday_id__">\n'+
                            '	        <thead>\n'+
                            '	            <tr>\n'+
                            '		            <th>Check-in & Checkout</th>\n'+
                            '		            <th>Price</th>\n'+
                            '		            <th>Active</th>\n'+
                            '		            <th>Actions</th>\n'+
                            '	            </tr>\n'+
                            '	        </thead>\n'+
                            '	        <tbody>\n'+
                            //=========================================================================================
                            '<tr class="item" id="tr_package___property_id_____holiday_id___1">\n' +
                            '<td>From Friday 2:00PM to 2 hours after Shabbat</td>\n' +

                            '	<td style="line-height: 0.8!important;">\n' +
                            '<input id="package_price___property_id_____holiday_id___1" class="form-control" type="text"\n'+
                            'style="width:80px" value="__price_1__" ' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="1"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_1__"' +
                            '                   data-active="__active_1__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '                   onclick="actionPackages(this, \'edit\')" />\n' +
                            '</td>\n' +

                            '   <td style="line-height: 0.8!important;"><input id="package_active___property_id_____holiday_id___1" type="checkbox" ' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="1"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_1__"' +
                            '                   data-active="__active_1__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '                   onclick="actionPackages(this, \'edit\')" ' +
                            '       __holiday_active_1__ /></td>\n' +

                            '	<td style="line-height: 0.8!important;">\n' +
                            '       <div class="btn-group">\n'+
                            '			<button type=button class="btn btn-sm btn-success"\n' +
                            '                   id="package_save___property_id_____holiday_id___1"\n' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="1"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_1__"' +
                            '                   data-active="__active_1__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '					onclick="actionPackages(this, \'save\')"\n' +
                            '					style="display:none;"><i class="fa fa-check"></i>\n'+
                            '           </button>\n' +

                            '			<button type=button class="btn btn-sm btn-warning"\n' +
                            '                   id="package_edit___property_id_____holiday_id___1"\n' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="1"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_1__"' +
                            '                   data-active="__active_1__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '                   style="display:none;">\n' +
                            '					onclick="actionPackages(this, \'edit\')">\n' +
                            '					<i class="fa fa-pencil"></i>\n'+
                            '           </button>\n' +

                            '			<button type=button class="btn btn-sm btn-warning"\n' +
                            '                   id="package_cancel___property_id_____holiday_id___1"\n' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="1"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_1__"' +
                            '                   data-active="__active_1__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '					onclick="actionPackages(this, \'cancel\')"\n' +
                            '					style="display:none;"><i class="fa fa-times"></i>\n'+
                            '           </button>\n' +

                            '       </div>\n' +
                            '	</td>\n' +
                            '</tr>\n' +

                            '<tr class="item" id="tr_package___property_id_____holiday_id___2">\n' +
                            '<td>From Friday 2:00PM to Sunday 10:00AM</td>\n' +

                            '	<td style="line-height: 0.8!important;">\n' +
                            '<input id="package_price___property_id_____holiday_id___2" class="form-control" type="text"\n'+
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="2"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_2__"' +
                            '                   data-active="__active_2__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '                   onclick="actionPackages(this, \'edit\')" ' +
                            '                   style="width:80px" value="__price_2__" disabled/>\n' +
                            '</td>\n' +

                            '   <td style="line-height: 0.8!important;"><input id="package_active___property_id_____holiday_id___2" type="checkbox" ' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="2"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_2__"' +
                            '                   data-active="__active_2__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '                   onclick="actionPackages(this, \'edit\')" ' +
                            '       __holiday_active_2__ /></td>\n' +

                            '	<td style="line-height: 0.8!important;">\n' +
                            '       <div class="btn-group">\n'+
                            '			<button type=button class="btn btn-sm btn-success"\n' +
                            '                   id="package_save___property_id_____holiday_id___2"\n' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="2"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_2__"' +
                            '                   data-active="__active_2__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '					onclick="actionPackages(this, \'save\')"\n' +
                            '					style="display:none;"><i class="fa fa-check"></i>\n'+
                            '           </button>\n' +

                            '			<button type=button class="btn btn-sm btn-warning"\n' +
                            '                   id="package_edit___property_id_____holiday_id___2"\n' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="2"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_2__"' +
                            '                   data-active="__active_2__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '					onclick="actionPackages(this, \'edit\')" ' +
                            '                   style="display:none;">\n' +
                            '					<i class="fa fa-pencil"></i>\n'+
                            '           </button>\n' +

                            '			<button type=button class="btn btn-sm btn-warning"\n' +
                            '                   id="package_cancel___property_id_____holiday_id___2"\n' +
                            '                   data-holidaytype="__holiday_type__"' +
                            '                   data-propertyid="__property_id__"' +
                            '                   data-holidayid="__holiday_id__"' +
                            '                   data-packageid="2"' +
                            '                   data-start="__start_date__"' +
                            '                   data-end="__end_date__"' +
                            '                   data-price="__price_2__"' +
                            '                   data-active="__active_2__"' +
                            '                   data-disabled_dates="__disabled_dates__"' +
                            '					onclick="actionPackages(this, \'cancel\')"\n' +
                            '					style="display:none;"><i class="fa fa-times"></i>\n'+
                            '           </button>\n' +

                            '       </div>\n' +
                            '	</td>\n' +
                            '</tr>\n' +
                            //=========================================================================================
                            '	        </tbody>\n'+
                            '       </table>\n' +
                            '   </td>\n' +
                            '</tr>\n';

                        //all weekends
                        let aWLength = data.all_weekend.length;
                        let aW1 = aWLength > 0 ? data.all_weekend[0] : {};
                        let aW2 = aWLength > 1 ? data.all_weekend[1] : {};

                        let all_weekends = allWeekendsTemplate
                            .replace(/__property_id__/g, propertyId)
                            .replace(/__holiday_id__/g, '0')
                            .replace(/__holiday_name__/g, 'All Weekends')
                            .replace(/__holiday_type__/g, 'all_weekend')

                            .replace(/__price_1__/g, aWLength > 0 ? aW1.price : '')
                            .replace(/__active_1__/g, aWLength > 0 ? aW1.active : '')
                            .replace(/__holiday_active_1__/g, aWLength > 0 ? (aW1.active ? 'checked="checked"' : '') : '')

                            .replace(/__price_2__/g, aWLength > 1 ? aW2.price : '')
                            .replace(/__active_2__/g, aWLength > 1 ? aW2.active : '')
                            .replace(/__holiday_active_2__/g, aWLength > 1 ? (aW2.active ? 'checked="checked"' : '') : '');

                        listWK.append(all_weekends);

                        //==================================================================================================================
                        $.each(data.data, function(i, item){
                            let element = headerTemplate
                                .replace(/__property_id__/g, item['property_id'])
                                .replace(/__holiday_id__/g, item['holiday_id'])
                                .replace(/__holiday_name__/g, item['name'])
                                .replace(/__holiday_start__/g, item['date_start'])
                                .replace(/__holiday_end__/g, item['date_end'])
                                .replace(/__from_hide__/g, (item['holiday_type'] === 'weekend' ? 'hidden' : 'visible'));

                            if(item['packages'].length > 0) {
                                $.each(item['packages'], function(i, packItem){
                                    element += itemTemplate
                                        .replace(/__holiday_type__/g, item['holiday_type'])
                                        .replace(/__property_id__/g, item['property_id'])
                                        .replace(/__holiday_id__/g, item['holiday_id'])
                                        .replace(/__package_id__/g, packItem['package_id'])
                                        .replace(/__holiday_name__/g, item['name'])
                                        .replace(/__start_date__/g, packItem['date_checkin'])
                                        .replace(/__end_date__/g, packItem['date_checkout'])
                                        .replace(/__price__/g, packItem['price'])
                                        .replace(/__active__/g, packItem['active'])
                                        .replace(/__holiday_active__/g, packItem['active'] ? 'checked="checked"' : '')
                                        .replace(/__disabled_dates__/g, item['disabled_dates'].join(","));
                                });
                            }

                            element += itemTemplate
                                .replace(/__holiday_type__/g, item['holiday_type'])
                                .replace(/__property_id__/g, item['property_id'])
                                .replace(/__holiday_id__/g, item['holiday_id'])
                                .replace(/__package_id__/g, '0')
                                .replace(/__holiday_name__/g, item['name'])
                                .replace(/__start_date__/g, item['date_checkin'])
                                .replace(/__end_date__/g, item['date_checkout'])
                                .replace(/__price__/g, '')
                                .replace(/__active__/g, false)
                                .replace(/__holiday_active__/g, '')
                                .replace(/__disabled_dates__/g, item['disabled_dates'].join(","));

                            element += footerTemplate;

                            //jew
                            if(item['holiday_type'] === 'jew'){
                                listJEW.append(element);
                            }

                            //us
                            if(item['holiday_type'] === 'us'){
                                listUS.append(element);
                            }
                            //weekend
                            if(item['holiday_type'] === 'weekend'){
                                listWK.append(element);
                            }
                        });
                    } else {
                        listJEW.html(window.template['restrictions']['not_found']);
                        listUS.html(window.template['restrictions']['not_found']);
                        listWK.html(window.template['restrictions']['not_found']);
                    }
                }else {
                    console.log(data.error);
                    listJEW.html(window.template['restrictions']['not_found']);
                    listUS.html(window.template['restrictions']['not_found']);
                    listWK.html(window.template['restrictions']['not_found']);
                }
            }).fail(function (jqXHR, textStatus, errorThrown){
                console.log(errorThrown);
                listJEW.html(window.template['restrictions']['not_found']);
                listUS.html(window.template['restrictions']['not_found']);
                listWK.html(window.template['restrictions']['not_found']);
            }).always(function(){
                $('#packages_property_modal_form :input').removeAttr('disabled');

                $('#packages_property_modal_jew_table tr.item :input:not(:button)').attr('disabled', 'disabled');
                $('#packages_property_modal_us_table tr.item :input:not(:button)').attr('disabled', 'disabled');
                //$('#packages_property_modal_weekend_table tr.item :input:not(:button)').attr('disabled', 'disabled');
            });
        }

        function actionPackages(element, action) {
            let item = $(element).data();

            const ident = '__property_id_____holiday_id_____package_id__'
                    .replace('__property_id__', item['propertyid'])
                    .replace('__holiday_id__', item['holidayid'])
                    .replace('__package_id__', item['packageid']);

            if (action === 'edit') {
                console.log('editing', ident);

                //$('#package_start_' + ident).val('');
                //$('#package_end_' + ident).val('');

                var arrayDisabledDates = item['disabled_dates'].split(',');

                $('#package_start_' + ident)
                .datepicker({
                    autoclose: true,
                    container: '#packages_property_modal_tab_'+ item['holidaytype'] +'_holidays',
                    zIndexOffset: 100,
                    format: 'yyyy-mm-dd',
                    //startDate: new Date(),
                    defaultViewDate: item['start'],
                    //daysOfWeekDisabled: '6',
                    //datesDisabled: arrayDisabledDates,
                    beforeShowDay: function (date) {
                        var string_date = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+date.getDate()).slice(-2);
                        var search_index = $.inArray(string_date, arrayDisabledDates);

                        if(search_index > -1){
                            return {classes: 'highlight_holiday'};
                        }

                        if(date.getDay() === 0){
                            return {classes: 'highlight_sunday'};
                        }

                        if(date.getDay() === 6){
                            return {classes: 'highlight_saturday'};
                        }
                    }
                });

                $('#package_end_' + ident)
                    .datepicker({
                        autoclose: true,
                        container: '#packages_property_modal_tab_'+ item['holidaytype'] +'_holidays',
                        format: 'yyyy-mm-dd',
                        //startDate: new Date(),
                        defaultViewDate: item['end'],
                        //daysOfWeekDisabled: '6',
                        //datesDisabled: arrayDisabledDates,
                        beforeShowDay: function (date) {
                            var string_date = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+date.getDate()).slice(-2);
                            var search_index = $.inArray(string_date, arrayDisabledDates);

                            if(search_index > -1){
                                return {classes: 'highlight_holiday'};
                            }

                            if(date.getDay() === 0){
                                return {classes: 'highlight_sunday'};
                            }

                            if(date.getDay() === 6){
                                return {classes: 'highlight_saturday'};
                            }
                        }
                    });

                /*
                .on('show', function(e) {
                    $('#packages_property_modal_tab_link_'+ item['holidaytype'] +'_holidays').addClass('active');
                    $('#packages_property_modal_tab_link_'+ item['holidaytype'] +'_holidays').addClass('show');

                    $('#packages_property_modal_tab_'+ item['holidaytype'] +'_holidays').addClass('active');
                    $('#packages_property_modal_tab_'+ item['holidaytype'] +'_holidays').addClass('show');
                })
                */

                console.log(item['packageid']);
                if(item['packageid'] === 0) {
                    $('#package_start_' + ident).val('');
                    $('#package_end_' + ident).val('');
                }

                $('#tr_package_' + ident + ' :input:not(:button)').removeAttr('disabled');
                const hide = '#package_edit_' + ident;
                const show = '#package_save_' + ident + ', #package_cancel_' + ident;

                $(hide).css({display: 'none'});
                $(show).css({display: 'inline-block'});
            }

            if (action === 'cancel') {
                //return original values
                $('#package_start_' + ident).val(item['start']);
                $('#package_end_' + ident).val(item['end']);
                $('#package_price_' + ident).val(item['price']);
                //$('#package_active_' + ident).val(item['active']);
                document.getElementById('package_active_' + ident).checked = item['active'];

                //$('#tr_package_' + ident + ' :input:not(:button)').attr('disabled', 'disabled');

                const hide = '#package_save_' + ident + ', #package_cancel_' + ident;
                //const show = '#package_edit_' + ident;

                $(hide).css({display: 'none'});
                //$(show).css({display: 'inline-block'});
            }

            if (action === 'save') {
                //todo post values
                const form = {
                    'property_id': item['propertyid'],
                    'holiday_id': item['holidayid'],
                    'package_id': item['packageid'],
                    'check_in': $('#package_start_' + ident).val(),
                    'check_out': $('#package_end_' + ident).val(),
                    'price': $('#package_price_' + ident).val(),
                    'active': $('#package_active_' + ident).prop('checked')
                };

                RSApp.jsonPost('{{route('property_packages')}}/' + item['propertyid'] + '/save', form, true, null)
                    .done(function (data) {
                        //$(element).data('packageid', data.package_id);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log('save package', textStatus, errorThrown);
                    })
                    .always(function (data) {
                        //$('#tr_package_' + ident + ' :input:not(:button)').attr('disabled', 'disabled');

                        const hide = '#package_save_' + ident + ', #package_cancel_' + ident;
                        const show = '#package_edit_' + ident;

                        $(hide).css({display: 'none'});
                        //$(show).css({display: 'inline-block'});

                        if(item['packageid'] === 0) {
                            loadPackages(item['propertyid']);
                        }
                    });
                //======
            }
        }
        //=============================================================
        //=============================================================
        function addPropertyClick(userId){
            $('#add_property_modal').modal('show');

            /*RSApp.jsonPost("{{ route('user_fee_config') }}/" + userId).done(function (data) {
                console.log(data);
                if (data.stripe_account_completed === 1 && data.has_credit_card >= 1) {
                    $('#add_property_modal').modal('show');
                } else {
                    let html = '<h4>To be able to post a property you must connect your user with Stripe and attach a valid credit card to your account.</h4>';
                    $('#user_fee_property_modal').find('.modal-body').html(html);
                    $('#user_fee_property_modal').modal('show');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
            });*/
        }

        async function createPropertyClick(form){
            //check add
            RSApp.jsonPost("{{ route('property_check_add') }}", form, false, function () {
                $('#add_property_modal_form :input').attr('disabled', 'disabled');
            })
                .done(function (data) {
                    if (data.done) {
                        //show payment
                        $('#payment_modal').modal('show');
                    } else {
                        $('#add_property_modal_alert_danger').html(data.error).fadeIn();
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status == 422) {
                        RSApp.inputValidation(jqXHR['responseJSON'], 'add_property_modal_');
                    } else {
                        $('#add_property_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                    }
                })
                .always(function () {
                    $('#add_property_modal_form :input').removeAttr('disabled');
                });
        }

        async function saveProperty(form){
            console.log(await getMapProperties('add_property_modal'));
            RSApp.jsonPost("{{ route('property_add') }}", form, false, function () {
                $('#add_property_modal_form :input').attr('disabled', 'disabled');
            })
                .done(function (data) {
                    if (data.done) {
                        $('#add_property_modal').modal('hide');
                        RSApp.alert('{{ trans('general.done') }}', '{{ trans('property.added') }}', 'success', false);
                        RSApp.clearForm('add_property_modal');
                        $('#property_datatable').DataTable().ajax.reload();
                    } else {
                        $('#add_property_modal_alert_danger').html(data.error).fadeIn();
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status == 422) {
                        RSApp.inputValidation(jqXHR['responseJSON'], 'add_property_modal_');
                    } else {
                        $('#add_property_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                    }
                })
                .always(function () {
                    $('#add_property_modal_form :input').removeAttr('disabled');
                });
        }
        //=============================================================
        //=============================================================

        (async function ($) {
            let property_edit = 0;

            const propertyTable = $('#property_datatable').DataTable({
                order: [[0, 'desc']],
                ajax: '{{ route('properties_datatable') }}',
                "columns": [
                    {data: 'DT_Row_Index'},
                        @if($user->is_broker)
                    {
                        data: 'owner_full_name'
                    },
                    {
                        data: 'phone_number', name:'phone_number'
                    },
                        @endif
                    {
                        data: 'title', name: 'title'
                    },
                    {data: 'property_type_text'},
                    {data: 'zipcode_id'},
                    {data: 'price', name: 'price', mRender: $.fn.dataTable.render.number(',','.',2)},
                    {data: 'hall_price', name: 'hall_price', mRender: function(data, type, row){
                            return (row['second_property_type'] === 'short_term_com_hall') ? formatCurrency(data): '-';
                        }
                    },
                    {data: 'cancellation_type_text'},
                    {data: 'active_text'},
                    {
                        sortable: false,
                        width: 115,
                        mRender: function (data, type, row) {
                            var butPauseIcon = (row['is_paused'] == 1 ? 'fa-pause' : 'fa-play');
                            var butPauseColor = (row['is_paused'] == 1 ? 'btn-secondary' : 'btn-primary');

                             return '<div class="d-flex justify-content-center"><div class="btn-group">' +
                                '   <button class="btn ' + butPauseColor + ' btn-sm paused-button">' +
                                '       <i class="fa ' + butPauseIcon +'" title="Pause / Resume"></i>' +
                                '   </button>' +

                                '   <button class="btn btn-success btn-sm package-button">' +
                                '       <i class="fa fa-usd" title="Property\'s packages"></i>' +
                                '   </button>' +

                                '   <button class="btn btn-info btn-sm edit-button">' +
                                '       <i class="fa fa-pencil" title="{{trans('property.edit_property_title')}}"></i>' +
                                '   </button>' +
                                '   <button class="btn btn-outline-info btn-sm images-button">' +
                                '       <i class="fa fa-file-image-o" title="{{trans('property.images_property_title')}}"></i>' +
                                '   </button>' +
                                '   <button class="btn btn-warning btn-sm availability-button">' +
                                '       <i class="fa fa-calendar" title="{{trans('property.availability_property_title')}}"></i>' +
                                '   </button>' +
                                 '   <button class="btn btn-secondary btn-sm restriction-button">' +
                                 '       <i class="fa fa-ban" title="Edit Restrictions"></i>' +
                                 '   </button>' +
                                '   <button class="btn btn-danger btn-sm delete-button">' +
                                '       <i class="fa fa-times" title="{{trans('property.delete_property_title')}}"></i>' +
                                '   </button>' +
                                '</div></div>';
                        }
                    }
                ]
            });

            $('[role="cancellation-type"]').on('change', function (e) {
                const type = $(this).val();
                const target = $('#' + $(this).data('target'));
                const source = $('#' + $(this).data('source'));

                target.attr('readonly', 'readonly');

                if (type == '{{\SMD\Common\ReservationSystem\Enums\CancellationType::FULL}}') {
                    target.val(source.val());
                }

                if (type == '{{\SMD\Common\ReservationSystem\Enums\CancellationType::PARTIAL}}') {
                    var targetNumber = Number(target.val());
                    var sourceNumber = Number(source.val());
                    if (targetNumber >= sourceNumber) {
                        target.val('0.00')
                    }
                    target.removeAttr('readonly', 'readonly');
                }

                if (type == '{{\SMD\Common\ReservationSystem\Enums\CancellationType::NONE}}') {
                    target.val('0.00');
                }
            });

            $('[role="price"]').on('change', function () {
                const target = $('#' + $(this).data('target'));
                const type = target.val();

                if (type == '{{\SMD\Common\ReservationSystem\Enums\CancellationType::FULL}}') {
                    target.trigger('change');
                }
            });

            //=========================================================================
            //=========================================================================
            $('[data-role="short-term-type"]').on('change', function (e) {
                const type = $(this).val();
                const target1 = $('#' + $(this).data('target1'));
                const target2 = $('#' + $(this).data('target2'));
                const target3 = $('#' + $(this).data('target3'));
                const target4 = $('#' + $(this).data('target4'));
                const target5 = $('#' + $(this).data('target5'));
                const target6 = $('#' + $(this).data('target6'));
                const target7 = $('#' + $(this).data('target7'));
                const target8 = $('#' + $(this).data('target8'));
                const target9 = $('#' + $(this).data('target9'));
                const target10 = $('#' + $(this).data('target10'));
                const target11 = $('#' + $(this).data('target11'));
                const target12 = $('#' + $(this).data('target12'));

                if (type == '{{\SMD\Common\ReservationSystem\Enums\PropertyType::SHORT_TERM_RES_APT_ROOM}}'
                    || type == '{{\SMD\Common\ReservationSystem\Enums\PropertyType::SHORT_TERM_RES_HOU_ROOM}}'
                    || type == '{{\SMD\Common\ReservationSystem\Enums\PropertyType::SHORT_TERM_POOL}}'
                    || type == '{{\SMD\Common\ReservationSystem\Enums\PropertyType::SHORT_TERM_PARKING}}'
                    || type == '{{\SMD\Common\ReservationSystem\Enums\PropertyType::SHORT_TERM_COM_OFFICE}}'
                    || type == '{{\SMD\Common\ReservationSystem\Enums\PropertyType::SHORT_TERM_COM_WAREHOUSE}}'
                    || type == '{{\SMD\Common\ReservationSystem\Enums\PropertyType::SHORT_TERM_COM_HALL}}'
                ) {
                    target1.show();
                    target2.show();
                    target3.show();
                    target4.show();
                    target5.show();
                    target6.show();
                    target7.show();
                    target8.show();
                    target9.show();
                    target10.show();
                    target11.show();
                    target12.show();
                } else {
                    target1.hide();
                    target2.hide();
                    target3.hide();
                    target4.hide();
                    target5.hide();
                    target6.hide();
                    target7.hide();
                    target8.hide();
                    target9.hide();
                    target10.hide();
                    target11.hide();
                    target12.hide();
                }
            });
            //=========================================================================
            //=========================================================================

            $('#add_property_modal_form').submit(async function (e) {
                $('#add_property_modal_alert_danger').fadeOut();
                $('#add_property_modal_alert_success').fadeOut();
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();

                    if($('#add_property_modal_property_billing_mode').val() === 'subscription') {
                        await createPropertyClick(this);
                    } else {
                        await saveProperty(this);
                    }
                    /*console.log(await getMapProperties('add_property_modal'));
                    RSApp.jsonPost("{{ route('property_add') }}", this, false, function () {
                        $('#add_property_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#add_property_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('property.added') }}', 'success', false);
                                RSApp.clearForm('add_property_modal');
                                propertyTable.ajax.reload();
                            } else {
                                $('#add_property_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_property_modal_');
                            } else {
                                $('#add_property_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#add_property_modal_form :input').removeAttr('disabled');
                        });*/
                }
            });

            $('#add_property_modal_property_billing_mode').on('change', function(e){
                /*
                if($('#add_property_modal_property_billing_mode').val() === 'subscription'){
                    let html = '<h4>To post the property by subscription, $15.00 will be charge to your credit card.</h4>';
                    $('#user_fee_pay_property_modal').find('.modal-body').html(html);
                    $('#user_fee_pay_property_modal').modal('show');

                    $('#add_property_modal_property_billing_mode').val('commission');
                }*/
            });

            $('#user_fee_pay_property_modal_button').click(function () {
                $('#user_fee_pay_property_modal').modal('hide');
                $('#add_property_modal_property_billing_mode').val('subscription');
            });

            //========================================================================
            //========================================================================
            $('#payment_modal_form').submit(async function (e) {
                $('#payment_modal_alert_danger').fadeOut();
                $('#payment_modal_alert_success').fadeOut();
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();

                    RSApp.jsonPost("{{ route('cc_payment') }}", this, false, function () {
                        $('#payment_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(async function (data) {
                            if (data.done) {
                                //save property
                                await saveProperty($('#add_property_modal_form'));

                                $('#payment_modal').modal('hide');
                                RSApp.clearForm('payment_modal');
                            } else {
                                $('#payment_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_profile_source_modal_');
                            } else {
                                $('#payment_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#payment_modal_form :input').removeAttr('disabled');
                        });

                }
            });
            //========================================================================
            //========================================================================

            $('#edit_property_modal_form').submit(async function (e) {
                $('#edit_property_modal_alert_danger').fadeOut();
                $('#edit_property_modal_alert_success').fadeOut();
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    console.log(await getMapProperties('edit_property_modal'));
                    RSApp.jsonPost("{{ route('property_edit') }}/" + property_edit, this, false, function () {
                        $('#edit_property_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#edit_property_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('property.edited') }}', 'success', false);
                                RSApp.clearForm('edit_property_modal');
                                propertyTable.ajax.reload();
                            } else {
                                $('#edit_property_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'edit_property_modal_');
                            } else {
                                $('#edit_property_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#edit_property_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#property_datatable').on('click', '.paused-button', function () {
                const property = propertyTable.row($(this).parents('tr')).data();

                var pauseMsg = (property.is_paused == 1 ? 'Resume' : 'Pause');
                var pausemodalId = (property.is_paused == 1 ? '#resume_property_modal' : '#pause_property_modal');

                $(pausemodalId + '_button').data('property-id', property.id);

                let html = '<h4>Please confirm you want to ' + pauseMsg + ' this property for listing.</h4>';
                html += '<p class="m-0"><strong>{{ trans('general.title') }}:</strong> ' + property.title + '</p>';
                html += '<p class="m-0"><strong>{{ trans('property.owner') }}:</strong> ' + property.owner.first_name + ' ' + property.owner.last_name + '</p>';
                html += '<p class="m-0"><strong>{{ trans('property.zipcode') }}:</strong> ' + property.zipcode_id + '</p>';
                html += '<p class="m-0"><strong>{{ trans('property.street_name') }}:</strong> ' + property.street_name + '</p>';
                $(pausemodalId).find('.modal-body').html(html);
                $(pausemodalId).modal('show');
            });

            $('#property_datatable').on('click', '.edit-button', async function () {
                var property = propertyTable.row($(this).parents('tr')).data();
                property_edit = property.id;
                RSApp.clearForm('edit_property_modal');
                $('#edit_property_modal_property_id').val(property.id);
                $('#edit_property_modal_title').val(property.title);
                $('#edit_property_modal_property_type').val(property.property_type).trigger('change');
                $('#edit_property_modal_owner_id').val(property.owner_id).trigger('change');

                $('#edit_property_modal_phone_number').val(property.phone_number);

                $('#edit_property_modal_street_name').val(property.street_name);
                $('#edit_property_modal_zipcode_id').val(property.zipcode_id);
                $('#edit_property_modal_house_number').val(property.house_number);
                $('#edit_property_modal_apt_number').val(property.apt_number);
                $('#edit_property_modal_price').val(formatCurrency(property.price));
                $('#edit_property_modal_guest_count').val(property.guest_count);
                $('#edit_property_modal_bed_count').val(property.bed_count);
                $('#edit_property_modal_bedroom_count').val(property.bedroom_count);
                $('#edit_property_modal_bathroom_count').val(property.bathroom_count);

                $('#edit_property_modal_when_is_available').val(property.when_is_available).trigger('change');
                //$('#edit_property_modal_how_long_is_available').val(property.how_long_is_available);
                $('#edit_property_modal_how_long_is_available').val('FOREVER');

                $('#edit_property_modal_cancellation_cut').val(formatCurrency(property.cancellation_cut));
                $('#edit_property_modal_additional_luxury').val(property.additional_luxury);
                $('#edit_property_modal_additional_information').val(property.additional_information);
                $('#edit_property_modal_cancellation_type').val(property.cancellation_type).trigger('change');

                $('#edit_property_modal_property_option_group').val(property.property_option_group).trigger('change');

                $('#edit_property_modal_couple_quantity').val(property.couple_quantity);
                $('#edit_property_modal_floor_location').val(property.floor_location);

                //console.log('link code', property.embed_link);
                $('#edit_property_modal_embed_link').val(property.embed_link_code.replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, "\""));
                $('#iframe_embed_link').attr('src', property.embed_link);

                loadSearchCriteria(property.id);

                $('#edit_property_modal_zipcode_id').trigger("change");

                if (property.active) {
                    $('#edit_property_modal_active_input').prop("checked", "checked").trigger('change');
                }

                $('#edit_property_modal_hall_price').val(null);

                if (property.second_property_type === '{{\SMD\Common\ReservationSystem\Enums\PropertyType::SHORT_TERM_COM_HALL}}') {
                    $('#edit_property_modal_suitable_hall_input').prop("checked", "checked").trigger('change');

                    if(property.hall_price > 0){
                        $('#edit_property_modal_hall_price').val(formatCurrency(property.hall_price));
                    }
                }

                if (property.has_elevator) {
                    $('#edit_property_modal_has_elevator_input').prop("checked", "checked").trigger('change');
                }

                $('#edit_property_modal').modal('show');
            });

            $('#property_datatable').on('click', '.delete-button', function () {
                const property = propertyTable.row($(this).parents('tr')).data();
                $('#del_property_modal_button').data('property-id', property.id);
                let html = '<h4>{{ trans('property.confirm_delete')}}</h4>';
                html += '<p class="m-0"><strong>{{ trans('general.title') }}:</strong> ' + property.title + '</p>';
                html += '<p class="m-0"><strong>{{ trans('property.owner') }}:</strong> ' + property.owner.first_name + ' ' + property.owner.last_name + '</p>';
                html += '<p class="m-0"><strong>{{ trans('property.zipcode') }}:</strong> ' + property.zipcode_id + '</p>';
                html += '<p class="m-0"><strong>{{ trans('property.street_name') }}:</strong> ' + property.street_name + '</p>';
                $('#del_property_modal').find('.modal-body').html(html);
                $('#del_property_modal').modal('show');
            });

            $('#property_datatable').on('click', '.images-button', function (e) {
                const property = propertyTable.row($(this).parents('tr')).data();

                $('#images_property_modal_croppie_wrapper').css({display: 'none'});

                $('#images_property_modal_file').data('propertyId', property.id);

                loadImages(property.id);

                $('#images_property_modal').modal('show');
            });

            $('#property_datatable').on('click', '.availability-button', function (e) {
                const property = propertyTable.row($(this).parents('tr')).data();

                loadAvailabilities(property.id);

                $('#availability_property_modal').modal('show');
            });

            $('#property_datatable').on('click', '.restriction-button', function (e) {
                const property = propertyTable.row($(this).parents('tr')).data();

                loadRestrictions(property.id);

                $('#restriction_property_modal').modal('show');
            });

            $('#property_datatable').on('click', '.package-button', function (e) {
                const property = propertyTable.row($(this).parents('tr')).data();

                loadPackages(property.id);

                $('#packages_property_modal').modal('show');
            });

            $('#images_property_modal').on('hide.bs.modal', function (e) {
                $('#images_property_modal_croppie_wrapper').css({display: 'none'});
                if (window.croppie) {
                    window.croppie.croppie('destroy');
                    window.croppie = null;
                }
            });

            $('#del_property_modal_button').click(function () {
                $('#del_property_modal').modal('hide');
                RSApp.jsonPost("{{ route('property_delete') }}/" + $(this).data('property-id')).done(function (data) {
                    if (data.done) {
                        RSApp.alert('{{ trans('general.done') }}', '{{ trans('property.deleted') }}', 'success', false);
                        propertyTable.ajax.reload();
                    } else {
                        RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
                });
            });

            //=============================================================
            //=============================================================
            $('#pause_property_modal_button').click(function () {
                $('#pause_property_modal').modal('hide');
                RSApp.jsonPost("{{ route('property_pause') }}/" + $(this).data('property-id')).done(function (data) {
                    if (data.done) {
                        RSApp.alert('{{ trans('general.done') }}', 'Property Paused', 'success', false);
                        propertyTable.ajax.reload();
                    } else {
                        RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
                });
            });

            $('#resume_property_modal_button').click(function () {
                $('#resume_property_modal').modal('hide');
                RSApp.jsonPost("{{ route('property_pause') }}/" + $(this).data('property-id')).done(function (data) {
                    if (data.done) {
                        RSApp.alert('{{ trans('general.done') }}', 'Property Resumed', 'success', false);
                        propertyTable.ajax.reload();
                    } else {
                        RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
                });
            });

            //=============================================================
            //=============================================================
            $('#user_fee_property_modal_button').click(function () {
                $('#user_fee_property_modal').modal('hide');
                window.location.href = "{{ route('profile')}}";
            });
            //=============================================================
            //=============================================================
            $(document).on('click', '.add-criteria-button', async function () {
               //RSApp.clearForm('add_criterion_modal');
                $('#add_criterion_modal').modal('show');
                document.getElementById('show_has_quantity').style.display = 'none';
                document.getElementById('show_has_distance_w').style.display = 'none';
                document.getElementById('show_has_distance_d').style.visibility  = 'hidden';
                $('#add_criterion_modal_criterion').empty().append('<option value="" selected disabled hidden>Choose...</option>');
            });

            $('#add_criterion_modal_type').on('change', function (e){
                const type = $(this).val();

                if(type == null) return;

                //search criteria by type
                RSApp.jsonPost("{{route('criteria_by_type')}}/" + type, this, false, null)
                    .done(function (data) {
                        //console.log(data);
                        //===========================
                        var select = document.getElementById('add_criterion_modal_criterion');

                        $('#add_criterion_modal_criterion').empty().append('<option value="" selected disabled hidden>Choose...</option>');

                        var index;
                        for (index = 0; index < data.length; ++index) {
                            var opt = document.createElement("option");
                            opt.setAttribute("data-has_quantity", data[index].has_quantity);
                            opt.setAttribute("data-has_distance", data[index].has_distance);
                            opt.value= data[index].id;
                            opt.innerHTML = data[index].name;

                            // then append it to the select element
                            select.appendChild(opt);
                        }

                        document.getElementById('show_has_quantity').style.display = 'none';
                        document.getElementById('show_has_distance_w').style.display = 'none';
                        document.getElementById('show_has_distance_d').style.visibility  = 'hidden';
                        //===========================
                    })
                    .fail(function (jqXHR, textStatus, errorThrown){
                        console.log(errorThrown);
                    });
            });

            $('#add_criterion_modal_criterion').on('change', function (e){
                const criterion = $(this).val();

                if(criterion == null) return;

                const has_quantity = $(this).find(':selected').data('has_quantity');
                const has_distance = $(this).find(':selected').data('has_distance');

                if(has_quantity === 1) {
                    document.getElementById('show_has_quantity').style.display = 'block';
                } else {
                    document.getElementById('show_has_quantity').style.display = 'none';
                }

                if(has_distance === 1) {
                    document.getElementById('show_has_distance_w').style.display = 'block';
                    document.getElementById('show_has_distance_d').style.visibility  = 'visible';
                } else {
                    document.getElementById('show_has_distance_w').style.display = 'none';
                    document.getElementById('show_has_distance_d').style.visibility  = 'hidden';
                }
            });

            $('#add_criterion_modal_form').submit(async function(e) {
                $('#add_criterion_modal_alert_danger').fadeOut();
                $('#add_criterion_modal_alert_success').fadeOut();

                if(!e.isDefaultPrevented()){
                    e.preventDefault();
                    //console.log('form', this);
                    var propertyId = $('#add_criterion_modal_property_id').val();

                    RSApp.jsonPost("{{route('save_property_criteria')}}/" + propertyId, this, false, function() {
                        //$('#add_criterion_modal').modal('hide');
                        $('#add_criterion_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            //console.log('response', data);
                            //console.log('property', propertyId);
                            //if (data.done) {
                                $('#add_criterion_modal').modal('hide');
                                //RSApp.alert('{{ trans('general.done') }}', '{{ trans('criteria.type_added') }}', 'success', false);
                                //RSApp.clearForm('add_criterion_modal');
                                loadSearchCriteria(propertyId);
                            /*} else {
                                $('#add_property_modal_alert_danger').html(data.error).fadeIn();
                            }*/
                        })
                        .fail(function (jqXHR, textStatus, errorThrown){
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_criterion_modal_');
                            } else {
                                $('#add_criterion_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#add_criterion_modal_form :input').removeAttr('disabled');
                        });
                }
            });
            //=============================================================
            //=============================================================
            //autocomplete street name
            $('#add_property_modal_zipcode_id').bind("change paste keyup", function (e) {
                var zipcode = $(this).val();

                if(zipcode.length < 5) {
                    return;
                }

                console.log('zipcode', zipcode);

                RSApp.jsonPost('{{route('street_names')}}/' + zipcode, this, false, null)
                    .done(function (data) {
                        //===========================
                        var result = [];
                        var index;
                        for (index = 0; index < data.length; ++index) {
                            let item = {};
                            item["value"] = data[index].value;
                            item["data"] = data[index].data;
                            result.push(item);
                        }

                        $('#add_property_modal_street_name').autocomplete({
                            deferRequestBy: 250,
                            minChar: 3,
                            lookup: result
                        });

                        //===========================
                    })
                    .fail(function (jqXHR, textStatus, errorThrown){
                        //console.log(errorThrown);
                    });

                $('#add_property_modal_area_name').val(null);
                RSApp.jsonPost('{{route('area_name')}}/' + zipcode, this, false, null)
                    .done(function (data) {
                        $('#add_property_modal_area_name').val(data.name);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown){
                        //console.log(errorThrown);
                    });
            });
            //========

            $('#edit_property_modal_zipcode_id').bind("change paste keyup", function (e) {
                var zipcode = $(this).val();

                if(zipcode.length < 5) {
                    return;
                }

                console.log('zipcode', zipcode);

                RSApp.jsonPost('{{route('street_names')}}/' + zipcode, this, false, null)
                    .done(function (data) {
                        //===========================
                        var result = [];
                        var index;
                        for (index = 0; index < data.length; ++index) {
                            let item = {};
                            item["value"] = data[index].value;
                            item["data"] = data[index].data;
                            result.push(item);
                        }

                        $('#edit_property_modal_street_name').autocomplete({
                            deferRequestBy: 250,
                            minChar: 3,
                            lookup: result
                        });

                        //===========================
                    })
                    .fail(function (jqXHR, textStatus, errorThrown){
                        //console.log(errorThrown);
                    });

                $('#edit_property_modal_area_name').val(null);
                RSApp.jsonPost('{{route('area_name')}}/' + zipcode, this, false, null)
                    .done(function (data) {
                        $('#edit_property_modal_area_name').val(data.name);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown){
                        //console.log(errorThrown);
                    });
            });
            //=============================================================
            //=============================================================

            @if(isset($edit_property) && $edit_property->id > 0)
                property_edit = '{{ $edit_property->id }}';
            RSApp.clearForm('edit_property_modal');
            $('#edit_property_modal_property_id').val('{{ $edit_property->id }}');
            $('#edit_property_modal_property_type').val('{{ $edit_property->property_type }}').trigger('change');
            $('#edit_property_modal_title').val('{{ $edit_property->title }}');
            $('#edit_property_modal_owner_id').val('{{ $edit_property->owner_id }}').trigger('change');

            $('#edit_property_modal_phone_number').val('{{$edit_property->phone_number}}');

            $('#edit_property_modal_street_name').val('{{ $edit_property->street_name }}');
            $('#edit_property_modal_house_number').val('{{ $edit_property->house_number }}');
            $('#edit_property_modal_zipcode_id').val('{{ $edit_property->zipcode_id }}');
            $('#edit_property_modal_price').val('{{ $edit_property->price }}');
            $('#edit_property_modal_guest_count').val('{{ $edit_property->guest_count }}');
            $('#edit_property_modal_bed_count').val('{{ $edit_property->bed_count }}');
            $('#edit_property_modal_bedroom_count').val('{{ $edit_property->bedroom_count }}');
            $('#edit_property_modal_bathroom_count').val('{{ $edit_property->bathroom_count }}');

            $('#edit_property_modal_when_is_available').val('{{ $edit_property->when_is_available }}').trigger('change');
            //$('#edit_property_modal_how_long_is_available').val('{{ $edit_property->how_long_is_available }}');
            $('#edit_property_modal_how_long_is_available').val('FOREVER');

            $('#edit_property_modal_cancellation_cut').val('{{ $edit_property->cancellation_cut }}');
            $('#edit_property_modal_additional_luxury').val('{{ $edit_property->additional_luxury }}');
            $('#edit_property_modal_additional_information').val('{{ $edit_property->additional_information }}');
            $('#edit_property_modal_cancellation_type').val('{{ $edit_property->cancellation_type }}').trigger('change');

            $('#edit_property_modal_property_option_group').val('{{ $edit_property->property_option_group }}').trigger('change');

            @if ($edit_property->active)
            $('#edit_property_modal_active_input').prop("checked", "checked").trigger('change');
            @endif

            $('#edit_property_modal').modal('show');
            @endif
        })(jQuery)
    </script>

    <script src="{{google_script_api_url()}}" async defer></script>
@stop
