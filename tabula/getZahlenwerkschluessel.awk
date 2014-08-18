#!/usr/bin/awk -f
# gewünschtes Ergebnis: Kapitel \t Titel \t Beschreibungstext in einer Zeile

BEGIN {
	FS="\t";
}
{
	# drei Fälle: 
	# 1. Kapitelnummer (vierstellig ohne Punkt: ^#### (evtl. mit mehreren "-", siehe parsingfehler.md))
	# 2. Titel (entweder ^###.##?, oder dreistellig mit Buchstaben, in etwa ^[A-Z]## oder auch ^[A-Z][A-Z][A-Z])
	# 3. Text (zweite Spalte, erste Spalte leer: ^"")
	if ( $1 ~ /[0-9][0-9][0-9][0-9]/ ) {
		print kapitel, FS, titel, FS, text;
		titel = ""; text = "";
		kapitel = $1;
	}
	if ( $1 ~ /[0-9][0-9][0-9]\.[0-9]+/ ) {
		print kapitel, FS, titel, FS, text;
		titel = $1;
		text = $2;
	}
	if ( $1 ~ /""/ ) {
		if ( $2 != "\"\"" ) text = text " " $2;
	}
}
END {
	print kapitel, FS, titel, FS, text;
}
