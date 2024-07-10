<?php

use App\Http\Controllers\Backend\NotificationApiController;
use App\Models\TransactionModel;
use Carbon\Carbon;

function idToArray($ids, $array, $keyName = null)
{
    $names = [];

    foreach ($array as $key) {
        if (in_array((string)$key->id, $ids)) {
            $names[] = $key->$keyName;
        }
    }

    return implode(", ", $names);
}

function generateOTP($length = 6)
{
    $otp = '';
    $characters = '123456789'; // You can include alphabets if needed
    $charLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, $charLength - 1)];
    }

    return '111111';
}

function redirectUser($role)
{
    switch ($role) {
        case 1:
            return 'admin/';
            break;
        case 2:
            dd("College");
            break;
        case 4:
            return 'admin/';
        default:
            return 'admin/';
    }
}

function humanizeDate($date)
{
    $date = Carbon::parse($date);
    return  $date->diffForHumans();
}

// function sendNotification($title, $description, $receiverId)
// {
//     // Instantiate NotificationController
//     $notificationController = new NotificationApiController();

//     // Simulate a request with the necessary data
//     $request = new \Illuminate\Http\Request([
//         'title' => $title,
//         'descriptions' => $description,
//         'receiver_id' => $receiverId,
//     ]);

//     try {
//         // Call the create method directly
//         $response = $notificationController->create($request);

//         // Assuming the create method returns a response
//         return $response;
//     } catch (\Exception $e) {
//         // Handle any exceptions
//         return $e->getMessage();
//     }
// }
