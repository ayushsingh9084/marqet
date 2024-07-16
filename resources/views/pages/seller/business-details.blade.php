<!-- Begin Page -->
@extends('layouts.auth')

@section('title', 'Seller Business Details')

@section('content')

    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="card">
            <div class="card-body">
                <div class="text-center"><a class="logo logo-admin" href="{{ url('/') }}"><img
                            src="{{ asset('assets/images/e-logo.png') }}" height="20" alt="logo"></a></div>
                <div class="px-3 pb-3">
                    <form id="loginForm" class="form-horizontal m-t-20">
                        @csrf
                        <div class="form-group row">
                            <div class="col-12" id="valueInput">
                                <input class="form-control" type="text" name="business_name" required
                                    placeholder="Business Name">
                            </div>
                            @error('business_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12" id="valueInput">
                                <input class="form-control" type="text" name="gst" required
                                    placeholder="GST Number">
                            </div>
                        @error('gst')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12" id="valueInput">
                                <select class="form-control" name="service_type" required>
                                    <option value="">Select service</option>
                                    <option value="web_and_app">Web & App</option>
                                    <option value="app">App</option>
                                    <option value="web">Web</option>
                                </select>
                            </div>
                        @error('service_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group text-center row m-t-20">
                            <div class="col-12">
                                <button class="btn btn-danger btn-block waves-effect waves-light"
                                    type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent form submission

                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('[type="submit"]');
                const id = {{ $id }};
                // submitBtn.disabled = true;

                const url = `{{ env('API_URL') . '/v1/seller/update/${id}' }}`;
                console.log(url);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log('Response Body:', data);
                        submitBtn.disabled = false;
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.status);
                    },
                });
            });
        });
    </script>

@endsection
