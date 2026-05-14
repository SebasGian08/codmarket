@extends('layouts.appweb')

@section('title', $empresa->nombre ?? 'Mi Empresa')

@section('content')

    @include('sections.contact')
    @include('sections.steps')
    
@endsection