-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 15 déc. 2024 à 13:53
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mmd`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` enum('actif','inactif') NOT NULL,
  `user` varchar(255) DEFAULT 'admin',
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`, `description`, `date_creation`, `statut`, `user`, `id_utilisateur`) VALUES
(1, 'SALON', '', '2024-12-11 12:28:42', 'actif', 'admin', 3);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `nom_client` varchar(100) NOT NULL,
  `prenom_client` varchar(100) NOT NULL,
  `email_client` varchar(150) NOT NULL,
  `telephone_client` varchar(20) NOT NULL,
  `adresse_client` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom_client`, `prenom_client`, `email_client`, `telephone_client`, `adresse_client`, `date_creation`, `statut`, `id_utilisateur`) VALUES
(1, 'Faye', 'gnilou', 'fayegnilane01@gmail.com', '775857140', 'Mariste', '2024-12-11 12:44:38', 'actif', 3),
(2, 'SARR', 'Issa', 'issa@sarr', '779455666', 'Touba Toul', '2024-12-11 13:19:04', 'actif', 3);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `type_commande` enum('vente','proformat','','') NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut_commande` enum('en_attente','validee','annulee') DEFAULT 'en_attente',
  `id_utilisateur` int(11) NOT NULL,
  `numero_commande` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_client`, `type_commande`, `date_commande`, `statut_commande`, `id_utilisateur`, `numero_commande`) VALUES
(1, 1, 'vente', '2024-12-11 12:45:15', 'validee', 3, 'MDD-20241211-34372'),
(2, 1, 'proformat', '2024-12-11 13:12:53', 'validee', 3, 'MDD-20241211-88634'),
(3, 2, 'vente', '2024-12-11 14:13:25', 'validee', 3, 'MDD-20241211-57705'),
(7, 1, 'vente', '2024-12-11 14:31:47', 'validee', 3, 'MDD-20241211-77449');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id_fournisseur` int(11) NOT NULL,
  `nom_fournisseur` varchar(255) NOT NULL,
  `adresse` text DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `contact_personne` varchar(255) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id_fournisseur`, `nom_fournisseur`, `adresse`, `telephone`, `email`, `site_web`, `contact_personne`, `date_creation`, `statut`, `id_utilisateur`) VALUES
(1, 'SISTECH', 'Dakar', '779455666', 'sarrissa@gmail.com', '', 'Mr Sarr', '2024-12-11 12:31:54', 'actif', 3);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_commande`
--

CREATE TABLE `ligne_commande` (
  `id_ligne_commande` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_stock` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_reduction` decimal(10,2) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ligne_commande`
--

INSERT INTO `ligne_commande` (`id_ligne_commande`, `id_commande`, `id_stock`, `quantite`, `prix_reduction`, `id_utilisateur`) VALUES
(1, 1, 1, 1, 1800000.00, 3),
(2, 2, 1, 2, 0.00, 3),
(3, 3, 3, 1, 0.00, 3),
(9, 7, 1, 1, 0.00, 3);

-- --------------------------------------------------------

--
-- Structure de la table `log_connexion`
--

