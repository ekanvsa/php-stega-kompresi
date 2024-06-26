<?php
class HuffmanNode {
    public $char;
    public $freq;
    public $left;
    public $right;

    public function __construct($char, $freq) {
        $this->char = $char;
        $this->freq = $freq;
        $this->left = null;
        $this->right = null;
    }
}

function buildFreqTable($data) {
    $freqTable = array_count_values(str_split($data));
    arsort($freqTable);
    return $freqTable;
}

function buildHuffmanTree($freqTable) {
    $heap = new SplPriorityQueue();
    foreach ($freqTable as $char => $freq) {
        $heap->insert(new HuffmanNode($char, $freq), -$freq);
    }
    while ($heap->count() > 1) {
        $left = $heap->extract();
        $right = $heap->extract();
        $merged = new HuffmanNode(null, $left->freq + $right->freq);
        $merged->left = $left;
        $merged->right = $right;
        $heap->insert($merged, -$merged->freq);
    }
    return $heap->extract();
}

function buildHuffmanCodes($root) {
    $codes = array();
    buildCodesRecursive($root, '', $codes);
    return $codes;
}

function buildCodesRecursive($node, $code, &$codes) {
    if ($node->char !== null) {
        $codes[$node->char] = $code;
        return;
    }
    buildCodesRecursive($node->left, $code . '0', $codes);
    buildCodesRecursive($node->right, $code . '1', $codes);
}

function huffmanCompress($data) {
    $freqTable = buildFreqTable($data);
    $huffmanTree = buildHuffmanTree($freqTable);
    $huffmanCodes = buildHuffmanCodes($huffmanTree);
    $encodedData = '';
    for ($i = 0; $i < strlen($data); $i++) {
        $encodedData .= $huffmanCodes[$data[$i]];
    }
    return $encodedData;
}

function writeCompressedData($encodedData, $outputFile) {
    $encodedDataBytes = str_split($encodedData, 8);
    $encodedBytes = array_map(function($byte) { return bindec($byte); }, $encodedDataBytes);
    $fp = fopen($outputFile, 'wb');
    foreach ($encodedBytes as $byte) {
        fwrite($fp, pack('C', $byte));
    }
    fclose($fp);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image']['tmp_name'])) {
    header('Content-Type: application/json');
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if ($imageFileType != "png") {
        echo json_encode(["status" => "error", "message" => "Hanya file PNG yang diperbolehkan."]);
        exit;
    }
    if ($_FILES["image"]["size"] > 500000) {
        echo json_encode(["status" => "error", "message" => "Maaf, ukuran gambar terlalu besar."]);
        exit;
    }
    if (!is_dir("compressed_images")) {
        mkdir("compressed_images", 0777, true);
    }
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $outputFile = "compressed_images/" . basename($_FILES["image"]["name"]);
        compressImage($targetFile, $outputFile);
        echo json_encode(["status" => "success", "downloadLink" => $outputFile]);
    } else {
        echo json_encode(["status" => "error", "message" => "Maaf, terjadi kesalahan saat mengunggah gambar."]);
    }
}

function compressImage($inputFile, $outputFile) {
    $data = file_get_contents($inputFile);
    $encodedData = huffmanCompress($data);
    writeCompressedData($encodedData, $outputFile);
}
?>
