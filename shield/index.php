<?php
$styles = [
    'blue' => '深海蓝',
    'purple' => '贵族紫',
    'green' => '薄荷绿',
    'orange' => '焦糖棕',
    'sunrise' => '琥珀色',
    'coral' => '炽热红',
    'aurora' => '冰河蓝',
    'berry' => '桑葚紫',
    'cottoncandy' => '紫雾蓝',
    'limeade' => '抹茶绿',
    'neon' => '电子紫'
];

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $part1 = urlencode($_POST['part1'] ?? '');
    $part2 = urlencode($_POST['part2'] ?? '');
    $url = urlencode($_POST['url'] ?? '');
    $style = $_POST['style'] ?? 'blue';

    if (!array_key_exists($style, $styles)) {
        $style = 'blue';
    }

    $baseUrl = 'https://shields.wudu.ltd/gen.php';
    $imageUrl = $baseUrl . "?part1={$part1}&part2={$part2}&url={$url}&style={$style}";

    $htmlCode = htmlspecialchars('<a href="' . urldecode($url) . '" target="_blank"><img src="' . $imageUrl . '" alt="' . urldecode($part1) . ': ' . urldecode($part2) . '"></a>');
    $mdCode = htmlspecialchars('[![' . urldecode($part1) . ': ' . urldecode($part2) . '](' . $imageUrl . ')](' . urldecode($url) . ')');
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>徽章生成器</title>
    <link href="https://npm.elemecdn.com/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://npm.elemecdn.com/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #e6f2ff;
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h20v20H0z' fill='%23e6f2ff'/%3E%3Cpath d='M0 0h20v1H0zM0 0h1v20H0z' stroke='%23a3d0ff' fill='none'/%3E%3C/svg%3E");

            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #ff8a5c 0%, #e85aad 100%);
            color: white;
            padding: 2rem 0;
            margin: 60px auto 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            width: calc(100% - 40px);
            max-width: 1300px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.22);
            margin-bottom: 20px;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #7d4cdb 0%, #3d8bfd 100%);
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
        }
        .preview-badge {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .color-option {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
            vertical-align: middle;
        }
        .color-blue { background-color: #1e3a8a; }
        .color-purple { background-color: #7e22ce; }
        .color-green { background-color: #065f46; }
        .color-orange { background-color: #92400e; }
        .badge-example {
            display: inline-block;
            margin: 0 10px 10px 0;
        }
        .code-container {
            margin-top: 30px;
        }
        .code-card {
            width: 100%;
            margin-bottom: 20px;
        }
        .code-block {
            background-color: #1e1e1e;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
            overflow-x: auto;
            color: #ffffff;
            margin-bottom: 10px;
        }
        .code-block code {
            display: block;
            white-space: pre;
            line-height: 1.5;
            color: #ffffff;
        }
        .code-title {
            color: #6a11cb;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .copy-btn {
            background: linear-gradient(135deg, #4dabf7 0%, #339af0 100%);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .copy-btn:hover {
            background: linear-gradient(135deg, #339af0 0%, #228be6 100%);
            transform: translateY(-1px);
        }
        .copy-btn i {
            margin-right: 5px;
        }
        .loading-spinner {
            display: none;
            width: 40px;
            height: 40px;
            margin: 20px auto;
            border: 4px solid rgba(106, 17, 203, 0.2);
            border-radius: 50%;
            border-top: 4px solid #6a11cb;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #2b8a3e;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: none;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -20px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }
        
        footer {
            margin-top: auto;
            padding: 20px 0;
            color: #343a40;
        }
        
        .container {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="notification" id="notification">
        <i class="bi bi-check-circle-fill"></i> 已成功复制到剪贴板
    </div>

    <div class="header text-center">
        <div class="container">
            <h1>🎨 徽章生成器</h1>
            <p class="lead">为您的网站创建漂亮的备案徽章</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">🛠️ 生成设置</h5>
                        <form method="post" id="badge-form">
                            <div class="mb-3">
                                <label for="part1" class="form-label">第一部分文本</label>
                                <input type="text" class="form-control" id="part1" name="part1" value="<?= htmlspecialchars($_POST['part1'] ?? '雾备') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="part2" class="form-label">第二部分文本</label>
                                <input type="text" class="form-control" id="part2" name="part2" value="<?= htmlspecialchars($_POST['part2'] ?? '20250607') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="url" class="form-label">跳转链接 (可选)</label>
                                <input type="url" class="form-control" id="url" name="url" value="<?= htmlspecialchars($_POST['url'] ?? 'https://icp.wudu.ltd/id.php?keyword=20250607') ?>">
                            </div>
                            <div class="mb-4">
                                <label for="style" class="form-label">配色方案</label>
                                <select class="form-select" id="style" name="style">
                                    <?php foreach ($styles as $value => $name): ?>
                                        <option value="<?= $value ?>" <?= (isset($_POST['style']) && $_POST['style'] === $value) || (!isset($_POST['style']) && $value === 'blue') ? 'selected' : '' ?>>
                                            <?= $name ?> <span class="color-option color-<?= $value ?>"></span>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="generate-btn">
                                ✨ 生成徽章
                            </button>
                            <div class="loading-spinner" id="loading-spinner"></div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" id="right-column">
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">👀 徽章预览</h5>
                            <div class="text-center">
                                <img src="<?= $imageUrl ?>" alt="徽章预览" class="preview-badge">
                            </div>
                        </div>
                    </div>
                    
                    <div class="card code-card">
                        <div class="card-body">
                            <h5 class="card-title">🔗 图片URL</h5>
                            <div class="code-block">
                                <code><?= htmlspecialchars($imageUrl) ?></code>
                            </div>
                            <button class="copy-btn" data-clipboard-text="<?= htmlspecialchars($imageUrl) ?>">
                                <i class="bi bi-clipboard"></i> 复制
                            </button>
                        </div>
                    </div>
                    
                    <div class="card code-card">
                        <div class="card-body">
                            <h5 class="card-title">📄 HTML代码</h5>
                            <div class="code-block">
                                <code><?= $htmlCode ?></code>
                            </div>
                            <button class="copy-btn" data-clipboard-text="<?= $htmlCode ?>">
                                <i class="bi bi-clipboard"></i> 复制
                            </button>
                        </div>
                    </div>
                    
                    <div class="card code-card">
                        <div class="card-body">
                            <h5 class="card-title">📝 Markdown代码</h5>
                            <div class="code-block">
                                <code><?= $mdCode ?></code>
                            </div>
                            <button class="copy-btn" data-clipboard-text="<?= $mdCode ?>">
                                <i class="bi bi-clipboard"></i> 复制
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body text-center">
                            <span style="font-size: 3rem;">😊</span>
                            <h3 class="mt-3">欢迎使用徽章生成器</h3>
                            <p class="text-muted">填写左侧表单，生成您的专属徽章</p>
                            <div class="mt-4">
                                <img src="https://shields.wudu.ltd/gen.php/?part1=雾备&part2=20250607&style=blue" alt="示例徽章" class="preview-badge">
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">🎨 配色示例</h5>
                            <div class="text-center">
                                <?php foreach ($styles as $value => $name): ?>
                                    <div class="badge-example">
                                        <img src="https://shields.wudu.ltd/gen.php/?part1=雾备&part2=20250607&style=<?= $value ?>" alt="<?= $name ?>示例" class="preview-badge">
                                        <div class="text-muted small"><?= $name ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="text-center">
        <p>&copy; 2025 雾都云 | 更美观的Shields徽章</p>
        <a href="https://icp.wudu.ltd/id.php?keyword=20250607" target="_blank"><img src="https://shields.wudu.ltd/gen.php?part1=%E9%9B%BE%E5%A4%87&part2=20250607&url=https%3A%2F%2Ficp.wudu.ltd%2Fid.php%3Fkeyword%3D20250607&style=blue" alt="雾备: 20250607"></a>
    </footer>

    <script src="https://npm.elemecdn.com/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://npm.elemecdn.com/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script>

        new ClipboardJS('.copy-btn');
        

        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const notification = document.getElementById('notification');
                notification.style.display = 'block';
                
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 2000);
            });
        });
        

        document.getElementById('badge-form').addEventListener('submit', function() {
            const btn = document.getElementById('generate-btn');
            const spinner = document.getElementById('loading-spinner');
            
            btn.style.display = 'none';
            spinner.style.display = 'block';
            

            setTimeout(() => {
                spinner.style.display = 'none';
                btn.style.display = 'block';
            }, 1500);
        });
    </script>
</body>
</html>