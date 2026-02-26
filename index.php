<?php
$result = '';
$input = '';
$action = 'encrypt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['text'] ?? '';
    $action = $_POST['action'] ?? 'encrypt';

    $encMap = [6, 3, 1, 4, 7, 0, 2, 5];
    $decMap = [5, 2, 6, 1, 3, 7, 0, 4];

    $map = ($action === 'encrypt') ? $encMap : $decMap;

    $padLength = ceil(strlen($input) / 8) * 8;
    $paddedInput = str_pad($input, $padLength, " ");
    
    $chunks = str_split($paddedInput, 8);

    foreach ($chunks as $chunk) {
        $newChunk = '';
        for ($i = 0; $i < 8; $i++) {
            $newChunk .= $chunk[$map[$i]];
        }
        $result .= $newChunk;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transposition Cipher</title>
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
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .output-box {
            margin-top: 25px;
            padding: 15px;
            background-color: #e8f4fd;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }
        .output-text {
            font-family: monospace;
            white-space: pre-wrap;
            word-break: break-all;
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Transposition Cipher</h2>
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
