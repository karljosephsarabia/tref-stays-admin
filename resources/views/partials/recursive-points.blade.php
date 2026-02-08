<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-5" id="row-point-{{$point->id}}">
                <h5 class="mb-0 collapsed" data-toggle="collapse"
                    data-target="#collapse-item-{{$point->parent_id}}-{{$point->id}}"
                    aria-controls="collapse-item-{{$point->parent_id}}-{{$point->id}}">
                    <div class="row">
                        <div class="col-1"><i class="fa" aria-hidden="true"></i></div>
                        <div class="col-2">({{$point->menu_order}})</div>
                        <div class="col-9">{{$point->name}}</div>
                    </div>
                </h5>
            </div>

            <div class="col-3" id="row-button-point-{{$point->id}}">
                <button class="btn btn-success btn-sm edit-point-button" type="button"
                        onclick="editItem('{{object_json($point)}}')">
                    <i class="fa fa-pencil"></i>
                </button>

                <button class="btn btn-danger btn-sm delete-point-button" type="button"
                        onclick="deleteItem({{$point->id}},'{{$point->name}}','{{$point->category}}')">
                    <i class="fa fa-times"></i>
                </button>
            </div>

        </div>
    </div>

    <div id="collapse-item-{{$point->parent_id}}-{{$point->id}}" class="collapse"
         data-parent="#accordion-{{$point->parent_id}}">
        <div class="card-body p-3 pl-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="btn-group mb-0 btn-group-sm">
                        @if($point->category == 'menu' && count($point->points) == 0)
                            <button class="btn btn-success add-point-button" type="button"
                                    onclick="addItem({{$point->id}},'category', '{{count($point->categories) + 1}}')">
                                {{__('point_interests.add_category')}}
                            </button>
                        @endif
                        @if(count($point->categories) == 0)
                            <button class="btn btn-warning add-point-button" type="button"
                                    onclick="addItem({{$point->id}},'point', '{{count($point->points) + 1}}')">
                                {{__('point_interests.add_point')}}
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            @if(count($point->categories) > 0)
                <div class="row">
                    <div class="col-12">
                        <div id="accordion-{{$point->id}}" class="accordion mt-3">
                            @foreach($point->categories as $index => $child)
                                @include('partials.recursive-points', ['point' => $child])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if(count($point->points) > 0)
                <hr>
                <ul>
                    @foreach($point->points as $index => $child)
                        <li>
                            <div class="row mb-1">
                                <div class="col-6">
                                    {{$child->name}}
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-success btn-sm edit-point-button" type="button"
                                            onclick="editItem('{{object_json($child)}}')">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-point-button" type="button"
                                            onclick="deleteItem('{{$child->id}}','{{$child->name}}', 'point')">
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
