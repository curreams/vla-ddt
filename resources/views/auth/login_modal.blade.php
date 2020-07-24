<div id="login_modal" class="modal fade bs-modal-lg" tabindex="-1" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="ddt-title">Welcome to the
                        Data Discovery  <span class="ddt-title-black">Tool</span> </h3>
                        <p class="ddt-title">A resource to help support effective legal
                        service planning and development</p>
                        <p>The Data Discovery Tool supports greater transparency
                        across the legal assistance sector, increased collaboration
                        in legal service planning and quality service design to meet the
                        needs of priority groups</p>

                        <p>Search for current data on legal assistance services, indicators
                        of legal need and priority groups for your region of interest.
                        Choose which visualisations are most useful for your purpose
                        and download data in a user-friendly form.</p>

                        <p class="ddt-title"> Who can use the Data Discovery Tool? </p>

                        <p>The Data Discovery Tool is available to xxxxx. To request access, email xxxxx. </p>

                        <p class="ddt-title">Have a suggestion on how we can improve the Data Discovery Tool?
                        Contact us to share your ideas.</p>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="form-horizontal col-md-6 vl">
                        @csrf
                        <h3 class="ddt-title-black">Sign in to your account</h3>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-8">
                                <input id="email-modal" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-modal" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-8">
                                <input id="password-modal" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
