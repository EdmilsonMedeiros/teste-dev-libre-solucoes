<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Contact/Create', ["user" => Auth::user()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request['phone'] = $this->cleanNotIsNumbers($request->phone);
        $request['user_id'] = Auth::id();
        $address = $request->address;

        $contact = Contact::create($request->all());
        if($address && $contact){
            $address['contact_id'] = $contact->id;
            Address::create($address);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contact = Contact::find($id);
        $contact['address'] = $contact->address;
        
        return Inertia::render('Contact/Edit', ["user" => Auth::user(), "contact" => $contact]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $contact = Contact::find($request->id);
        if(Auth::id() == $contact->user_id){
            $contact->update($request->all());
            $address = $contact->address;
            $address->update($request->address);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $contact = Contact::find($request->id);
        if($contact && Auth::user()->id == $contact->user_id){
            $contact->delete();
        }
    }

    /**
     * Limpa todos os caracteres não numéricos de uma string.
     *
     * @param  string  $string
     * @return string
     */
    function cleanNotIsNumbers($string)
    {
        // Remove todos os caracteres que não são números
        return preg_replace('/\D/', '', $string);
    }
}
