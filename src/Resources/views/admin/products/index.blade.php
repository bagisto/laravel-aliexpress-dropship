@extends('admin::layouts.content')

@section('page_title')
    {{ __('dropship::app.admin.products.title') }}
@stop

@section('content')

    <div class="content">

        <?php $locale = request()->get('locale') ?: null; ?>
        <?php $channel = request()->get('channel') ?: null; ?>

        <div class="page-header">
            <div class="page-title">
                <h1>
                    {{ __('dropship::app.admin.products.title') }}
                </h1>

                <div class="control-group">
                    <select class="control" id="channel-switcher" name="channel" onchange="reloadPage('channel', this.value)" >
                        <option value="all" {{ ! isset($channel) ? 'selected' : '' }}>
                            {{ __('admin::app.admin.system.all-channels') }}
                        </option>

                        @foreach (core()->getAllChannels() as $channelModel)

                            <option
                                    value="{{ $channelModel->code }}" {{ (isset($channel) && ($channelModel->code) == $channel) ? 'selected' : '' }}>
                                {{ $channelModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div class="control-group">
                    <select class="control" id="locale-switcher" name="locale" onchange="reloadPage('locale', this.value)" >
                        <option value="all" {{ ! isset($locale) ? 'selected' : '' }}>
                            {{ __('admin::app.admin.system.all-locales') }}
                        </option>

                        @foreach (core()->getAllLocales() as $localeModel)

                            <option
                                    value="{{ $localeModel->code }}" {{ (isset($locale) && ($localeModel->code) == $locale) ? 'selected' : '' }}>
                                {{ $localeModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>
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

@push('scripts')
    <script>
        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);
            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }
    </script>
@endpush