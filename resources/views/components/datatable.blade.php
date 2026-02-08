<!-- DataTable -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $title }}
                    @if(isset($buttons))
                        <div class="pull-right">{{ $buttons }}</div>
                    @endif
                </h4>
                <div class="table-responsive">
                    <table id="{{ $table_id }}" class="table table-striped table-bordered zero-configuration"
                           style="width: 100%">
                        <thead>
                        <tr>{{ $slot }}</tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>