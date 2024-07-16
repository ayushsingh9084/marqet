<!-- Begin Page -->
@extends('layouts.auth')

@section('title', 'Seller Details')

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
                        <input type="hidden" name="role" value="2">
                        <input type="hidden" name="mode" value="email">
                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" type="text" name="business_name" required
                                    placeholder="Business Name">
                            </div>
                            @error('business_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12" id="valueInput">
                                <input class="form-control" type="email" name="value" required
                                    placeholder="Email">
                            </div>
                            @error('value')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="otpFieldContainer" class="form-group row" style="display: none;">
                            <div class="col-12"><input class="form-control" type="number" name="otp" required placeholder="OTP" disabled></div>
                            @error('otp')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group text-center row m-t-20">
                            <div class="col-12">
                                <button id="otpButton" class="btn btn-danger btn-block waves-effect waves-light"
                                    type="submit">Send OTP</button>
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
            const otpFieldContainer = document.getElementById('otpFieldContainer');
            const otpInput = document.querySelector('input[name="otp"]');
            const valueInput = document.querySelector('input[name="value"]');
            const otpButton = document.getElementById('otpButton');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent form submission
                otpFieldContainer.style.display = 'block';
                otpInput.disabled = false; // Ensure OTP input is enabled
                otpButton.innerText = 'Verify OTP';
                const id = {{ $id }};
                const form = e.target;
                const formData = new FormData(form);
                if (!formData.has('value')) {
                    formData.append('value', valueInput.value); // Append 'value' only if it doesn't exist
                }
                const submitBtn = form.querySelector('[type="submit"]');
                // submitBtn.disabled = true;

                const url = `{{ env('API_URL') . '/v1/login/${id}' }}`;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log('Response Body:', data);
                        submitBtn.disabled = false;
                        if (data.message === "OTP verified successfully") {
                            let redirectUrl = `{{ env('APP_URL') . 'seller-business-details/${id}' }}`;
                            window.location.href = redirectUrl;
                        } else if (data.message === "OTP sent successfully") {
                            const inputValue = valueInput.value;
                            const html = `<div class="input-group">
                                <input class="form-control" type="email" name="email" required
                                    placeholder="Email" value="${inputValue}" disabled>
                            <div class="input-group-append">
                                <button class="btn btn-danger" onclick="handleEditButtonClick()" type="button"> Edit </button>
                            </div>
                        </div>`;
                            $('#valueInput').html(html);
                            otpInput.disabled = false;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.status);
                    },
                });
            });
        });

        function handleEditButtonClick() {
            console.log('Button clicked');

            // Enable OTP input field and focus on it
            document.querySelector('input[name="otp"]').disabled = true;

            // Your logic to handle click event
            const inputValue = document.querySelector('input[name="value"]').value;
            const html =
                `<input class="form-control" type="email" name="value" required placeholder="Email" value="${inputValue}">`;
            $('#valueInput').html(html); // Replace HTML content inside '#valueInput'

            // Hide OTP field container and update button text
            document.getElementById('otpFieldContainer').style.display = 'none';
            otpButton.innerText = 'Send OTP';
        }
    </script>

@endsection
