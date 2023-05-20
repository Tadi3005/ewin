-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el8.remi
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 16 avr. 2023 à 16:32
-- Version du serveur : 8.0.30
-- Version de PHP : 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `q210115`
--

-- --------------------------------------------------------

--
-- Structure de la table `ewin_creer`
--

CREATE TABLE `ewin_creer` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `id_tournoi` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ewin_participer`
--

CREATE TABLE `ewin_participer` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `id_tournoi` int NOT NULL,
  `date_inscription` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `ewin_participer`
--

INSERT INTO `ewin_participer` (`id`, `id_user`, `id_tournoi`, `date_inscription`) VALUES
(31, 8, 37, '2023-04-14'),
(32, 9, 37, '2023-04-14'),
(33, 8, 38, '2023-04-14'),
(34, 10, 38, '2023-04-14');

-- --------------------------------------------------------

--
-- Structure de la table `ewin_rencontre`
--

CREATE TABLE `ewin_rencontre` (
  `id` int NOT NULL,
  `idTournoi` int NOT NULL,
  `idJoueur1` int DEFAULT NULL,
  `idJoueur2` int DEFAULT NULL,
  `scoreJoueur1` int NOT NULL,
  `scoreJoueur2` int NOT NULL,
  `vainqueur` varchar(200) DEFAULT NULL,
  `idRencontreNext` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `ewin_rencontre`
--

INSERT INTO `ewin_rencontre` (`id`, `idTournoi`, `idJoueur1`, `idJoueur2`, `scoreJoueur1`, `scoreJoueur2`, `vainqueur`, `idRencontreNext`) VALUES
(1, 37, 8, 9, 5, 0, '8', NULL),
(2, 38, 8, 10, 5, 12, '10', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ewin_sport`
--

