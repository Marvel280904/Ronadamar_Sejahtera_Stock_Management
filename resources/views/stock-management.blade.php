@extends('layouts.app')

@section('title', 'Manajemen Stok')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Manajemen Stok</h1>
        <p class="text-gray-500">Kelola semua produk dan inventaris</p>
    </div>

    <!-- Success Notification -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-2"></i>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.style.display='none'" 
                class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <i class="fas fa-times text-green-500"></i>
        </button>
    </div>
    @endif

    <!-- Controls -->
    <div class="flex items-center justify-between gap-4">
        <!-- Search -->
        <div class="flex-1 max-w-md relative">
            <i class="fas fa-search absolute left-3 top-1/3 -translate-y-1/2 text-gray-400"></i>
            <input type="text" 
                   placeholder="Cari produk..." 
                   class="pl-10 h-10 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   id="searchInput">
        </div>
        
        <!-- Buttons -->
        <div class="flex gap-3">
            <!-- Download Laporan PDF -->
            <a href="{{ route('reports.stock.pdf') }}" 
            class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <i class="fas fa-download mr-2"></i>
                Download Laporan
            </a>

            <!-- Add Produk -->
            <button onclick="openAddModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Produk
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-12 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $product->sku }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $product->category->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm {{ $product->stock < 5 ? 'text-orange-600 font-medium' : 'text-gray-900' }}">
                                {{ $product->stock }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->status == 'available')
                                <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs font-medium">Tersedia</span>
                            @elseif($product->status == 'low_stock')
                                <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs font-medium">Stok Menipis</span>
                            @else
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs font-medium">Habis</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Tombol Kelola Stok -->
                                <button onclick="openStockModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }})" 
                                        class="text-purple-600 hover:text-purple-900 hover:bg-purple-50 p-2 rounded-lg transition-colors"
                                        title="Kelola Stok">
                                    <i class="fas fa-warehouse"></i>
                                </button>
                                
                                <!-- Tombol Edit Produk -->
                                <button onclick="openEditModal({{ $product->id }}, '{{ $product->name }}', '{{ $product->sku }}', {{ $product->category_id }}, {{ $product->buy_price }}, {{ $product->sell_price }}, {{ $product->stock }}, {{ $product->min_stock }}, `{{ $product->description }}`)" 
                                        class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-2 rounded-lg transition-colors"
                                        title="Edit Produk">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <!-- Tombol Hapus Produk -->
                                <button onclick="openDeleteModal({{ $product->id }}, '{{ $product->name }}')" 
                                    class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded-lg transition-colors"
                                    title="Hapus Produk">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Tambah Produk Baru</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="name" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Lem Fox Putih 250gr">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="LFP-001">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" required 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                        <input type="number" name="buy_price" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
                        <input type="number" name="sell_price" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
                        <input type="number" name="stock" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                        <input type="number" name="min_stock" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="5" value="5">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              rows="3" placeholder="Deskripsi produk..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeAddModal()" 
                        class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Edit Produk</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="name" id="editName" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Lem Fox Putih 250gr">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku" id="editSku" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="LEM-FOX-250">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" id="editCategory" required 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                        <input type="number" name="buy_price" id="editBuyPrice" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
                        <input type="number" name="sell_price" id="editSellPrice" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Saat Ini</label>
                        <input type="number" id="editStockDisplay" disabled
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-500">
                        <small class="text-gray-400">* Untuk ubah stok, gunakan fitur Tambah Stok (+)</small>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                        <input type="number" name="min_stock" id="editMinStock" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="5">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="editDescription"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              rows="3" placeholder="Deskripsi produk..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeEditModal()" 
                        class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Update Produk
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stock Management Modal -->
<div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden items-center justify-center">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Kelola Stok</h3>
            <button onclick="closeStockModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="stockForm" method="POST">
            @csrf
            <div class="space-y-4">
                <!-- Product Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900" id="stockProductName"></h4>
                    <p class="text-sm text-gray-500">Stok saat ini: <span id="currentStock" class="font-medium"></span></p>
                </div>
                
                <!-- Action Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Aksi</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="action_type" value="in" checked 
                                   class="peer sr-only" onchange="toggleStockAction()">
                            <div class="flex items-center justify-center w-full p-3 border-2 border-gray-300 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition-colors">
                                <i class="fas fa-plus text-green-600 mr-2"></i>
                                <span class="text-green-600 font-medium">Tambah Stok</span>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="action_type" value="out"
                                   class="peer sr-only" onchange="toggleStockAction()">
                            <div class="flex items-center justify-center w-full p-3 border-2 border-gray-300 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 transition-colors">
                                <i class="fas fa-minus text-red-600 mr-2"></i>
                                <span class="text-red-600 font-medium">Kurangi Stok</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Quantity Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" id="quantityLabel">
                        Jumlah Stok
                    </label>
                    <input type="number" name="quantity" id="stockQuantity" required min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0">
                    <small class="text-gray-400" id="quantityHelp">
                        Masukkan jumlah stok yang akan ditambahkan
                    </small>
                </div>
                
                <!-- Note -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <textarea name="note" id="stockNote"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              rows="2" placeholder="Contoh: Pembelian dari supplier..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeStockModal()" 
                        class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" id="stockSubmitBtn"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Tambah Stok
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden items-center justify-center">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-red-100">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
        </div>
        
        <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Hapus Produk</h3>
        <p class="text-gray-500 text-center mb-6" id="deleteProductName">Apakah Anda yakin ingin menghapus produk ini?</p>
        
        <div class="flex justify-center gap-3">
            <button type="button" onclick="closeDeleteModal()" 
                    class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors w-24">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors w-24">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Open Add Modal
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}

