<?php

namespace App\Services\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactService
{

	/**
	 * It fetches all contacts from the database and returns them in a paginated format
	 * 
	 * @param Request request The request object.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function index(Request $request)
	{
		$per_page = $request->per_page ?? 10;
		$contacts = Contact::orderBy('id', 'desc')->paginate($per_page);
		return [
			'code' => 200,
			'message' => 'Contacts fetched successfully',
			'data' => $contacts
		];
	}

	/**
	 * It returns a JSON response with a 200 status code, a message, and the contact data
	 * 
	 * @param contact The contact object that was fetched from the database.
	 * 
	 * @return An array with three keys: code, message, and data.
	 */
	public function show($contact)
	{
		return [
			'code' => 200,
			'message' => 'Contact fetched successfully',
			'data' => $contact
		];
	}

	/**
	 * It takes a request, validates it, and if it passes, creates a new contact
	 * 
	 * @param Request request The request object.
	 * 
	 * @return An array with the following keys:
	 * - code
	 * - message
	 * - data
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|max:255',
			'link' => 'required|string'
		]);
		if ($validator->fails()) {
			return [
				'code' => 422,
				'message' => 'Validation error',
				'errors' => $validator->errors()
			];
		}
		$contact = Contact::create([
			'name' => $request->name,
			'link' => $request->link
		]);
		return [
			'code' => 200,
			'message' => 'Contact created successfully',
			'data' => $contact
		];
	}

	/**
	 * It validates the request, updates the contact and returns a response
	 * 
	 * @param request The request object
	 * @param contact The contact object that we want to update.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function update($request, $contact)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|max:255',
			'link' => 'required|string'
		]);
		if ($validator->fails()) {
			return [
				'code' => 422,
				'message' => 'Validation error',
				'errors' => $validator->errors()
			];
		}
		$contact->update([
			'name' => $request->name,
			'link' => $request->link
		]);
		return [
			'code' => 200,
			'message' => 'Contact updated successfully',
			'data' => $contact
		];
	}

	/**
	 * It deletes the contact and returns a response
	 * 
	 * @param contact The contact object that we want to delete.
	 * 
	 * @return An array with a code and a message.
	 */
	public function destroy($contact)
	{
		$contact->delete();
		return [
			'code' => 200,
			'message' => 'Contact deleted successfully'
		];
	}
}
