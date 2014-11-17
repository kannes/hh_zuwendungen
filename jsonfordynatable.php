<?php
ob_start();
header('Content-Type: application/json');

$dbh = new PDO('sqlite:zuwendungen.sqlite');

// mit umlauten damit dynatable es direkt den tableheads entsprechend parsen kann
$sth = $dbh->prepare("SELECT inez, empfaenger AS empfänger, behoerde AS behörde, substr(von, -4) AS jahr,
	einzelplaene.name AS einzelplan, 
	CASE WHEN einzelplan_stern = 1 THEN '*' 
	ELSE ''
	END AS einzelplan_stern,
	aob.name AS aob,
	z.kapitel, z.titel, zweckbestimmung, 
	CASE WHEN haushaltstyp = 'Investivhaushalt' THEN 'I'
	WHEN haushaltstyp = 'Betriebshaushalt' THEN 'B'
	WHEN haushaltstyp = 'Investiv- und Betriebshaushalt' THEN 'IB'
	END AS haushaltstyp,
	zuwendung,
	CASE WHEN foerderungsart = 'Projektförderung' THEN 'Pf'
	WHEN foerderungsart = 'institutionelle Förderung' THEN 'iF'	
	END AS förderungsart, 
	gesamtzuwendung 
	FROM zuwendungen AS z 
	LEFT JOIN aob ON z.aob=aob.id 
	LEFT JOIN einzelplaene ON z.einzelplan = einzelplaene.id 
	LEFT JOIN kapiteltitel ON z.kapitel=kapiteltitel.kapitel AND z.titel=kapiteltitel.titel
	--LIMIT 1000
	;");
$sth->execute();

$result = $sth->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
//echo '{"records": ';
echo json_encode($result, JSON_NUMERIC_CHECK);
//echo '}';

ob_end_flush();
?>
