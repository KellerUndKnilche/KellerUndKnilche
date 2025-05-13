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
(69, 'Nekrotische Multiberührung', 'klick'),
(70, 'Seelenkitzler', 'klick'),
(71, 'Grabscher der Untoten', 'klick'),
(72, 'Finger des Verderbens', 'klick'),
(73, 'Hand des Todes', 'klick');

-- UPGRADES einfügen
INSERT INTO upgrades (id, name, basispreis, effektart, effektwert, kategorie, ziel_id) VALUES
-- Basis-Produktion (basispreis = 10*vorheriges, effekt = 4*vorheriges)
(1, 'Gerippe', 10, 'absolut', 1, 'produktion', 1),
(2, 'Untoter', 100, 'absolut', 5, 'produktion', 2),
(3, 'Fledermausschwarm', 1000, 'absolut', 20, 'produktion', 3),
(4, 'Geistererscheinung', 10000, 'absolut', 80, 'produktion', 4),
(5, 'Dämon aus der Mittagspause', 100000, 'absolut', 320, 'produktion', 5),
(6, 'Höllenhundebrigade', 1000000, 'absolut', 1280, 'produktion', 6),
(7, 'Schattenkaiser', 10000000, 'absolut', 5120, 'produktion', 7),

-- Booster: Billigstes 5–10x Preis des Basisitems, dann 2x Preis des vorherigen Boosters


-- Gerippe Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(8, 'Knochentraining', 50, 'prozent', 10, 'boost', 1),
(9, 'Beckenschwingen', 100, 'prozent', 25, 'boost', 1),
(10, 'Toten-Tai-Chi', 200, 'prozent', 50, 'boost', 1),
(11, 'Knochen-Choreographie', 400, 'prozent', 100, 'boost', 1),
(12, 'Höllenknochen-Ballett', 800, 'prozent', 200, 'boost', 1),
(13, 'Knochensplitter-Sprint', 1600, 'prozent', 500, 'boost', 1),
(14, 'Totentanz-Power', 3200, 'prozent', 1000, 'boost', 1),

-- Untoter Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(15, 'Untoten-Schreitherapie', 500, 'prozent', 10, 'boost', 2),
(16, 'Moder-Meditation', 1000, 'prozent', 25, 'boost', 2),
(17, 'Verfaulungs-Yoga', 2000, 'prozent', 50, 'boost', 2),
(18, 'Aas-Massage', 4000, 'prozent', 100, 'boost', 2),
(19, 'Gammel-Salbung', 8000, 'prozent', 200, 'boost', 2),
(20, 'Schwamm-Ritual', 16000, 'prozent', 500, 'boost', 2),
(21, 'Aas-Schreitherapie 2.0', 32000, 'prozent', 1000, 'boost', 2),

-- Fledermausschwarm Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(22, 'Fledermaus-Chorprobe', 5000, 'prozent', 10, 'boost', 3),
(23, 'Ultraschall-Stimmenbruch', 10000, 'prozent', 25, 'boost', 3),
(24, 'Flatter-Fitness', 20000, 'prozent', 50, 'boost', 3),
(25, 'Luftakrobatik', 40000, 'prozent', 100, 'boost', 3),
(26, 'Scharf-Mäulchen', 80000, 'prozent', 200, 'boost', 3),
(27, 'Super-Schwarm', 160000, 'prozent', 500, 'boost', 3),
(28, 'Fledermaus-Tornado', 320000, 'prozent', 1000, 'boost', 3),

-- Geistererscheinung Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(29, 'Geisterrausch', 50000, 'prozent', 10, 'boost', 4),
(30, 'Ektoplasma-Workout', 100000, 'prozent', 25, 'boost', 4),
(31, 'Spuk-Intensivkurs', 200000, 'prozent', 50, 'boost', 4),
(32, 'Nebelsturm', 400000, 'prozent', 100, 'boost', 4),
(33, 'Schwarze Aura', 800000, 'prozent', 200, 'boost', 4),
(34, 'Konditioniert für Spuk', 1600000, 'prozent', 500, 'boost', 4),
(35, 'Geister-Tsunami', 3200000, 'prozent', 1000, 'boost', 4),

