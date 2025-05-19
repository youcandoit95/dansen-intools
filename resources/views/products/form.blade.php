<x-alert-error />

<div class="mb-4">
    <label class="block text-sm mb-1">Barcode</label>

    @php $barcodeLocked = !empty(old('barcode', $product->barcode ?? '')); @endphp

    <div class="flex items-center gap-2">
        <input type="text"
               id="barcode"
               name="barcode"
               class="flex-1 border rounded px-3 py-2 bg-gray-100 {{ $barcodeLocked ? 'cursor-not-allowed text-gray-500' : '' }}"
               value="{{ old('barcode', $product->barcode ?? '') }}"
               {{ $barcodeLocked ? 'readonly' : '' }}>

        @unless($barcodeLocked)
            <button type="button"
                    id="generateBarcode"
                    class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">
                Generate
            </button>
        @endunless
    </div>

    @if($barcodeLocked)
        <p class="text-xs text-red-600 mt-1">Barcode tidak bisa diubah, harap hubungi administrator.</p>
    @endif
</div>


<div class="mb-4">
    <label class="block text-sm mb-1">Nama Produk</label>
    <input type="text" name="nama" class="w-full border rounded px-3 py-2"
           value="{{ old('nama', $product->nama ?? '') }}">
</div>
<div class="mb-4">
    <label class="block text-sm mb-1">Kategori</label>
    <select name="kategori" class="w-full border rounded px-3 py-2">
        <option value="1" {{ old('kategori', $product->kategori ?? '') == 1 ? 'selected' : '' }}>Loaf/Kg</option>
        <option value="2" {{ old('kategori', $product->kategori ?? '') == 2 ? 'selected' : '' }}>Cut/Kg</option>
        <option value="3" {{ old('kategori', $product->kategori ?? '') == 3 ? 'selected' : '' }}>Pcs/Pack</option>
    </select>
</div>
<div class="mb-4">
    <label class="block text-sm mb-1">Brand</label>
    <select name="brand" class="w-full border rounded px-3 py-2">
        <option value="1" {{ old('brand', $product->brand ?? '') == 1 ? 'selected' : '' }}>Tokusen</option>
        <option value="2" {{ old('brand', $product->brand ?? '') == 2 ? 'selected' : '' }}>Sher Wagyu</option>
        <option value="3" {{ old('brand', $product->brand ?? '') == 3 ? 'selected' : '' }}>Angus Pure/G</option>
    </select>
</div>
<div class="mb-4">
    <label class="block text-sm mb-1">Deskripsi</label>
    <textarea name="deskripsi" class="w-full border rounded px-3 py-2">{{ old('deskripsi', $product->deskripsi ?? '') }}</textarea>
</div>
<div class="mb-4">
    <label class="block text-sm mb-1">Status</label>
    <select name="status" class="w-full border rounded px-3 py-2">
        <option value="1" {{ old('status', $product->status ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ old('status', $product->status ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
    </select>
</div>
<div class="mb-4">
    <label class="block text-sm mb-1">Gambar Produk (bisa multiple)</label>
    <input type="file" id="inputImage" name="images[]" multiple class="w-full">
</div>
<div id="imagePreview" class="flex gap-3 mt-2 overflow-x-auto border rounded p-2" style="scrollbar-width: thin;"></div>

<!-- Modal Preview Gambar -->
<div id="modalPreview" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center">
    <div class="relative bg-white rounded-lg shadow-lg max-w-5xl w-[80%] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">Preview Gambar</h3>
            <button id="closeModal" type="button" class="text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        <div class="p-4 bg-gray-50 flex items-center justify-center relative">
            <button id="prevImage" type="button" class="absolute left-2 top-1/2 transform -translate-y-1/2 text-2xl bg-white bg-opacity-80 rounded-full px-3 py-1 shadow hover:bg-opacity-100">❮</button>
            <img id="modalImage" class="max-h-[80vh] w-auto rounded transition-transform duration-200" alt="Preview Gambar">
            <button id="nextImage" type="button" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xl bg-white bg-opacity-80 rounded-full px-3 py-1 shadow hover:bg-opacity-100">❯</button>
        </div>
    </div>
</div>


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const input = document.getElementById('inputImage');
        const preview = document.getElementById('imagePreview');
        input.addEventListener('change', function () {
            preview.innerHTML = '';
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-32 rounded object-cover aspect-square cursor-pointer';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
        new Sortable(preview, {
            animation: 150,
            direction: 'horizontal'
        });

        const modal = document.getElementById('modalPreview');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.getElementById('closeModal');
        const prevBtn = document.getElementById('prevImage');
        const nextBtn = document.getElementById('nextImage');
        let currentIndex = 0;

        preview.addEventListener('click', function (e) {
            if (e.target.tagName === 'IMG') {
                const imgs = Array.from(preview.querySelectorAll('img'));
                currentIndex = imgs.indexOf(e.target);
                showModalImage(currentIndex);
            }
        });

        function showModalImage(index) {
            const imgs = preview.querySelectorAll('img');
            if (imgs.length === 0) return;
            index = Math.max(0, Math.min(index, imgs.length - 1));
            modalImage.src = imgs[index].src;
            modal.classList.remove('hidden');
            currentIndex = index;
        }

        closeModal.addEventListener('click', function () {
            modal.classList.add('hidden');
        });

        document.addEventListener('keydown', function (e) {
            if (!modal.classList.contains('hidden')) {
                if (e.key === 'Escape') {
                    modal.classList.add('hidden');
                } else if (e.key === 'ArrowLeft') {
                    showModalImage(currentIndex - 1);
                } else if (e.key === 'ArrowRight') {
                    showModalImage(currentIndex + 1);
                }
            }
        });

        prevBtn.addEventListener('click', function () {
            showModalImage(currentIndex - 1);
        });

        nextBtn.addEventListener('click', function () {
            showModalImage(currentIndex + 1);
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
@endsection
