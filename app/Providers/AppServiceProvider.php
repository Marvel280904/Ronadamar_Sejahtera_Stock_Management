<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share notifications data to all views
        View::composer('*', function ($view) {
            $notifications = $this->getNotifications();
            
            $view->with([
                'notifications' => $notifications,
                'unreadCount' => count($notifications) // Semua notification dianggap "baru"
            ]);
        });
    }

    /**
     * Get all notifications for the application
     */
    private function getNotifications()
    {
        $notifications = [];
        
        // 1. Stock alerts (low stock & out of stock)
        $stockAlerts = $this->getStockAlerts();
        $notifications = array_merge($notifications, $stockAlerts);
        
        // 2. Stock opname reminder (setiap awal bulan)
        $opnameReminder = $this->getStockOpnameReminder();
        if ($opnameReminder) {
            $notifications[] = $opnameReminder;
        }
        
        return $notifications;
    }

    /**
     * Get stock alerts for low stock and out of stock products
     */
    private function getStockAlerts()
    {
        $alerts = [];
        
        // Products with low stock (stock <= min_stock but > 0)
        $lowStockProducts = Product::where('stock', '>', 0)
            ->whereRaw('stock <= min_stock')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();
            
        foreach ($lowStockProducts as $product) {
            $alerts[] = [
                'id' => 'low_stock_' . $product->id,
                'type' => 'warning',
                'title' => 'Stok Menipis',
                'message' => "Stok {$product->name} menipis - sisa {$product->stock} unit",
                'url' => route('products.index'),
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'text-orange-500',
                'time' => 'Baru',
                'product_id' => $product->id
            ];
        }
        
        // Out of stock products
        $outOfStockProducts = Product::where('stock', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($outOfStockProducts as $product) {
            $alerts[] = [
                'id' => 'out_of_stock_' . $product->id,
                'type' => 'danger',
                'title' => 'Stok Habis',
                'message' => "Stok {$product->name} sudah habis",
                'url' => route('products.index'),
                'icon' => 'fas fa-times-circle',
                'color' => 'text-red-500',
                'time' => 'Baru',
                'product_id' => $product->id
            ];
        }
        
        return $alerts;
    }

    /**
     * Get stock opname reminder for the beginning of the month
     */
    private function getStockOpnameReminder()
    {
        $today = Carbon::now();
        $firstDayOfMonth = Carbon::now()->firstOfMonth();
        $fifthDayOfMonth = Carbon::now()->firstOfMonth()->addDays(4); // Hingga tanggal 5
        
        // Tampilkan reminder dari tanggal 1-5 setiap bulan
        if ($today->between($firstDayOfMonth, $fifthDayOfMonth)) {
            $daysLeft = $fifthDayOfMonth->diffInDays($today);
            
            return [
                'id' => 'stock_opname_reminder',
                'type' => 'info',
                'title' => 'Stock Opname',
                'message' => $daysLeft > 0 
                    ? "Jangan lupa lakukan stock opname! Sisa {$daysLeft} hari."
                    : "Hari terakhir untuk stock opname bulan ini!",
                'url' => route('products.index'),
                'icon' => 'fas fa-clipboard-check',
                'color' => 'text-blue-500',
                'time' => 'Bulanan',
            ];
        }
        
        return null;
    }
}