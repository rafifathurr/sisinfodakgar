<!DOCTYPE html>
<html lang="en">
@include('layouts.head')

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card m-5">
                    <form class="card-body cardbody-color p-lg-5" action="{{ route('forgot-authenticate') }}"
                        method="POST">
                        @csrf
                        <div class="text-center mb-5">
                            <img src="{{ asset('img/korlantas-mabes.png') }}"width="200px" alt="profile">
                            <h3 class="mt-1 fw-bold">Sisinfodakgar<br>Korlantas Polri</h3>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="Email atau Username" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password " placeholder="Password" value="{{ old('password') }}" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="re_password" name="re_password"
                                placeholder="Re-Password" placeholder="Re Password" value="{{ old('re_password') }}"
                                required>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary px-5 w-100 fw-bold">FORGOT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.script')
</body>

</html>
