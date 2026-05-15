<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contactPost(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'subject'  => 'required|string|max:255',
            'messages' => 'required|string|max:2000',
        ]);

        Mail::send(
            'contact.v_email',
            [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'subject' => $request->get('subject'),
                'messages' => $request->get('messages')
            ],
            function ($message) {
                $message->from('info@mysugarglider.id');
                $message->to('info@mysugarglider.id', 'Webmaster')
                    ->subject('Pesan dari Form Kontak Website');
            }
        );

        return back()->with('pesan', 'Terima kasih sudah menghubungi kami.  Kami akan segera membalas email Anda.');
    }
}
