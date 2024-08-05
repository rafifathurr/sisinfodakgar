<!DOCTYPE html>
<html lang="en">
@include('layouts.head')

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card m-5">
                    <form class="card-body cardbody-color p-lg-5" action="{{ route('authenticate') }}" method="POST">
                        @csrf
                        <div class="text-center mb-5">
                            <img src="{{ asset('img/korlantas-mabes.png') }}"width="200px" alt="profile">
                            <h3 class="mt-1 fw-bold">Sisinfodakgar<br>Korlantas Polri</h3>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control @error('email_or_username') is-invalid @enderror"
                                name="email_or_username" value="{{ old('email_or_username') }}"
                                placeholder="Email atau Username" required>
                            @error('email_or_username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="input-group border border-1 rounded">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                                    value="{{ old('password') }}" required>
                                <a class="btn btn-grey" id="togglePassword" onclick="togglePasswordVisibility()">
                                    <i class="fas fa-eye" id="eye-icon"></i>
                                </a>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between align-items-center mt-2 mb-4">
                            <div class="check-box form-check">
                                <label class="form-check-label  control-label">
                                    <input class="form-check-input" type="checkbox" value=""
                                        style="left: -9999px !important;">
                                    <span class="form-check-sign">Remember Me</span>
                                </label>
                            </div>
                            <a title="Forgot Password" class="text-primary text-right" href="{{ route('forgot') }}">
                                <i>Forgot
                                    password?</i>
                            </a>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5 w-100 fw-bold">LOGIN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.script')
    @include('js.auth.script')
</body>

</html>
