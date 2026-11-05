-- Création de la table client avec les contraintes appropriées
CREATE TABLE IF NOT EXISTS `client` (
  `idClient` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(32) NOT NULL,
  `prenom` VARCHAR(32) NOT NULL,
  `pseudo` VARCHAR(32) NOT NULL,
  `mdp` VARCHAR(255) NOT NULL, -- Augmenté à 255 pour permettre un hachage de mot de passe
  `mail` VARCHAR(50) DEFAULT NULL,
  `telephone` VARCHAR(15) DEFAULT NULL,
  PRIMARY KEY (`idClient`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;