@extends('layouts.app')

@section('title', 'API Tester')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">API Tester</h1>
        <p class="text-gray-500">Test API endpoints untuk integrasi sistem</p>
    </div>

    <!-- API Tester Interface -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200" x-data="apiTester()">
        <!-- Login Section -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-3">🔑 Step 1: Login untuk mendapatkan Token</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" x-model="loginData.email" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="admin@ronadamar.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" x-model="loginData.password" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="admin123">
                </div>
            </div>
            <button @click="login()" 
                    class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                    :disabled="loading">
                <span x-show="!loading">Login & Get Token</span>
                <span x-show="loading" class="flex items-center">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Loading...
                </span>
            </button>
            
            <div x-show="token" class="mt-3 p-3 bg-green-50 rounded border border-green-200">
                <label class="block text-sm font-medium text-gray-700 mb-1">API Token:</label>
                <code class="bg-green-100 text-green-800 px-3 py-2 rounded text-sm block break-all" x-text="token"></code>
            </div>
        </div>

        <!-- API Test Section -->
        <div x-show="token">
            <h3 class="text-lg font-medium text-gray-900 mb-4">🔧 Step 2: Test API Endpoints</h3>
            
            <!-- Endpoint Selection -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Endpoint:</label>
                
                <!-- Method Selection -->
                <div class="grid grid-cols-4 gap-2 mb-2">
                    <button @click="setMethod('GET')" 
                            :class="method === 'GET' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="py-2 px-3 rounded-lg font-medium transition-colors text-sm">
                        GET
                    </button>
                    <button @click="setMethod('POST')" 
                            :class="method === 'POST' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="py-2 px-3 rounded-lg font-medium transition-colors text-sm">
                        POST
                    </button>
                    <button @click="setMethod('PUT')" 
                            :class="method === 'PUT' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="py-2 px-3 rounded-lg font-medium transition-colors text-sm">
                        PUT
                    </button>
                    <button @click="setMethod('DELETE')" 
                            :class="method === 'DELETE' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="py-2 px-3 rounded-lg font-medium transition-colors text-sm">
                        DELETE
                    </button>
                </div>

                <!-- URL Input -->
                <div class="flex gap-2">
                    <div class="flex-1">
                        <input type="text" x-model="endpointUrl" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                            placeholder="/api/products/1">
                    </div>
                    <button @click="updateRequestData()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap">
                        Update Data
                    </button>
                </div>
                
                <!-- Quick Endpoints -->
                <div class="mt-2">
                    <label class="block text-sm text-gray-600 mb-1">Quick Endpoints:</label>
                    <div class="flex flex-wrap gap-1">
                        <button @click="setQuickEndpoint('/api/products')" 
                                class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors">
                            /products
                        </button>
                        <button @click="setQuickEndpoint('/api/products/1')" 
                                class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors">
                            /products/1
                        </button>
                        <button @click="setQuickEndpoint('/api/categories')" 
                                class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors">
                            /categories
                        </button>
                        <button @click="setQuickEndpoint('/api/user')" 
                                class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors">
                            /user
                        </button>
                        <button @click="setQuickEndpoint('/api/products/1/add-stock')" 
                                class="text-xs bg-green-100 hover:bg-green-200 text-green-700 px-2 py-1 rounded transition-colors">
                            /add-stock
                        </button>
                        <button @click="setQuickEndpoint('/api/products/1/reduce-stock')" 
                                class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded transition-colors">
                            /reduce-stock
                        </button>
                    </div>
                </div>
            </div>

            <!-- Request Data -->
            <div x-show="requestData" class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Request Data (JSON):</label>
                <textarea x-model="requestData" rows="6"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                          placeholder='{"name": "Product Name", "sku": "SKU-001", ...}'></textarea>
            </div>

            <!-- Test Button -->
            <button @click="testEndpoint()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors mb-4"
                    :disabled="loading">
                <span x-show="!loading">Test Endpoint</span>
                <span x-show="loading" class="flex items-center">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Testing...
                </span>
            </button>

            <!-- Response -->
            <div x-show="response">
                <label class="block text-sm font-medium text-gray-700 mb-2">Response:</label>
                <pre class="bg-gray-100 p-4 rounded-lg text-sm overflow-x-auto border border-gray-300" x-text="response"></pre>
            </div>
        </div>
    </div>

    <!-- API Documentation -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Available Endpoints -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">📚 Available Endpoints</h3>
            <div class="space-y-3">
                <div class="border-l-4 border-blue-500 pl-4">
                    <code class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">GET /api/products</code>
                    <p class="text-sm text-gray-600 mt-1">Get semua produk dengan filter opsional</p>
                </div>
                <div class="border-l-4 border-green-500 pl-4">
                    <code class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">POST /api/products</code>
                    <p class="text-sm text-gray-600 mt-1">Buat produk baru</p>
                </div>
                <div class="border-l-4 border-yellow-500 pl-4">
                    <code class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">PUT /api/products/{id}</code>
                    <p class="text-sm text-gray-600 mt-1">Update produk</p>
                </div>
                <div class="border-l-4 border-red-500 pl-4">
                    <code class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">DELETE /api/products/{id}</code>
                    <p class="text-sm text-gray-600 mt-1">Hapus produk</p>
                </div>
                <div class="border-l-4 border-purple-500 pl-4">
                    <code class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm">POST /api/products/{id}/add-stock</code>
                    <p class="text-sm text-gray-600 mt-1">Tambah stok produk</p>
                </div>
                <div class="border-l-4 border-orange-500 pl-4">
                    <code class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">POST /api/products/{id}/reduce-stock</code>
                    <p class="text-sm text-gray-600 mt-1">Kurangi stok produk</p>
                </div>
            </div>
        </div>

        <!-- Example Requests -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">💡 Example Requests</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Create Product:</p>
                    <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto">{
  "name": "Lem Fox Baru 500gr",
  "sku": "LEM-FOX-500",
  "category_id": 1,
  "buy_price": 25000,
  "sell_price": 40000,
  "stock": 50,
  "min_stock": 10,
  "description": "Lem fox kemasan besar"
}</pre>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Add Stock:</p>
                    <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto">{
  "quantity": 10,
  "note": "Pembelian dari supplier"
}</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function apiTester() {
    return {
        loading: false,
        token: null,
        loginData: {
            email: 'admin@ronadamar.com',
            password: 'admin123'
        },
        method: 'GET',
        endpointUrl: '/api/products',
        requestData: '',
        response: '',
        
        // Default request data untuk pattern endpoint
        endpointPatterns: {
  'POST /api/products$': `{
  "name": "Lem Fox Baru 500gr",
  "sku": "LEM-FOX-500",
  "category_id": 1,
  "buy_price": 25000,
  "sell_price": 40000,
  "stock": 50,
  "min_stock": 10,
  "description": "Lem fox kemasan besar"
}`,
    'PUT /api/products/': `{
  "name": "Lem Fox Updated",
  "sku": "LEM-FOX-UPDATED", 
  "category_id": 1,
  "buy_price": 26000,
  "sell_price": 42000,
  "min_stock": 5,
  "description": "Updated description"
}`,
    'POST /api/products/.*/add-stock': `{
  "quantity": 10,
  "note": "Pembelian dari supplier"
}`,
    'POST /api/products/.*/reduce-stock': `{
  "quantity": 5,
  "note": "Penjualan toko"
}`

        },
        
        setMethod(selectedMethod) {
            this.method = selectedMethod;
            this.updateRequestData();
        },
        
        setQuickEndpoint(url) {
            this.endpointUrl = url;
            
            // Auto-set method berdasarkan endpoint
            if (url.includes('/add-stock') || url.includes('/reduce-stock') || url === '/api/products') {
                this.method = 'POST';
            } else if (url.includes('/products/') && !url.includes('/add-stock') && !url.includes('/reduce-stock')) {
                this.method = 'PUT';
            } else {
                this.method = 'GET';
            }
            
            this.updateRequestData();
        },
        
        updateRequestData() {
            const fullEndpoint = `${this.method} ${this.endpointUrl}`;
            
            // Cari pattern yang match (gunakan includes untuk simpler matching)
            let matchedPattern = null;
            
            if (this.method === 'POST' && this.endpointUrl === '/api/products') {
                matchedPattern = 'POST /api/products$';
            } else if (this.method === 'PUT' && this.endpointUrl.includes('/api/products/')) {
                matchedPattern = 'PUT /api/products/';
            } else if (this.method === 'POST' && this.endpointUrl.includes('/add-stock')) {
                matchedPattern = 'POST /api/products/.*/add-stock';
            } else if (this.method === 'POST' && this.endpointUrl.includes('/reduce-stock')) {
                matchedPattern = 'POST /api/products/.*/reduce-stock';
            }
            
            if (matchedPattern && this.endpointPatterns[matchedPattern]) {
                this.requestData = this.endpointPatterns[matchedPattern];
                return;
            }
            
            // Default empty untuk GET/DELETE requests
            if (['GET', 'DELETE'].includes(this.method)) {
                this.requestData = '';
            } else {
                // Default data untuk POST/PUT tanpa pattern spesifik
                this.requestData = `{
        "data": "Isi request data sesuai kebutuhan"
        }`;
            }
        },
        
        async login() {
            this.loading = true;
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.loginData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.token = data.data.token;
                    this.response = JSON.stringify(data, null, 2);
                } else {
                    this.response = JSON.stringify(data, null, 2);
                }
            } catch (error) {
                this.response = `Error: ${error.message}`;
            } finally {
                this.loading = false;
            }
        },
        
        async testEndpoint() {
            if (!this.token) {
                alert('Please login first to get token!');
                return;
            }
            
            this.loading = true;
            try {
                const url = this.endpointUrl;
                const options = {
                    method: this.method,
                    headers: {
                        'Authorization': `Bearer ${this.token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                };
                
                // Add request body untuk POST/PUT
                if (['POST', 'PUT'].includes(this.method) && this.requestData) {
                    options.body = this.requestData;
                }
                
                const response = await fetch(url, options);
                const data = await response.json();
                
                this.response = JSON.stringify(data, null, 2);
            } catch (error) {
                this.response = `Error: ${error.message}`;
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection