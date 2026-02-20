<?php
/**
 * Multiple Barcode Generator with Download Functionality
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
    
    public function generateBarcodeBase64(string $text, int $width = 2, int $height = 80): string {
        $svg = $this->generateBarcode($text, $width, $height);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}

// Handle single barcode download (original functionality)
if (isset($_GET['download']) && isset($_GET['id']) && !isset($_GET['ids'])) {
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

// Handle multiple barcodes print view
if (isset($_GET['print']) && isset($_GET['ids'])) {
    $bookIds = explode(',', $_GET['ids']);
    $generator = new BarcodeGenerator();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Print Multiple Barcodes</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: Arial, sans-serif;
                background: #f5f5f5;
                padding: 20px;
            }
            
            .no-print {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                background: white;
                padding: 10px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .btn {
                padding: 10px 20px;
                margin: 5px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                font-weight: bold;
                transition: all 0.3s;
            }
            
            .btn-primary {
                background: #007bff;
                color: white;
            }
            
            .btn-primary:hover {
                background: #0056b3;
            }
            
            .btn-success {
                background: #28a745;
                color: white;
            }
            
            .btn-success:hover {
                background: #218838;
            }
            
            .btn-secondary {
                background: #6c757d;
                color: white;
            }
            
            .btn-secondary:hover {
                background: #5a6268;
            }
            
            .barcode-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                gap: 20px;
                padding: 20px;
                max-width: 1400px;
                margin: 0 auto;
            }
            
            .barcode-card {
                background: white;
                border: 2px solid #333;
                border-radius: 8px;
                padding: 20px;
                text-align: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                page-break-inside: avoid;
            }
            
            .barcode-id {
                font-size: 18px;
                font-weight: bold;
                color: #333;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 2px solid #eee;
            }
            
            .barcode-image {
                margin: 15px auto;
                display: block;
                max-width: 100%;
            }
            
            .barcode-label {
                font-size: 12px;
                color: #666;
                margin-top: 10px;
            }
            
            @media print {
                body {
                    background: white;
                    padding: 0;
                }
                
                .no-print {
                    display: none !important;
                }
                
                .barcode-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 15px;
                    padding: 10px;
                }
                
                .barcode-card {
                    box-shadow: none;
                    margin-bottom: 10px;
                }
                
                @page {
                    margin: 1cm;
                    size: A4;
                }
            }
            
            @media screen and (max-width: 768px) {
                .barcode-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <div class="no-print">
            <h3 style="margin-bottom: 10px;">Barcode Actions</h3>
            <button class="btn btn-primary" onclick="window.print()">
                🖨️ Print Barcodes
            </button>
            <button class="btn btn-success" onclick="downloadAllPDF()">
                📥 Download PDF
            </button>
            <button class="btn btn-secondary" onclick="window.close()">
                ❌ Close
            </button>
            <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #ddd;">
                <small>Total: <strong><?php echo count($bookIds); ?></strong> barcode(s)</small>
            </div>
        </div>
        
        <div class="barcode-grid">
            <?php foreach($bookIds as $bookId): 
                $bookId = trim($bookId);
                if(empty($bookId)) continue;
                
                $barcodeData = $generator->generateBarcodeBase64($bookId, 2, 80);
            ?>
            <div class="barcode-card">
                <div class="barcode-id">Book ID: <?php echo htmlspecialchars($bookId); ?></div>
                <img src="<?php echo $barcodeData; ?>" alt="Barcode for <?php echo htmlspecialchars($bookId); ?>" class="barcode-image">
                <div class="barcode-label">* Sri Lanka Army Head Quarters *</div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <script>
            function downloadAllPDF() {
                alert('To save as PDF:\n\n1. Click "Print Barcodes" button\n2. In print dialog, select "Save as PDF"\n3. Choose destination and save\n\nThis will save all barcodes in a single PDF file.');
                setTimeout(function() {
                    window.print();
                }, 500);
            }
            
            // Optional: Auto-print when page loads
            // window.onload = function() {
            //     setTimeout(function() { window.print(); }, 1000);
            // };
        </script>
    </body>
    </html>
    <?php
    exit;
}

// Handle multiple barcodes download as ZIP
if (isset($_GET['download_all']) && isset($_GET['ids'])) {
    $bookIds = explode(',', $_GET['ids']);
    $generator = new BarcodeGenerator();
    
    // Create ZIP file
    $zipFilename = 'barcodes_' . date('YmdHis') . '.zip';
    $zip = new ZipArchive();
    $tempZip = sys_get_temp_dir() . '/' . $zipFilename;
    
    if ($zip->open($tempZip, ZipArchive::CREATE) !== TRUE) {
        die("Cannot create ZIP file");
    }
    
    foreach($bookIds as $bookId) {
        $bookId = trim($bookId);
        if(empty($bookId)) continue;
        
        $svg = $generator->generateBarcode($bookId);
        $zip->addFromString('barcode_' . $bookId . '.svg', $svg);
    }
    
    $zip->close();
    
    // Download ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
    header('Content-Length: ' . filesize($tempZip));
    readfile($tempZip);
    unlink($tempZip);
    exit;
}

// Default view - single barcode generator
$bookId = $_POST['book_id'] ?? $_GET['id'] ?? 'BK0010';
$generator = new BarcodeGenerator();
$svgContent = $generator->generateBarcode($bookId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }
        .barcode-container {
            border: 2px solid #333;
            padding: 20px;
            margin: 20px 0;
            background: white;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Barcode Generator</h1>
    <form method="get">
        <input type="text" name="id" value="<?php echo htmlspecialchars($bookId); ?>" placeholder="Enter Book ID">
        <button type="submit">Generate</button>
    </form>
    
    <div class="barcode-container">
        <?php echo $svgContent; ?>
    </div>
    
    <a href="?download=1&id=<?php echo urlencode($bookId); ?>">
        <button type="button">Download SVG</button>
    </a>
</body>
</html>


    <!-- single barcode generator theory
<?php
/**
 * Barcode Generator with Download Functionality
 */

