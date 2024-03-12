<?php

// Loop through messages and display them
// Loop through messages and display them

use app\Models\Message\Chat;
use app\Models\Message\ChatMembers;
use app\Models\Message\Message;
use app\Models\Store\WhalesStore;
use app\Models\User\User;
use app\Models\Notification\Notification;
use app\Models\support\Support;

$message = new Message($conn);
$Members = new ChatMembers($conn);

$messages = $message->getMessagesByChat(decodeId($support_id));
$owner_id = WhalesStore::getStore($_SESSION['store_id'])['owner_id'];
$support = Support::getBySender($owner_id);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['reply_message_text'])) {
    // Retrieve the submitted message
    $reply_message = validateUserInput($_POST['reply_message_text'], $conn);

    $message_sender_id = validateUserInput(decodeId($_POST['message_sender_id']), $conn);
    $message_receiver_id = validateUserInput(decodeId($_POST['message_receiver_id']), $conn);

     $chatmembers = new ChatMembers($conn);

     $message_receiver = $chatmembers->getMembersByChat(decodeId($support_id));

     foreach ( $message_receiver as $member_id) {
        if ($member_id['member_id'] != $owner_id) {
            $message_receiver_id = $member_id['member_id'];
            // Check the character count
            $character_count = mb_strlen($reply_message, 'UTF-8');
            $max_characters = 2500;

            if ($character_count <= $max_characters) {
                messageSystem($conn, $message_sender_id, $message_receiver_id, $message_sender_id, $reply_message, 'support', true);
                
                echo '<meta http-equiv="refresh" content="0;URL=\'\store\support\\'.$support_id.'\'"/>';
                exit;
            } else {
                echo "Error: Message exceeds the maximum allowed character limit of 2.5K.";
            }
        }
     }


    // }
    
    
}
$user = User::findByID($conn, $support['from_whom_id']);
echo '<legend>Supporting u/'.$user->publicName.'/ for \''.$support['message_type'].'\'  by /mod/</legend>';
echo '<div id="message-system-div">';

echo '<form action="" method="post" class="notific-container">';
foreach ($messages as $message) {
    $user = User::findByID($conn, $message['sender_id']);
    $findStore =  $Members->getStoreMember($message['sender_id']);

    $storeUser = $findStore['is_store'] == 1 ? WhalesStore::getStoresByOwner($message['sender_id'])['store_name'] : $user->publicName;

    $timeDifference = getTimeDifference($message['created_at']);

    if ($timeDifference['years'] > 0) {
        $time_send =  $timeDifference['years'] == 1 ? $timeDifference['years'] . " year" : $timeDifference['years'] . " years";
    } elseif ($timeDifference['months'] > 0) {
        $time_send =  $timeDifference['months'] == 1 ? $timeDifference['months'] . " month" : $timeDifference['months'] . " months";
    } elseif ($timeDifference['days'] > 0) {
        $time_send =  $timeDifference['days'] == 1 ? $timeDifference['days'] . " day" : $timeDifference['days'] . " days";
    } elseif ($timeDifference['hours'] > 0) {
        $time_send =  $timeDifference['hours'] == 1 ? $timeDifference['hours'] . " hour" : $timeDifference['hours'] . " hours";
    }elseif ($timeDifference['minutes'] > 0) {
        $time_send =  $timeDifference['minutes'] == 1 ? $timeDifference['minutes'] . " minute" : $timeDifference['minutes'] . " minutes";
    }elseif ($timeDifference['seconds'] > 0) {
        $time_send =  $timeDifference['seconds'] == 1 ? $timeDifference['seconds'] . " second" : $timeDifference['seconds'] . " seconds";
    } else {
        $time_send =  "just now";
    }
    
    if ($message['sender_id'] === $owner_id) {
        // Display sender information
        echo '<p id="message-sender">';
        echo '<span id="message-receiver-name" class="' . ($findStore['is_store'] == 1 ? "store" : "") . '">'.$storeUser .  ($findStore['is_store'] == 1 ? '<span class="trust-level-'.$currentStore['trust_level'].'" style="font-size: 10px; margin-left: 5px; font-weight: bold;"> STL '.$currentStore['trust_level'].'</span>' .'</span>' : '<span class="trust-level-'.$user->trustLevel.'" style="font-size: 10px; margin-left: 5px; font-weight: bold;"> UTL '.$user->trustLevel.'</span>' .'</span>') .'</span>';
        echo '<span id="message-sender-chat">' . validateUserInput($message['content'], $conn) . '</span>';
        echo '<input type="hidden" name="message_sender_id" value="'.encodeId($owner_id).'">';
        echo '<input type="hidden" name="message_receiver_id" value="'.encodeId($user->id).'">';
        echo '<span id="message-sender-sent-time">' . ($time_send != "just now" ? $time_send . " ago" : $time_send) . '</span>';
        echo '</p>';
    } else {
        // Display receiver information
        echo '<p id="message-receiver">';
        echo '<span id="message-receiver-name" class="' . ($findStore['is_store'] == 1 ? "store" : "") . '">'.$storeUser .  ($findStore['is_store'] == 1 ? '<span class="trust-level-'.$currentStore['trust_level'].'" style="font-size: 10px; margin-left: 5px; font-weight: bold;"> STL '.$currentStore['trust_level'].'</span>' .'</span>' : '<span class="trust-level-'.$user->trustLevel.'" style="font-size: 10px; margin-left: 5px; font-weight: bold;"> UTL '.$user->trustLevel.'</span>' .'</span>') .'</span>';
        echo '<span id="message-receiver-chat">' . validateUserInput($message['content'], $conn) . '</span>';
        echo '<input type="hidden" name="message_receiver_id" value="'.encodeId($user->id).'">';
        echo '<input type="hidden" name="message_sender_id" value="'.encodeId($owner_id).'">';
        echo '<span id="message-receiver-sent-time">' . ($time_send != "just now" ? $time_send . " ago" : $time_send) . '</span>';
        echo '</p>';
    }
}  
echo '<div id="message-reply-div">';
echo '<textarea name="reply_message_text" class="support-msg reply-message-text" cols="30" rows="10" placeholder="Write your reply here... max 2.5K characters!" required></textarea><br><input type="submit" class="submit-nxt" name="reply_message" value="Send">
</div>';
echo '</form></div>';
?>
