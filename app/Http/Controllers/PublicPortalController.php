<?php

namespace App\Http\Controllers;

use App\Models\WasteCategory;
use App\Models\WasteItem;
use App\Models\WasteDeposit;
use App\Models\User;
use App\Models\NasabahProfile;
use Illuminate\Http\Request;

class PublicPortalController extends Controller
{
    public function index()
    {
        // Statistik Dampak Lingkungan
        $totalSampahKg = WasteDeposit::where('status', 'completed')->sum('total_weight_kg');
        $totalNasabah = User::where('role', 'nasabah')->count();
        $totalDanaDisalurkan = WasteDeposit::where('status', 'completed')->sum('total_amount');
        $totalCo2Tereduksi = WasteDeposit::where('status', 'completed')->sum('total_co2_kg');

        // Kategori Sampah untuk Cuplikan
        $categories = WasteCategory::with(['items' => function($q) {
            $q->where('is_active', true)->take(4);
        }])->where('is_active', true)->get();

        $featuredItems = WasteItem::with('category')
            ->where('is_active', true)
            ->orderBy('buy_price_per_unit', 'desc')
            ->take(6)
            ->get();

        return view('public.index', compact(
            'totalSampahKg',
            'totalNasabah',
            'totalDanaDisalurkan',
            'totalCo2Tereduksi',
            'categories',
            'featuredItems'
        ));
    }

    public function katalog(Request $request)
    {
        $selectedCategory = $request->query('kategori');
        $search = $request->query('cari');

        $categories = WasteCategory::where('is_active', true)->get();

        $query = WasteItem::with('category')->where('is_active', true);

        if ($selectedCategory) {
            $query->whereHas('category', function($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        $items = $query->orderBy('name')->paginate(12);

        return view('public.katalog', compact('categories', 'items', 'selectedCategory', 'search'));
    }

    public function kalkulator()
    {
        $categories = WasteCategory::with(['items' => function($q) {
            $q->where('is_active', true);
        }])->where('is_active', true)->get();

        $allItems = WasteItem::where('is_active', true)->orderBy('name')->get();

        return view('public.kalkulator', compact('categories', 'allItems'));
    }

    public function edukasi()
    {
        return view('public.edukasi');
    }
}
