<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="notification-container">
        <button id="notification-bell">
            🔔 <span id="notification-count">0</span>
        </button>
        <div id="notification-dropdown">
            <div id="notification-list"></div>
        </div>
    </div>

    <script src="notifications.js"></script>
</body>
<style>
    #notification-container {
    position: relative;
    display: inline-block;
}

#notification-bell {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    position: relative;
}

#notification-count {
    background: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    position: absolute;
    top: -5px;
    right: -10px;
}

#notification-dropdown {
    display: none;
    position: absolute;
    right: 0;
    background: black;
    border: 1px solid #ccc;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.notification-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.notification-item.unread {
    background:rgba(1, 1, 1, 0.92);
}
</style>
</html>