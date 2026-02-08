<div class="card">
    <div class="card-header" style="background-color: #eeeeee;">
        <div class="row">
            <div class="col-5" id="row-menu-{{$menu->id}}">
                @if($menu->is_menu == 1)
                <h5 class="mb-0 collapsed"
                    data-toggle="collapse"
                    data-target="#collapseMenu{{$menu->rs_parent_menu_id}}{{$menu->id}}"
                    aria-expanded="{{$indexKey == 0 ? 'true' : 'false' }}"
                    aria-controls="collapseMenu{{$menu->rs_parent_menu_id}}{{$menu->id}}">
                    <div class="row">
                        <div class="col-1">
                            <i class="fa" aria-hidden="true"></i>
                        </div>

                        <div class="col-9">
                            {{$menu->name}}
                        </div>

                        <div class="col-2">
                            (#{{$menu->menu_order}})
                        </div>
                    </div>
                </h5>
                @else
                <h5 class="mb-0">
                    <div class="row">
                        <div class="col-1">
                            <i class="fa" aria-hidden="true"></i>
                        </div>

                        <div class="col-9">
                            {{$menu->name}}
                        </div>

                        <div class="col-2">
                            (#{{$menu->menu_order}})
                        </div>
                    </div>
                </h5>
                @endif
            </div>

            <div class="col-3" id="row-button-menu-{{$menu->id}}">
                <div class="btn-group btn-group-sm">
                    @if($menu->is_menu == 1)
                        <button class="btn btn-primary btn-sm play-menu-button" type="button" onclick="playMenuRecording({{$menu->id}},'{{$menu->name}}')">
                            <i class="fa fa-play"></i>
                        </button>

                        <button class="btn btn-success btn-sm edit-menu-button" type="button" onclick="editMenu({{$menu->id}},'{{$menu->name}}',{{$menu->menu_order}}, '{{$menu->tts_text}}', {{$menu->rs_parent_menu_id}})">
                            <i class="fa fa-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm delete-menu-button" type="button" onclick="deleteMenu({{$menu->id}},'{{$menu->name}}')">
                            <i class="fa fa-times"></i>
                        </button>

                        <button class="btn btn-outline-primary btn-sm add-menu-button" type="button" onclick="addMenu({{$menu->id}})">
                            Add Menu
                        </button>

                        <button class="btn btn-outline-warning btn-sm add-option-button" type="button" onclick="addOption({{$menu->id}})">
                            Add Option
                        </button>
                    @else
                        <button class="btn btn-success btn-sm edit-menu-button" type="button" onclick="editOption({{$menu->id}},'{{$menu->name}}',{{$menu->menu_order}}, {{$menu->rs_action_id}}, {{$menu->rs_parent_menu_id}})">
                            <i class="fa fa-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm delete-menu-button" type="button" onclick="deleteOption({{$menu->id}},'{{$menu->name}}')">
                            <i class="fa fa-times"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($menu->is_menu == 1)
        <div id="collapseMenu{{$menu->rs_parent_menu_id}}{{$menu->id}}"
             class="collapse {{$indexKey == 0 ? /*'show'*/ '' : '' }}"
             data-parent="#accordion-{{$menu->rs_parent_menu_id}}">
            <div class="card-body shadow">
                @if(count($menu->options) > 0)
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="accordion-{{$menu->id}}" class="accordion">
                                @foreach($menu->options as $indexKey => $menu)
                                    @include('partials.recursive-custom-menu', $menu)
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
