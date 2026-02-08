<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-5" id="row-area-{{$area->id}}">
                <h5 class="mb-0 collapsed" data-toggle="collapse"
                    data-target="#collapse-area-{{$area->parent_id}}-{{$area->id}}"
                    aria-controls="collapse-area-{{$area->parent_id}}-{{$area->id}}">
                    <div class="row">
                        <div class="col-1"><i class="fa" aria-hidden="true"></i></div>
                        <div class="col-2">({{$area->menu_order}})</div>
                        <div class="col-9">{{$area->name}}</div>
                    </div>
                </h5>
            </div>

            <div class="col-3" id="row-button-area-{{$area->id}}">
                <button class="btn btn-success btn-sm edit-area-button" type="button"
                        onclick="editArea('{{object_json($area)}}')">
                    <i class="fa fa-pencil"></i>
                </button>

                <button class="btn btn-danger btn-sm delete-area-button" type="button"
                        onclick="deleteArea({{$area->id}}, '{{$area->name}}')">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="collapse-area-{{$area->parent_id}}-{{$area->id}}" class="collapse"
         data-parent="#accordion-{{$area->parent_id}}">
        <div class="card-body p-3 pl-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="btn-group mtb-0 btn-group-sm">
                        @if(count($area->zipcodes) <= 0)
                            <button class="btn btn-success add-area-button" type="button"
                                    onclick="addArea({{$area->id}}, '{{count($area->subareas) + 1}}')">
                                {{__('areas.add_sub_area')}}
                            </button>
                        @endif
                        @if(count($area->subareas) <= 0)
                            <button class="btn btn-warning add-zipcode-button" type="button"
                                    onclick="addZipcode({{$area->id}})">
                                {{__('areas.add_zipcode')}}
                            </button>
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    @if(count($area->subareas) > 0)
                        <div class="row">
                            <div class="col-12">
                                <div id="accordion-{{$area->id}}" class="accordion mt-3">
                                    @foreach($area->subareas as $subarea)
                                        @include('partials.recursive-areas', ['area' => $subarea])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(count($area->zipcodes) > 0)
                        <hr>
                        <h5>{{__('areas.zip_codes')}}</h5>
                        <hr>
                        <ul>
                            @foreach($area->zipcodes as $zipcode)
                                <li>
                                    <div class="row mb-1">
                                        <div class="col-6">
                                            {{$zipcode->zipcode}}
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-success btn-sm edit-zipcode-button" type="button"
                                                    onclick="editZipcode('{{object_json($zipcode)}}')">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-zipcode-button" type="button"
                                                    onclick="deleteZipcode({{$zipcode->id}},'{{$zipcode->zipcode}}')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
