<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

// update data into database sent by ajax request

use Admin\Translation;
use Admin\Term;

$id = trim(htmlspecialchars($_POST['id']));
$translation_text = trim(htmlspecialchars($_POST['translation']));

$errors = array();

if (!empty($translation_text)) {
    $errors['translation'] = 'You are trying to save an empty translation.';
}

$data = array(
    'translation' => $translation_text
);

// Update translation
$translation = new Translation();
$q = $translation->update($data, $id);

if (empty($q)) {
    // Encode json for failed as AJAX response code and string message
    $response = array('success' => false, 'message' => 'Translation could not be updated.', 'translation' => $translation);
} else {
    $response = array('success' => true, 'message' => 'Translation updated successfully.');
}

header('Content-type: application/json');
echo json_encode($response);