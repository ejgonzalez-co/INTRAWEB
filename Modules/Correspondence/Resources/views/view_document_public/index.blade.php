@extends('layouts.layoutPublic')
{{-- @php
dd($documento); @endphp --}}
@section('content')
<div class="w-75 mt-3">
    <viewer-attachement :open-default="true" :list="'{{$documento}}'" :key="'{{$documento}}'"></viewer-attachement>
</div>

@endsection