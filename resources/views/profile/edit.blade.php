@extends('layouts.app')

@section('content')
<h5 class="mb-3">Profil</h5>
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card p-3">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card p-3">
            @include('profile.partials.update-password-form')
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card p-3">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