// Close Add Modal
function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}

// Open Edit Modal
function openEditModal(id, name, sku, categoryId, buyPrice, sellPrice, stock, minStock, description) {
    // Isi form dengan data produk
    document.getElementById('editName').value = name;
    document.getElementById('editSku').value = sku;
    document.getElementById('editCategory').value = categoryId;
    document.getElementById('editBuyPrice').value = buyPrice;
    document.getElementById('editSellPrice').value = sellPrice;
    document.getElementById('editStockDisplay').value = stock;
    document.getElementById('editMinStock').value = minStock;
    document.getElementById('editDescription').value = description;
    
    // Set form action
    document.getElementById('editForm').action = `/products/${id}`;
    
    // Tampilkan modal
    document.getElementById('editModal').classList.remove('hidden');
}

// Close Edit Modal
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}


let currentProductId = null;
let currentStock = 0;

// Open Stock Modal
function openStockModal(productId, productName, stock) {
    currentProductId = productId;
    currentStock = stock;
    
    // Reset form
    document.getElementById('stockProductName').textContent = productName;
    document.getElementById('currentStock').textContent = stock;
    document.getElementById('stockQuantity').value = '';
    document.getElementById('stockNote').value = '';
    
    // Set default action
    document.querySelector('input[name="action_type"][value="in"]').checked = true;
    toggleStockAction();
    
    // Set form action
    document.getElementById('stockForm').action = `/products/${productId}/manage-stock`;
    
    // Show modal
    document.getElementById('stockModal').classList.remove('hidden');
}

// Close Stock Modal
function closeStockModal() {
    document.getElementById('stockModal').classList.add('hidden');
}

function toggleStockAction() {
    const actionType = document.querySelector('input[name="action_type"]:checked').value;
    const quantityLabel = document.getElementById('quantityLabel');
    const quantityHelp = document.getElementById('quantityHelp');
    const submitBtn = document.getElementById('stockSubmitBtn');
    
    if (actionType === 'in') {
        quantityLabel.textContent = 'Jumlah Stok';
        quantityHelp.textContent = 'Masukkan jumlah stok yang akan ditambahkan';
        submitBtn.textContent = 'Tambah Stok';
        submitBtn.className = 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors';
    } else {
        quantityLabel.textContent = 'Jumlah Stok';
        quantityHelp.textContent = `Masukkan jumlah stok yang akan dikurangi (maks: ${currentStock})`;
        submitBtn.textContent = 'Kurangi Stok';
        submitBtn.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors';
        
        // Set max value for reduction
        document.getElementById('stockQuantity').max = currentStock;
    }
}

// Open Delete Confirmation Modal
function openDeleteModal(productId, productName) {
    // Set nama produk di modal
    document.getElementById('deleteProductName').textContent = `Apakah Anda yakin ingin menghapus "${productName}"?`;
    
    // Set form action
    document.getElementById('deleteForm').action = `/products/${productId}`;
    
    // Tampilkan modal
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Close Delete Confirmation Modal
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Auto-hide success message after 5 seconds
@if(session('success'))
setTimeout(() => {
    const successMessage = document.querySelector('.bg-green-100');
    if (successMessage) {
        successMessage.style.display = 'none';
    }
}, 5000);
@endif
</script>
@endsection