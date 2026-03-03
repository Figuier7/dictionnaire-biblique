# AGENTS.md — Traduction Easton (À l'ombre du figuier) — VERSION FINALE

SOURCE
- Fichier source unique : eastons.json
  - Structure : tableau d’objets, chaque objet contient EXACTEMENT :
    - "mot": string
    - "definition": string

OBJECTIF
- Produire eastons_fr.json (même structure : tableau { "mot", "definition" }, ordre conservé)
- Traduction intégrale, zéro troncage, zéro ajout.
- Conserver ponctuation, guillemets, parenthèses, abréviations, renvois “Comp.”, “marg.”, etc.
- Conserver retours à la ligne / multi-paragraphes quand nécessaire (ne pas aplatir artificiellement).

RÈGLES GÉNÉRALES DE TRADUCTION
1) Traduction intégrale
- Traduire 100% de "definition", sans omettre un mot, sans troncage.
- Conserver toutes les informations : parenthèses, guillemets, abréviations, “marg.”, renvois “Comp.”, etc.
- Conserver les passages multi-paragraphes, alinéas et retours à la ligne lorsque nécessaire.

2) Fidélité / pensée de l’auteur
- Style : théologique, rigoureux, fidèle à l’original, mais accessible.
- Ne pas résumer, ne pas paraphraser librement, ne pas “améliorer” le fond.
- Aucun ajout : pas de notes, pas de précisions externes, pas d’informations absentes du texte source.

3) Champ "mot"
- Traduire "mot" en français classique (forme usuelle et lisible), sans “noms restaurés”.
- Exception unique : si "mot" vaut exactement "God", traduire "mot" par "El" (uniquement pour ce titre d’entrée).

RÈGLES SPÉCIALES — PROSCRIRE “Dieu” ET “l’Éternel” (NOM PROPRE) DANS "definition"
IMPORTANT : Dans "definition", éviter “Dieu” (majuscule) et “l’Éternel”.

A) Nom propre (Dieu biblique)
- “God” (quand il s’agit du Dieu biblique) => “Elohîm”
- “the LORD” / “LORD” (tétragramme) / “Jehovah” / “Yahweh” (et variantes proches) => “YHWH”
- “Jah” => “Yah”
- “the LORD God” / “LORD God” => “YHWH Elohîm”

B) Nom commun (divinités païennes / sens générique)
- “god/gods” au sens commun, divinités païennes => “dieu/dieux” (minuscule)
- Ne pas convertir ces occurrences en “Elohîm”.

C) Distinction LORD vs Lord
- “LORD” => “YHWH”
- “Lord” (titre) => “Seigneur” (autorisé)
- Ne jamais traduire “LORD” par “Seigneur”, ni par “l’Éternel”.

RÉFÉRENCES BIBLIQUES — RÈGLES OSTERVALD (IMPORTANT)
Objectif : conserver le style des références (abrégé vs entier) tel qu’il apparaît en anglais,
mais en français version Ostervald.

1) Si la référence biblique est ABRÉGÉE en anglais :
- Conserver une référence ABRÉGÉE, mais convertir les abréviations en version française Ostervald (abrégée).
- Conserver exactement chapitres/versets/plages (ex : 4:1-16 ; 21:22-34 ; etc.).

2) Si le nom du livre est ÉCRIT EN ENTIER en anglais :
- Le rendre en ENTIER en français version Ostervald.
- Conserver exactement chapitres/versets/plages.

3) Règle spéciale “Revelation”
- Traduire “Rev.”/“Revelation” par “Apoc.”/“Apocalypse” UNIQUEMENT quand il s’agit du livre biblique
  ou d’une référence au livre.
- Dans tout autre contexte (sens commun), traduire “revelation” par “révélation”.

A.V. / R.V. (ET FORMES EN TOUTES LETTRES) — KING JAMES
Ne pas traduire littéralement A.V./R.V. ; faire comprendre qu’il s’agit de versions de la King James :
- “A.V.” => “dans la version autorisée de la King James”
- “R.V.” => “dans la version révisée de la King James”
- “Authorized Version” => “la version autorisée de la King James”
- “Revised Version” => “la version révisée de la King James”

