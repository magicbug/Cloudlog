
    <div class="container">
        <h1>DX Cluster</h1>
        <p>Click the button below to open the DX Cluster Bandmap in a new window:</p>
        <button class="bandmap-link" onclick="openBandmap()">Open Bandmap</button>
    </div>

    <script>
        function openBandmap() {
            // Open bandmap in a new window without URL bar, toolbars, etc.
            const width = 500; 
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