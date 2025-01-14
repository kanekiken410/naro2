<?php

$apiKey = '7635269449:AAFvqQEKDxAqQh978zXTli5oCMVfxw5IPxI'; // Your Telegram bot API key
$apiUrl = "https://api.telegram.org/bot$apiKey/"; // Correct way to define the API URL
// Get the incoming message
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// Extract necessary information from the update
$chat_id = $update['message']['chat']['id'];
$text = $update['message']['text'];
$message_id = $update['message']['message_id'];

// Check if the received message is the "/start" command
if ($text === '/start') {

    // Send a photo with caption
    $photoPath = 'home1.jpg'; // Local path to the image

    // Debugging: Check if file exists and print path
    if (file_exists($photoPath)) {
        $realPath = realpath($photoPath);
    } else {
        error_log("File does not exist: " . $photoPath);
        $realPath = '';
    }

    // Updated Caption Message
    $caption = "
    👋 **Welcome to Naro!** 🌟

    Complete exciting tasks and invite your friends to earn points and unlock amazing rewards!

    🚀 **How It Works:**
    
    ✅ Complete daily tasks to collect points.  
    🤝 Invite your friends and get bonus points for every referral.  
    🏆 Climb the leaderboard and win exclusive rewards!

    🔥 Start now and become a top achiever!

    👉 [Join Our Naro Community](https://t.me/naro_community)
    ";

    // Send photo to Telegram
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . "sendPhoto");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $post_fields = [
        'chat_id' => $chat_id,
        'photo' => ($realPath ? new CURLFILE($realPath) : ''), // Use realpath to ensure correct path
        'caption' => $caption,
        'parse_mode' => 'Markdown', // Use Markdown for basic formatting
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => 'Start Now', 'web_app' => ['url' => 'https://naro1.vercel.app/']],  // زر للبدء
                    ['text' => 'Join Our Community', 'url' => 'https://t.me/naro_community'] // رابط الجروب
                ]
            ]
        ])
    ];

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $result = curl_exec($ch);

    if ($result === false) {
        error_log("CURL Error: " . curl_error($ch));
    } else {
        // Optionally, log the result for debugging
        error_log("Result: " . $result);
    }

    curl_close($ch);
}

?>
