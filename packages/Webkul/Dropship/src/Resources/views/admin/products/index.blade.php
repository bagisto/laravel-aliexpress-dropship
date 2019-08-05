@extends('dropship::admin.layouts.content')

@section('page_title')
    {{ __('dropship::app.admin.products.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    {{ __('dropship::app.admin.products.title') }}
                </h1>
            </div>

            <div class="page-action">
                <button class="btn btn-lg btn-primary" style="display: none">
                    {{ __('dropship::app.admin.products.import-btn-title') }}
                </button>
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\Dropship\DataGrids\Admin\ProductDataGrid')->render() !!}

        </div>
    </div>

@stop

<!-- @push('scripts')


@endpush -->