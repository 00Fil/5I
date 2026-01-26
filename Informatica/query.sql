
-- 1

SELECT s.id_spedizione, s.data_i, s.data_fine, a.nome, a.cognome
FROM spedizione s
LEFT JOIN anagrafica a
ON s.nomeresponsabile = a.id_persona;

-- 2

SELECT s.nome, r.id_reperto, r.peso
FROM specie s
LEFT JOIN reperti r
ON s.id_specie = r.id_reperto

-- 3

SELECT a.id_persona, a.nome
FROM anagrafica a
LEFT JOIN spedizione s
ON a.id_persona = s.id_spedizione
WHERE s.data_i < NOW() OR s.data_i IS NULL;

-- 4

SELECT s.nominativo, f.id_finanziare AS id_finanziamento, f.importo AS Importo
FROM sponsor s
LEFT JOIN finanziare f
ON s.id_sponsor = f.id_finanziare