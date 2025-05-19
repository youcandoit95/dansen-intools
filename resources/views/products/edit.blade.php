@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-semibold mb-4">Edit Produk</h2>

    <x-alert-error />


    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        @include('products.form', ['product' => $product])

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>

    @if($product->images->count())
    <div class="mt-4">
        <label class="block text-sm font-medium mb-2">Gambar Tersimpan:</label>
        <div id="savedImagePreview" class="flex gap-3 overflow-x-auto">
            @foreach($product->images as $img)
            <div class="relative">
                <img src="{{ asset('storage/' . $img->path) }}"
                    data-full="{{ asset('storage/' . $img->path) }}"
                    class="h-32 w-32 object-cover rounded cursor-pointer border">

                <form action="{{ route('product_image.destroy', $img->id) }}" method="POST"
                    class="absolute top-1 right-1 delete-image-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white text-xs px-2 py-1 rounded">✕</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<!-- Modal Preview -->
<div id="modalPreviewsaved" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center">
    <div class="relative bg-white rounded-lg shadow-lg max-w-5xl w-[80%] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">Preview Gambar</h3>
            <button id="closeModalSaved" type="button" class="text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        <div class="p-4 bg-gray-50 flex items-center justify-center">
            <img id="modalImageSaved" class="max-h-[80vh] w-auto rounded" alt="Preview Gambar">
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalPreviewsaved');
        const modalImageSaved = document.getElementById('modalImageSaved');
        const closeModalSaved = document.getElementById('closeModalSaved');
        const savedPreview = document.getElementById('savedImagePreview');

        if (savedPreview) {
            savedPreview.addEventListener('click', function(e) {
                if (e.target.tagName === 'IMG' && e.target.dataset.full) {
                    modalImageSaved.src = e.target.dataset.full;
                    modal.classList.remove('hidden');
                }
            });
        }

        closeModalSaved.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                modal.classList.add('hidden');
            }
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        document.querySelectorAll('.delete-image-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus gambar ini?',
                    text: 'Tindakan ini tidak bisa dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
