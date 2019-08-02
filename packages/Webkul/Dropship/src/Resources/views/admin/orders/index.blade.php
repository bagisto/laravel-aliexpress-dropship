@extends('dropship::admin.layouts.content')

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
            </div>
        </div>

        <div class="page-content">
            {!! app('Webkul\Dropship\DataGrids\Admin\OrderDataGrid')->render() !!}

        </div>
    </div>



@stop

@push('scripts')


@endpush