-- Alte Daten löschen
DELETE FROM user_upgrades;
DELETE FROM upgrades;
DELETE FROM targets;
-- Beute-Batzen für alle Spieler auf 0 setzen
UPDATE beute_batzen SET amount = 0;

-- Upgrade-Basispreis maximale Länge erhöhen
ALTER TABLE upgrades MODIFY basispreis BIGINT UNSIGNED;
-- Upgrade-Effektwert maximale Länge erhöhen
ALTER TABLE upgrades MODIFY effektwert DOUBLE(20,2);

-- TARGETS einfügen
INSERT INTO targets (id, name, typ) VALUES
-- Basis-Produktion
(1, 'Gerippe', 'produktion'),
(2, 'Untoter', 'produktion'),
(3, 'Fledermausschwarm', 'produktion'),
(4, 'Geistererscheinung', 'produktion'),
(5, 'Dämon aus der Mittagspause', 'produktion'),
(6, 'Höllenhundebrigade', 'produktion'),
(7, 'Schattenkaiser', 'produktion'),

-- Klick
(57, 'Muskelkater Finger', 'klick'),
(58, 'Verstauchte Skeletthand', 'klick'),
(59, 'Dämmergriff', 'klick'),
(60, 'Totenfinger', 'klick'),
(61, 'Phantomgriff', 'klick'),
(62, 'Nekro-Krallen', 'klick'),
(63, 'Schattengriff', 'klick'),
(64, 'Doppelschlag des Jenseits', 'klick'),
(65, 'Seelenschnapper', 'klick'),
(66, 'Seelenklau', 'klick'),
(67, 'Greifarm aus dem Jenseits', 'klick'),
(68, 'Griff des Grauens', 'klick'),
(69, 'Nekrotischer Multiberührung', 'klick'),
(70, 'Seelenkitzler', 'klick'),
(71, 'Grabscher der Untoten', 'klick'),
(72, 'Finger des Verderbens', 'klick'),
(73, 'Hand des Todes', 'klick');

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

-- Gerippe Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(8, 'Knochentraining', 500, 'prozent', 10, 'boost', 1),
(9, 'Beckenschwingen', 25000, 'prozent', 25, 'boost', 1),
(10, 'Toten-Tai-Chi', 75000, 'prozent', 50, 'boost', 1),
(11, 'Knochen-Choreographie', 250000, 'prozent', 100, 'boost', 1),
(12, 'Höllenknochen-Ballett', 1250000, 'prozent', 200, 'boost', 1),
(13, 'Knochensplitter-Sprint', 5000000, 'prozent', 500, 'boost', 1),
(14, 'Totentanz-Power', 20000000, 'prozent', 1000, 'boost', 1),

-- Untoter Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(15, 'Untoten-Schreitherapie', 50000, 'prozent', 10, 'boost', 2),
(16, 'Moder-Meditation', 250000, 'prozent', 25, 'boost', 2),
(17, 'Verfaulungs-Yoga', 750000, 'prozent', 50, 'boost', 2),
(18, 'Aas-Massage', 3750000, 'prozent', 100, 'boost', 2),
(19, 'Gammel-Salbung', 18750000, 'prozent', 200, 'boost', 2),
(20, 'Schwamm-Ritual', 75000000, 'prozent', 500, 'boost', 2),
(21, 'Aas-Schreitherapie 2.0', 300000000, 'prozent', 1000, 'boost', 2),

-- Fledermausschwarm Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(22, 'Fledermaus-Chorprobe', 1000000, 'prozent', 10, 'boost', 3),
(23, 'Ultraschall-Stimmenbruch', 5000000, 'prozent', 25, 'boost', 3),
(24, 'Flatter-Fitness', 15000000, 'prozent', 50, 'boost', 3),
(25, 'Luftakrobatik', 75000000, 'prozent', 100, 'boost', 3),
(26, 'Scharf-Mäulchen', 375000000, 'prozent', 200, 'boost', 3),
(27, 'Super-Schwarm', 1500000000, 'prozent', 500, 'boost', 3),
(28, 'Fledermaus-Tornado', 6250000000, 'prozent', 1000, 'boost', 3),

