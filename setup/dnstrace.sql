-- --------------------------------------------------------
-- Server version:               5.7.19-17 - Percona Server (GPL), Release '17', Revision 'e19a6b7b73f'
-- Server OS:                    debian-linux-gnu
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for dnstrace
CREATE DATABASE IF NOT EXISTS `dnstrace` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `dnstrace`;

-- Dumping structure for table dnstrace.Contact
CREATE TABLE IF NOT EXISTS `Contact` (
  `Domain` varchar(255) DEFAULT NULL,
  `NICHandle` varchar(510) DEFAULT NULL,
  `Name` varchar(510) DEFAULT NULL,
  `Organization` varchar(510) DEFAULT NULL,
  `Address` varchar(510) DEFAULT NULL,
  `City` varchar(510) DEFAULT NULL,
  `StateProvince` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `Email` varchar(330) DEFAULT NULL,
  `Phone` varchar(255) DEFAULT NULL,
  `Fax` varchar(255) DEFAULT NULL,
  KEY `ContactDomain` (`Domain`),
  CONSTRAINT `CT-WHO` FOREIGN KEY (`Domain`) REFERENCES `Whois` (`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping structure for table dnstrace.Nameservers
CREATE TABLE IF NOT EXISTS `Nameservers` (
  `Domain` varchar(255) DEFAULT NULL,
  `Nameserver` varchar(255) DEFAULT NULL,
  KEY `NameserversDomain` (`Domain`),
  CONSTRAINT `NS-WHO` FOREIGN KEY (`Domain`) REFERENCES `Whois` (`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping structure for table dnstrace.Reputation
CREATE TABLE IF NOT EXISTS `Reputation` (
  `FQDN` varchar(255) DEFAULT NULL,
  `Domain` varchar(255) DEFAULT NULL,
  `Source` varchar(255) DEFAULT NULL,
  `LastUpdated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `FQDN` (`FQDN`),
  KEY `ReputationDomain` (`Domain`),
  KEY `ReputationSource` (`Source`),
  CONSTRAINT `REP-SRC` FOREIGN KEY (`Source`) REFERENCES `Sources` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='For to-add and reputation lookups';

-- Dumping structure for table dnstrace.Sources
CREATE TABLE IF NOT EXISTS `Sources` (
  `ID` varchar(255) DEFAULT NULL,
  `Description` varchar(1020) DEFAULT NULL,
  `Score` tinyint(4) NOT NULL DEFAULT '0',
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping structure for table dnstrace.Whois
CREATE TABLE IF NOT EXISTS `Whois` (
  `Domain` varchar(255) DEFAULT NULL,
  `DomainID` varchar(510) DEFAULT NULL,
  `Status` varchar(510) DEFAULT NULL,
  `Registrar` varchar(510) DEFAULT NULL,
  `RegistrationDate` datetime DEFAULT NULL,
  `ExpirationDate` datetime DEFAULT NULL,
  `LastUpdated` datetime DEFAULT NULL,
  `AbuseEmail` varchar(330) DEFAULT NULL,
  UNIQUE KEY `Domain` (`Domain`),
  KEY `WhoisDomainKey` (`Domain`),
  CONSTRAINT `WHO-REP` FOREIGN KEY (`Domain`) REFERENCES `Reputation` (`Domain`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;