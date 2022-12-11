<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PageService;
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
	 * It takes a request, passes it to the store method of the Pages class, and returns the response as
	 * JSON
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a json object.
	 */
	public function store(Request $request)
	{
		return response()->json($this->pages->store($request));
	}

	/**
	 * It returns a JSON response of the page with the given ID.
	 * 
	 * @param id The id of the page you want to show.
	 * 
	 * @return The show method is returning a JSON response.
	 */
	public function show(Page $page)
	{
		return response()->json($this->pages->show($page));
	}

	/**
	 * It updates the page.
	 * 
	 * @param Request request The request object
	 * @param id The id of the page you want to update.
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function update(Request $request, Page $page)
	{
		return response()->json($this->pages->update($request, $page));
	}

	/**
	 * It deletes a page from the database.
	 * 
	 * @param id The id of the page to be deleted
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function destroy(Page $page)
	{
		return response()->json($this->pages->destroy($page));
	}

	/**
	 * It returns a json response of the active method of the pages model
	 * 
	 * @param id The id of the page you want to activate.
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function active(Page $page)
	{
		return response()->json($this->pages->active($page));
	}
}
