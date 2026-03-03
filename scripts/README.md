# Scripts pipeline Easton

- `build_batch_input.py` : construit `out/batch_input.jsonl` depuis `eastons.json`.
- `qa_results.py` : QA sur `out/results.jsonl` et export `out/qa_report.csv`.
- `build_repair_batch.py` : génère `out/repair_batch.jsonl` avec les IDs en échec.
- `merge_results.py` : fusionne résultats batch + repair vers `eastons_fr.json` et `proper_nouns_unknown.json`.

Voir `docs/TRANSLATION_PIPELINE.md` pour la procédure complète.
