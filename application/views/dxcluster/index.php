<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DX Cluster</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .bandmap-link {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .bandmap-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>DX Cluster</h1>
        <p>Click the button below to open the DX Cluster Bandmap in a new window:</p>
        <button class="bandmap-link" onclick="openBandmap()">Open Bandmap</button>
    </div>

    <script>
        function openBandmap() {
            // Open bandmap in a new window without URL bar, toolbars, etc.
            const width = 550;
            const height = 800;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            
            // Note: Modern browsers may still show address bar due to security restrictions
            // For Chrome, you can use: chrome.exe --app=http://localhost/index.php/dxcluster/bandmap
            const features = `width=${width},height=${height},left=${left},top=${top},` +
                           `toolbar=no,location=no,directories=no,status=no,menubar=no,` +
                           `scrollbars=yes,resizable=yes,copyhistory=no`;
            
            const popup = window.open('<?php echo site_url('dxcluster/bandmap'); ?>', 'bandmap', features);
            
            // Try to make it fullscreen (user will need to allow this)
            if (popup) {
                popup.focus();
            }
        }

        function openBandmapFullscreen() {
            // Alternative: Open in current window and go fullscreen
            window.location.href = '<?php echo site_url('dxcluster/bandmap'); ?>';
        }
    </script>
</body>
</html>
