<?php
$dbh = new PDO('sqlite:zuwendungen.sqlite');

$sth = $dbh->prepare("SELECT inez, empfaenger, behoerde, substr(von, -4) AS jahr,
	einzelplaene.name AS einzelplan, aob.name AS aob,
	z.kapitel, z.titel, zweckbestimmung, haushaltstyp, zuwendung,
	foerderungsart, gesamtzuwendung 
	FROM zuwendungen AS z 
	LEFT JOIN aob ON z.aob=aob.id 
	LEFT JOIN einzelplaene ON z.einzelplan LIKE einzelplaene.id || '%' 
	LEFT JOIN kapiteltitel ON z.kapitel=kapiteltitel.kapitel AND z.titel=kapiteltitel.titel
	;");
$sth->execute();

$result = $sth->fetchAll(PDO::FETCH_ASSOC);

echo '{
  "data": ';
echo json_encode($result);
echo '}';
?>
