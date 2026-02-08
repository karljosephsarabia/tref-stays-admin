<div class="row">
    <div class="col-8">
        <h4 class="card-title">{{__('point_interests.points_main_menu')}}</h4>
    </div>

    <div class="col-4">
        <button class="btn btn-primary" type="button"
                onclick="addItem(0, 'menu', '{{count($points) + 1}}')">
            <i class="fa fa-plus"
               title="{{__('point_interests.add_main_menu')}}"></i> {{trans('general.add')}}
        </button>
    </div>
</div>
<p class="text-muted"><code></code></p>
<div id="accordion-0" class="accordion">
    @foreach($points as $index => $point)
        @include('partials.recursive-points', ['point' => $point])
    @endforeach
</div>