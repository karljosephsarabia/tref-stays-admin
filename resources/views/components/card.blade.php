<!-- Card -->
<div class="card {{ $card_class ?? '' }}">
    @if(isset($header))
        <div class="card-header {{ $header_class ?? '' }}">{{ $header }}</div>
    @endif

    {{ $image ?? '' }}

    <div class="card-body {{ $body_class ?? ''}}">
        {{ $body ?? $slot }}
    </div>

    @if(isset($footer))
        <div class="card-footer {{ $footer_class ?? '' }}">{{ $footer }}</div>
    @endif
</div>