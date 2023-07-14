@extends('master')
@section('sidebar')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> ABC</a></li>

@endsection

@section('content')

<div>Something</div>
@php backpack_user()->hasAnyRole(Role::all()); @endphp


@endsection
