@extends('clumsy/cms::admin.templates.master')

@section('title')
    
	@parent

    @if (isset($importer))
        
        @if ($importer)
            <a href="{{ route('import', $resource) }}"><button type="button" class="btn btn-primary btn-title pull-right add-new">{{ trans('clumsy/cms::buttons.import', array('resources' => $display_name_plural)) }}</button></a>
        @endif
    
    @else
        <a href="{{ route("admin.$resource.create") }}"><button type="button" class="btn btn-success btn-title pull-right add-new">{{ trans('clumsy/cms::buttons.add') }}</button></a>
    @endif

@stop

@section('master')

    @include('clumsy/cms::admin.templates.table')

@stop