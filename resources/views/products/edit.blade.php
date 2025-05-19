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
        <div class="p-4 bg-gray-50 flex items-center justify-center relative">
            <button id="prevSavedImage" type="button"
                    class="absolute left-2 top-1/2 transform -translate-y-1/2 text-2xl bg-white bg-opacity-80 rounded-full px-3 py-1 shadow hover:bg-opacity-100">❮</button>

            <img id="modalImageSaved" class="max-h-[80vh] w-auto rounded transition-transform duration-200" alt="Preview Gambar">

            <a id="downloadImageBtn" href="#" download
               class="absolute bottom-2 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-4 py-1 rounded shadow hover:bg-blue-700">
                Download
            </a>

            <button id="nextSavedImage" type="button"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xl bg-white bg-opacity-80 rounded-full px-3 py-1 shadow hover:bg-opacity-100">❯</button>
        </div>
    </div>
</div>


<script>
   document.addEventListener('DOMContentLoaded', function () {
    const savedModal = document.getElementById('modalPreviewsaved');
    const savedModalImage = document.getElementById('modalImageSaved');
    const savedCloseModal = document.getElementById('closeModalSaved');
    const savedPreviewContainer = document.getElementById('savedImagePreview');
    const savedDownloadBtn = document.getElementById('downloadImageBtn');
    const savedPrevBtn = document.getElementById('prevSavedImage');
    const savedNextBtn = document.getElementById('nextSavedImage');

    let savedCurrentIndex = 0;
    let savedImageList = [];

    if (savedPreviewContainer) {
        savedImageList = Array.from(savedPreviewContainer.querySelectorAll('img'));

        savedPreviewContainer.addEventListener('click', function (e) {
            if (e.target.tagName === 'IMG' && e.target.dataset.full) {
                savedCurrentIndex = savedImageList.indexOf(e.target);
                showSavedImage(savedCurrentIndex);
            }
        });
    }

    function showSavedImage(index) {
        if (index < 0 || index >= savedImageList.length) return;

        const imageSrc = savedImageList[index].dataset.full;
        savedModalImage.src = imageSrc;
        savedDownloadBtn.href = imageSrc;
        savedModal.classList.remove('hidden');
        savedCurrentIndex = index;
    }

    savedCloseModal.addEventListener('click', function () {
        savedModal.classList.add('hidden');
    });

    savedPrevBtn.addEventListener('click', function () {
        showSavedImage(savedCurrentIndex - 1);
    });

    savedNextBtn.addEventListener('click', function () {
        showSavedImage(savedCurrentIndex + 1);
    });

    document.addEventListener('keydown', function (e) {
        if (savedModal.classList.contains('hidden')) return;

        if (e.key === 'Escape') {
            savedModal.classList.add('hidden');
        } else if (e.key === 'ArrowLeft') {
            showSavedImage(savedCurrentIndex - 1);
        } else if (e.key === 'ArrowRight') {
            showSavedImage(savedCurrentIndex + 1);
        }
    });

    savedModal.addEventListener('click', function (e) {
        if (e.target === savedModal) {
            savedModal.classList.add('hidden');
        }
    });

    // Konfirmasi hapus
    document.querySelectorAll('.delete-image-form').forEach(form => {
        form.addEventListener('submit', function (e) {
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
