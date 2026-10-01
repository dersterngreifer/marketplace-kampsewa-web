<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate chatRooms logic
$feedbacks = App\Models\Feedback::with(['user', 'latestMessage.sender', 'messages.sender'])->latest()->get();
$rooms = collect();

foreach ($feedbacks as $feedback) {
    $adminIds = $feedback->messages->where('sender_type', 'admin')->pluck('sender_id')->unique();
    if ($adminIds->isEmpty()) {
        $feedbackData = $feedback->toArray();
        $feedbackData['room_id'] = $feedback->id . '-0';
        $feedbackData['admin_id'] = 0;
        $rooms->push($feedbackData);
    } else {
        foreach ($adminIds as $adminId) {
            $cloned = $feedback->toArray();
            $cloned['room_id'] = $feedback->id . '-' . $adminId;
            $cloned['admin_id'] = $adminId;
            $latest = $feedback->messages
                ->where(function($msg) use ($adminId) {
                    return $msg->sender_type === 'customer' || ($msg->sender_type === 'admin' && $msg->sender_id === $adminId);
                })->last();
            if ($latest) {
                $latestArray = $latest->toArray();
                if ($latest->sender) {
                    $latestArray['sender'] = $latest->sender->toArray();
                }
                $cloned['latest_message'] = $latestArray;
            }
            $rooms->push($cloned);
        }
    }
}
echo json_encode($rooms->values(), JSON_PRETTY_PRINT);
