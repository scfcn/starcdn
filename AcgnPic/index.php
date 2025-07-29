<?php
// 使用更快的输出缓冲控制（如果不需要特殊处理，可考虑移除）
ob_start();

// ==================== Redis初始化 ====================
$redis = null;
$redisConnected = false;

// 尝试连接Redis（配置根据实际情况调整）
try {
    $redis = new Redis();
    $redisConnected = $redis->connect('127.0.0.1', 6379, 0.001, null, 0);
    // 如果有密码验证
    // $redis->auth('your_password');
} catch (Exception $e) {
    $redisConnected = false;
}

// ==================== 参数获取与校验 ====================
$type = $_GET['type'] ?? '';
$returnType = $_GET['return'] ?? 'img';

// 精简版错误处理函数
function sendError($code, $message, $returnType) {
    http_response_code($code);
    if ($returnType === 'json') {
        header('Content-Type: application/json; charset=utf-8');
        echo '{"code":' . $code . ',"msg":"' . $message . '","data":null}';
    } else {
        header('Content-Type: text/plain; charset=utf-8');
        echo $message;
    }
    exit;
}

// 参数校验优化（减少函数调用）
if ($type !== '' && $type !== 'pc' && $type !== 'pe') {
    sendError(400, '无效的 type 参数，仅支持 pc/pe 或留空', $returnType);
}

if ($returnType !== 'img' && $returnType !== 'text' && $returnType !== 'json') {
    sendError(400, '无效的 return 参数，仅支持 img/text/json', $returnType);
}

// ==================== 设备类型快速判断 ====================
if ($type === 'pc') {
    $device = 'pc';
} elseif ($type === 'pe') {
    $device = 'pe';
} else {
    // 超快速移动设备检测（优化字符串检测）
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $device = (strpos($ua, 'Mobi') !== false || strpos($ua, 'Android') !== false) ? 'pe' : 'pc';
}

// ==================== Redis缓存处理 ====================
$imageUrl = null;
$redisKey = "acg_urls:{$device}";

// 尝试从Redis获取随机URL
if ($redisConnected) {
    try {
        // 使用更高效的SRANDMEMBER命令（如果使用Redis Set存储）
        // $imageUrl = $redis->sRandMember($redisKey);
        
        // 或者使用List结构（如果使用List存储）
        $listLength = $redis->lLen($redisKey);
        if ($listLength > 0) {
            $randomIndex = random_int(0, $listLength - 1);
            $imageUrl = $redis->lIndex($redisKey, $randomIndex);
        }
    } catch (Exception $e) {
        // 静默处理，回退到文件读取
    }
}

// 缓存未命中时从文件读取
if (!$imageUrl) {
    $listFile = __DIR__ . ($device === 'pc' ? '/acg_pc.txt' : '/acg_m.txt');
    
    if (!file_exists($listFile)) {
        sendError(404, "{$device}设备对应的URL列表文件不存在", $returnType);
    }
    
    // 高效读取文件内容
    $urlList = file($listFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if (empty($urlList)) {
        sendError(500, "{$device}设备的URL列表文件为空", $returnType);
    }
    
    // 随机选择图片URL
    $imageUrl = trim($urlList[array_rand($urlList)]);
    
    // 缓存到Redis（使用Set或List结构）
    if ($redisConnected) {
        try {
            // 方法1：使用Set存储（适合随机获取）
            // $redis->sAddArray($redisKey, $urlList);
            // $redis->expire($redisKey, 3600); // 1小时过期
            
            // 方法2：使用List存储（适合按索引访问）
            $redis->del($redisKey); // 清除旧数据
            foreach ($urlList as $url) {
                $redis->rPush($redisKey, $url);
            }
            $redis->expire($redisKey, 3600); // 1小时过期
        } catch (Exception $e) {
            // 忽略缓存错误
        }
    }
}

// ==================== 极速响应输出 ====================
switch ($returnType) {
    case 'img':
        // 302重定向是最快的方式
        header("Location: $imageUrl", true, 302);
        break;
    
    case 'text':
        // 直接输出文本
        header('Content-Type: text/plain; charset=utf-8');
        echo $imageUrl;
        break;
    
    case 'json':
        // 使用手动构建JSON减少函数调用
        header('Content-Type: application/json; charset=utf-8');
        // 注意：这里简化了输出，实际使用需确保$device变量存在
        echo '{"code":200,"msg":"成功获取图片","data":{"image_url":"' . $imageUrl . '","device":"' . $device . '"}}';
        break;
}

// 确保输出发送
if (ob_get_level() > 0) ob_end_flush();
exit;