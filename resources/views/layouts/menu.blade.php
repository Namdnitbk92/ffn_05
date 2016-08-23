<div id='{{ $id }}'>
    <ul>
        @can('is_admin', Auth::user())
            <li type="add"><i class="plus icon"></i>{{ trans('common.add_row') }}</li>
            <li type="edit"><i class="edit icon"></i>{{ trans('common.edit_row') }}</li>
            <li type="delete"><i class="trash icon"></i>{{ trans('common.delete_row') }}</li>
            <li type="bet"><i class="dollar icon"></i>{{ trans('common.bet_match') }}</li>
        @endcan
        <li type="show"><i class="arrow circle right icon"></i>{{ trans('common.show_row_detail') }}</li>
        <li type="map"
            onclick="app.viewLocation('jqxgrid')">
            <i class="map icon"></i>
            {{ trans('common.view_location_on_map') }}
        </li>
        <li type="export">
            {{ trans('common.export') }}
            <ul>
                <li type="pdf">
                    <i class="file pdf outline icon"></i>{{ trans('common.pdf') }}
                </li>
                <li type="excel">
                    <i class="file excel outline icon"></i>{{ trans('common.excel') }}
                </li>
                <li type="csv">
                    <i class="file archive outline icon"></i>
                    {{ trans('common.csv') }}
                </li>
            </ul>
        </li>
    </ul>
</div>
