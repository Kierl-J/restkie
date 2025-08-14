<?php
session_start();

$name = "Kierl Jay-ar Inot";
$json_file = 'data.json';

// Brutal quotes for notifications (JS will use them too) - 100 quotes
$quotes = [
    "$name, no one will remember the hours you wasted — only that you died with them.",
    "You aren't running out of time — you're being eaten by it, second by second.",
    "Your heart is a ticking bomb. One day soon, it stops.",
    "Your lungs won't warn you before they stop. They'll just stop.",
    "Each sunrise isn't a gift — it's a countdown marker.",
    "Nobody survives time. Not kings, not geniuses, not you.",
    "You're not living slowly. You're dying quietly.",
    "$name, your future self doesn't exist. He dies in 3 days.",
    "That dream you keep putting off? The clock doesn't care.",
    "You waste your breath like it's infinite. It isn't.",
    "Someone else is using their last 72 hours to beg for another minute. You're just scrolling.",
    "Procrastination is suicide in slow motion, $name.",
    "You're not getting older. You're rotting beautifully.",
    "Your legacy will be what you do today. Or don't.",
    "The grave doesn't take requests. It takes everything.",
    "$name, your coffin is already waiting. You're just walking toward it.",
    "People say 'there's still time.' They lie. You have 3 days.",
    "No one will care how busy you were when you're dead.",
    "You're not promised tonight. Only this moment. Maybe.",
    "You'll never know your last moment. But it's scheduled.",
    "Every breath you take is one less you have left, $name.",
    "Your tombstone is being carved right now. What will it say?",
    "Time doesn't heal wounds. It creates them.",
    "You're practicing for death every time you sleep.",
    "The reaper doesn't knock. He just enters.",
    "Your eulogy is being written by your actions today.",
    "Every heartbeat is a countdown to silence.",
    "You're not living on borrowed time. You're stealing it.",
    "$name, your expiration date was set at birth.",
    "The clock is your enemy, and it never loses.",
    "You're dying faster than you're living.",
    "Your last words are approaching. Choose them wisely.",
    "Death is patient. It can wait forever. Can you?",
    "You're not immortal. You just pretend to be.",
    "The grave is democracy's final victory.",
    "Your bones will outlast your dreams, $name.",
    "Time is the cruelest dictator ever known.",
    "You're not making memories. You're using up chances.",
    "Every sunset brings you closer to your final darkness.",
    "Your youth isn't returning. It's already dead.",
    "Life is terminal. Accept your diagnosis.",
    "You're not writing your story. You're ending it.",
    "The hourglass doesn't care about your plans.",
    "Your pulse is morse code spelling 'temporary'.",
    "Death owns your calendar. You just borrow days.",
    "You're not growing up. You're growing out of time.",
    "Your shadow grows shorter as your time does.",
    "The universe is indifferent to your survival.",
    "You're practicing disappearance every day.",
    "Your final breath is already assigned a number.",
    "$name, you're not running a marathon. You're sprinting to the grave.",
    "Time doesn't forgive. It doesn't forget. It just ends.",
    "You're not aging gracefully. You're decomposing slowly.",
    "Your bucket list is really a death list.",
    "Every 'tomorrow' you waste is one you'll never get back.",
    "You're not making progress. You're making regrets.",
    "The cemetery is full of irreplaceable people. You're not one of them.",
    "Your DNA is programmed for self-destruction.",
    "You're not living the dream. You're dying the reality.",
    "Time is the house that always wins.",
    "Your existence is a brief interruption in eternity.",
    "$name, you're not building a life. You're building a grave.",
    "Every decision you delay is a nail in your coffin.",
    "You're not postponing tasks. You're postponing living.",
    "Death doesn't negotiate. It doesn't compromise. It just takes.",
    "Your biological warranty expired years ago.",
    "You're not saving time. Time is spending you.",
    "Every mirror shows you aging in real time.",
    "Your mortality is your only certainty.",
    "You're not fighting time. You're surrendering to it.",
    "The funeral director is already measuring your dimensions.",
    "$name, your last day is already circled on the calendar.",
    "You're not living in the moment. The moment is living in you.",
    "Death doesn't care about your unfinished business.",
    "You're not making history. You're becoming it.",
    "Time is the ultimate serial killer.",
    "Your life story has already reached its climax.",
    "You're not procrastinating. You're practicing for your final delay.",
    "Every sunrise is one closer to your sunset.",
    "Your cells are committing suicide right now.",
    "You're not gaining experience. You're losing chances.",
    "$name, your name is already on the waiting list for eternity.",
    "Time doesn't pause for your hesitation.",
    "You're not accumulating days. You're spending them.",
    "Your heartbeat is a countdown timer you can't reset.",
    "Death is the debt collector of life.",
    "You're not building memories. You're consuming opportunities.",
    "Every regret is a rehearsal for your final disappointment.",
    "Time doesn't care about your potential.",
    "You're not living your best life. You're dying your only one.",
    "$name, your ghost is already writing your obituary.",
    "Every breath is borrowed from your future silence.",
    "You're not making choices. You're making consequences.",
    "Death is the only appointment you can't reschedule.",
    "Your timeline isn't infinite. It's already ending.",
    "You're not surviving. You're expiring gradually.",
    "Every moment you waste is a moment you'll never recover.",
    "Time is the currency you can never earn back.",
    "You're not living in the present. You're dying in it.",
    "$name, your final chapter is already being written.",
    "Death doesn't wait for you to be ready.",
    "You're not making progress. You're making your way to the end.",
    "Every second is a second closer to your final silence.",
    "Your life insurance policy is really a death guarantee.",
    "You're not growing older. You're approaching your expiration.",
    "Time doesn't heal all wounds. It creates the final one."
];

