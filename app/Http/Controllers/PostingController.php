<?php

namespace App\Http\Controllers;

use App\Filters\OzonPostingFilter;
use App\Models\OzonPosting;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    public function index(Request $request, OzonPostingFilter $filter)
    {
        $postings = OzonPosting::latest()->filter($filter)->paginate();

        return view('admin.posting.list', compact('postings'));
    }
}
