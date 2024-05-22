<?php

namespace Chatify\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\ChMessage as Message;
use App\Models\ChFavorite as Favorite;
use App\Models\ChChannel as Channel;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class grupoCreate extends Controller
{
    public function createGroupChat(Request $request)
    {
        $msg = null;
        $error = $success = 0;

        $user_ids = array_map('intval', explode(',', $request['user_ids']));
        $user_ids[] = Auth::user()->id;

        $group_name = $request['group_name'];

        $new_channel = new Channel();
        $new_channel->name = $group_name;
        $new_channel->owner_id = Auth::user()->id;
        $new_channel->save();
        $new_channel->users()->sync($user_ids);

        // add first message
        $message = Chatify::newMessage([
            'from_id' => Auth::user()->id,
            'to_channel_id' => $new_channel->id,
            'body' => Auth::user()->name . ' has created a new chat group: ' . $group_name,
            'attachment' => null,
        ]);
        $message->user_name = Auth::user()->name;
        $message->user_email = Auth::user()->email;

        $messageData = Chatify::parseMessage($message, null);
        Chatify::push("private-chatify.".$new_channel->id, 'messaging', [
            'from_id' => Auth::user()->id,
            'to_channel_id' => $new_channel->id,
            'message' => Chatify::messageCard($messageData, true)
        ]);


        // if there is a [file]
        if ($request->hasFile('avatar')) {
            // allowed extensions
            $allowed_images = Chatify::getAllowedImages();

            $file = $request->file('avatar');
            // check file size
            if ($file->getSize() < Chatify::getMaxUploadSize()) {
                if (in_array(strtolower($file->extension()), $allowed_images)) {
                    $avatar = Str::uuid() . "." . $file->extension();
                    $update = $new_channel->update(['avatar' => $avatar]);
                    $file->storeAs(config('chatify.channel_avatar.folder'), $avatar, config('chatify.storage_disk_name'));
                    $success = $update ? 1 : 0;
                } else {
                    $msg = "File extension not allowed!";
                    $error = 1;
                }
            } else {
                $msg = "File size you are trying to upload is too large!";
                $error = 1;
            }
        }

        return Response::json([
            'status' => $success ? 1 : 0,
            'error' => $error ? 1 : 0,
            'message' => $error ? $msg : 0,
            'channel' => $new_channel
        ], 200);
    }


}
