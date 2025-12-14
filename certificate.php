<?php
define('ROOT', __DIR__);

$quiz_id = $_GET['quiz_id'] ?? null;
$score = (int)($_GET['score'] ?? 0);
$total = (int)($_GET['total'] ?? 1);

if (!$quiz_id || $total <= 0) die("Données manquantes.");

// Charger le quiz
require_once ROOT . '/config/database.php';
require_once ROOT . '/models/Quiz.php';
$quizModel = new Quiz($GLOBALS['pdo']);
$quiz = $quizModel->getById($quiz_id);
if (!$quiz) die("Quiz introuvable.");

// Charger TCPDF
require_once ROOT . '/libraries/tcpdf/tcpdf.php';

// Créer le PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Campus Connect');
$pdf->SetTitle('Certificat de Quiz');
$pdf->SetMargins(20, 20, 20);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();

$html = '
<div style="text-align:center; padding: 40px; color: #bb2649; font-family: helvetica;">
    <h1 style="font-size: 28px; margin-bottom: 30px;">CERTIFICAT DE RÉUSSITE</h1>
    <p style="font-size: 16px;">Ce certificat atteste que</p>
    <h2 style="font-size: 22px; color: #0f0c29;">Apprenant</h2>
    <p style="font-size: 16px;">a complété le quiz :</p>
    <h3 style="font-size: 20px; color: #474787;">"' . htmlspecialchars($quiz['title']) . '"</h3>
    <p style="font-size: 18px; margin: 20px 0; color: #0f0c29;">
        Score : <strong>' . $score . ' / ' . $total . '</strong><br>
        (' . round(($score / $total) * 100) . '%)
    </p>
    <p style="font-size: 14px; margin-top: 40px; color: #555;">
        Date : ' . date('d/m/Y') . ' — Campus Connect
    </p>
</div>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('certificat_quiz.pdf', 'D'); // 'D' = Download
exit;
?>