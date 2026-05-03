<div class="container">

    <header>
        <h1>SmartNAS Settings</h1>
        <link rel="stylesheet" href="/css/style.css?v=5">
    </header>

    <!-- Use top-controls WITHOUT the "card" class here -->
    <div class="top-controls">
        <div class="control-buttons">
            <button class="dashboard-btn" onclick="window.open('http://pinas.local:80', '_blank')">
                Dashboard
            </button>

            <button class="setting-btn active" onclick="location.href='settings.php'">
                Settings
            </button>

             <button class="omv-btn" onclick="window.open('http://pinas.local:8080', '_blank')">
                OMV
            </button>
        </div>
    </div>

    <!-- Wrapped in a group to match the index width and centering -->
    <div class="card-group">
        <section class="card">
            <h2>System Control</h2>

            <div class="row">
                <div class="label">Safe Shutdown:</div>
                <div class="value">
                    <button id="shutdownBtn">Shutdown NAS</button>
                </div>
            </div>

            <div class="row">
                <div class="label">Reboot System:</div>
                <div class="value">
                    <button id="rebootBtn">Reboot NAS</button>
                </div>
            </div>

            <div class="row">
                <div class="label">Fan Mode:</div>
                <div class="value">
                    <select id="fanMode">
                        <option value="AUTO" <?php echo ($current_mode == 'AUTO') ? 'selected' : ''; ?>>Auto</option>
                        <option value="MANUAL" <?php echo ($current_mode == 'MANUAL') ? 'selected' : ''; ?>>Manual</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="label">Fan Speed:</div>
                <div class="value">
                    <input type="range" id="fanSlider" min="0" max="100" value="<?php echo $current_duty; ?>">
                    <span id="fanValueDisplay"><?php echo $current_duty; ?>%</span>
                </div>
            </div>
        </section>
    </div>
</div>