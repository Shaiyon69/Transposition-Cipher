<?php
$result = '';
$input = $_POST['text'] ?? '';
$action = $_POST['action'] ?? 'encrypt';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($input)) {
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #3b82f6;
            --primary-hover: #60a5fa;
            --border: #334155;
            --focus-ring: rgba(59, 130, 246, 0.5);
            --success-bg: #064e3b;
            --success-border: #059669;
            --success-text: #a7f3d0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .app-container {
            background: var(--card-bg);
            width: 100%;
            max-width: 600px;
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        .header {
            padding: 30px 30px 20px;
            border-bottom: 1px solid var(--border);
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header p {
            margin: 8px 0 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-container {
            padding: 30px;
        }

        .input-group {
            margin-bottom: 24px;
        }

        textarea {
            width: 100%;
            height: 120px;
            padding: 16px;
            background-color: #0f172a;
            color: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: 'Fira Code', monospace;
            font-size: 14px;
            resize: vertical;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--focus-ring);
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .radio-group {
            display: flex;
            gap: 15px;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        input[type="radio"] {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .submit-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background-color: var(--primary-hover);
        }

        .output-container {
            margin-top: 10px;
            background-color: var(--success-bg);
            border: 1px solid var(--success-border);
            border-radius: 10px;
            padding: 20px;
        }

        .output-label {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--success-text);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .output-text {
            font-family: 'Fira Code', monospace;
            font-size: 15px;
            color: var(--text-main);
            word-break: break-all;
            white-space: pre-wrap;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="app-container">
    <div class="header">
        <h1>Transposition Cipher</h1>
        <p>8-character block encryption and decryption.</p>
    </div>

    <div class="form-container">
        <form method="POST">
            <div class="input-group">
                <textarea name="text" required placeholder="Enter your text here..."><?= htmlspecialchars($input) ?></textarea>
            </div>
            
            <div class="controls">
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="action" value="encrypt" <?= $action === 'encrypt' ? 'checked' : '' ?>> Encrypt
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="action" value="decrypt" <?= $action === 'decrypt' ? 'checked' : '' ?>> Decrypt
                    </label>
                </div>
                
                <button type="submit" class="submit-btn">Process Text</button>
            </div>
        </form>

        <?php if ($result !== ''): ?>
            <div class="output-container">
                <div class="output-label">Result</div>
                <div class="output-text"><?= htmlspecialchars($result) ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
