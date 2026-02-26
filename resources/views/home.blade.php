@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p><strong>{{ __('Name') }}:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>{{ __('Email') }}:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>{{ __('Role') }}:</strong> {{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
