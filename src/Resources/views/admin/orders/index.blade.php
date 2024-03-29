@extends('admin::layouts.content')

@section('page_title')
    {{ __('dropship::app.admin.orders.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    {{ __('dropship::app.admin.orders.title') }}
                </h1>
            </div>

            <div class="page-action">
                <div class="export-import" @click="showModal('downloadDataGrid')">
                    <i class="export-icon"></i>
                    <span>
                        {{ __('admin::app.export.export') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\Dropship\DataGrids\Admin\OrderDataGrid')->render() !!}

        </div>
    </div>

    <modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.download') }}</h3>
        <div slot="body">
            <export-form></export-form>
        </div>
    </modal>
@stop

@push('scripts')
    @include('admin::export.export', ['gridName' => app('Webkul\Dropship\DataGrids\Admin\OrderDataGrid')])
@endpush
