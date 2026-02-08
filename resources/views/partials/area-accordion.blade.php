<div class="row">
    <div class="col-8">
        <h4 class="card-title">{{__('areas.area_main_menu')}}</h4>
    </div>

    <div class="col-4">
        <button class="btn btn-primary add-area-button" type="button" onclick="addArea(0, '{{count($areas) + 1}}')">
            <i class="fa fa-plus" title="Add area"></i> {{trans('general.add')}}
        </button>
    </div>
</div>
<p class="text-muted"><code></code></p>
<div id="accordion-0" class="accordion">
    @foreach($areas as $area)
        @include('partials.recursive-areas', ['area' => $area])
    @endforeach
</div>