RÈGLES NOMS — JÉSUS / CHRIST (VERSION ROBUSTE, SANS AMBIGUÏTÉ)
PÉRIMÈTRE : ces règles s’appliquent UNIQUEMENT au champ "definition".

PRINCIPE DE SÉCURITÉ (OBLIGATOIRE)
- Appliquer les remplacements dans l’ordre EXACT ci-dessous: du plus spécifique au plus général.
- Protéger temporairement les formes FR déjà correctes :
  "Yehoshoua (Jésus)", "Mashiah (Christ)", "Yehoshoua Mashiah (Jésus-Christ)"
  pour éviter une double transformation si elles apparaissent déjà dans la source.

A) FORMES COMPOSÉES PRIORITAIRES
A1) "Jesus Christ" -> "Yehoshoua Mashiah (Jésus-Christ)"
    Variantes à couvrir:
    - espaces multiples / sauts de ligne: "Jesus  Christ"
    - tirets: "Jesus-Christ" et variantes de tirets unicode
    - ponctuation autour conservée

A2) "Jesus the Christ" -> "Yehoshoua (Jésus) le Mashiah (Christ)"
A3) "Jesus, the Christ" -> "Yehoshoua (Jésus), le Mashiah (Christ)"
A4) "Jesus the Messiah" -> "Yehoshoua (Jésus) le Messie"
A5) "Jesus, the Messiah" -> "Yehoshoua (Jésus), le Messie"

A6) "Christ Jesus" -> "Mashiah (Christ) Yehoshoua (Jésus)"
    IMPORTANT : ne pas réordonner en "Jésus-Christ".

B) FORMES AVEC ARTICLE
B1) "the Christ" -> "le Mashiah (Christ)"
B2) "The Christ" -> "Le Mashiah (Christ)"
B3) "the Messiah" -> "le Messie"
B4) "The Messiah" -> "Le Messie"

C) POSSESSIFS
C1) "Jesus’" ou "Jesus's" (génitif) -> "de Yehoshoua (Jésus)"
C2) "Christ’s" ou "Christ's" ou "Christ’" -> "du Mashiah (Christ)"

D) FORMES SIMPLES (EN DERNIER)
D1) "Jesus" -> "Yehoshoua (Jésus)"
D2) "Christ" -> "Mashiah (Christ)"
D3) "Messiah" (hors cas A/B) -> "Messie"

E) CAS COMPOSÉS AVEC POSSESSIF (ANTI-BUG)
E1) "Jesus Christ’s" / "Jesus Christ's" / variantes avec tiret ->
    "de Yehoshoua Mashiah (Jésus-Christ)"
E2) "Christ Jesus’" / "Christ Jesus's" ->
    "de Mashiah (Christ) Yehoshoua (Jésus)"

F) CONTRÔLES / INTERDICTIONS
F1) INTERDIT : produire "Yehoshoua (Jésus) Mashiah (Christ)" lorsque la source contient "Jesus Christ".
     La forme obligatoire est "Yehoshoua Mashiah (Jésus-Christ)".
F2) Ne pas interférer avec la règle LORD/Lord (YHWH vs Seigneur).
F3) Ne pas altérer les références bibliques ni les abréviations.

G) IDEMPOTENCE (ANTI DOUBLE-TRANSFORMATION)
- Les formes françaises cibles déjà présentes ne doivent jamais être modifiées.

SORTIES ATTENDUES
- eastons_fr.json : tableau final { "mot", "definition" }
- proper_nouns_unknown.json : liste unique des noms propres inconnus (si le pipeline en produit)
- /scripts + /docs : pipeline reproductible
- “Repair mode” : relancer uniquement les entrées en échec (troncage suspect, erreurs format, etc.)

FIN DU CONTENU.
