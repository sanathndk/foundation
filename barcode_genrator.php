<?php
/**
 * Barcode Generator with Download Functionality
 */

class BarcodeGenerator {
    private array $code39 = [
        '0' => '101001101101', '1' => '110100101011', '2' => '101100101011',
        '3' => '110110010101', '4' => '101001101011', '5' => '110100110101',
        '6' => '101100110101', '7' => '101001011011', '8' => '110100101101',
        '9' => '101100101101', 'A' => '110101001011', 'B' => '101101001011',
        'C' => '110110100101', 'D' => '101011001011', 'E' => '110101100101',
        'F' => '101101100101', 'G' => '101010011011', 'H' => '110101001101',
        'I' => '101101001101', 'J' => '101011001101', 'K' => '110101010011',
        'L' => '101101010011', 'M' => '110110101001', 'N' => '101011010011',
        'O' => '110101101001', 'P' => '101101101001', 'Q' => '101010110011',
        'R' => '110101011001', 'S' => '101101011001', 'T' => '101011011001',
        'U' => '110010101011', 'V' => '100110101011', 'W' => '110011010101',
        'X' => '100101101011', 'Y' => '110010110101', 'Z' => '100110110101',
        '-' => '100101011011', '.' => '110010101101', ' ' => '100110101101',
        '*' => '100101101101', '$' => '100100100101', '/' => '100100101001',
        '+' => '100101001001', '%' => '101001001001'
    ];

    public function generateBarcode(string $text, int $width = 2, int $height = 80): string {
        $text = strtoupper($text);
        $encoded = '*' . $text . '*';
        
        $binary = '';
        for ($i = 0; $i < strlen($encoded); $i++) {
            $char = $encoded[$i];
            if (isset($this->code39[$char])) {
                $binary .= $this->code39[$char] . '0';
            }
        }
        
        $totalWidth = strlen($binary) * $width;
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg width="' . $totalWidth . '" height="' . ($height + 30) . '" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<rect width="' . $totalWidth . '" height="' . ($height + 30) . '" fill="white"/>';
        
        $x = 0;
        for ($i = 0; $i < strlen($binary); $i++) {
            if ($binary[$i] === '1') {
                $svg .= '<rect x="' . $x . '" y="0" width="' . $width . '" height="' . $height . '" fill="black"/>';
            }
            $x += $width;
        }
        
        $svg .= '<text x="' . ($totalWidth / 2) . '" y="' . ($height + 20) . '" font-family="monospace" font-size="16" text-anchor="middle" fill="black">' . htmlspecialchars($text) . '</text>';
        $svg .= '</svg>';
        
        return $svg;
    }
}

// Handle download request
if (isset($_GET['download']) && isset($_GET['id'])) {
    $generator = new BarcodeGenerator();
    $bookId = strtoupper($_GET['id']);
    
    $svg = $generator->generateBarcode($bookId);
    
    // Set headers for download
    header('Content-Type: image/svg+xml');
    header('Content-Disposition: attachment; filename="barcode_' . $bookId . '.svg"');
    header('Content-Length: ' . strlen($svg));
    
    echo $svg;
    exit;
}

// Get book ID from form or use default
$bookId = $_POST['book_id'] ?? 'BK0010';
$generator = new BarcodeGenerator();
$svgContent = $generator->generateBarcode($bookId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/png">
  	<title>Barcode Generator |  Library Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        button, .download-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        button {
            background-color: #007bff;
            color: white;
        }
        button:hover {
            background-color: #0056b3;
        }
        .download-btn {
            background-color: #28a745;
            color: white;
        }
        .download-btn:hover {
            background-color: #218838;
        }
        .barcode-display {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        .barcode-display svg {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Barcode Generator</h1>
        
        <form method="POST" action="">
            <div class="row p-2">
                <div class="form-group">
                    <label for="book_id">Enter Book ID:</label>
                    <input type="text" id="book_id" name="book_id" value="<?php echo htmlspecialchars($bookId); ?>" placeholder="e.g., BK0010">
                </div>
                
                <div class="button-group">
                    <button type="submit">Generate Barcode</button>
                    <a href="?download=1&id=<?php echo urlencode($bookId); ?>" class="download-btn" download>⬇ Download SVG</a>
                </div>

                <div class="button-group">
                    <button type="button" onclick="window.location.href='dashboard.php'" class="btn btn-primary btn-md">Cancel</button>
                </div>
            </div>
        </form>
        
        <div class="barcode-display">
            <h3>Generated Barcode: <?php echo htmlspecialchars($bookId); ?></h3>
            <?php echo $svgContent; ?>
        </div>
    </div>
</body>
</html>