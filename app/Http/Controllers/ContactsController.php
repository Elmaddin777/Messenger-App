<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use App\Events\NewMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{
    public function getContacts(){
        // Get all user except authenticated one 
        $contacts = User::where('id', '!=', Auth::id())->get();

        // Get a collection of items where sender_id is the user who sent us a message
        // and messages_count is the number of unread messages we have from him
        $unreadIds = Message::select(\DB::raw('`from` as sender_id, count(`from`) as messages_count'))
            ->where('to', auth()->id())
            ->where('read', false)
            ->groupBy('from')
            ->get();

        // Add an unread key to each contact with the count of unread messages
        $contacts = $contacts->map(function($contact) use ($unreadIds) {
            $contactUnread = $unreadIds->where('sender_id', $contact->id)->first();

            $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;

            return $contact;
        });

        return response()->json($contacts);
    }

    public function getMessagesFor($id){
        // Mark all messages with the selected contact read
        Message::where('from', $id)->where('to', Auth::id())->update(['read' => true]);

        // Get sent and received messages between clicked contact and authenticated user
        $messages = Message
            ::where(function($query) use ($id){
                $query->where('from', $id);
                $query->where('to', Auth::id());
            })
            ->orWhere(function($query) use ($id){
                $query->where('from', Auth::id());
                $query->where('to', $id);
            })
            ->get();

        return response()->json($messages);
    }

    public function send(Request $request){
        $message = Message::create([
            'from' => Auth::id(),
            'to' => $request->contact_id,
            'text' => $request->text
        ]);

        $sender = User::where('id', Auth::id())->first();

        broadcast(new NewMessage($message, $sender));

        return response()->json($message);
    }

  
}
