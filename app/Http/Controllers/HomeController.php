<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Show the homepage.
     */
    public function index()
    {
        // Get featured lessons for the homepage
        $featuredLessons = Lesson::take(3)->get();

        return view('home.index', [
            'featuredLessons' => $featuredLessons
        ]);
    }

    /**
     * Show the about us page.
     */
    public function about()
    {
        return view('home.about');
    }

    /**
     * Show the contact page.
     */
    public function contact()
    {
        return view('home.contact');
    }

    /**
     * Handle the contact form submission.
     */
    public function sendContactForm(Request $request)
    {
        $validated = $request->validate([
            'naam' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'onderwerp' => 'required|string|max:255',
            'bericht' => 'required|string',
        ]);

        // In a real application, you would send an email here
        // Mail::to('info@windkracht12.nl')->send(new \App\Mail\ContactFormMail($validated));

        return redirect()->route('contact')->with('success', 'Bedankt voor je bericht! We nemen zo snel mogelijk contact met je op.');
    }
}
