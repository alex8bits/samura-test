<?php

namespace App\Http\Controllers;

use App\Exports\OzonPostingExport;
use App\Filters\OzonPostingFilter;
use App\Models\OzonPosting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PostingController extends Controller
{
    public function index(Request $request, OzonPostingFilter $filter)
    {
        $postings = OzonPosting::filter($filter)->paginate();

        return view('admin.posting.list', compact('postings'));
    }

    public function export(Request $request, OzonPostingFilter $filter)
    {
        $postings = OzonPosting::filter($filter)->latest()->get();

        return Excel::download(new OzonPostingExport($postings), 'posting.xlsx');
    }
}
