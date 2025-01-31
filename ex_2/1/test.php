<?php

require_once('../_helpers/strip.php');

// Désactiver le chargement des entités externes
libxml_disable_entity_loader(true);

$xml = !empty($_GET['xml']) ? $_GET['xml'] : '<root><content>No XML found</content></root>';

$document = new DOMDocument();
$document->loadXML($xml, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING); // Suppression de LIBXML_NOENT et LIBXML_DTDLOAD
$parsedDocument = simplexml_import_dom($document);

echo htmlspecialchars($parsedDocument->content, ENT_QUOTES, 'UTF-8'); // Protection XSS