// class BarcodeGenerator {
//     private array $code39 = [
//         '0' => '101001101101', '1' => '110100101011', '2' => '101100101011',
//         '3' => '110110010101', '4' => '101001101011', '5' => '110100110101',
//         '6' => '101100110101', '7' => '101001011011', '8' => '110100101101',
//         '9' => '101100101101', 'A' => '110101001011', 'B' => '101101001011',
//         'C' => '110110100101', 'D' => '101011001011', 'E' => '110101100101',
//         'F' => '101101100101', 'G' => '101010011011', 'H' => '110101001101',
//         'I' => '101101001101', 'J' => '101011001101', 'K' => '110101010011',
//         'L' => '101101010011', 'M' => '110110101001', 'N' => '101011010011',
//         'O' => '110101101001', 'P' => '101101101001', 'Q' => '101010110011',
//         'R' => '110101011001', 'S' => '101101011001', 'T' => '101011011001',
//         'U' => '110010101011', 'V' => '100110101011', 'W' => '110011010101',
//         'X' => '100101101011', 'Y' => '110010110101', 'Z' => '100110110101',
//         '-' => '100101011011', '.' => '110010101101', ' ' => '100110101101',
//         '*' => '100101101101', '$' => '100100100101', '/' => '100100101001',
//         '+' => '100101001001', '%' => '101001001001'
//     ];

    // public function generateBarcode(string $text, int $width = 2, int $height = 80): string {
    //     $text = strtoupper($text);
    //     $encoded = '*' . $text . '*';
        
    //     $binary = '';
    //     for ($i = 0; $i < strlen($encoded); $i++) {
    //         $char = $encoded[$i];
    //         if (isset($this->code39[$char])) {
    //             $binary .= $this->code39[$char] . '0';
    //         }
    //     }
        
        // $totalWidth = strlen($binary) * $width;
        
        // $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        // $svg .= '<svg width="' . $totalWidth . '" height="' . ($height + 30) . '" xmlns="http://www.w3.org/2000/svg">';
        // $svg .= '<rect width="' . $totalWidth . '" height="' . ($height + 30) . '" fill="white"/>';
        
        // $x = 0;
        // for ($i = 0; $i < strlen($binary); $i++) {
        //     if ($binary[$i] === '1') {
        //         $svg .= '<rect x="' . $x . '" y="0" width="' . $width . '" height="' . $height . '" fill="black"/>';
        //     }
        //     $x += $width;
        // }
        
        // $svg .= '<text x="' . ($totalWidth / 2) . '" y="' . ($height + 20) . '" font-family="monospace" font-size="16" text-anchor="middle" fill="black">' . htmlspecialchars($text) . '</text>';
        // $svg .= '</svg>';
        
        // return $svg;
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

?> // -->