<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\SharedMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all conversations for the user
        $conversations = Conversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['participants', 'messages' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get()
            ->sortByDesc(function ($conversation) {
                return $conversation->lastMessage?->created_at;
            });


        // Get all users except the current user for group creation
        $users = User::where('id', '!=', $user->id)
            // ->where('role', '!=', 'admin') // Exclude admin users if needed
            ->get();

        return view('chat.index', [
            'conversations' => $conversations,
            'user' => $user,
            'users' => $users
        ]);
    }

    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'participants' => 'required|array|min:2'
        ]);

        $conversation = Conversation::create([
            'type' => 'group',
            'name' => $request->name
        ]);

        $participants = collect($request->participants)->mapWithKeys(function ($id) {
            return [$id => ['is_creator' => false]];
        })->toArray();

        // $conversation->participants()->attach(array_merge([Auth::id()], $request->participants));

        // Add the current user as creator
        $participants[Auth::id()] = ['is_creator' => true];

        $conversation->participants()->attach($participants);

        return response()->json([
            'success' => true,
            'message' => "Group created successfully"
        ]);

        // return redirect()->route('chat.index')->with('success', 'Group created successfully');
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required_without:media|string|nullable',
            'media' => 'required_without:message|file|nullable'
        ]);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('chat/media/' . $conversation->id, 'public');

            $media = SharedMedia::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'type' => $file->getMimeType(),
                'path' => $path
            ]);
        }

        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'status' => 'sent'
        ]);

        // Mark as delivered for other participants
        $conversation->participants()
            ->where('user_id', '!=', Auth::id())
            ->get()
            ->each(function ($participant) use ($message) {
                $message->markAsDelivered();
            });

        return response()->json($message->load('user'));
    }

    public function getMessages(Conversation $conversation)
    {
        $messages = $conversation->messages()
            ->with(['user', 'media'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $conversation->markAsRead();

        return response()->json($messages);
    }

    public function checkLatestMessages(Conversation $conversation)
    {
        $lastMessage = $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->first();

        $hasNewMessages = false;
        if ($lastMessage) {
            $lastReadAt = $conversation->participants()
                ->where('user_id', Auth::id())
                ->first()
                ->pivot
                ->last_read_at;

            $hasNewMessages = !$lastReadAt || $lastMessage->created_at > $lastReadAt;
        }

        return response()->json([
            'hasNewMessages' => $hasNewMessages
        ]);
    }

    public function checkAllConversations()
    {
        $user = Auth::user();

        $conversations = Conversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['messages' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'participants' => function ($query) use ($user) {
            $query->where('user_id', '!=', $user->id);
        }])->get();

        $unreadConversations = [];
        foreach ($conversations as $conversation) {
            $lastMessage = $conversation->messages->first();
            if ($lastMessage) {
                $lastReadAt = $conversation->participants()
                    ->where('user_id', $user->id)
                    ->first()
                    ->pivot
                    ->last_read_at;

                if (!$lastReadAt || $lastMessage->created_at > $lastReadAt) {
                    $otherParticipant = $conversation->participants->first();
                    $unreadConversations[] = [
                        'id' => $conversation->id,
                        'name' => $conversation->name ?? $otherParticipant->name,
                        'lastMessage' => $lastMessage->message,
                        'timestamp' => $lastMessage->created_at,
                        'type' => $conversation->type,
                        'isGroup' => $conversation->type === 'group',
                        'icon' => $conversation->type === 'group' ? 'ni-users' : 'ni-user',
                        'iconBg' => $conversation->type === 'group' ? 'bg-primary-dim' : 'bg-info-dim'
                    ];
                }
            }
        }

        return response()->json([
            'unreadConversations' => $unreadConversations
        ]);
    }

    public function toggleFavorite(Conversation $conversation)
    {
        $participant = $conversation->participants()
            ->where('user_id', Auth::id())
            ->first();

        $participant->pivot->update([
            'is_favorite' => !$participant->pivot->is_favorite
        ]);

        return response()->json(['success' => true]);
    }

    public function toggleMute(Conversation $conversation)
    {
        $participant = $conversation->participants()
            ->where('user_id', Auth::id())
            ->first();

        $participant->pivot->update([
            'is_muted' => !$participant->pivot->is_muted
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteConversation(Conversation $conversation)
    {
        $conversation->delete();
        return response()->json(['success' => true]);
    }

    public function editMessage(Message $message, Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        if ($message->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->edit($request->message);
        return response()->json($message);
    }

    public function startDirectChat(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $currentUser = Auth::user();
        $otherUser = User::find($request->user_id);

        if (!$otherUser) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Check if a direct chat already exists
        $existingChat = Conversation::where('type', 'direct')
            ->whereHas('participants', function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            })
            ->whereHas('participants', function ($query) use ($otherUser) {
                $query->where('user_id', $otherUser->id);
            })
            ->first();

        if ($existingChat) {
            return response()->json([
                'success' => true,
                'conversation' => $existingChat->load('participants')
            ]);
        }

        // Create new direct chat
        $conversation = Conversation::create([
            'type' => 'direct'
        ]);

        // Attach both users to the conversation
        $conversation->participants()->attach([
            $currentUser->id => ['is_favorite' => false, 'is_muted' => false, 'last_read_at' => now()],
            $otherUser->id => ['is_favorite' => false, 'is_muted' => false, 'last_read_at' => now()]
        ]);

        // Load the conversation with participants
        $conversation->load('participants');

        return response()->json([
            'success' => true,
            'conversation' => $conversation
        ]);
    }

    public function getConversationDetails(Conversation $conversation)
    {
        $conversation->load([
            'participants',
            'messages' => function ($query) {
                $query->orderBy('created_at', 'asc');
            },
            'messages.user',
            'messages.media'
        ]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation
        ]);
    }

    public function updateGroup(Request $request, Conversation $conversation)
    {
        if ($conversation->type !== 'group') {
            return response()->json([
                'success' => false,
                'message' => 'This is not a group conversation'
            ], 400);
        }

        // Check if the current user is the group creator
        // $creator = $conversation->participants()
        //     ->wherePivot('is_creator', true)
        //     ->first();

        // if (!$creator || $creator->id !== Auth::id()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Only the group creator can edit the group'
        //     ], 403);
        // }

        $request->validate([
            'name' => 'required|string|max:255',
            'participants' => 'required|array|min:2'
        ]);

        $conversation->update([
            'name' => $request->name
        ]);

        // Sync participants (this will detach old ones and attach new ones)
        $conversation->participants()->sync(array_merge([Auth::id()], $request->participants));

        return response()->json([
            'success' => true,
            'message' => 'Group updated successfully'
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'conversation_id' => 'required|exists:conversations,id'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();

            // Create uploads directory if it doesn't exist
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }

            // Move the file to public/uploads
            $image->move(public_path('uploads'), $filename);
            $url = '/uploads/' . $filename;

            // Create shared media record
            $sharedMedia = SharedMedia::create([
                'conversation_id' => $request->conversation_id,
                'user_id' => Auth::id(),
                'type' => 'image',
                'path' => $url
            ]);

            // Get the conversation
            $conversation = Conversation::findOrFail($request->conversation_id);

            // Create message with image
            $message = $conversation->messages()->create([
                'user_id' => Auth::id(),
                'status' => 'sent',
                'media_id' => $sharedMedia->id
            ]);

            // Mark as delivered for other participants
            $conversation->participants()
                ->where('user_id', '!=', Auth::id())
                ->get()
                ->each(function ($participant) use ($message) {
                    $message->markAsDelivered();
                });

            return response()->json([
                'success' => true,
                'url' => $url,
                'media_id' => $sharedMedia->id,
                'message_id' => $message->id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image was uploaded'
        ], 400);
    }
}
