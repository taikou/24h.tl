<?php
/*
 * 緊急パスワード修正スクリプト
 * 切り詰められたbcryptハッシュを持つユーザーのパスワードを修正
 * 使用後は必ず削除してください！
 */

require_once 'application/Config.php';
require_once 'application/Db.php';
require_once 'application/SPDO.php';

$db = SPDO::singleton();

echo "<h1>パスワード修正スクリプト</h1>";
echo "<p style='color:red;'><strong>警告:</strong> このスクリプトは実行後すぐに削除してください！</p>";

// データベースのpasswordフィールド長を確認
echo "<h2>1. データベース構造の確認</h2>";
$query = $db->query("SHOW COLUMNS FROM users LIKE 'password'");
$column = $query->fetch(PDO::FETCH_ASSOC);
echo "<p><strong>現在のpasswordフィールド:</strong> {$column['Type']}</p>";

if (preg_match('/varchar\((\d+)\)/i', $column['Type'], $matches)) {
    $maxLength = $matches[1];
    if ($maxLength < 60) {
        echo "<p style='color:red;'><strong>⚠️ フィールドが短すぎます！</strong> まずこのSQLを実行してください:</p>";
        echo "<pre style='background:#f5f5f5;padding:10px;'>ALTER TABLE users MODIFY password VARCHAR(255);</pre>";
        echo "<p>実行後、このページをリロードしてください。</p>";
        exit;
    } else {
        echo "<p style='color:green;'>✓ フィールド長は十分です (VARCHAR({$maxLength}))</p>";
    }
}

// 影響を受けたユーザーを確認
echo "<h2>2. 影響を受けたユーザーの確認</h2>";
$query = $db->query("
    SELECT id, username, email, password, LENGTH(password) as pass_length 
    FROM users 
    WHERE password LIKE '\$2y\$%' 
      AND LENGTH(password) < 60
      AND status = 'active'
");
$affectedUsers = $query->fetchAll(PDO::FETCH_ASSOC);

echo "<p><strong>影響を受けたユーザー数:</strong> " . count($affectedUsers) . " 人</p>";

if (count($affectedUsers) == 0) {
    echo "<p style='color:green;'>✓ 切り詰められたbcryptハッシュを持つユーザーはいません。</p>";
} else {
    echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
    echo "<tr><th>ID</th><th>ユーザー名</th><th>メール</th><th>ハッシュ長</th></tr>";
    foreach ($affectedUsers as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['username']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td style='color:red;'>{$user['pass_length']} 文字（切り詰め）</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 修正オプション
    echo "<h2>3. 修正方法の選択</h2>";
    
    echo "<h3>オプション1: 一時パスワードを設定（推奨）</h3>";
    echo "<form method='post' onsubmit='return confirm(\"本当に実行しますか？\");'>";
    echo "<p>すべての影響を受けたユーザーに一時パスワード <strong>'TempPass2024!'</strong> を設定します。</p>";
    echo "<input type='hidden' name='action' value='set_temp_password'>";
    echo "<button type='submit' style='padding:10px 20px;background:#ff9800;color:white;border:none;cursor:pointer;'>一時パスワードを設定</button>";
    echo "</form>";
    
    echo "<h3>オプション2: アカウントを一時停止</h3>";
    echo "<form method='post' onsubmit='return confirm(\"本当に実行しますか？\");'>";
    echo "<p>影響を受けたユーザーのstatusを 'pending' に変更し、手動で対応します。</p>";
    echo "<input type='hidden' name='action' value='suspend_accounts'>";
    echo "<button type='submit' style='padding:10px 20px;background:#f44336;color:white;border:none;cursor:pointer;'>アカウントを一時停止</button>";
    echo "</form>";
}

// 処理実行
if (isset($_POST['action'])) {
    echo "<hr><h2>実行結果</h2>";
    
    if ($_POST['action'] == 'set_temp_password') {
        $tempPassword = 'TempPass2024!';
        $tempHash = sha1($tempPassword);
        
        $query = $db->prepare("
            UPDATE users 
            SET password = :temp_hash
            WHERE password LIKE '\$2y\$%' 
              AND LENGTH(password) < 60
              AND status = 'active'
        ");
        $result = $query->execute([':temp_hash' => $tempHash]);
        $count = $query->rowCount();
        
        if ($result) {
            echo "<p style='color:green;'><strong>✓ 成功:</strong> {$count} 人のユーザーに一時パスワードを設定しました。</p>";
            echo "<h3>影響を受けたユーザーに通知してください:</h3>";
            echo "<ul>";
            foreach ($affectedUsers as $user) {
                echo "<li><strong>{$user['username']}</strong> ({$user['email']})</li>";
            }
            echo "</ul>";
            echo "<p><strong>一時パスワード:</strong> <code style='background:#f5f5f5;padding:5px;'>{$tempPassword}</code></p>";
            echo "<p>ユーザーはこのパスワードでログイン後、パスワード変更ページで新しいパスワードに変更できます。</p>";
        } else {
            echo "<p style='color:red;'><strong>✗ 失敗:</strong> パスワードの更新に失敗しました。</p>";
        }
    } elseif ($_POST['action'] == 'suspend_accounts') {
        $query = $db->prepare("
            UPDATE users 
            SET status = 'pending',
                password = SHA1(CONCAT(username, '_suspended_', UNIX_TIMESTAMP()))
            WHERE password LIKE '\$2y\$%' 
              AND LENGTH(password) < 60
              AND status = 'active'
        ");
        $result = $query->execute();
        $count = $query->rowCount();
        
        if ($result) {
            echo "<p style='color:green;'><strong>✓ 成功:</strong> {$count} 人のユーザーを一時停止しました。</p>";
            echo "<p>管理画面から個別に対応してください。</p>";
        } else {
            echo "<p style='color:red;'><strong>✗ 失敗:</strong> アカウントの停止に失敗しました。</p>";
        }
    }
    
    echo "<p><a href=''>ページをリロード</a></p>";
}

echo "<hr><p style='color:red;'><strong>完了後、このファイルを必ず削除してください！</strong></p>";
?>