// Pick a random quote for page
$page_quote = $quotes[array_rand($quotes)];

// Start time (first visit)
if (!isset($_SESSION['start_date'])) {
    $_SESSION['start_date'] = date('Y-m-d H:i:s');
}

$start = new DateTime($_SESSION['start_date']);
$now = new DateTime();
$end = clone $start;
$end->modify('+3 days');

$seconds_left = max($end->getTimestamp() - $now->getTimestamp(), 0);
$dead = $seconds_left === 0;

// Handle confessions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$dead) {
    $reflection = trim($_POST['reflection']);

    if ($reflection !== '') {
        $entry = [
            'date' => date('Y-m-d H:i:s'),
            'reflection' => $reflection
        ];

        $existing = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];
        $existing[] = $entry;

        file_put_contents($json_file, json_encode($existing, JSON_PRETTY_PRINT));
    }
}

// Load confessions
$reflections = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];

function formatCountdown($seconds)
{
    $days = floor($seconds / 86400);
    $hours = floor(($seconds % 86400) / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    return sprintf("%02d days %02d:%02d:%02d", $days, $hours, $minutes, $seconds);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>You Will Die Soon, <?php echo $name; ?></title>
    <style>
        body {
            background: black;
            color: #f44336;
            font-family: monospace;
            text-align: center;
            padding: 50px;
        }

        h1 {
            font-size: 3em;
            margin-bottom: 10px;
        }

        .countdown {
            font-size: 2em;
            color: white;
            margin-bottom: 20px;
        }

        blockquote {
            font-style: italic;
            color: #888;
            margin-bottom: 40px;
        }

        textarea {
            width: 90%;
            height: 100px;
            background: #222;
            color: white;
            border: none;
            padding: 10px;
        }

        button {
            padding: 10px 20px;
            background: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            border-bottom: 1px dashed #444;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .dead {
            color: #888;
            font-size: 2em;
        }
    </style>
</head>

<body>
    <h1>💀 KIERL JAY-AR INOT 💀</h1>
    <div class="countdown">
        <?php echo $dead ? "<span class='dead'>💀 You're out of time, $name. 💀</span>" : formatCountdown($seconds_left); ?>
    </div>
    <blockquote><?php echo $page_quote; ?></blockquote>

    <?php if (!$dead): ?>
        <form method="post">
            <textarea name="reflection" placeholder="What did you waste today on, <?php echo $name; ?>? Write it down."></textarea><br>
            <button type="submit">Confess Your Regret</button>
        </form>
    <?php else: ?>
        <p>$name... you're dead. Time is gone. Were you who you were meant to be?</p>
    <?php endif; ?>

    <h3>📝 <?php echo $name; ?>'s Final Confessions</h3>
    <ul>
        <?php foreach (array_reverse($reflections) as $entry): ?>
            <li>
                <strong><?php echo htmlspecialchars($entry['date']); ?></strong><br>
                <?php echo htmlspecialchars($entry['reflection']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <script>
        // Brutal quotes JS version (for notifications)
        const quotes = <?php echo json_encode($quotes); ?>;

        // Ask for permission
        if ('Notification' in window && Notification.permission !== 'granted') {
            Notification.requestPermission();
        }

        // Send a brutal quote notification
        function sendQuoteNotification() {
            if (Notification.permission === 'granted') {
                const quote = quotes[Math.floor(Math.random() * quotes.length)];
                new Notification("💀 Death Reminder", {
                    body: quote,
                    icon: "https://i.imgur.com/oU0bvhV.png" // Optional skull icon
                });
            }
        }

        // Send one on load
        sendQuoteNotification();

        // Send every 30 minutes (1800000 ms)
        setInterval(sendQuoteNotification, 1800000);
    </script>
</body>

</html>