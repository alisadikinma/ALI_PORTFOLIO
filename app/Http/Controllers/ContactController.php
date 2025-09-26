<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{

    public function index()
    {
        // Use pagination for better performance with large datasets
        $contacts = Contact::orderBy('created_at', 'desc')
                          ->paginate(20);

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
