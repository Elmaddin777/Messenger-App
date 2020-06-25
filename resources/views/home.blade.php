@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">EdisMessenger</div>

                <div class="card-body" id="chat-component">
                    <chat-component :user="{{ auth()->user() }}"></chat-component>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
