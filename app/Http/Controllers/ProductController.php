<?php

namespace App\Http\Controllers;

use App\Data\Param\ProductSearchData;
use App\Service\ProductSearchService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request, ProductSearchService $productSearchService)
    {
        $searchData = ProductSearchData::from($request->all());
        $page = $request->input('page', 1);
        $perPage = 10; // Default items per page
        $searchResults = $productSearchService->search($searchData, $page, $perPage);

        return Inertia::render('Welcome', [
            'filters' => $searchData,
            'searchResults' => $searchResults,
        ]);
    }
}
