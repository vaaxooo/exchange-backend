<?php

namespace App\Services\Admin;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PageService
{
	/**
	 * It fetches all pages from the database and returns them in a paginated format
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function index()
	{
		$per_page = request()->per_page ?? 10;
		$pages = Page::orderBy('id', 'desc')->paginate($per_page);
		return [
			'code' => 200,
			'message' => 'Pages fetched successfully',
			'data' => $pages
		];
	}

	/**
	 * It stores a page in the database.
	 * 
	 * @param Request request The request object
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function store($request)
	{
		$validator = Validator::make($request->all(), [
			'title' => 'required|string|max:255',
			'content' => 'required|string'
		]);
		if ($validator->fails()) {
			return [
				'code' => 422,
				'message' => 'Validation error',
				'errors' => $validator->errors()
			];
		}
		$page = Page::create([
			'title' => $request->title,
			'slug' => Str::slug($request->title),
			'content' => $request->content
		]);
		return [
			'code' => 200,
			'message' => 'Page created successfully',
			'data' => $page
		];
	}

	/**
	 * It fetches a page from the database and returns it.
	 * 
	 * @param id The id of the page you want to show.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function show($page)
	{
		return [
			'code' => 200,
			'message' => 'Page fetched successfully',
			'data' => $page
		];
	}

	/**
	 * It updates the page.
	 * 
	 * @param Request request The request object
	 * @param id The id of the page you want to update.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function update($request, $page)
	{
		$validator = Validator::make($request->all(), [
			'title' => 'required|string|max:255',
			'content' => 'required|string'
		]);
		if ($validator->fails()) {
			return [
				'code' => 422,
				'message' => 'Validation error',
				'errors' => $validator->errors()
			];
		}
		$page->update([
			'title' => $request->title,
			'slug' => Str::slug($request->title),
			'content' => $request->content
		]);
		return [
			'code' => 200,
			'message' => 'Page updated successfully',
			'data' => $page
		];
	}

	/**
	 * It deletes the page.
	 * 
	 * @param id The id of the page you want to delete.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function destroy($page)
	{
		$page->delete();
		return [
			'code' => 200,
			'message' => 'Page deleted successfully',
			'data' => null
		];
	}

	/**
	 * It publishes the page.
	 * 
	 * @param id The id of the page you want to publish.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function active($page)
	{
		$page->update([
			'is_published' => !$page->is_published
		]);
		return [
			'code' => 200,
			'message' => 'Page status updated successfully',
			'data' => $page
		];
	}
}
