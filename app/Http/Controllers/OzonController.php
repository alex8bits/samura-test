<?php

namespace App\Http\Controllers;

use App\Exports\OzonProductExport;
use App\Services\Ozon\OzonShowService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OzonController extends Controller
{
    public function __construct(protected OzonShowService $service)
    {}

    public function index()
    {
        $products = $this->service->getProducts();

        return view('products.list', compact('products'));
    }

    public function export()
    {
        $products = $this->service->getProducts();

        return Excel::download(new OzonProductExport(collect($products)), 'products.xlsx');
    }
}
