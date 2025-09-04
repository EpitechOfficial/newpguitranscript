<x-admin-layout :pageName="'2025 Ph.D Convocation'">
    <div class="container">
        <div class="page-title">
            <div class="row w-full w-100">
                <div class="">
                    <h1 class="mb-0 pb-0 display-4" id="title">2025 Ph.D Convocation</h1>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-body h-100">
                <form method="POST" action="{{ route('admin.phd_convocation.find') }}" class="flex items-center gap-3">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Matric Number</label>
                        <input type="text" name="matric" class="form-control" placeholder="Enter matric number" required>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Proceed</button>
                    </div>
                </form>
                @if(session('error'))
                    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

