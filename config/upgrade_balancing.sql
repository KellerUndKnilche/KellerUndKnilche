-- Alte Daten löschen
DELETE FROM user_upgrades;
DELETE FROM upgrades;
DELETE FROM targets;

-- Upgrade-Basispreis maximale Länge erhöhen
ALTER TABLE upgrades MODIFY basispreis BIGINT UNSIGNED;
-- Upgrade-Effektwert maximale Länge erhöhen
ALTER TABLE upgrades MODIFY effektwert DOUBLE(20,2);

-- TARGETS einfügen
INSERT INTO targets (id, name, typ) VALUES
(1, 'Gerippe', 'produktion'),
(2, 'Untoter', 'produktion'),
(3, 'Fledermausschwarm', 'produktion'),
(4, 'Geistererscheinung', 'produktion'),
(5, 'Dämon aus der Mittagspause', 'produktion'),
(6, 'Höllenhundebrigade', 'produktion'),
(7, 'Schattenkaiser', 'produktion'),

(29, 'Muskelkater-Finger', 'klick'),
(30, 'Nekro-Handschuh', 'klick'),
(31, 'Grabscher der Untoten', 'klick'),
(32, 'Greifarm aus dem Jenseits', 'klick'),
(33, 'Finger des Verderbens', 'klick'),
(34, 'Magischer Klicker des Todes', 'klick');

-- UPGRADES einfügen
INSERT INTO upgrades (id, name, basispreis, effektart, effektwert, kategorie, ziel_id) VALUES

-- Basis-Produktion
(1, 'Gerippe', 10, 'absolut', 1, 'produktion', 1),
(2, 'Untoter', 500, 'absolut', 10, 'produktion', 2),
(3, 'Fledermausschwarm', 2500, 'absolut', 100, 'produktion', 3),
(4, 'Geistererscheinung', 50000, 'absolut', 1000, 'produktion', 4),
(5, 'Dämon aus der Mittagspause', 750000, 'absolut', 10000, 'produktion', 5),
(6, 'Höllenhundebrigade', 250000000, 'absolut', 100000, 'produktion', 6),
(7, 'Schattenkaiser', 15000000000, 'absolut', 1000000, 'produktion', 7),

-- Gerippe Booster
(8, 'Knochentraining', 500, 'prozent', 10, 'boost', 1),
(9, 'Beckenschwingen', 25000, 'prozent', 15, 'boost', 1),
(10, 'Toten-Tai-Chi', 75000, 'prozent', 20, 'boost', 1),

-- Untoter Booster
(11, 'Untoten-Schreitherapie', 50000, 'prozent', 10, 'boost', 2),
(12, 'Moder-Meditation', 250000, 'prozent', 15, 'boost', 2),
(13, 'Verfaulungs-Yoga', 750000, 'prozent', 20, 'boost', 2),

-- Fledermausschwarm Booster
(14, 'Fledermaus-Chorprobe', 1000000, 'prozent', 10, 'boost', 3),
(15, 'Ultraschall-Stimmenbruch', 5000000, 'prozent', 15, 'boost', 3),
(16, 'Flatter-Fitness', 15000000, 'prozent', 20, 'boost', 3),

-- Geistererscheinung Booster
(17, 'Geisterrausch', 25000000, 'prozent', 10, 'boost', 4),
(18, 'Ektoplasma-Workout', 125000000, 'prozent', 15, 'boost', 4),
(19, 'Spuk-Intensivkurs', 375000000, 'prozent', 20, 'boost', 4),

-- Dämon Booster
(20, 'Dämonenvertrag', 75000000, 'prozent', 10, 'boost', 5),
(21, 'Blutschwur der Faulen', 375000000, 'prozent', 15, 'boost', 5),
(22, 'Höllischer Halbtagsjob', 1125000000, 'prozent', 20, 'boost', 5),

-- Höllenhundebrigade Booster
(23, 'Höllenhund-Rudelschule', 250000000, 'prozent', 10, 'boost', 6),
(24, 'Feueratem-Training', 1250000000, 'prozent', 15, 'boost', 6),
(25, 'Infernaler Aufmarsch', 3750000000, 'prozent', 20, 'boost', 6),

-- Schattenkaiser Booster
(26, 'Schattenmeisterei', 1500000000, 'prozent', 10, 'boost', 7),
(27, 'Dunkelheitsdoktorat', 7500000000, 'prozent', 15, 'boost', 7),
(28, 'Herrschaft des Grauens', 22500000000, 'prozent', 20, 'boost', 7),

-- Klick Upgrades (leicht erhöht)
(29, 'Muskelkater-Finger', 150, 'absolut', 2, 'klick', 29),
(30, 'Nekro-Handschuh', 500, 'absolut', 3, 'klick', 30),
(31, 'Grabscher der Untoten', 1200, 'absolut', 4, 'klick', 31),
(32, 'Greifarm aus dem Jenseits', 2500, 'absolut', 5, 'klick', 32),
(33, 'Finger des Verderbens', 5000, 'absolut', 6, 'klick', 33),
(34, 'Magischer Klicker des Todes', 10000, 'absolut', 7, 'klick', 34);
