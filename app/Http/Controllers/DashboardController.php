<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        $contacts = Auth::user()->contacts;
        $contactsArray = [];
        foreach($contacts as $key => $contact){
            $contactsArray[$key] = $contact;
            $contactsArray[$key]['address'] = $contact->address;
        }

        return Inertia::render("Dashboard/Index", ["user" => Auth::user(), "contacts" => $contactsArray]);
    }
}
