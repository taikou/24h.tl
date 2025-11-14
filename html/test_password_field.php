<?php
/*
 * パスワードフィールドのデバッグスクリプト
 * 使用後は必ず削除してください！
 */

require_once 'application/Config.php';
require_once 'application/Db.php';
require_once 'application/SPDO.php';

$db = SPDO::singleton();

// テーブル構造を確認
echo "<h2>users テーブルの password フィールド定義</h2>";
$query = $db->query("SHOW COLUMNS FROM users LIKE 'password'");
$column = $query->fetch(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($column);
echo "</pre>";

// フィールドの最大長を確認
if (preg_match('/varchar\((\d+)\)/i', $column['Type'], $matches)) {
    $maxLength = $matches[1];
    echo "<p><strong>パスワードフィールドの最大長:</strong> {$maxLength} 文字</p>";
    
    if ($maxLength < 60) {
        echo "<p style='color:red;'><strong>警告:</strong> bcrypt ハッシュには最低60文字必要です！</p>";
        echo "<p style='color:red;'>修正が必要です: ALTER TABLE users MODIFY password VARCHAR(255);</p>";
    } else {
        echo "<p style='color:green;'><strong>OK:</strong> フィールド長は十分です。</p>";
    }
}

// 特定のユーザーのパスワードハッシュを確認（ユーザー名を指定）
echo "<hr><h2>パスワードハッシュの確認</h2>";
echo "<form method='post'>";
echo "ユーザー名またはメールアドレス: <input type='text' name='username' required>";
echo "<button type='submit'>確認</button>";
echo "</form>";

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $query = $db->prepare("SELECT username, email, password, LENGTH(password) as pass_length FROM users WHERE (username = :user OR email = :user) LIMIT 1");
    $query->execute([':user' => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<h3>ユーザー: {$user['username']}</h3>";
        echo "<p><strong>メールアドレス:</strong> {$user['email']}</p>";
        echo "<p><strong>パスワードハッシュ長:</strong> {$user['pass_length']} 文字</p>";
        echo "<p><strong>ハッシュの種類:</strong> ";
        
        if (strlen($user['password']) == 40) {
            echo "SHA-1 (40文字)";
        } elseif (strlen($user['password']) == 60 && substr($user['password'], 0, 4) == '$2y$') {
            echo "bcrypt (60文字) - 正常";
        } elseif (strlen($user['password']) < 60 && substr($user['password'], 0, 4) == '$2y$') {
            echo "<span style='color:red;'>bcrypt (切り詰められている!) - 異常</span>";
        } else {
            echo "不明";
        }
        echo "</p>";
        
        echo "<p><strong>ハッシュ:</strong> <code>{$user['password']}</code></p>";
        
        // パスワードリセット用のSQL
        echo "<hr><h3>パスワードリセット用SQL（パスワードを 'password' にリセット）</h3>";
        $tempHash = sha1('password');
        echo "<pre>UPDATE users SET password = '{$tempHash}' WHERE username = '{$user['username']}' LIMIT 1;</pre>";
        echo "<p style='color:orange;'>このSQLを実行後、ユーザー名: <strong>{$user['username']}</strong>、パスワード: <strong>password</strong> でログインできます。</p>";
    } else {
        echo "<p style='color:red;'>ユーザーが見つかりません。</p>";
    }
}

echo "<hr><p style='color:red;'><strong>セキュリティ警告:</strong> このファイルは診断後、必ず削除してください！</p>";
?>

