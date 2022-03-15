<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
class FrontController extends Controller
{
    public function show($slug)
	{
		
		$content = Page::where('slug', $slug)->first();
		//dd($content);

		if (is_null($content)) {
			abort(404);
		}
		return view('page', compact('content'));
	}
}
