<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\PageService;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{

	private $pages;

	public function __construct()
	{
		$this->pages = new PageService();
	}

	/**
	 * It returns a JSON response of the index method of the pages class.
	 * 
	 * @return The index method of the Pages class is being returned.
	 */
	public function index()
	{
		return response()->json($this->pages->index());
	}

	/**
	 * It returns a JSON response of the page with the given ID.
	 * 
	 * @param id The id of the page you want to show.
	 * 
	 * @return The show method is returning a JSON response.
	 */
	public function show($page)
	{
		return response()->json($this->pages->show($page));
	}

	/**
	 * It returns a JSON response of the result of the `getContacts` function in the `Pages` class
	 * 
	 * @return A JSON response.
	 */
	public function getContacts()
	{
		return response()->json($this->pages->getContacts());
	}
}
