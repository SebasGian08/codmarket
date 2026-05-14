@extends('layouts.appweb')

@section('title', $empresa->nombre ?? 'Mi Empresa')

@section('content')

    @include('sections.blog')

@endsection

