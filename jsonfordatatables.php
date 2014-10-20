<?php
$dbh = new PDO('sqlite:zuwendungen.sqlite');

$sth = $dbh->prepare("SELECT inez, empfaenger, behoerde, substr(von, -4) AS jahr,
	einzelplaene.name AS einzelplan, CASE WHEN einzelplan_stern = 1 THEN '*' ELSE '' END AS einzelplan_stern, aob.name AS aob,
	z.kapitel, z.titel, zweckbestimmung, haushaltstyp, zuwendung,
	foerderungsart, gesamtzuwendung 
	FROM zuwendungen AS z 
	LEFT JOIN aob ON z.aob=aob.id 
	LEFT JOIN einzelplaene ON z.einzelplan = einzelplaene.id 
	LEFT JOIN kapiteltitel ON z.kapitel=kapiteltitel.kapitel AND z.titel=kapiteltitel.titel
	;");
$sth->execute();

$result = $sth->fetchAll(PDO::FETCH_ASSOC);

echo '{
  "data": ';
echo json_encode($result, JSON_NUMERIC_CHECK);
echo '}';
?>