-- Dämon Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(36, 'Dämonenvertrag', 500000, 'prozent', 100, 'boost', 5),
(37, 'Blutschwur der Faulen', 1000000, 'prozent', 250, 'boost', 5),
(38, 'Höllischer Halbtagsjob', 2000000, 'prozent', 500, 'boost', 5),
(39, 'Dämonen-Aufzucht', 4000000, 'prozent', 1000, 'boost', 5),
(40, 'Inferno-Bindung', 8000000, 'prozent', 2000, 'boost', 5),
(41, 'Teufelsbeschwörung', 16000000, 'prozent', 5000, 'boost', 5),
(42, 'Dämonen-Energieschub', 32000000, 'prozent', 10000, 'boost', 5),

-- Höllenhundebrigade Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(43, 'Höllenhund-Rudelschule', 5000000, 'prozent', 100, 'boost', 6),
(44, 'Feueratem-Training', 10000000, 'prozent', 250, 'boost', 6),
(45, 'Infernaler Aufmarsch', 20000000, 'prozent', 500, 'boost', 6),
(46, 'Hundekommando-Feuer', 40000000, 'prozent', 1000, 'boost', 6),
(47, 'Höllenhund-Renntraining', 80000000, 'prozent', 2000, 'boost', 6),
(48, 'Feuer-Pulverisierung', 160000000, 'prozent', 5000, 'boost', 6),
(49, 'Teufelsfeuer-Angriff', 320000000, 'prozent', 10000, 'boost', 6),

-- Schattenkaiser Booster (+10%, +25%, +50%, +100%, +200%, +500%, +1000%)
(50, 'Schattenmeisterei', 50000000, 'prozent', 100, 'boost', 7),
(51, 'Dunkelheitsdoktorat', 100000000, 'prozent', 250, 'boost', 7),
(52, 'Herrschaft des Grauens', 200000000, 'prozent', 500, 'boost', 7),
(53, 'Todeshauch', 400000000, 'prozent', 1000, 'boost', 7),
(54, 'Dunkle Verschmelzung', 800000000, 'prozent', 2000, 'boost', 7),
(55, 'Schattenschwemme', 1600000000, 'prozent', 5000, 'boost', 7),
(56, 'Schreckensherrschaft', 3200000000, 'prozent', 10000, 'boost', 7),

-- Klick Upgrades (basispreis = runden(50 * 2.8^n))
(57, 'Muskelkater Finger', 50, 'absolut', 1, 'klick', 57),
(58, 'Verstauchte Skeletthand', 140, 'absolut', 2, 'klick', 58),
(59, 'Dämmergriff', 392, 'absolut', 4, 'klick', 59),
(60, 'Totenfinger', 1098, 'absolut', 8, 'klick', 60),
(61, 'Phantomgriff', 3073, 'absolut', 16, 'klick', 61),
(62, 'Nekro-Krallen', 8605, 'absolut', 32, 'klick', 62),
(63, 'Schattengriff', 24095, 'absolut', 64, 'klick', 63),
(64, 'Doppelschlag des Jenseits', 67465, 'absolut', 128, 'klick', 64),
(65, 'Seelenschnapper', 188901, 'absolut', 256, 'klick', 65),
(66, 'Seelenklau', 528923, 'absolut', 512, 'klick', 66),
(67, 'Greifarm aus dem Jenseits', 1480984, 'absolut', 1024, 'klick', 67),
(68, 'Griff des Grauens', 4146755, 'absolut', 2048, 'klick', 68),
(69, 'Nekrotische Multiberührung', 11610913, 'absolut', 4096, 'klick', 69),
(70, 'Seelenkitzler', 32510557, 'absolut', 8192, 'klick', 70),
(71, 'Grabscher der Untoten', 91029560, 'absolut', 16384, 'klick', 71),
(72, 'Finger des Verderbens', 254882768, 'absolut', 32768, 'klick', 72),
(73, 'Hand des Todes', 713671750, 'absolut', 65536, 'klick', 73);