<?php

namespace App\Http\Controllers;

use App\Data\Param\ProductSearchData;
use App\Service\ProductSearchService;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Search controller for products
 * ProductController
 */
class ProductController extends Controller
{
    /**
     * Search products
     * @param Request $request
     * @param ProductSearchService $productSearchService
     * @return \Inertia\Response
     */
    public function index(Request $request, ProductSearchService $productSearchService)
    {
        $searchData = ProductSearchData::from($request->all());

        $page = $request->input('page', 1);
        $searchResults = $productSearchService->search($searchData, $page);

        return Inertia::render('Welcome', [
            'filters' => $searchData,
            'searchResults' => $searchResults,
        ]);
    }

}
