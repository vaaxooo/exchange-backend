<?php

namespace App\Services\User;

use App\Models\Page;

class PageService
{

	/**
	 * It fetches all the published pages and returns them in a paginated format
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function index()
	{
		$per_page  = request()->per_page ?? 10;
		$pages     = Page::where('is_published', true)->orderBy('created_at', 'desc')->paginate($per_page);
		return [
			'code'    => 200,
			'message' => 'Pages fetched successfully',
			'data'    => $pages
		];
	}

	/**
	 * It returns a JSON response with a 200 status code, a message, and the page data
	 * 
	 * @param Page page The page model instance.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function show(Page $page)
	{
		return [
			'code'    => 200,
			'message' => 'Page fetched successfully',
			'data'    => $page
		];
	}
}