CREATE TABLE `ewin_sport` (
  `id` int NOT NULL,
  `nom_sport` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `ewin_sport`
--

INSERT INTO `ewin_sport` (`id`, `nom_sport`) VALUES
(1, 'Belotte'),
(2, 'Jeu d’échecs'),
(3, 'Tennis'),
(4, 'Ping-Pong'),
(5, 'Fifa');

-- --------------------------------------------------------

--
-- Structure de la table `ewin_statut`
--

CREATE TABLE `ewin_statut` (
  `id_statut` int NOT NULL,
  `statut` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `ewin_statut`
--

INSERT INTO `ewin_statut` (`id_statut`, `statut`) VALUES
(1, 'Ouvert'),
(2, 'Termine'),
(3, 'Ferme'),
(4, 'En-cours'),
(5, 'Cloture'),
(6, 'Genere'),
(7, 'Inactif');

-- --------------------------------------------------------

--
-- Structure de la table `ewin_tournoi`
--

CREATE TABLE `ewin_tournoi` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `sportId` int NOT NULL,
  `placesDispo` int NOT NULL,
  `idStatut` int NOT NULL,
  `dateTournoi` date NOT NULL,
  `dateFinInscription` date NOT NULL,
  `estActif` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `ewin_tournoi`
--

INSERT INTO `ewin_tournoi` (`id`, `nom`, `sportId`, `placesDispo`, `idStatut`, `dateTournoi`, `dateFinInscription`, `estActif`) VALUES
(37, 'Tournoi Tadino', 3, 2, 2, '2023-06-04', '2023-04-19', 1),
(38, 'Tournoi Helmo', 5, 2, 2, '2023-07-01', '2023-05-21', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ewin_users`
--

CREATE TABLE `ewin_users` (
  `id` int NOT NULL,
  `courriel` varchar(100) NOT NULL,
  `pseudo` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `motDePasse` varchar(100) NOT NULL,
  `estActif` int NOT NULL DEFAULT '0',
  `estOrganisateur` int NOT NULL DEFAULT '0',
  `urlPhoto` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `ewin_users`
--

INSERT INTO `ewin_users` (`id`, `courriel`, `pseudo`, `nom`, `prenom`, `motDePasse`, `estActif`, `estOrganisateur`, `urlPhoto`) VALUES
(1, 'alex.tadino@gmail.com', 'altad3005', 'Tadino', 'Alex', '25f9e794323b453885f5181f1b624d0b', 1, 1, '642b16c689715.jpg'),
(8, 'a.tadino@student.helmo.be', 'xela3005', 'Tadino', 'Alex', '25f9e794323b453885f5181f1b624d0b', 1, 0, '64232718619f0.jpg'),
(9, 'ian.tadino@gmail.com', 'ian', 'Tadino', 'Ian', '25f9e794323b453885f5181f1b624d0b', 1, 0, '64345da5d405b.jpg'),
(10, 'o.alobeidy@student.helmo.be', 'don omar', 'Alobeidy', 'Omar', '25f9e794323b453885f5181f1b624d0b', 1, 1, '64345da5d405b.jpg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ewin_creer`
--
ALTER TABLE `ewin_creer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tournoi` (`id_tournoi`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `ewin_participer`
--
ALTER TABLE `ewin_participer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ewin_participer_ibfk_1` (`id_user`),
  ADD KEY `ewin_participer_ibfk_2` (`id_tournoi`);

--
-- Index pour la table `ewin_rencontre`
--
ALTER TABLE `ewin_rencontre`
  ADD PRIMARY KEY (`id`,`idTournoi`),
  ADD KEY `id_tournoi` (`idTournoi`),
  ADD KEY `id_joueur1` (`idJoueur1`),
  ADD KEY `id_joueur2` (`idJoueur2`);

--
-- Index pour la table `ewin_sport`
--
ALTER TABLE `ewin_sport`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `ewin_statut`
--
ALTER TABLE `ewin_statut`
  ADD PRIMARY KEY (`id_statut`);

--
-- Index pour la table `ewin_tournoi`
--
ALTER TABLE `ewin_tournoi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sportRelation` (`sportId`),
  ADD KEY `idStatut` (`idStatut`);

--
-- Index pour la table `ewin_users`
--
ALTER TABLE `ewin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courriel` (`courriel`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ewin_creer`
--
ALTER TABLE `ewin_creer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ewin_participer`
--
ALTER TABLE `ewin_participer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `ewin_rencontre`
--
ALTER TABLE `ewin_rencontre`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `ewin_tournoi`
--
ALTER TABLE `ewin_tournoi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `ewin_users`
--
ALTER TABLE `ewin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ewin_creer`
--
ALTER TABLE `ewin_creer`
  ADD CONSTRAINT `ewin_creer_ibfk_1` FOREIGN KEY (`id_tournoi`) REFERENCES `ewin_tournoi` (`id`),
  ADD CONSTRAINT `ewin_creer_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `ewin_users` (`id`);

--
-- Contraintes pour la table `ewin_participer`
--
ALTER TABLE `ewin_participer`
  ADD CONSTRAINT `ewin_participer_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `ewin_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ewin_participer_ibfk_2` FOREIGN KEY (`id_tournoi`) REFERENCES `ewin_tournoi` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ewin_rencontre`
--
ALTER TABLE `ewin_rencontre`
  ADD CONSTRAINT `ewin_rencontre_ibfk_1` FOREIGN KEY (`idTournoi`) REFERENCES `ewin_tournoi` (`id`),
  ADD CONSTRAINT `ewin_rencontre_ibfk_2` FOREIGN KEY (`idJoueur1`) REFERENCES `ewin_users` (`id`),
  ADD CONSTRAINT `ewin_rencontre_ibfk_3` FOREIGN KEY (`idJoueur2`) REFERENCES `ewin_users` (`id`);

--
-- Contraintes pour la table `ewin_tournoi`
--
ALTER TABLE `ewin_tournoi`
  ADD CONSTRAINT `ewin_tournoi_ibfk_1` FOREIGN KEY (`idStatut`) REFERENCES `ewin_statut` (`id_statut`),
  ADD CONSTRAINT `sportRelation` FOREIGN KEY (`sportId`) REFERENCES `ewin_sport` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
