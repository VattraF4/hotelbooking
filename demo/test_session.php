<?php
// Must be first line
session_start();

// Test writing to session
$_SESSION['test_time'] = date('Y-m-d H:i:s');

// Output all info
echo "<h2>Session Test</h2>";
echo "<p>ID: ".session_id()."</p>";
echo "<p>Status: ".session_status()." (2=active)</p>";
echo "<pre>Session: ".print_r($_SESSION, true)."</pre>";
echo "<pre>Cookie: ".print_r($_COOKIE, true)."</pre>";
echo "<p>Save Path: ".session_save_path()."</p>";

// Verify file was created
if (strpos(session_save_path(), 'php_sessions') !== false) {
    $sessFile = session_save_path().'/sess_'.session_id();
    echo "<p>Session file exists: ".(file_exists($sessFile) ? 'Yes' : 'No')."</p>";
}
?>