<?php

namespace App\Services\User;

use App\Models\Page;
use App\Models\Contact;

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
	public function show($page)
	{
		$page = Page::where('slug', $page)->where('is_published', true)->first();
		return [
			'code'    => 200,
			'message' => 'Page fetched successfully',
			'data'    => $page
		];
	}

	/**
	 * It fetches all contacts from the database and returns them in a JSON response
	 * 
	 * @return An array with three keys: code, message, and data.
	 */
	public function getContacts()
	{
		$contacts = Contact::all();
		return [
			'code'    => 200,
			'message' => 'Contacts fetched successfully',
			'data'    => $contacts
		];
	}
}
