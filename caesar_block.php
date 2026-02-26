<?php
$result = '';
$input = '';
$action = 'encrypt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['text'] ?? '';
    $action = $_POST['action'] ?? 'encrypt';

    $processedInput = str_replace(' ', '_', $input);
    
    $padLength = ceil(strlen($processedInput) / 25) * 25;
    $paddedInput = str_pad($processedInput, $padLength, "_");
    
    $chunks = str_split($paddedInput, 25);

    foreach ($chunks as $chunk) {
        $newChunk = str_repeat(' ', 25);
        for ($i = 0; $i < 25; $i++) {
            $col = intdiv($i, 5);
            $row = $i % 5;
            $newIndex = $row * 5 + $col;
            $newChunk[$newIndex] = $chunk[$i];
        }
        $result .= $newChunk;
    }

    if ($action === 'decrypt') {
        $result = str_replace('_', ' ', $result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caesar Block Cipher (5x5)</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            padding: 40px 20px;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
        }
        h2 {
            margin-top: 0;
            color: #2c3e50;
        }
        textarea {
            width: 100%;
            height: 120px;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
            font-family: monospace;
            box-sizing: border-box;
            font-size: 16px;
        }
        .options {
            margin-bottom: 20px;
        }
        .options label {
            margin-right: 15px;
            cursor: pointer;
            font-weight: bold;
        }
        button {
            background-color: #e67e22;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #d35400;
        }
        .output-box {
            margin-top: 25px;
            padding: 15px;
            background-color: #fdf3e8;
            border-left: 4px solid #e67e22;
            border-radius: 4px;
        }
        .output-text {
            font-family: monospace;
            white-space: pre-wrap;
            word-break: break-all;
            margin-top: 10px;
            font-size: 18px;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Caesar 5x5 Block Cipher</h2>
    <form method="POST">
        <textarea name="text" required placeholder="Enter text here..."><?= htmlspecialchars($input) ?></textarea>
        
        <div class="options">
            <label>
                <input type="radio" name="action" value="encrypt" <?= $action === 'encrypt' ? 'checked' : '' ?>> Encrypt
            </label>
            <label>
                <input type="radio" name="action" value="decrypt" <?= $action === 'decrypt' ? 'checked' : '' ?>> Decrypt
            </label>
        </div>
        
        <button type="submit">Process Text</button>
    </form>

    <?php if ($result !== ''): ?>
        <div class="output-box">
            <strong>Result:</strong>
            <div class="output-text"><?= htmlspecialchars($result) ?></div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
