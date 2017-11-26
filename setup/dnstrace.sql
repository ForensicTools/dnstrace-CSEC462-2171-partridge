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

-- Dumping structure for table dnstrace.API_Keys
CREATE TABLE IF NOT EXISTS `API_Keys` (
  `Key` varchar(256) DEFAULT NULL,
  `RegisteredTo` varchar(1024) DEFAULT NULL,
  KEY `Key` (`Key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping structure for table dnstrace.DNS_A
CREATE TABLE IF NOT EXISTS `DNS_A` (
  `Subdomain` varchar(255) DEFAULT NULL,
  `Domain` varchar(255) DEFAULT NULL,
  `IPv4` varchar(16) DEFAULT NULL,
  `Current` bit(1) DEFAULT NULL,
  UNIQUE KEY `UNIQ_DATA` (`Subdomain`,`Domain`,`IPv4`),
  KEY `Domain` (`Domain`),
  CONSTRAINT `A_REP` FOREIGN KEY (`Domain`) REFERENCES `Reputation` (`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='type 1';

-- Dumping structure for table dnstrace.DNS_AAAA
CREATE TABLE IF NOT EXISTS `DNS_AAAA` (
  `Subdomain` varchar(255) DEFAULT NULL,
  `Domain` varchar(255) DEFAULT NULL,
  `IPv6` varchar(40) DEFAULT NULL,
  `Current` bit(1) DEFAULT NULL,
  UNIQUE KEY `UNIQ_DATA` (`Subdomain`,`Domain`,`IPv6`),
  KEY `Domain` (`Domain`),
  CONSTRAINT `DNS_AAAA_ibfk_1` FOREIGN KEY (`Domain`) REFERENCES `Reputation` (`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='type 28';

-- Dumping structure for table dnstrace.DNS_CNAME
CREATE TABLE IF NOT EXISTS `DNS_CNAME` (
  `Subdomain` varchar(255) DEFAULT NULL,
  `Domain` varchar(255) DEFAULT NULL,
  `CNAME` varchar(40) DEFAULT NULL,
  `Current` bit(1) DEFAULT NULL,
  UNIQUE KEY `UNIQ_DATA` (`Subdomain`,`Domain`,`CNAME`),
  KEY `Domain` (`Domain`),
  CONSTRAINT `DNS_CNAME_ibfk_1` FOREIGN KEY (`Domain`) REFERENCES `Reputation` (`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='type 5';

-- Dumping structure for table dnstrace.DNS_MX
CREATE TABLE IF NOT EXISTS `DNS_MX` (
  `Subdomain` varchar(255) DEFAULT NULL,
  `Domain` varchar(255) DEFAULT NULL,
  `MX_Subdomain` varchar(255) DEFAULT NULL,
  `MX_Domain` varchar(255) DEFAULT NULL,
  `Current` bit(1) DEFAULT NULL,
  UNIQUE KEY `UNIQ_DATA` (`Subdomain`,`Domain`,`MX_Subdomain`,`MX_Domain`),
  KEY `Domain` (`Domain`),
  CONSTRAINT `DNS_MX_ibfk_1` FOREIGN KEY (`Domain`) REFERENCES `Reputation` (`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='type 15';

-- Dumping structure for table dnstrace.DNS_NS
CREATE TABLE IF NOT EXISTS `DNS_NS` (
  `Subdomain` varchar(255) DEFAULT NULL,
  `Domain` varchar(255) DEFAULT NULL,
  `NS_Subdomain` varchar(255) DEFAULT NULL,
  `NS_Domain` varchar(255) DEFAULT NULL,
  `Current` bit(1) DEFAULT NULL,
  UNIQUE KEY `UNIQ_DATA` (`Subdomain`,`Domain`,`NS_Subdomain`,`NS_Domain`),
  KEY `Domain` (`Domain`),
  CONSTRAINT `DNS_NS_ibfk_1` FOREIGN KEY (`Domain`) REFERENCES `Reputation` (`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='type 2';

-- Dumping structure for table dnstrace.Jobs
CREATE TABLE IF NOT EXISTS `Jobs` (
  `JobID` mediumint(9) NOT NULL AUTO_INCREMENT,
  `Key` varchar(256) NOT NULL DEFAULT '0',
  `TimeStart` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `TimeEnd` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Domain` varchar(300) DEFAULT NULL,
  `Degree` tinyint(3) unsigned DEFAULT NULL,
  `MXEN` bit(1) DEFAULT NULL,
  `NSEN` bit(1) DEFAULT NULL,
  `Current` varchar(256) DEFAULT 'WAITING',
  KEY `JobID` (`JobID`),
  KEY `Current` (`Current`)
) ENGINE=MEMORY AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping structure for table dnstrace.Processors
CREATE TABLE IF NOT EXISTS `Processors` (
  `Count` int(11) DEFAULT NULL
) ENGINE=MEMORY DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- Dumping structure for table dnstrace.Reputation
CREATE TABLE IF NOT EXISTS `Reputation` (
  `Subdomain` varchar(255) DEFAULT NULL,
  `Domain` varchar(255) DEFAULT NULL,
  `Source` varchar(255) DEFAULT NULL,
  UNIQUE KEY `KEEP_DATA_SEMIUNIQ` (`Subdomain`,`Domain`,`Source`),
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

-- Dumping structure for table dnstrace.WHOIS_Contact
CREATE TABLE IF NOT EXISTS `WHOIS_Contact` (
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
  CONSTRAINT `CT-WHO` FOREIGN KEY (`Domain`) REFERENCES `WHOIS_General` (`Domain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping structure for table dnstrace.WHOIS_General
CREATE TABLE IF NOT EXISTS `WHOIS_General` (
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

-- Dumping structure for table dnstrace.Worker
CREATE TABLE IF NOT EXISTS `Worker` (
  `Count` int(11) DEFAULT NULL
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

INSERT IGNORE INTO `Sources` (`ID`, `Description`, `Score`) VALUES
	('USERADD', 'Subdomain was added manually, and has no scoring weight.', 0);

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;