-- Geistererscheinung Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(29, 'Geisterrausch', 25000000, 'prozent', 10, 'boost', 4),
(30, 'Ektoplasma-Workout', 125000000, 'prozent', 25, 'boost', 4),
(31, 'Spuk-Intensivkurs', 375000000, 'prozent', 50, 'boost', 4),
(32, 'Nebelsturm', 1250000000, 'prozent', 100, 'boost', 4),
(33, 'Schwarze Aura', 6250000000, 'prozent', 200, 'boost', 4),
(34, 'Konditioniert für Spuk', 25000000000, 'prozent', 500, 'boost', 4),
(35, 'Geister-Tsunami', 100000000000, 'prozent', 1000, 'boost', 4),

-- Dämon Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(36, 'Dämonenvertrag', 75000000, 'prozent', 10, 'boost', 5),
(37, 'Blutschwur der Faulen', 375000000, 'prozent', 25, 'boost', 5),
(38, 'Höllischer Halbtagsjob', 1125000000, 'prozent', 50, 'boost', 5),
(39, 'Dämonen-Aufzucht', 3750000000, 'prozent', 100, 'boost', 5),
(40, 'Inferno-Bindung', 15000000000, 'prozent', 200, 'boost', 5),
(41, 'Teufelsbeschwörung', 62500000000, 'prozent', 500, 'boost', 5),
(42, 'Dämonen-Energieschub', 250000000000, 'prozent', 1000, 'boost', 5),

-- Höllenhundebrigade Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(43, 'Höllenhund-Rudelschule', 250000000, 'prozent', 10, 'boost', 6),
(44, 'Feueratem-Training', 1250000000, 'prozent', 25, 'boost', 6),
(45, 'Infernaler Aufmarsch', 3750000000, 'prozent', 50, 'boost', 6),
(46, 'Hundekommando-Feuer', 12500000000, 'prozent', 100, 'boost', 6),
(47, 'Höllenhund-Renntraining', 62500000000, 'prozent', 200, 'boost', 6),
(48, 'Feuer-Pulverisierung', 250000000000, 'prozent', 500, 'boost', 6),
(49, 'Teufelsfeuer-Angriff', 1000000000000, 'prozent', 1000, 'boost', 6),

-- Schattenkaiser Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(50, 'Schattenmeisterei', 1500000000, 'prozent', 10, 'boost', 7),
(51, 'Dunkelheitsdoktorat', 7500000000, 'prozent', 25, 'boost', 7),
(52, 'Herrschaft des Grauens', 22500000000, 'prozent', 50, 'boost', 7),
(53, 'Todeshauch', 75000000000, 'prozent', 100, 'boost', 7),
(54, 'Dunkle Verschmelzung', 375000000000, 'prozent', 200, 'boost', 7),
(55, 'Schattenschwemme', 1500000000000, 'prozent', 500, 'boost', 7),
(56, 'Schreckensherrschaft', 6250000000000, 'prozent', 1000, 'boost', 7),

-- Klick Upgrades (leicht erhöht)
(57, 'Muskelkater Finger', 50, 'absolut', 2, 'klick', 57),
(58, 'Verstauchte Skeletthand', 250, 'absolut', 5, 'klick', 58),
(59, 'Dämmergriff', 1000, 'absolut', 10, 'klick', 59),
(60, 'Totenfinger', 2000, 'absolut', 15, 'klick', 60),
(61, 'Phantomgriff', 3500, 'absolut', 20, 'klick', 61),
(62, 'Nekro-Krallen', 5500, 'absolut', 40, 'klick', 62),
(63, 'Schattengriff', 8500, 'absolut', 75, 'klick', 63),
(64, 'Doppelschlag des Jenseits', 12000, 'absolut', 100, 'klick', 64),
(65, 'Seelenschnapper', 25000, 'absolut', 150, 'klick', 65),
(66, 'Seelenklau', 50000, 'absolut', 200, 'klick', 66),
(67, 'Greifarm aus dem Jenseits', 100000, 'absolut', 250, 'klick', 67),
(68, 'Griff des Grauens', 250000, 'absolut', 300, 'klick', 68),
(69, 'Nekrotischer Multiberührung', 500000, 'absolut', 400, 'klick', 69),
(70, 'Seelenkitzler', 1000000, 'absolut', 500, 'klick', 70),
(71, 'Grabscher der Untoten', 2500000, 'absolut', 600, 'klick', 71),
(72, 'Finger des Verderbens', 5000000, 'absolut', 750, 'klick', 72),
(73, 'Hand des Todes', 10000000, 'absolut', 1000, 'klick', 73);