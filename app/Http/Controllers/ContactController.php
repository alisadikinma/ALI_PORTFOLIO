<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{

    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', [
            'title' => 'Data Contact',
            'contacts' => $contacts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'subject'   => 'nullable|string|max:255',
            'service'   => 'nullable|string|max:100',
            'budget'    => 'nullable|string|max:100',
            'message'   => 'nullable|string',
        ]);

        Contact::create($validated);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}
