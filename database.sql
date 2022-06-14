-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 10, 2022 at 05:13 PM
-- Server version: 5.7.38-0ubuntu0.18.04.1
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `biens`
--

CREATE TABLE `biens` (
  `Id_Bien` int(11) NOT NULL,
  `Id_Block` int(11) NOT NULL,
  `Num_Titre_Foncier` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `Num` int(11) NOT NULL,
  `Type` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Surface` int(50) NOT NULL,
  `Etage` int(11) NOT NULL,
  `Prix_Bien` int(11) NOT NULL,
  `Num_Ordre` varchar(50) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `biens`
--

-- --------------------------------------------------------

--
-- Table structure for table `blocs`
--

CREATE TABLE `blocs` (
  `Id_Bloc` int(11) NOT NULL,
  `Id_Projet` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Nom_Bloc` varchar(50) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `blocs`
--


-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `Id_Client` int(11) NOT NULL,
  `Nom` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Prenom` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `CIN` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Adresse` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Tel1` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Tel2` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Email` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Date_Naissance` date NOT NULL,
  `Lieu_Naissance` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Source` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Type_demande` enum('Aucune Demande','Achat en Attente','Demande Spéciale') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `desc_demande` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client`
--

-- --------------------------------------------------------

--
-- Table structure for table `comptes`
--

CREATE TABLE `comptes` (
  `Id_Compte` int(11) NOT NULL,
  `RIB` varchar(24) COLLATE latin1_general_ci NOT NULL,
  `Banque` varchar(50) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `comptes`
--

-- --------------------------------------------------------

--
-- Table structure for table `echeance`
--

CREATE TABLE `echeance` (
  `Id_Echeance` int(11) NOT NULL,
  `Id_Vente` int(24) NOT NULL,
  `Montant` int(11) NOT NULL,
  `Partie_Versante` varchar(50) DEFAULT NULL,
  `Date_echeance` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Payée` enum('Oui','Non') NOT NULL DEFAULT 'Non',
  `type` enum('Virement','Versement','Chèque','Espèces') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Num_Operation` int(11) NOT NULL,
  `id_Compte` int(20) NOT NULL,
  `date_Operation` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `echeance`
--
----------------------------------------------

--
-- Table structure for table `paiement`
--

CREATE TABLE `paiement` (
  `Id_paiement` int(11) NOT NULL,
  `Id_Client` int(11) NOT NULL,
  `Date_paiement` date NOT NULL,
  `Methode` enum('Manuelle','Automatique') NOT NULL,
  `Periodicite` enum('Mensuelle','Bimensuelle','Trimestrielle','Semestrielle') DEFAULT NULL,
  `Montant_Echeance` int(11) DEFAULT NULL,
  `Id_Vente` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paiement`
--
--------------------------------------------------

--
-- Table structure for table `projet`
--

CREATE TABLE `projet` (
  `Id_Projet` int(11) NOT NULL,
  `Nom_Projet` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Nbr_Bien` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Adresse` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Date_lancement` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `projet`
--
-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `Id_Role` int(11) NOT NULL,
  `Nom_Role` varchar(55) COLLATE latin1_general_ci NOT NULL,
  `Description_Role` varchar(99) COLLATE latin1_general_ci NOT NULL,
  `Peut_Lister` set('Client','Projet','Blocs','Biens','Vente','Paiement','Echeance','Comptes','Utilisateurs','Roles','Suivi de Paiement') COLLATE latin1_general_ci NOT NULL,
  `Peut_Lire` set('Client','Projet','Blocs','Biens','Vente','Paiement','Echeance','Comptes','Utilisateurs','Roles') COLLATE latin1_general_ci NOT NULL,
  `Peut_Ajouter` set('Client','Projet','Blocs','Biens','Vente','Paiement','Echeance','Comptes','Utilisateurs','Roles') COLLATE latin1_general_ci NOT NULL,
  `Peut_Modifier` set('Client','Projet','Blocs','Biens','Vente','Paiement','Echeance','Comptes','Utilisateurs','Roles') COLLATE latin1_general_ci NOT NULL,
  `Peut_Supprimer` set('Client','Projet','Blocs','Biens','Vente','Paiement','Echeance','Comptes','Utilisateurs','Roles') COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `roles`
--

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(128) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `value` varchar(255) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'app_nom', 'Real Estate SARL'),
(2, 'app_adresse1', '123 Main Street'),
(3, 'app_adresse2', 'Ste. 401'),
(4, 'app_ville', 'Tanger'),
(5, 'app_CodePostal', '90000'),
(6, 'app_Telephone', '0539000000'),
(7, 'app_IdentifiantFiscal', '00000000'),
(8, 'app_ICE', '000000000000000'),
(9, 'app_RC', '00000'),
(10, 'app_Patente', '00000000'),
(11, 'app_RIB', '0000000000000000000000000000000'),
(12, 'app_AdminEmail', 'test@test.ma,test2@test.ma');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `Id_Utilisateur` int(11) NOT NULL,
  `Id_Role` int(11) NOT NULL,
  `Nom_Utilisateur` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Mot_De_Passe` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Prenom` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Nom` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Email` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Telephone_1` varchar(99) COLLATE latin1_general_ci NOT NULL,
  `Telephone_2` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Adresse` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Ville` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Code_Postal` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_ip` varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`Id_Utilisateur`, `Id_Role`, `Nom_Utilisateur`, `Mot_De_Passe`, `Prenom`, `Nom`, `Email`, `Telephone_1`, `Telephone_2`, `Adresse`, `Ville`, `Code_Postal`, `last_login`, `last_login_ip`) VALUES
(13, 1, 'admin', 'c3284d0f94606de1fd2af172aba15bf3|2|e86cebe1', 'Admin', 'Admin', 'admin@test.ma', '+21200000000', '', '', '', '', '2022-03-04 10:07:18', '105.147.28.47'),
(14, 2, 'ASSISTANTE 1', '7e1524471c83e1eda2a9e6556a7bdbc4|2|e86cebe1', 'KHALIDA', 'ZMIZEM', '', '0698971771', '', '', 'TANGER', '', '2019-08-02 14:10:10', '196.89.226.132'),
(15, 2, 'ASSISTANTE 2', '3a5125bd0c8d096d0d68f4b36cf5f67a|2|e86cebe1', 'MARIAM', 'LAABBAS', '', '06669725690', '', '', 'TANGER', '', '2019-11-28 14:43:33', '160.177.122.178');

-- --------------------------------------------------------

--
-- Table structure for table `vente`
--

CREATE TABLE `vente` (
  `Id_Vente` int(11) NOT NULL,
  `Id_Bien` int(11) NOT NULL,
  `Id_Client` int(11) NOT NULL,
  `Avance_Prix` int(11) NOT NULL,
  `Date_Achat` date NOT NULL,
  `Statut` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Id_Client_prev` int(11) NOT NULL,
  `Id_Client_next` int(11) NOT NULL,
  `Num_Vente` varchar(50) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `vente`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biens`
--
ALTER TABLE `biens`
  ADD PRIMARY KEY (`Id_Bien`),
  ADD KEY `Id_Block` (`Id_Block`);

--
-- Indexes for table `blocs`
--
ALTER TABLE `blocs`
  ADD PRIMARY KEY (`Id_Bloc`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`Id_Client`);

--
-- Indexes for table `comptes`
--
ALTER TABLE `comptes`
  ADD PRIMARY KEY (`Id_Compte`);

--
-- Indexes for table `echeance`
--
ALTER TABLE `echeance`
  ADD PRIMARY KEY (`Id_Echeance`);

--
-- Indexes for table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`Id_paiement`),
  ADD KEY `Id_Client` (`Id_Client`),
  ADD KEY `Id_Vente` (`Id_Vente`);

--
-- Indexes for table `projet`
--
ALTER TABLE `projet`
  ADD PRIMARY KEY (`Id_Projet`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`Id_Role`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`Id_Utilisateur`);

--
-- Indexes for table `vente`
--
ALTER TABLE `vente`
  ADD PRIMARY KEY (`Id_Vente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `biens`
--
ALTER TABLE `biens`
  MODIFY `Id_Bien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `blocs`
--
ALTER TABLE `blocs`
  MODIFY `Id_Bloc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `Id_Client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comptes`
--
ALTER TABLE `comptes`
  MODIFY `Id_Compte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `echeance`
--
ALTER TABLE `echeance`
  MODIFY `Id_Echeance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `Id_paiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `projet`
--
ALTER TABLE `projet`
  MODIFY `Id_Projet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `Id_Role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `Id_Utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vente`
--
ALTER TABLE `vente`
  MODIFY `Id_Vente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
