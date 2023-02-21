<?php
// 設定計數器儲存的 JSON 檔案路徑
$counterFile = 'counter.json';

// 顯示今天、昨天、累計的文字（或圖片）
$txt_t = '今日：';  // Today
$txt_y = '昨日：';  // Yesterday
$txt_a = '累計：';  // Total
$line =  ' / '; // 分隔線（也可使用<br>換行）

// 設定顯示數字的位數，如果數字不足這個位數，就會在前面補 0
$digitCount = 3;    // e.g. 007

// 設定顯示計數器的方式，txt 代表純文字顯示，img 代表使用圖片顯示
$display_counter = 'txt';

// 設定數字圖片的儲存資料夾（如果設為圖片）
$imgDir = 'img/';

// 設定數字圖片的格式
$imgFormat = '.png';

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

// 讀取 JSON 檔案的資料
$jsonData = file_get_contents($counterFile);
$data = json_decode($jsonData, true);

// 檢查是否是同一天，如果不是，就把昨天的訪問量更新到 $data['yesterday']，並重置今天的訪問量
$currentDate = date('Y-m-d');
if ($currentDate !== $data['date']) {
    $data['yesterday'] = $data['today'];
    $data['today'] = 0;
    $data['date'] = $currentDate;
}

// 訪問量 +1
$data['today']++;
$data['total']++;

// 更新 JSON 檔案的資料
$jsonData = json_encode($data);
file_put_contents($counterFile, $jsonData);

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