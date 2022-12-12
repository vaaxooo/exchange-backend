<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Services\Admin\ContactService;

class ContactController extends Controller
{
	private $contactService;

	public function __construct()
	{
		$this->contactService = new ContactService();
	}


	/**
	 * It returns a JSON response of the index method of the contact service
	 * 
	 * @param Request request The request object.
	 * 
	 * @return The index method of the ContactService class is being returned.
	 */
	public function index(Request $request)
	{
		return response()->json($this->contactService->index($request));
	}

	/**
	 * > This function returns a JSON response of the contact service's show function
	 * 
	 * @param Request request The request object
	 * @param Contact contact The contact model instance
	 * 
	 * @return A JSON response of the contact.
	 */
	public function show(Contact $contact)
	{
		return response()->json($this->contactService->show($contact));
	}

	/**
	 * It takes a request, passes it to the contact service, and returns the response from the service as a
	 * JSON object
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function store(Request $request)
	{
		return response()->json($this->contactService->store($request));
	}

	/**
	 * It takes a request and a contact, and returns a json response of the contact service's update
	 * function
	 * 
	 * @param Request request The request object
	 * @param Contact contact The contact object that was passed in the URL.
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function update(Request $request, Contact $contact)
	{
		return response()->json($this->contactService->update($request, $contact));
	}

	/**
	 * > This function destroys a contact
	 * 
	 * @param Contact contact This is the model that we're using.
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function destroy(Contact $contact)
	{
		return response()->json($this->contactService->destroy($contact));
	}
}
