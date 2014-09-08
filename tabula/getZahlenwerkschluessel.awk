#!/usr/bin/awk -f
# gewünschtes Ergebnis: Kapitel \t Titel \t Beschreibungstext in einer Zeile

BEGIN {
	FS="\t";
}
{
	# beim Parsen der PDF sind an einigen Stellen die Spalten für die Zahlenwerte um eine Spalte versetzt:
	if ( NF == 7 ) offset = 1; else offset = 0;

	# drei Fälle: 
	# 1. Kapitelnummer (vierstellig ohne Punkt: ^#### (evtl. mit mehreren "-", siehe parsingfehler.md))
	# 2. Titel (entweder ^###.##?, oder dreistellig mit Buchstaben, in etwa ^[A-Z]## oder auch ^[A-Z][A-Z][A-Z])
	# 3. Text (zweite Spalte, erste Spalte leer: ^"")
	if ( $1 ~ /[0-9][0-9][0-9][0-9]/ ) {
		print kapitel, FS, titel, FS, text, FS, nullacht, FS, nullneun, FS, zehn, FS, elf, FS, zwoelf;
		titel = text = "";
		nullacht = nullneun = zehn = elf = zwoelf = "";
		kapitel = $1;
	}
	if ( $1 ~ /[0-9][0-9][0-9]\.[0-9]+/ ) {
		print kapitel, FS, titel, FS, text, FS, nullacht, FS, nullneun, FS, zehn, FS, elf, FS, zwoelf;
		titel = $1; text = $2; nullneun = "";
		if ( offset == 0 ) {
			nullacht = $3; zehn = $4; elf = $5; zwoelf = $6;
		} else {
			nullacht = $4; zehn = $5; elf = $6; zwoelf = $7;
		};	
	}
	if ( $1 ~ /""/ ) {
		if ( $2 !~ /(""|----+|Titelgruppe)/ ) text = text " " $2;
		if ( nullneun == "" ) { 
			if ( offset == 0 ) nullneun = $3; else nullneun = $4;
		};
	}
}
END {
	print kapitel, FS, titel, FS, text, FS, nullacht, FS, nullneun, FS, zehn, FS, elf, FS, zwoelf;
}
