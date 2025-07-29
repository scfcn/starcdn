<?php
// 获取参数
$part1 = isset($_GET['part1']) ? htmlspecialchars($_GET['part1']) : 'TuanAPI';
$part2 = isset($_GET['part2']) ? htmlspecialchars($_GET['part2']) : '生成我的个性徽章';
$url = isset($_GET['url']) ? htmlspecialchars($_GET['url']) : 'https://shields.wudu.ltd/gen.php';
$style = isset($_GET['style']) && in_array($_GET['style'], ['blue', 'purple', 'green', 'orange', 'sunrise', 'coral', 'aurora', 'berry', 'cottoncandy', 'limeade', 'neon']) ? $_GET['style'] : 'blue';

// 定义样式
$styles = [
    'blue' => [
        'left_color' => '#1e3a8a',    // 深海蓝
        'right_color' => '#3a86ff'    // 明亮湖蓝
    ],
    'purple' => [
        'left_color' => '#7e22ce',    // 贵族紫
        'right_color' => '#ff4d9a'    // 霓虹粉
    ],
    'green' => [
        'left_color' => '#065f46',    // 深丛林绿
        'right_color' => '#10b981'    // 薄荷绿
    ],
    'orange' => [
        'left_color' => '#92400e',    // 焦糖棕
        'right_color' => '#f59e0b'    // 阳光橙
    ],
    'sunrise' => [
        'left_color' => '#d97706',    // 琥珀色
        'right_color' => '#fcd34d'    // 金丝雀黄
    ],
    'coral' => [
        'left_color' => '#ef4444',    // 炽热红
        'right_color' => '#fda4af'    // 珊瑚粉
    ],
    'aurora' => [
        'left_color' => '#0d9488',    // 孔雀石绿
        'right_color' => '#67e8f9'    // 冰河蓝
    ],
    'berry' => [
        'left_color' => '#6d28d9',    // 桑葚紫
        'right_color' => '#f0abfc'    // 薰衣草紫
    ],
    'cottoncandy' => [
        'left_color' => '#db2777',    // 覆盆子红
        'right_color' => '#a5b4fc'    // 紫雾蓝
    ],
    'limeade' => [
        'left_color' => '#3f6212',    // 抹茶绿
        'right_color' => '#bef264'    // 青柠黄
    ],
    'neon' => [
        'left_color' => '#5b21b6',    // 电子紫
        'right_color' => '#22d3ee'    // 霓虹蓝
    ]
];

$currentStyle = $styles[$style];

// 计算文本宽度（区分中英文）
function calculateTextWidth($text) {
    $width = 0;
    $length = mb_strlen($text);
    
    for ($i = 0; $i < $length; $i++) {
        $char = mb_substr($text, $i, 1);
        // 判断字符类型：英文/数字或中文
        if (preg_match('/^[a-zA-Z0-9]$/', $char)) {
            $width += 6; // 英文/数字较窄
        } else {
            $width += 11; // 中文较宽
        }
    }
    
    return $width + 10; // 加上基础padding
}

// 计算文本长度和SVG宽度
$part1Width = calculateTextWidth($part1);
$part2Width = calculateTextWidth($part2);
$totalWidth = $part1Width + $part2Width;

// 计算textLength属性值（区分中英文）
function calculateTextLength($text) {
    $length = 0;
    $charCount = mb_strlen($text);
    
    for ($i = 0; $i < $charCount; $i++) {
        $char = mb_substr($text, $i, 1);
        if (preg_match('/^[a-zA-Z0-9]$/', $char)) {
            $length += 60; // 英文/数字间距较小
        } else {
            $length += 110; // 中文间距较大
        }
    }
    
    return $length;
}

// 生成SVG
header('Content-Type: image/svg+xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="<?php echo $totalWidth; ?>" height="20" role="img" aria-label="<?php echo $part1; ?>: <?php echo $part2; ?>">
    <?php if (!empty($url)): ?>
    <a xlink:href="<?php echo $url; ?>" target="_blank">
    <?php endif; ?>
    <title><?php echo $part1; ?>: <?php echo $part2; ?></title>
    <defs>
        <clipPath id="rounded-corner">
            <rect width="<?php echo $totalWidth; ?>" height="20" rx="3" ry="3"/>
        </clipPath>
    </defs>
    <g shape-rendering="crispEdges" clip-path="url(#rounded-corner)">
        <rect width="<?php echo $part1Width; ?>" height="20" fill="<?php echo $currentStyle['left_color']; ?>"/>
        <rect x="<?php echo $part1Width; ?>" width="<?php echo $part2Width; ?>" height="20" fill="<?php echo $currentStyle['right_color']; ?>"/>
    </g>
    <g fill="#fff" text-anchor="middle" font-family="Verdana,Geneva,DejaVu Sans,sans-serif" text-rendering="geometricPrecision" font-size="110">
        <text x="<?php echo ($part1Width * 5); ?>" y="140" transform="scale(.1)" fill="#fff" textLength="<?php echo calculateTextLength($part1); ?>"><?php echo $part1; ?></text>
        <text x="<?php echo ($part1Width * 10 + $part2Width * 5); ?>" y="140" transform="scale(.1)" fill="#fff" textLength="<?php echo calculateTextLength($part2); ?>"><?php echo $part2; ?></text>
    </g>
    <?php if (!empty($url)): ?>
    </a>
    <?php endif; ?>
</svg>