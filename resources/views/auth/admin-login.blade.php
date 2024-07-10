<!-- Begin Page -->
@extends('layouts.auth')

@section('title', 'Admin Login')

@section('content')
    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="card">
            <div class="card-body">
                <div class="text-center"><a class="logo logo-admin" href="/admin"><img
                            src="{{ asset('assets/images/e-logo.png') }}" height="20" alt="logo"></a></div>
                <div class="px-3 pb-3">
                    <form class="form-horizontal m-t-20" action="{{ route('adminLogin.request') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-12"><input class="form-control" type="text" name="email" required
                                    placeholder="Username"></div>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12"><input class="form-control" type="password" name="password" required
                                    placeholder="Password"></div>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group text-center row m-t-20">
                            <div class="col-12">
                                <button class="btn btn-danger btn-block waves-effect waves-light" type="submit">Log In</button>
                            </div>
                        </div>
                        <div class="form-group m-t-10 mb-0 row">
                            <div class="col-sm-7 m-t-20"><a class="text-muted" href="/zoter/vertical/pages-recoverpw"><i
                                        class="mdi mdi-lock"></i> <small>Forgot your password ?</small></a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
