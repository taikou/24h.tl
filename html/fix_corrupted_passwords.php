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
    
    echo "<h3>オプション1: ユーザーごとにランダムな一時パスワードを生成（推奨）</h3>";
    echo "<form method='post' onsubmit='return confirm(\"本当に実行しますか？\");'>";
    echo "<p>各ユーザーに異なるランダムな一時パスワードを設定し、CSVファイルでダウンロードします。</p>";
    echo "<p style='color:orange;'><strong>重要:</strong> CSVファイルを安全に保管し、各ユーザーに個別に通知してください。</p>";
    echo "<input type='hidden' name='action' value='set_random_passwords'>";
    echo "<button type='submit' style='padding:10px 20px;background:#4caf50;color:white;border:none;cursor:pointer;'>ランダムパスワードを生成</button>";
    echo "</form>";
    
    echo "<h3>オプション2: パスワードリセットメール送信フラグを設定</h3>";
    echo "<form method='post' onsubmit='return confirm(\"本当に実行しますか？\");'>";
    echo "<p>影響を受けたユーザーのstatusを 'password_reset_required' に変更します。<br>";
    echo "ログイン時に自動的にパスワードリセット画面に誘導されます。</p>";
    echo "<input type='hidden' name='action' value='flag_reset_required'>";
    echo "<button type='submit' style='padding:10px 20px;background:#2196f3;color:white;border:none;cursor:pointer;'>リセット要求フラグを設定</button>";
    echo "</form>";
    
    echo "<h3>オプション3: アカウントを一時停止（手動対応）</h3>";
    echo "<form method='post' onsubmit='return confirm(\"本当に実行しますか？\");'>";
    echo "<p>影響を受けたユーザーのstatusを 'pending' に変更し、管理者が個別に対応します。</p>";
    echo "<input type='hidden' name='action' value='suspend_accounts'>";
    echo "<button type='submit' style='padding:10px 20px;background:#f44336;color:white;border:none;cursor:pointer;'>アカウントを一時停止</button>";
    echo "</form>";
}

// 処理実行
if (isset($_POST['action'])) {
    echo "<hr><h2>実行結果</h2>";
    
    if ($_POST['action'] == 'set_random_passwords') {
        // 影響を受けたユーザーを取得
        $query = $db->query("
            SELECT id, username, email 
            FROM users 
            WHERE password LIKE '\$2y\$%' 
              AND LENGTH(password) < 60
              AND status = 'active'
        ");
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $resetData = [];
        $updateStmt = $db->prepare("UPDATE users SET password = :hash WHERE id = :id LIMIT 1");
        
        foreach ($users as $user) {
            // ランダムパスワード生成（12文字、英数字+記号）
            $randomPassword = bin2hex(random_bytes(6)); // 12文字の16進数
            $hash = sha1($randomPassword);
            
            $updateStmt->execute([
                ':hash' => $hash,
                ':id' => $user['id']
            ]);
            
            $resetData[] = [
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $randomPassword
            ];
        }
        
        echo "<p style='color:green;'><strong>✓ 成功:</strong> " . count($resetData) . " 人のユーザーにランダムパスワードを設定しました。</p>";
        
        // CSVダウンロードボタン
        echo "<h3>パスワードリスト（CSV）</h3>";
        echo "<p style='color:red;'><strong>警告:</strong> このデータは安全に保管してください！</p>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='download_csv' value='1'>";
        foreach ($resetData as $data) {
            echo "<input type='hidden' name='csv_data[]' value='" . htmlspecialchars(json_encode($data)) . "'>";
        }
        echo "<button type='submit' style='padding:10px 20px;background:#4caf50;color:white;border:none;cursor:pointer;'>CSVダウンロード</button>";
        echo "</form>";
        
        // プレビュー
        echo "<h4>プレビュー:</h4>";
        echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
        echo "<tr><th>ユーザー名</th><th>メール</th><th>一時パスワード</th></tr>";
        foreach ($resetData as $data) {
            echo "<tr>";
            echo "<td>{$data['username']}</td>";
            echo "<td>{$data['email']}</td>";
            echo "<td><code>{$data['password']}</code></td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } elseif ($_POST['action'] == 'flag_reset_required') {
        $query = $db->prepare("
            UPDATE users 
            SET status = 'password_reset_required'
            WHERE password LIKE '\$2y\$%' 
              AND LENGTH(password) < 60
              AND status = 'active'
        ");
        $result = $query->execute();
        $count = $query->rowCount();
        
        if ($result) {
            echo "<p style='color:green;'><strong>✓ 成功:</strong> {$count} 人のユーザーにパスワードリセットフラグを設定しました。</p>";
            echo "<p><strong>注意:</strong> 次回ログイン試行時に、パスワードリセット画面に誘導する必要があります。</p>";
            echo "<p>ログインフォームで status='password_reset_required' をチェックする処理を追加してください。</p>";
        } else {
            echo "<p style='color:red;'><strong>✗ 失敗:</strong> フラグの設定に失敗しました。</p>";
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

// CSVダウンロード処理
if (isset($_POST['download_csv'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="password_reset_' . date('Ymd_His') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // BOM for Excel UTF-8 support
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // ヘッダー
    fputcsv($output, ['ユーザー名', 'メールアドレス', '一時パスワード']);
    
    // データ
    foreach ($_POST['csv_data'] as $jsonData) {
        $data = json_decode($jsonData, true);
        fputcsv($output, [$data['username'], $data['email'], $data['password']]);
    }
    
    fclose($output);
    exit;
}

echo "<hr><p style='color:red;'><strong>完了後、このファイルを必ず削除してください！</strong></p>";
?>

