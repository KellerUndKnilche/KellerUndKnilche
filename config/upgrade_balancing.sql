-- Alte Daten löschen
DELETE FROM upgrades;
DELETE FROM targets;

-- TARGETS einfügen
INSERT INTO targets (id, name, typ) VALUES
(1, 'Gerippe', 'produktion'),
(2, 'Untoter', 'produktion'),
(3, 'Fledermausschwarm', 'produktion'),
(4, 'Geistererscheinung', 'produktion'),
(5, 'Dämon aus der Mittagspause', 'produktion'),
(6, 'Höllenhundebrigade', 'produktion'),
(7, 'Schattenkaiser', 'produktion'),

-- Klick-Targets mit den neuen IDs und Namen
(29, 'Muskelkater-Finger', 'klick'),
(30, 'Nekro-Handschuh', 'klick'),
(31, 'Grabscher der Untoten', 'klick'),
(32, 'Greifarm aus dem Jenseits', 'klick'),
(33, 'Finger des Verderbens', 'klick'),
(34, 'Magischer Klicker des Todes', 'klick');

-- UPGRADES einfügen (mit Basispreisen und neuen Werten)
INSERT INTO upgrades (id, name, basispreis, effektart, effektwert, kategorie, ziel_id) VALUES

-- Basis-Produktion Upgrades (mit den neuen Produktionswerten und Basispreisen)
(1, 'Gerippe', 25, 'absolut', 1, 'produktion', 1),
(2, 'Untoter', 150, 'absolut', 7, 'produktion', 2),
(3, 'Fledermausschwarm', 750, 'absolut', 25, 'produktion', 3),
(4, 'Geistererscheinung', 3000, 'absolut', 100, 'produktion', 4),
(5, 'Dämon aus der Mittagspause', 10000, 'absolut', 300, 'produktion', 5),
(6, 'Höllenhundebrigade', 25000, 'absolut', 750, 'produktion', 6),
(7, 'Schattenkaiser', 75000, 'absolut', 2000, 'produktion', 7),

-- Gerippe Booster
(8, 'Knochentraining', 50, 'prozent', 10, 'boost', 1),
(9, 'Beckenschwingen', 125, 'prozent', 15, 'boost', 1),
(10, 'Toten-Tai-Chi', 250, 'prozent', 20, 'boost', 1),

-- Untoter Booster
(11, 'Untoten-Schreitherapie', 150, 'prozent', 10, 'boost', 2),
(12, 'Moder-Meditation', 350, 'prozent', 15, 'boost', 2),
(13, 'Verfaulungs-Yoga', 750, 'prozent', 20, 'boost', 2),

-- Fledermausschwarm Booster
(14, 'Fledermaus-Chorprobe', 600, 'prozent', 10, 'boost', 3),
(15, 'Ultraschall-Stimmenbruch', 1500, 'prozent', 15, 'boost', 3),
(16, 'Flatter-Fitness', 3000, 'prozent', 20, 'boost', 3),

-- Geistererscheinung Booster
(17, 'Geisterrausch', 2500, 'prozent', 10, 'boost', 4),
(18, 'Ektoplasma-Workout', 6000, 'prozent', 15, 'boost', 4),
(19, 'Spuk-Intensivkurs', 12000, 'prozent', 20, 'boost', 4),

-- Dämon Booster
(20, 'Dämonenvertrag', 8000, 'prozent', 10, 'boost', 5),
(21, 'Blutschwur der Faulen', 18000, 'prozent', 15, 'boost', 5),
(22, 'Höllischer Halbtagsjob', 40000, 'prozent', 20, 'boost', 5),

-- Höllenhundebrigade Booster
(23, 'Höllenhund-Rudelschule', 20000, 'prozent', 10, 'boost', 6),
(24, 'Feueratem-Training', 45000, 'prozent', 15, 'boost', 6),
(25, 'Infernaler Aufmarsch', 90000, 'prozent', 20, 'boost', 6),

-- Schattenkaiser Booster
(26, 'Schattenmeisterei', 60000, 'prozent', 10, 'boost', 7),
(27, 'Dunkelheitsdoktorat', 120000, 'prozent', 15, 'boost', 7),
(28, 'Herrschaft des Grauens', 250000, 'prozent', 20, 'boost', 7),

-- Klick Upgrades
(29, 'Muskelkater-Finger', 75, 'absolut', 2, 'klick', 29),
(30, 'Nekro-Handschuh', 250, 'absolut', 3, 'klick', 30),
(31, 'Grabscher der Untoten', 600, 'absolut', 4, 'klick', 31),
(32, 'Greifarm aus dem Jenseits', 1200, 'absolut', 5, 'klick', 32),
(33, 'Finger des Verderbens', 2000, 'absolut', 6, 'klick', 33),
(34, 'Magischer Klicker des Todes', 3500, 'absolut', 7, 'klick', 34);
