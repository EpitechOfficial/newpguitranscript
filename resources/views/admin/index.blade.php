<x-guest-layout :title="'Transcript Application | Login Page'">
    <div class="flex items-center justify-center">

    <div class="box lr">


        <div>
            @if (session()->has('success'))
                <div>
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="right">
            <div id="form">
                <i class="fas fa-user-shield"></i>
                @error('message')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror
                <form method="post" action="{{ route('login.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" placeholder="Username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" placeholder="Password" name="password" required>
                    </div>

                    <input type="submit" value="Sign in" name="send" class="btn">
                </form>
                <p class="noacc">
                    {{-- <a class="reg" href="{{ route('admin.password.reset') }}">Forgot Password?</a> --}}
                </p>
            </div>
        </div>
    </div>
    </div>

</x-guest-layout>
