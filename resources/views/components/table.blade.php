<!-- Table -->
<table class="table table-striped {{$responsive ?? false ? 'ui-responsive' : ''}}">
    @if(isset($header))
        <thead>
        <tr>{{$header}}</tr>
        </thead>
    @endif
    <tbody>
    @if(isset($body))
        {{$body}}
    @endif
    {{$slot}}
    </tbody>
</table>