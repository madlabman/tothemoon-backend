<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Message;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function chat_list(UserRepository $userRepository)
    {
        try {
            $user = auth()->user();
            if (!$user !== null) {
                $dialogs = $userRepository->dialogs($user);
                return response()->json([
                    'status' => 'success',
                    'chats' => $dialogs,
                ]);
            } else {
                throw new \Exception('Have not user');
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function chat($uuid, MessageRepository $messageRepository)
    {
        try {
            $chat_user = User::where('uuid', $uuid)->first();
            $user = auth()->user();
            if (!empty($user) && !empty($chat_user)) {
                $messages = $messageRepository->chat($user, $chat_user);
                return response()->json([
                    'status' => 'success',
                    'messages' => $messages,
                ]);
            } else {
                throw new \Exception('Error in chat');
            }
        } catch (\Exception $ex) {
            return response()->json([
                'ex' => $ex->getMessage()
            ], 500);
        }
    }

    public function chat_all(MessageRepository $messageRepository)
    {
        try {
            $user = auth()->user();
            if (!empty($user)) {
                $messages = $messageRepository->allForUser($user);
                return response()->json([
                    'status' => 'success',
                    'messages' => $messages,
                ]);
            } else {
                throw new \Exception('Error in retrieving messages');
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function read($id)
    {
        try {
            $message = Message::find($id);
            if (!empty($message)) {
                if ($message->toUser->uuid === auth()->user()->uuid) {
                    $message->is_read = true;
                    $message->save();
                    return response()->json([
                        'status' => 'success',
                    ]);
                } else {
                    return response()->json([
                        'status' => 'not.this.user',
                    ]);
                }
            } else {
                throw new \Exception('No message found');
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $receiver = User::where('phone', $request->post('to'))->first();
            if (!empty($receiver)) {
                $message = Message::create([
                    'text' => $request->post('text'),
                ]);
                $message->toUser()->save($receiver);
                $message->fromUser()->associate(auth()->user())->save();
                return response()->json([
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'to' => 'Пользователь с таким номером телефона не найден'
                    ]
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }
}