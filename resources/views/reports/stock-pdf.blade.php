<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Produk</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1E293B;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            color: #3B82F6;
            margin-bottom: 10px;
        }
        .report-date {
            color: #64748B;
            font-size: 14px;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 15px;
        }
        .summary-card {
            flex: 1;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
        }
        .summary-number {
            font-size: 24px;
            font-weight: bold;
            color: #1E293B;
        }
        .summary-label {
            font-size: 12px;
            color: #64748B;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #3B82F6;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #E2E8F0;
        }
        tr:nth-child(even) {
            background-color: #F8FAFC;
        }
        .status-available {
            color: #059669;
            font-weight: bold;
        }
        .status-low {
            color: #D97706;
            font-weight: bold;
        }
        .status-out {
            color: #DC2626;
            font-weight: bold;
        }
        .category-header {
            background-color: #EFF6FF;
            font-weight: bold;
            padding: 8px;
            margin-top: 15px;
            border-left: 4px solid #3B82F6;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E2E8F0;
            text-align: center;
            color: #64748B;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $companyName }}</div>
        <div class="report-title">LAPORAN STOK PRODUK</div>
        <div class="report-date">Periode: {{ $reportDate }}</div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-number">{{ $totalProducts }}</div>
            <div class="summary-label">Total Produk</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">{{ number_format($totalStock, 0, ',', '.') }}</div>
            <div class="summary-label">Total Stok</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">Rp {{ number_format($totalValue, 0, ',', '.') }}</div>
            <div class="summary-label">Total Nilai Stok</div>
        </div>
    </div>

    <!-- Products Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 8%">SKU</th>
                <th style="width: 25%">Nama Produk</th>
                <th style="width: 15%">Kategori</th>
                <th style="width: 12%" class="text-right">Harga Beli</th>
                <th style="width: 12%" class="text-right">Harga Jual</th>
                <th style="width: 8%" class="text-right">Stok</th>
                <th style="width: 10%" class="text-right">Stok Min</th>
                <th style="width: 10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentCategory = null;
            @endphp
            
            @foreach($products as $product)
                @if($currentCategory !== $product->category->name)
                    @php $currentCategory = $product->category->name; @endphp
                    <tr>
                        <td colspan="8" class="category-header">
                            KATEGORI: {{ $currentCategory }}
                        </td>
                    </tr>
                @endif
                
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td class="text-right">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $product->stock }}</td>
                    <td class="text-right">{{ $product->min_stock }}</td>
                    <td>
                        @if($product->status == 'available')
                            <span class="status-available">TERSEDIA</span>
                        @elseif($product->status == 'low_stock')
                            <span class="status-low">MENIPIS</span>
                        @else
                            <span class="status-out">HABIS</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <div style="margin-top: 30px; padding: 15px; background: #F1F5F9; border-radius: 8px;">
        <strong>Ringkasan Kategori:</strong><br>
        @foreach($categories as $category)
            • {{ $category->name }}: {{ $category->products_count }} produk<br>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan ini dihasilkan secara otomatis oleh Sistem Manajemen Stok Ronadamar<br>
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>