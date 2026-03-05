<?php
// 链接映射文件路径
$linksMapFile = 'https://fh.govs.xin/links_map.txt';

// 从查询参数中获取随机字符
$randomStr = isset($_GET['code']) ? trim($_GET['code']) : '';

// 验证随机字符格式（必须是6位字母数字）
if (strlen($randomStr) !== 6 || !preg_match('/^[a-zA-Z0-9]{6}$/', $randomStr)) {
    die('无效的访问链接：随机字符格式错误');
}

// 查找对应的原链接
$originalLink = '';
if (file_exists($linksMapFile)) {
    $lines = file($linksMapFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode(',', $line, 2);
        if (count($parts) === 2 && $parts[0] === $randomStr) {
            $originalLink = trim($parts[1]);
            break;
        }
    }
}

// 如果未找到对应链接或链接无效
if (empty($originalLink) || !filter_var($originalLink, FILTER_VALIDATE_URL)) {
    die('未找到有效的目标链接');
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>官方客服</title>
    <style>
        /* 重置样式 */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html { height: 100%; overflow: hidden; }

        /* 正常浏览的容器样式 */
        .normal-container {
            width: 100%;
            height: 100%;
            display: block;
        }
        iframe { width: 100%; height: 100%; border: none; }

        /* 引导图容器样式（默认隐藏） */
        .guide-container {
            width: 100%;
            height: 100%;
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            background-color: #fff;
        }
        .guide-img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* 图片全屏铺满，保持比例 */
        }
    </style>
</head>
<body>
    <!-- 正常浏览器显示的容器 -->
    <div class="normal-container">
        <iframe src="<?php echo htmlspecialchars($originalLink); ?>" 
                sandbox="allow-same-origin allow-scripts allow-popups allow-forms">
        </iframe>
    </div>

    <!-- 微信/QQ环境显示的引导图容器 -->
    <div class="guide-container">
        <!-- 替换为你的引导图实际路径（比如 pic.jpg），确保图片和PHP文件同目录 -->
        <img src="pic.jpg" class="guide-img" alt="请用浏览器打开">
    </div>

    <script>
        // 核心：检测是否是微信/QQ内置浏览器
        function isWechatOrQQ() {
            const ua = navigator.userAgent.toLowerCase();
            // 匹配微信内置浏览器
            const isWechat = /micromessenger/.test(ua);
            // 匹配QQ内置浏览器（包含QQ、QQ浏览器内置版）
            const isQQ = /qq\/|qzone\/|qqbrowser/.test(ua) && !/mqqbrowser\/.* chrome\/.*/.test(ua);
            return isWechat || isQQ;
        }

        // 页面加载完成后执行
        window.onload = function() {
            const normalContainer = document.querySelector('.normal-container');
            const guideContainer = document.querySelector('.guide-container');
            
            // 如果是微信/QQ环境，显示引导图，隐藏原内容
            if (isWechatOrQQ()) {
                normalContainer.style.display = 'none';
                guideContainer.style.display = 'block';
            }
        };
    </script>
</body>
</html>