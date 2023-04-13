@extends('layouts.panel')
@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('page-content')
    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
            </div>
        </div>

    </div>
     <!-- Modal -->
 </div>
@endsection
@push('js')
