<?php
$result = '';
$input = isset($_POST['text']) ? strtoupper($_POST['text']) : '';
$action = isset($_POST['action']) ? $_POST['action'] : 'encrypt';

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
    <style>
        :root {
            --bg-primary: #121212;
            --bg-secondary: #1a1a1a;
            --text-primary: #ffffff;
            --text-secondary: #e0e0e0;
            --text-tertiary: #b0b0b0;
            --accent-primary: #6D8597;
            --accent-secondary: #4e7fa5;
            --border-color: rgba(109, 133, 151, 0.2);
        }

        @media (prefers-color-scheme: light) {
            :root {
                --bg-primary: #ffffff;
                --bg-secondary: #f5f5f5;
                --text-primary: #1a1a1a;
                --text-secondary: #333333;
                --text-tertiary: #666666;
                --accent-primary: #6D8597;
                --accent-secondary: #4e7fa5;
                --border-color: rgba(109, 133, 151, 0.15);
            }
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-secondary);
            position: relative;
            min-height: 100vh;
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body.light-mode {
            --bg-primary: #ffffff;
            --bg-secondary: #f5f5f5;
            --text-primary: #1a1a1a;
            --text-secondary: #333333;
            --text-tertiary: #666666;
            --border-color: rgba(109, 133, 151, 0.15);
        }

        body.dark-mode {
            --bg-primary: #121212;
            --bg-secondary: #1a1a1a;
            --text-primary: #ffffff;
            --text-secondary: #e0e0e0;
            --text-tertiary: #b0b0b0;
            --border-color: rgba(109, 133, 151, 0.2);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            height: 100%;
            width: 100%;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
            pointer-events: none;
        }

        body.light-mode::before {
            background-image: 
                linear-gradient(to right, rgba(0, 0, 0, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.02) 1px, transparent 1px);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .theme-toggle {
            background: none;
            border: 2px solid var(--accent-primary);
            color: var(--accent-primary);
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: var(--accent-primary);
            color: var(--bg-primary);
        }

        .hero {
            text-align: center;
            padding: 3rem 1.5rem 2rem;
            background: linear-gradient(135deg, rgba(109, 133, 151, 0.08) 0%, rgba(18, 18, 18, 1) 100%);
            border-bottom: 1px solid rgba(109, 133, 151, 0.2);
        }

        .hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            margin-top: 0;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .hero-text {
            font-size: 1rem;
            color: var(--text-tertiary);
            max-width: 500px;
            margin: 0 auto;
        }

        .tool-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 2rem 4rem;
            align-items: start;
        }

        .input-wrapper {
            grid-column: 1;
        }

        .output-wrapper {
            grid-column: 2;
        }

        .tool-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(109, 133, 151, 0.15);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .tool-card:hover {
            border-color: rgba(109, 133, 151, 0.3);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .input-header, .output-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .section-title {
            margin: 0;
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .action-group {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            background: transparent;
            border: 1px solid var(--accent-primary);
            color: var(--accent-primary);
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background: var(--accent-primary);
            color: var(--bg-primary);
        }

        textarea {
            width: 100%;
            height: 180px;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.95rem;
            resize: vertical;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        textarea:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(109, 133, 151, 0.15);
        }

        textarea::placeholder {
            text-transform: none;
            color: var(--text-tertiary);
        }

        .radio-group {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding: 0.75rem 1rem;
            background-color: var(--bg-secondary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
        }

        input[type="radio"] {
            accent-color: var(--accent-primary);
            width: 1rem;
            height: 1rem;
            cursor: pointer;
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #6D8597;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
            margin-top: auto;
        }

        .btn:hover {
            background-color: #4e7fa5;
            transform: translateY(-1px);
        }

        .result-card {
            border-left: 4px solid var(--accent-primary);
            animation: fadeIn 0.4s ease-out;
        }

        .empty-state {
            border-left: 1px solid rgba(109, 133, 151, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--text-tertiary);
            font-style: italic;
            border: 1px dashed var(--border-color);
            background: transparent;
            box-shadow: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .result-content {
            background: var(--bg-secondary);
            color: var(--text-primary);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 1.1rem;
            line-height: 1.5;
            margin: 0;
            font-family: ui-monospace, SFMono-Regular, monospace;
            white-space: pre-wrap;
            word-break: break-all;
            letter-spacing: 1.5px;
            max-height: 350px;
            overflow-y: auto;
            flex-grow: 1;
        }
        
        /* Custom scrollbar for the result content */
        .result-content::-webkit-scrollbar {
            width: 8px;
        }
        .result-content::-webkit-scrollbar-track {
            background: var(--bg-primary);
            border-radius: 4px;
        }
        .result-content::-webkit-scrollbar-thumb {
            background: var(--accent-primary);
            border-radius: 4px;
        }
        
        @media (max-width: 768px) {
            .tool-container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .hero { padding: 2rem 1rem; }
            .hero-title { font-size: 1.8rem; }
            .tool-card { padding: 1.25rem; }
            .tool-container { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body class="dark-mode">

<nav class="nav">
    <div class="nav-logo">Cipher Tool</div>
    <button class="theme-toggle" onclick="toggleTheme()">Toggle Theme</button>
</nav>

<header class="hero">
    <h1 class="hero-title">Transposition Cipher</h1>
    <p class="hero-text">8-character block cryptographic processor.</p>
</header>

<main class="tool-container">
    
    <div class="input-wrapper">
        <div class="tool-card">
            <div class="input-header">
                <h3 class="section-title">Input</h3>
                <div class="action-group">
                    <input type="file" id="fileInput" accept=".txt" style="display: none;" onchange="handleFileUpload(event)">
                    <button type="button" class="action-btn" onclick="document.getElementById('fileInput').click()">Import .TXT</button>
                </div>
            </div>

            <form method="POST" id="cipherForm">
                <textarea name="text" id="textInput" required placeholder="Enter or import your text here..."><?= htmlspecialchars($input) ?></textarea>
                
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="action" value="encrypt" <?= $action === 'encrypt' ? 'checked' : '' ?>> Encrypt
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="action" value="decrypt" <?= $action === 'decrypt' ? 'checked' : '' ?>> Decrypt
                    </label>
                </div>
                
                <button type="submit" class="btn">Process Text</button>
            </form>
        </div>
    </div>

    <div class="output-wrapper">
        <?php if ($result !== ''): ?>
            <div class="tool-card result-card">
                <div class="output-header">
                    <h3 class="section-title">Output Result</h3>
                    <div class="action-group">
                        <button type="button" class="action-btn" onclick="copyResult(this)">Copy</button>
                        <button type="button" class="action-btn" onclick="downloadResult()">Export .TXT</button>
                    </div>
                </div>
                <div class="result-content" id="resultContent"><?= htmlspecialchars($result) ?></div>
            </div>
        <?php else: ?>
            <div class="tool-card empty-state">
                <p>Awaiting text to process...</p>
            </div>
        <?php endif; ?>
    </div>

</main>

<script>
    // Force uppercase while typing
    const textInput = document.getElementById('textInput');
    textInput.addEventListener('input', function() {
        const cursorPosition = this.selectionStart;
        this.value = this.value.toUpperCase();
        this.setSelectionRange(cursorPosition, cursorPosition);
    });

    // Handle File Upload
    function handleFileUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            textInput.value = e.target.result.toUpperCase();
        };
        reader.readAsText(file);
    }

    // Handle Copy to Clipboard (With HTTP Fallback)
    function copyResult(btnElement) {
        const resultText = document.getElementById('resultContent').innerText;

        // Function to show the "Copied!" feedback
        const showFeedback = () => {
            const originalText = btnElement.innerText;
            btnElement.innerText = 'Copied!';
            setTimeout(() => { btnElement.innerText = originalText; }, 2000);
        };

        // Try modern Clipboard API first (Requires HTTPS or localhost)
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(resultText).then(showFeedback).catch(err => {
                console.error('Modern copy failed, trying fallback.', err);
                fallbackCopy(resultText, showFeedback);
            });
        } else {
            // Fallback for HTTP (Laravel Herd .test domains)
            fallbackCopy(resultText, showFeedback);
        }
    }

    function fallbackCopy(text, successCallback) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        
        // Prevent scrolling to the bottom of the page
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) successCallback();
        } catch (err) {
            console.error('Fallback copy failed.', err);
            alert("Copying failed. Please copy the text manually.");
        }

        document.body.removeChild(textArea);
    }

    // Handle Export to TXT
    function downloadResult() {
        const resultText = document.getElementById('resultContent').innerText;
        const blob = new Blob([resultText], { type: "text/plain" });
        const url = URL.createObjectURL(blob);
        
        const a = document.createElement('a');
        a.href = url;
        const action = document.querySelector('input[name="action"]:checked').value;
        a.download = `cipher_${action}_result.txt`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Theme Toggle
    function toggleTheme() {
        const body = document.body;
        if (body.classList.contains('dark-mode')) {
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
        } else {
            body.classList.remove('light-mode');
            body.classList.add('dark-mode');
        }
    }

    // Init theme based on OS preference
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches && !document.body.classList.contains('dark-mode')) {
        document.body.classList.add('light-mode');
    }
</script>

</body>
</html>
