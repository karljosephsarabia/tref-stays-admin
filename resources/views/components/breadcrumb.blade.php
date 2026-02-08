<!-- Breadcrumb -->
@if(isset($items))
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                @foreach($items as $item)
                    <li class="breadcrumb-item {{ $item['class'] ?? '' }}">
                        <a href="{{ $item['route'] ?? 'javascript:void(0)'}}">{{ $item['name'] }}</a>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
@endif
