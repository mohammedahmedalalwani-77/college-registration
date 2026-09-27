<?php

$targetDir = __DIR__ . '/../sample_files';
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// 1. إنشاء صورة الهوية الوطنية (National_ID_Sample.jpg)
$img1 = imagecreatetruecolor(800, 500);
$bg1 = imagecolorallocate($img1, 230, 240, 250);
$border1 = imagecolorallocate($img1, 79, 70, 229);
$text1 = imagecolorallocate($img1, 30, 41, 59);
imagefill($img1, 0, 0, $bg1);
imagerectangle($img1, 10, 10, 780, 480, $border1);
imagestring($img1, 5, 50, 50, "KINGDOM OF SAUDI ARABIA - NATIONAL ID", $text1);
imagestring($img1, 5, 50, 100, "Name: Sample Student ID", $text1);
imagestring($img1, 5, 50, 150, "ID Number: 1098765432", $text1);
imagestring($img1, 5, 50, 200, "Expiry Date: 2030/01/01", $text1);
imagejpeg($img1, $targetDir . '/صورة_الهوية_الوطنية.jpg', 90);
imagedestroy($img1);

// 2. إنشاء صورة شهادة الثانوية العامة (High_School_Certificate.png)
$img2 = imagecreatetruecolor(800, 600);
$bg2 = imagecolorallocate($img2, 255, 253, 245);
$border2 = imagecolorallocate($img2, 22, 101, 52);
$text2 = imagecolorallocate($img2, 20, 83, 45);
imagefill($img2, 0, 0, $bg2);
imagerectangle($img2, 15, 15, 785, 585, $border2);
imagestring($img2, 5, 50, 50, "MINISTRY OF EDUCATION - HIGH SCHOOL CERTIFICATE", $text2);
imagestring($img2, 5, 50, 120, "Student Name: Sample Student", $text2);
imagestring($img2, 5, 50, 170, "GPA Grade: 92.50 % (EXCELLENT)", $text2);
imagestring($img2, 5, 50, 220, "Graduation Year: 2026", $text2);
imagepng($img2, $targetDir . '/صورة_شهادة_الثانوية_العامة.png');
imagedestroy($img2);

// 3. إنشاء ملف PDF وهمي صحيح (High_School_Certificate.pdf)
$pdfContent = "%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>
endobj
4 0 obj
<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>
endobj
5 0 obj
<< /Length 120 >>
stream
BT
/F1 18 Tf
50 700 Td
(HIGH SCHOOL CERTIFICATE - OFFICIAL DOCUMENT SAMPLE) Tj
0 -30 Td
/F1 12 Tf
(Student Name: Mohammed Ahmed) Tj
0 -20 Td
(GPA Score: 88.50 percent) Tj
ET
endstream
endobj
xref
0 6
0000000000 65535 f 
0000000010 00000 n 
0000000060 00000 n 
0000000117 00000 n 
0000000244 00000 n 
0000000318 00000 n 
trailer
<< /Size 6 /Root 1 0 R >>
startxref
490
%%EOF";

file_put_contents($targetDir . '/شهادة_الثانوية_الرسمية.pdf', $pdfContent);
file_put_contents($targetDir . '/مستند_إضافي_تزكية.pdf', $pdfContent);

echo "Successfully generated 4 sample files in: {$targetDir}\n";
