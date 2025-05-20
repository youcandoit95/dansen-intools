@if (session('error') || $errors->any())
    <div class="mb-4 bg-red-100 border border-red-300 text-red-800 text-sm px-4 py-3 rounded">
        @if (session('error'))
            <div>{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
