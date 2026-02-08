<!-- Carousel -->
@if(isset($images) && count($images) > 0)
    @if(count($images) > 1)
        <div class="carousel slide" id="carousel_{{$id}}">
            <ol class="carousel-indicators">
                @for($i=0; $i<count($images); $i++)
                    <li data-slide-to="{{$i}}" data-target="#carousel_{{$id}}"
                        class="{{ ($i == ($active or 0) ? 'active' : '') }}"></li>
                @endfor
            </ol>
            <div class="carousel-inner">
                @foreach($images as $key => $image)
                    <div class="carousel-item {{ ($key == ($active or 0) ? 'active' : '') }}">
                        <img class="d-block w-100" src="{{ asset($image['url']) }}" alt="">
                        @if(isset($image['title']) && !is_null_or_empty($image['title']) || isset($image['caption']) && !is_null_or_empty($image['caption']))
                            <div class="carousel-caption d-none d-md-block">
                                @if(isset($image['title']) && !is_null_or_empty($image['title']))
                                    <h5>{{$image['title']}}</h5>
                                @endif
                                @if(isset($image['caption']) && !is_null_or_empty($image['caption']))
                                    <p>{{$image['caption']}}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <a data-slide="prev" href="#carousel_{{$id}}" class="carousel-control-prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="sr-only">{{trans('pagination.previous_1')}}</span>
            </a>
            <a data-slide="next" href="#carousel_{{$id}}" class="carousel-control-next">
                <span class="carousel-control-next-icon"></span>
                <span class="sr-only">{{trans('pagination.next_1')}}</span>
            </a>
        </div>
    @else
        <img class="img-fluid" src="{{ asset($images[0]['url']) }}" alt="">
    @endif
@else
    {{ $slot }}
@endif