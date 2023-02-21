<?php
// PHP Visitor Counter by vegbao 2023.
// https://github.com/vegbao/PHP-Visitor-Counter
// ---------------------------------------------------------------
// Retro-style website counter that requires no database,
// uses plain text storage, and can display today's, yesterday's,
// and total visitors in either text or image format.
// 純文字免資料庫 PHP 文字、圖片網站計數器，可顯示今天、昨天、累計人次。
// ---------------------------------------------------------------

// Store the counter data in a JSON file
// 設定計數器儲存的 JSON 檔案路徑
$counterFile = 'counter.json';

// Display visitor count for today, yesterday, and total as text or images
// 顯示今天、昨天、累計的文字（或圖片）
$txt_t = 'Today:';  // 今天
$txt_y = 'Yesterday:';  // 昨天
$txt_a = 'Total:';  // 累計
$line =  ' / '; // 分隔線（也可使用<br>換行） // Add a separator line or line break(<br>)

// Choose number of digits and add leading zeros if needed
// 設定顯示數字的位數，如果數字不足這個位數，就會在前面補 0
$digitCount = 3;

// Choose text-only(txt) or image display(img)
// 設定顯示計數器的方式，txt 代表純文字顯示，img 代表使用圖片顯示
$display_counter = 'txt';

// Choose image's folder
// 設定數字圖片的儲存資料夾（如果設為圖片）
$imgDir = 'img/';

// Choose image format for numbers
// 設定數字圖片的格式
$imgFormat = '.png';

// If JSON file doesn't exist, create it
// 檢查 JSON 檔案是否存在，如果不存在就建立
if (!file_exists($counterFile)) {
    $data = [
        'today' => 0,
        'yesterday' => 0,
        'total' => 0,
        'date' => ''
    ];
    $jsonData = json_encode($data);
    file_put_contents($counterFile, $jsonData);
}

// Read data from JSON file
// 讀取 JSON 檔案的資料
$jsonData = file_get_contents($counterFile);
$data = json_decode($jsonData, true);

// Update yesterday's count and reset today's count if it's not the same day
// 檢查是否是同一天，如果不是，就把昨天的訪問量更新到 $data['yesterday']，並重置今天的訪問量
$currentDate = date('Y-m-d');
if ($currentDate !== $data['date']) {
    $data['yesterday'] = $data['today'];
    $data['today'] = 0;
    $data['date'] = $currentDate;
}

// Increment visitor count
// 訪問量 +1
$data['today']++;
$data['total']++;

// Update data in JSON file
// 更新 JSON 檔案的資料
$jsonData = json_encode($data);
file_put_contents($counterFile, $jsonData);

// Display the result
// 顯示結果
echo '<div style="text-align: center">';
if ($display_counter === 'txt') {
    echo $txt_t . str_pad($data['today'], $digitCount, '0', STR_PAD_LEFT) . $line;
    echo $txt_y . str_pad($data['yesterday'], $digitCount, '0', STR_PAD_LEFT) . $line;
    echo $txt_a . str_pad($data['total'], $digitCount, '0', STR_PAD_LEFT);
} else {
    echo $txt_t;
    numImg($data['today'], $imgDir, $imgFormat, $digitCount) . $line;
    echo $txt_y;
    numImg($data['yesterday'], $imgDir, $imgFormat, $digitCount) . $line;
    echo $txt_a;
    numImg($data['total'], $imgDir, $imgFormat, $digitCount);
}
echo '</div>';

// Use this function to show number images.
// 輸出數字圖片的函數
function numImg($number, $imgDir, $imgFormat, $digitCount)
{
    $numString = str_pad($number, $digitCount, '0', STR_PAD_LEFT);
    for($i = 0; $i < $digitCount; $i++) {
        $digit = $numString[$i];
        $imgName = $imgDir . $digit . $imgFormat;
        echo '<img src="' . $imgName . '" alt="' . $digit . '">';
    }
}
?>