CREATE TABLE `log_connexion` (
  `id_log` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_connexion` datetime DEFAULT current_timestamp(),
  `date_deconnexion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `log_connexion`
--

INSERT INTO `log_connexion` (`id_log`, `id_utilisateur`, `date_connexion`, `date_deconnexion`) VALUES
(1, 3, '2024-12-09 21:19:34', '2024-12-09 21:28:15'),
(9, 3, '2024-12-11 13:20:47', '2024-12-11 13:22:39'),
(10, 3, '2024-12-11 13:22:55', '2024-12-11 13:23:49'),
(11, 3, '2024-12-11 13:23:52', '2024-12-11 15:34:16'),
(12, 3, '2024-12-11 15:34:50', NULL),
(13, 3, '2024-12-15 13:52:53', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id_paiement` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `mode_paiement` enum('wave','orange_money','virement','especes') NOT NULL,
  `transaction` varchar(50) NOT NULL,
  `montant_ht` decimal(10,2) NOT NULL,
  `tva` decimal(10,2) NOT NULL,
  `montant_ttc` decimal(10,2) NOT NULL,
  `date_paiement` datetime DEFAULT current_timestamp(),
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id_paiement`, `id_commande`, `mode_paiement`, `transaction`, `montant_ht`, `tva`, `montant_ttc`, `date_paiement`, `id_utilisateur`) VALUES
(1, 1, 'especes', '', 1800000.00, 324000.00, 2124000.00, '2024-12-11 13:48:44', 3),
(2, 2, '', '', 4000000.00, 720000.00, 4720000.00, '2024-12-11 14:13:12', 3),
(3, 3, 'especes', '', 11000000.00, 1980000.00, 11000000.00, '2024-12-11 15:13:39', 3),
(7, 7, 'especes', '', 1640000.00, 360000.00, 2000000.00, '2024-12-11 15:31:59', 3);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `prix_achat` decimal(10,2) NOT NULL,
  `prix_vente` decimal(10,2) NOT NULL,
  `fournisseur_id` int(11) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `image_produit` varchar(255) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom_produit`, `description`, `prix_unitaire`, `categorie_id`, `prix_achat`, `prix_vente`, `fournisseur_id`, `date_creation`, `statut`, `image_produit`, `id_utilisateur`) VALUES
(1, 'chambre a coucher king  size 11 pieces', '', 1000000.00, 1, 1500000.00, 2000000.00, 1, '2024-12-11 12:37:19', 'actif', '../../assets/img/castle-458058_1280.jpg', 3),
(2, 'SALON 15 PLACES', 'NEANT', 9000000.00, 1, 10000000.00, 11000000.00, 1, '2024-12-11 13:12:38', 'actif', '../../assets/img/baby-boy-3368016_1280.jpg', 3);

-- --------------------------------------------------------

--
-- Structure de la table `sales_data`
--

CREATE TABLE `sales_data` (
  `id` int(11) NOT NULL,
  `year` varchar(4) NOT NULL,
  `usa` int(11) NOT NULL,
  `uk` int(11) NOT NULL,
  `au` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sales_data`
--

INSERT INTO `sales_data` (`id`, `year`, `usa`, `uk`, `au`) VALUES
(1, '2016', 15, 8, 12),
(2, '2017', 30, 35, 25),
(3, '2018', 55, 40, 45),
(4, '2019', 65, 60, 55),
(5, '2020', 60, 70, 65),
(6, '2021', 80, 55, 70),
(7, '2022', 95, 75, 60);

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id_stock` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite_disponible` int(11) NOT NULL DEFAULT 0,
  `emplacement_produit` varchar(255) DEFAULT NULL,
  `date_ajout` varchar(25) NOT NULL DEFAULT current_timestamp(),
  `date_mise_a_jour` varchar(25) NOT NULL DEFAULT current_timestamp(),
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id_stock`, `produit_id`, `quantite_disponible`, `emplacement_produit`, `date_ajout`, `date_mise_a_jour`, `id_utilisateur`) VALUES
(1, 1, 4, 'mmd_siege', '2024-12-11 13:39:35', '2024-12-11 13:39:35', 3),
(3, 2, 1, 'mmd_siege', '2024-12-11 14:19:39', '2024-12-11 14:19:39', 3);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `telephone` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT 'contact@mdd.com',
  `login` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','employe') DEFAULT 'employe',
  `date_creation` datetime DEFAULT current_timestamp(),
  `dernier_acces` datetime DEFAULT NULL,
  `statut` enum('actif','inactif') DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `telephone`, `email`, `login`, `mot_de_passe`, `role`, `date_creation`, `dernier_acces`, `statut`) VALUES
(3, 'SARR', 'Issa', '779455666', 'contact@mdd.com', '779455666', '$2y$10$o0LLZR5zzk2FLG4AZLDXcO5HtnB43TFYUSkS62ORgeVwoWQ030CXm', 'admin', '2024-12-08 01:27:01', '2024-12-15 13:52:53', 'actif');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`),
  ADD KEY `fk_categorie_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `email_client` (`email_client`),
  ADD KEY `fk_client_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD UNIQUE KEY `numero_commande` (`numero_commande`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `fk_commande_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`),
  ADD KEY `fk_fournisseur_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD PRIMARY KEY (`id_ligne_commande`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_stock` (`id_stock`),
  ADD KEY `fk_ligne_commande_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `log_connexion`
--
ALTER TABLE `log_connexion`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id_paiement`),
  ADD UNIQUE KEY `id_commande` (`id_commande`),
  ADD KEY `fk_paiement_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `fournisseur_id` (`fournisseur_id`),
  ADD KEY `fk_produit_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `sales_data`
--
ALTER TABLE `sales_data`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_stock`),
  ADD UNIQUE KEY `produit_id` (`produit_id`),
  ADD KEY `fk_stock_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`login`),
  ADD UNIQUE KEY `telephone` (`telephone`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  MODIFY `id_ligne_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `log_connexion`
--
ALTER TABLE `log_connexion`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `sales_data`
--
ALTER TABLE `sales_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD CONSTRAINT `fk_categorie_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `fk_client_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_commande_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD CONSTRAINT `fk_fournisseur_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD CONSTRAINT `fk_ligne_commande_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_commande_ibfk_2` FOREIGN KEY (`id_stock`) REFERENCES `stock` (`id_stock`) ON DELETE CASCADE;

--
-- Contraintes pour la table `log_connexion`
--
ALTER TABLE `log_connexion`
  ADD CONSTRAINT `log_connexion_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `fk_paiement_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_produit_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id_categorie`),
  ADD CONSTRAINT `produit_ibfk_2` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id_fournisseur`);

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_stock_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id_produit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
